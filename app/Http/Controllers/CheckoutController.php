<?php

namespace App\Http\Controllers;

use App\Models\OrderAddress;
use App\Models\ProductOrder;
use App\Models\UserAddress;
use App\Services\CashfreeService;
use App\Services\CheckoutAttemptService;
use App\Services\CheckoutOrderService;
use App\Services\OrderConfirmationMailService;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckoutController extends Controller
{
    public function index()
    {
        $defaultCheckoutAddress = null;
        if (auth()->check()) {
            $address = null;
            $addressQuery = UserAddress::query()
                ->where('user_id', auth()->id())
                ->whereRaw('LOWER(address_type_name) = ?', ['shipping']);
            $addressQuery->orderByDesc('is_default');
            $address = $addressQuery->orderByDesc('id')
                ->first();
            if (! $address) {
                $address = OrderAddress::query()
                    ->where('user_id', auth()->id())
                    ->whereRaw('LOWER(address_type_name) = ?', ['shipping'])
                    ->whereNotNull('address_line_one')
                    ->where('address_line_one', '!=', '')
                    ->orderByDesc('id')
                    ->first();
            }
            if ($address) {
                $rawAddress = trim((string) ($address->address_line_one ?? ''));
                $addressParts = array_map('trim', explode(',', $rawAddress, 3));
                $hasCheckoutParts = count($addressParts) >= 3;
                $phone = preg_replace('/\D+/', '', (string) ($address->address_phone_number ?? auth()->user()->phone ?? auth()->user()->phone_number ?? ''));
                $addressName = trim((string) ($address->address_username ?? collect([$address->address_first_name ?? $address->firstname ?? '', $address->address_last_name ?? $address->secondname ?? ''])->filter()
                    ->implode(' ')));
                $defaultCheckoutAddress = [
                    'name' => $addressName ?: auth()->user()->name,
                    'phone' => strlen($phone) > 10 ? substr($phone, -10) : $phone,
                    'door_no' => $hasCheckoutParts ? $addressParts[0] : trim((string) ($address->address_line_two ?? $rawAddress)),
                    'street' => $hasCheckoutParts ? $addressParts[1] : $rawAddress,
                    'area' => $hasCheckoutParts ? $addressParts[2] : trim((string) ($address->landmark ?? '')),
                    'state' => trim((string) ($address->state ?? '')),
                    'city' => trim((string) ($address->city ?? '')),
                    'pincode' => preg_replace('/\D+/', '', (string) ($address->pincode ?? '')),
                ];
            } else {
                $lastOrder = ProductOrder::query()
                    ->where('user_id', auth()->id())
                    ->whereNotNull('shipping_street')
                    ->orderByDesc('id')
                    ->first();
                if ($lastOrder) {
                    $addressParts = array_map('trim', explode(',', (string) $lastOrder->shipping_street, 3));
                    $phone = preg_replace('/\D+/', '', (string) ($lastOrder->shipping_phone ?? ''));
                    $defaultCheckoutAddress = [
                        'name' => trim((string) ($lastOrder->shipping_name ?: auth()->user()->name)),
                        'phone' => strlen($phone) > 10 ? substr($phone, -10) : $phone,
                        'door_no' => $addressParts[0] ?? '',
                        'street' => $addressParts[1] ?? $lastOrder->shipping_street ?? '',
                        'area' => $addressParts[2] ?? '',
                        'state' => trim((string) ($lastOrder->shipping_state ?? '')),
                        'city' => trim((string) ($lastOrder->shipping_city ?? '')),
                        'pincode' => preg_replace('/\D+/', '', (string) ($lastOrder->shipping_pincode ?? '')),
                    ];
                }
            }
        }

        return view('pages.checkout', [
            'cartProducts' => house_products_from_session('cart'),
            'coupon' => session('checkout_coupon'),
            'defaultCheckoutAddress' => $defaultCheckoutAddress,
        ]);
    }

    public function store(Request $request, CheckoutOrderService $orders, CashfreeService $cashfree, ShiprocketService $shiprocket, OrderConfirmationMailService $mailService, CheckoutAttemptService $attempts)
    {
        $cartProducts = house_products_from_session('cart');
        if ($cartProducts->isEmpty()) {
            return redirect('cart')->withErrors(['cart' => 'Your cart is empty.']);
        }
        $billingMode = $request->input('billing_mode', 'same');
        $shippingAddress = collect([$request->input('shipping_door_no'), $request->input('shipping_street'), $request->input('shipping_area')])->filter(fn ($value) => trim((string) $value) !== '')
            ->implode(', ');
        if ($request->has('customer_name')) {
            $customerPhone = preg_replace('/\D+/', '', (string) $request->input('customer_phone'));
            if (strlen($customerPhone) > 10) {
                $customerPhone = substr($customerPhone, -10);
            }
            if ($billingMode === 'different') {
                $billingPhone = preg_replace('/\D+/', '', (string) $request->input('billing_phone', $customerPhone));
                if (strlen($billingPhone) > 10) {
                    $billingPhone = substr($billingPhone, -10);
                }
                $billingAddress = collect([$request->input('billing_door_no'), $request->input('billing_street'), $request->input('billing_area')])->filter(fn ($value) => trim((string) $value) !== '')
                    ->implode(', ');
                $request->merge([
                    'billing_first_name' => $request->input('billing_name'),
                    'billing_last_name' => null,
                    'billing_email' => $request->input('billing_email', $request->input('customer_email')),
                    'billing_phone' => $billingPhone,
                    'billing_address' => $billingAddress,
                    'billing_city' => $request->input('billing_city'),
                    'billing_state' => $request->input('billing_state'),
                    'billing_postcode' => $request->input('billing_postcode'),
                    'shipping_first_name' => $request->input('customer_name'),
                    'shipping_last_name' => null,
                    'shipping_phone' => $customerPhone,
                    'shipping_address' => $shippingAddress,
                    'shipping_city' => $request->input('shipping_city'),
                    'shipping_state' => $request->input('shipping_state'),
                    'shipping_postcode' => $request->input('shipping_postcode'),
                    'same_as_billing' => null,
                ]);
            } else {
                $request->merge([
                    'billing_first_name' => $request->input('customer_name'),
                    'billing_last_name' => null,
                    'billing_email' => $request->input('customer_email'),
                    'billing_phone' => $customerPhone,
                    'billing_address' => $shippingAddress,
                    'billing_city' => $request->input('shipping_city'),
                    'billing_state' => $request->input('shipping_state'),
                    'billing_postcode' => $request->input('shipping_postcode'),
                    'same_as_billing' => 1,
                ]);
            }
        }
        $rules = [
            'billing_first_name' => ['required', 'string', 'max:100'],
            'billing_last_name' => ['nullable', 'string', 'max:100'],
            'billing_email' => ['required', 'email', 'max:255'],
            'billing_phone' => ['required', 'digits:10'],
            'billing_address' => ['required', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:120'],
            'billing_state' => ['required', 'string', 'max:120'],
            'billing_postcode' => ['required', 'digits:6'],
            'same_as_billing' => ['nullable', 'boolean'],
            'shipping_first_name' => ['required_without:same_as_billing', 'nullable', 'string', 'max:100'],
            'shipping_last_name' => ['nullable', 'string', 'max:100'],
            'shipping_phone' => ['required_without:same_as_billing', 'nullable', 'digits:10'],
            'shipping_address' => ['required_without:same_as_billing', 'nullable', 'string', 'max:255'],
            'shipping_city' => ['required_without:same_as_billing', 'nullable', 'string', 'max:120'],
            'shipping_state' => ['required_without:same_as_billing', 'nullable', 'string', 'max:120'],
            'shipping_postcode' => ['required_without:same_as_billing', 'nullable', 'digits:6'],
            'payment_method' => ['required', 'in:cod,upi,card'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'create_account' => ['nullable', 'boolean'],
            'account_password' => ['required_if:create_account,1', 'nullable', 'string', 'min:8', 'confirmed'],
        ];
        $data = $request->validate($rules, [], [
            'billing_first_name' => 'full name',
            'billing_email' => 'email address',
            'billing_phone' => 'phone number',
            'billing_address' => 'address',
            'billing_city' => 'city',
            'billing_state' => 'state',
            'billing_postcode' => 'pincode',
            'shipping_phone' => 'phone number',
            'shipping_address' => 'shipping address',
            'shipping_city' => 'city',
            'shipping_state' => 'state',
            'shipping_postcode' => 'pincode',
            'account_password' => 'password',
        ]);
        $subtotal = $cartProducts->sum(fn ($product) => house_product_price($product) * $product->cart_qty);
        $coupon = session('checkout_coupon');
        $discount = house_coupon_discount($coupon, $subtotal);
        $shippingPincode = $data['shipping_postcode'] ?? $data['billing_postcode'];
        $shipping = 0;
        try {
            $shippingQuote = $shiprocket->shippingQuote($shippingPincode, 0.5, $data['payment_method'] === 'cod', $subtotal);
            if (! $shippingQuote['serviceable']) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Delivery is not available for this pincode.', 'errors' => ['shipping_postcode' => ['Delivery is not available for this pincode.']]], 422);
                }

                return back()->withErrors(['shipping_postcode' => 'Delivery is not available for this pincode.'])
                    ->withInput();
            }
            $shipping = house_checkout_shipping_amount($subtotal, $shippingQuote['charge']);
        } catch (Throwable $exception) {
            Log::error('Shiprocket pincode serviceability check failed', ['pincode' => $shippingPincode, 'error' => $exception->getMessage()]);
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unable to check delivery for this pincode: '.$exception->getMessage(), 'errors' => ['shipping_postcode' => ['Unable to check delivery for this pincode: '.$exception->getMessage()]]], 422);
            }

            return back()->withErrors(['shipping_postcode' => 'Unable to check delivery for this pincode: '.$exception->getMessage()])
                ->withInput();
        }
        $total = round(max(0, $subtotal + $shipping - $discount), 2);
        if ($data['payment_method'] !== 'cod') {
            try {
                $attempt = $attempts->start($data, $cartProducts, $coupon, $subtotal, $shipping, $discount, $total);
            } catch (Throwable $exception) {
                Log::error('Cashfree checkout could not be started', ['error' => $exception->getMessage()]);
                $message = 'Unable to start payment. Please try again.';

                return $request->expectsJson()
                    ? response()->json(['message' => $message, 'errors' => ['payment' => [$message]]], 422)
                    : back()->withErrors(['payment' => $message])->withInput();
            }

            $request->session()->put('pending_payment_order', $attempt->order_number);

            if ($request->expectsJson()) {
                return response()->json([
                    'payment_session_id' => $attempt->payment_session_id,
                    'cashfree_mode' => $cashfree->mode(),
                    'order_number' => $attempt->order_number,
                ]);
            }

            return view('pages.cashfree_redirect', [
                'paymentSessionId' => $attempt->payment_session_id,
                'cashfreeMode' => $cashfree->mode(),
                'orderNumber' => $attempt->order_number,
            ]);
        }
        try {
            $order = $orders->create($data, $cartProducts, $coupon, $subtotal, $shipping, $discount, $total);
        } catch (\RuntimeException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage(), 'errors' => ['cart' => [$e->getMessage()]]], 422);
            }

            return back()->with('error', $e->getMessage())
                ->withInput();
        }
        if (! auth()->check() && ! empty($data['create_account']) && ! empty($order->user_id)) {
            auth()->loginUsingId($order->user_id);
            $request->session()
                ->regenerate();
        }
        session(['last_order' => [
            'number' => $order->order_number,
            'payment_method' => $data['payment_method'],
            'subtotal' => $subtotal,
            'shipping_amount' => $shipping,
            'discount' => $discount,
            'coupon_code' => $coupon['code'] ?? null,
            'total' => $total,
        ]]);
        if ($data['payment_method'] === 'cod') {
            try {
                $shiprocketResponse = $shiprocket->createOrder($orders->shiprocketPayload($order));
                $orders->markShiprocket($order, $shiprocketResponse);
            } catch (Throwable $exception) {
                Log::error('Shiprocket COD order creation failed', ['order_number' => $order->order_number, 'error' => $exception->getMessage()]);
            }
            session()->forget(['cart', 'checkout_coupon', 'pending_payment_order']);
            $mailService->send($order);

            return redirect('thankyou')->with('success', 'Order placed successfully.');
        }
    }

    public function shippingQuote(Request $request, ShiprocketService $shiprocket)
    {
        $data = $request->validate(['shipping_postcode' => ['required', 'regex:/^\d{6}$/'], 'payment_method' => ['nullable', 'in:online,card,upi,cod']], [], ['shipping_postcode' => 'pincode']);
        $cartProducts = house_products_from_session('cart');
        if ($cartProducts->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.', 'errors' => ['cart' => ['Your cart is empty.']]], 422);
        }
        $subtotal = $cartProducts->sum(fn ($product) => house_product_price($product) * $product->cart_qty);
        $coupon = session('checkout_coupon');
        $discount = house_coupon_discount($coupon, $subtotal);
        try {
            $quote = $shiprocket->shippingQuote($data['shipping_postcode'], 0.5, ($data['payment_method'] ?? 'online') === 'cod', $subtotal);
        } catch (Throwable $exception) {
            Log::error('Shiprocket checkout quote failed', ['pincode' => $data['shipping_postcode'], 'error' => $exception->getMessage()]);

            return response()->json(['message' => 'Unable to check delivery for this pincode: '.$exception->getMessage(), 'errors' => ['shipping_postcode' => ['Unable to check delivery for this pincode.']]], 422);
        }
        if (! $quote['serviceable']) {
            return response()->json(['message' => 'Delivery is not available for this pincode.', 'errors' => ['shipping_postcode' => ['Delivery is not available for this pincode.']]], 422);
        }
        $shipping = house_checkout_shipping_amount($subtotal, $quote['charge']);
        $total = max(0, $subtotal + $shipping - $discount);

        return response()->json([
            'serviceable' => true,
            'shipping' => $shipping,
            'total' => $total,
            'courier' => $quote['courier'],
            'etd' => $quote['etd'],
        ]);
    }
}
