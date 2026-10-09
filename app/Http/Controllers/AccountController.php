<?php

namespace App\Http\Controllers;

use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\ProductOrder;
use App\Models\UserAddress;

class AccountController extends Controller
{
    public function index()
    {
        if (! UserAddress::query()
            ->where('user_id', auth()->id())
            ->exists()) {
            $orderAddresses = OrderAddress::query()
                ->where('user_id', auth()->id())
                ->whereRaw('LOWER(address_type_name) = ?', ['shipping'])
                ->whereNotNull('address_line_one')
                ->where('address_line_one', '!=', '')
                ->orderByDesc('id')
                ->get();
            foreach ($orderAddresses as $index => $orderAddress) {
                $name = trim(($orderAddress->firstname ?? '').' '.($orderAddress->secondname ?? ''));
                $insert = [
                    'user_id' => auth()->id(),
                    'address_username' => $name ?: auth()->user()->name,
                    'address_first_name' => $orderAddress->firstname ?: auth()->user()->name,
                    'address_last_name' => $orderAddress->secondname,
                    'address_line_one' => $orderAddress->address_line_one,
                    'address_line_two' => $orderAddress->address_line_two,
                    'landmark' => $orderAddress->landmark,
                    'area_id' => $orderAddress->area_id,
                    'city' => $orderAddress->city,
                    'state' => $orderAddress->state,
                    'pincode' => $orderAddress->pincode,
                    'phone_code' => $orderAddress->phonecode ?: '+91',
                    'address_phone_number' => $orderAddress->address_phone_number ?: auth()->user()->phone,
                    'address_type_id' => $orderAddress->address_type_id,
                    'address_type_name' => $orderAddress->address_type_name ?: 'Shipping',
                    'address_type_others_name' => $orderAddress->address_type_others_name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $insert['is_default'] = $index === 0 ? 1 : 0;
                UserAddress::query()
                    ->create($insert);
            }
        }
        $addresses = UserAddress::query()
            ->where('user_id', auth()->id())
            ->whereRaw('LOWER(address_type_name) = ?', ['shipping'])
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get();
        $orders = ProductOrder::query()
            ->where('user_id', auth()->id())
            ->with([
                'items' => fn ($query) => $query->with('product')->orderBy('id'),
                'shippingAddresses' => fn ($query) => $query->where('user_id', auth()->id())->orderByDesc('id'),
            ])
            ->orderByDesc('id')
            ->get();
        $orders->each(function (ProductOrder $order) {
            $order->items->each(function (OrderItem $item) {
                $item->image_url = house_product_images($item->product ?? $item)->first();
                $item->slug = $item->product?->slug;
            });
            $order->main_item = $order->items->first();
            $order->shipping_address_record = $order->shippingAddresses->first();
            $order->courier_name = collect([$order->shiprocket_courier_name ?? null, $order->courier_name ?? null, $order->shipping_courier ?? null, $order->shiprocket_status ?? null])->first(fn ($value) => trim((string) $value) !== '') ?: 'Not assigned';
        });

        return view('pages.account', compact('addresses', 'orders'));
    }
}
