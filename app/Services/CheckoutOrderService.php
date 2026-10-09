<?php

namespace App\Services;

use App\Models\CheckoutAttempt;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\ProductOrder;
use App\Models\ProductSlot;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutOrderService
{
    public function create(array $data, Collection $cartProducts, ?array $coupon, float $subtotal, float $shipping, float $discount, float $total, ?CheckoutAttempt $paidAttempt = null): ProductOrder
    {
        if ($data['payment_method'] !== 'cod' && ! $paidAttempt) {
            throw new \LogicException('Online orders can only be created after payment verification.');
        }

        return ProductOrder::resolveConnection()
            ->transaction(function () use ($data, $cartProducts, $subtotal, $shipping, $discount, $total, $paidAttempt) {
                $billingName = trim($data['billing_first_name'].' '.($data['billing_last_name'] ?? ''));
                $shippingAddress = $this->shippingData($data);
                $shippingName = trim($shippingAddress['first_name'].' '.($shippingAddress['last_name'] ?? ''));
                $userId = $this->customerId($data, $billingName, $shippingAddress, $shippingName, $paidAttempt);
                $orderNumber = $paidAttempt?->order_number ?? $this->nextOrderNumber();
                $gstAmount = house_cart_included_gst($cartProducts);
                $orderId = ProductOrder::query()
                    ->create([
                        'user_id' => $userId,
                        'order_number' => $orderNumber,
                        'order_id' => $orderNumber,
                        'billing_name' => $billingName,
                        'billing_email' => $data['billing_email'],
                        'billing_phone' => $data['billing_phone'],
                        'billing_street' => $data['billing_address'],
                        'billing_city' => $data['billing_city'],
                        'billing_state' => $data['billing_state'],
                        'billing_pincode' => $data['billing_postcode'],
                        'shipping_name' => $shippingName,
                        'shipping_email' => $data['billing_email'],
                        'shipping_phone' => $shippingAddress['phone'],
                        'shipping_street' => $shippingAddress['address'],
                        'shipping_city' => $shippingAddress['city'],
                        'shipping_state' => $shippingAddress['state'],
                        'shipping_pincode' => $shippingAddress['postcode'],
                        'subtotal' => $subtotal,
                        'gst_amount' => $gstAmount,
                        'shipping_charge' => $shipping,
                        'coupon_discount' => $discount,
                        'total_amount' => $total,
                        'payment_status' => $paidAttempt ? 'paid' : ($data['payment_method'] === 'cod' ? 'unpaid' : 'pending'),
                        'razorpay_order_id' => $paidAttempt?->cashfree_order_id,
                        'razorpay_payment_id' => $paidAttempt?->payment_session_id,
                        'payment_method' => $data['payment_method'],
                        'status' => 'Order Placed',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                    ->getKey();
                OrderAddress::query()
                    ->create([
                        'user_id' => $userId,
                        'order_id' => $orderNumber,
                        'firstname' => $shippingAddress['first_name'],
                        'secondname' => $shippingAddress['last_name'] ?? null,
                        'address_line_one' => $shippingAddress['address'],
                        'city' => $shippingAddress['city'],
                        'state' => $shippingAddress['state'],
                        'pincode' => $shippingAddress['postcode'],
                        'address_phone_number' => preg_replace('/\D+/', '', $shippingAddress['phone']) ?: null,
                        'address_type_name' => 'Shipping',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                foreach ($cartProducts as $product) {
                    $price = (float) house_product_price($product);
                    $lineTotal = $price * (int) $product->cart_qty;
                    $gstRate = house_product_gst_rate($product);
                    $lineGstAmount = house_included_gst_amount($lineTotal, $gstRate);
                    OrderItem::query()
                        ->create([
                            'order_id' => $orderId,
                            'product_id' => $product->id,
                            'product_variant_id' => $product->cart_variant_id,
                            'product_name' => $product->product_name,
                            'quantity' => (int) $product->cart_qty,
                            'price' => $price,
                            'gst_rate' => $gstRate,
                            'gst_amount' => $lineGstAmount,
                            'total' => $lineTotal,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    ProductSlot::query()
                        ->create([
                            'delivery_date' => now()->addDays(3)
                                ->toDateString(),
                            'order_id' => $orderNumber,
                            'product_id' => $product->id,
                            'product_name' => $product->product_name,
                            'product_rate' => $price,
                            'quantity' => (int) $product->cart_qty,
                            'product_total' => $lineTotal,
                            'delivery_status' => 0,
                            'is_cancelled' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                }
                if ($data['payment_method'] === 'cod') {
                    $this->transferOrderStock($orderId);
                    ProductOrder::query()
                        ->where('id', $orderId)
                        ->update(['stock_transferred_at' => now()]);
                }

                return ProductOrder::query()
                    ->where('id', $orderId)
                    ->first();
            });
    }

    public function markCashfreeStarted(object $order, array $cashfreeOrder): void
    {
        ProductOrder::query()
            ->where('id', $order->id)
            ->update([
                'razorpay_order_id' => $cashfreeOrder['cf_order_id'] ?? $cashfreeOrder['order_id'] ?? null,
                'razorpay_payment_id' => $cashfreeOrder['payment_session_id'] ?? null,
                'updated_at' => now(),
            ]);
    }

    public function markPaid(string $orderNumber): ?ProductOrder
    {
        return ProductOrder::resolveConnection()
            ->transaction(function () use ($orderNumber) {
                $order = ProductOrder::query()
                    ->where('order_number', $orderNumber)
                    ->lockForUpdate()
                    ->first();
                if (! $order) {
                    return null;
                }
                if ($order->payment_status !== 'paid') {
                    ProductOrder::query()
                        ->where('id', $order->id)
                        ->update(['payment_status' => 'paid', 'updated_at' => now()]);
                }

                return ProductOrder::query()
                    ->where('id', $order->id)
                    ->first();
            });
    }

    public function allocatePaidStock(object $order): void
    {
        ProductOrder::resolveConnection()
            ->transaction(function () use ($order) {
                $current = ProductOrder::query()
                    ->where('id', $order->id)
                    ->lockForUpdate()
                    ->first();
                if (! $current || $current->payment_status !== 'paid' || $current->stock_transferred_at) {
                    return;
                }
                $this->transferOrderStock($current->id);
                ProductOrder::query()
                    ->where('id', $current->id)
                    ->update(['stock_transferred_at' => now(), 'updated_at' => now()]);
            });
    }

    public function markShiprocket(object $order, array $response): void
    {
        ProductOrder::query()
            ->where('id', $order->id)
            ->update([
                'shiprocket_order_id' => $response['order_id'] ?? null,
                'shiprocket_shipment_id' => $response['shipment_id'] ?? null,
                'shiprocket_status' => $response['status'] ?? 'created',
                'updated_at' => now(),
            ]);
    }

    public function cashfreePayload(object $order): array
    {
        $returnUrl = trim((string) config('services.cashfree.return_url'));
        $returnUrl = $returnUrl !== '' ? $returnUrl : url('checkout/cashfree/return').'?order_id={order_id}';
        if (! str_contains($returnUrl, '{order_id}')) {
            $returnUrl .= (str_contains($returnUrl, '?') ? '&' : '?').'order_id={order_id}';
        }

        return [
            'order_id' => $order->order_number,
            'order_amount' => (float) number_format((float) $order->total_amount, 2, '.', ''),
            'order_currency' => config('services.cashfree.currency', 'INR'),
            'customer_details' => [
                'customer_id' => 'CUST_'.(string) $order->user_id,
                'customer_name' => trim((string) $order->billing_name),
                'customer_email' => trim((string) $order->billing_email),
                'customer_phone' => $this->phoneNumber($order->billing_phone),
            ],
            'order_meta' => ['return_url' => $returnUrl, 'notify_url' => route('checkout.cashfree.webhook')],
            'order_note' => 'House of KNP order '.$order->order_number,
        ];
    }

    public function shiprocketPayload(object $order): array
    {
        $items = OrderItem::query()
            ->where('order_id', $order->id)
            ->get();
        [$billingFirstName, $billingLastName] = $this->nameParts($order->billing_name);
        [$shippingFirstName, $shippingLastName] = $this->nameParts($order->shipping_name);
        $shippingIsBilling = $this->sameAddress($order);
        $payload = [
            'order_id' => $order->order_number,
            'order_date' => now()->format('Y-m-d H:i'),
            'pickup_location' => trim((string) config('services.shiprocket.pickup_location')),
            'billing_customer_name' => $billingFirstName,
            'billing_last_name' => $billingLastName,
            'billing_address' => trim((string) $order->billing_street),
            'billing_city' => trim((string) $order->billing_city),
            'billing_pincode' => preg_replace('/\D+/', '', (string) $order->billing_pincode),
            'billing_state' => trim((string) $order->billing_state),
            'billing_country' => 'India',
            'billing_email' => trim((string) $order->billing_email),
            'billing_phone' => $this->phoneNumber($order->billing_phone),
            'shipping_is_billing' => $shippingIsBilling,
            'shipping_customer_name' => $shippingFirstName,
            'shipping_last_name' => $shippingLastName,
            'shipping_address' => trim((string) $order->shipping_street),
            'shipping_city' => trim((string) $order->shipping_city),
            'shipping_pincode' => preg_replace('/\D+/', '', (string) $order->shipping_pincode),
            'shipping_state' => trim((string) $order->shipping_state),
            'shipping_country' => 'India',
            'shipping_email' => trim((string) $order->billing_email),
            'shipping_phone' => $this->phoneNumber($order->shipping_phone),
            'order_items' => $items->map(fn ($item) => [
                'name' => trim((string) $item->product_name),
                'sku' => 'KNP-'.$item->product_id.($item->product_variant_id ? '-V'.$item->product_variant_id : '').'-I'.$item->id,
                'units' => max(1, (int) $item->quantity),
                'selling_price' => max(1, round((float) $item->price, 2)),
                'discount' => 0,
                'tax' => 0,
                'hsn' => '',
            ])
                ->values()
                ->all(),
            'payment_method' => $order->payment_method === 'cod' ? 'COD' : 'Prepaid',
            'sub_total' => round((float) $order->total_amount, 2),
            'shipping_charges' => round((float) $order->shipping_charge, 2),
            'total_discount' => round((float) $order->coupon_discount, 2),
            'length' => 10,
            'breadth' => 10,
            'height' => 5,
            'weight' => 0.5,
        ];
        $channelId = trim((string) config('services.shiprocket.channel_id'));
        if (config('services.shiprocket.send_channel_id') && $channelId !== '' && ctype_digit($channelId) && (int) $channelId > 0) {
            $payload['channel_id'] = (int) $channelId;
        }

        return $payload;
    }

    private function transferOrderStock(int $orderId): void
    {
        $items = OrderItem::query()
            ->where('order_id', $orderId)
            ->get();
        foreach ($items as $item) {
            $remaining = max(0, (int) $item->quantity);
            if ($remaining === 0) {
                continue;
            }
            $stockRows = ProductStock::query()
                ->where('productid', $item->product_id)
                ->when($item->product_variant_id, function ($query) use ($item) {
                    $query->where('pro_ver_id', $item->product_variant_id);
                })
                ->orderBy('id')
                ->lockForUpdate()
                ->get();
            if ($stockRows->sum(fn ($stock) => max(0, (int) $stock->availablestock)) < $remaining) {
                throw new \RuntimeException("Insufficient stock for {$item->product_name}.");
            }
            foreach ($stockRows as $stock) {
                if ($remaining === 0) {
                    break;
                }
                $soldQuantity = min($remaining, max(0, (int) $stock->availablestock));
                if ($soldQuantity === 0) {
                    continue;
                }
                $availableStock = (int) $stock->availablestock - $soldQuantity;
                ProductStock::query()
                    ->where('id', $stock->id)
                    ->update([
                        'availablestock' => $availableStock,
                        'salestock' => (int) $stock->salestock + $soldQuantity,
                        'updated_at' => now(),
                    ]);
                if ($stock->pro_ver_id) {
                    ProductVariant::query()
                        ->where('id', $stock->pro_ver_id)
                        ->update(['product_qty' => $availableStock, 'updated_at' => now()]);
                }
                $remaining -= $soldQuantity;
            }
        }
    }

    private function phoneNumber(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if (strlen($digits) > 10) {
            $digits = substr($digits, -10);
        }

        return $digits;
    }

    private function nameParts(?string $name): array
    {
        $parts = preg_split('/\s+/', trim((string) $name), 2, PREG_SPLIT_NO_EMPTY);

        return [$parts[0] ?? 'Customer', $parts[1] ?? '.'];
    }

    private function sameAddress(object $order): bool
    {
        return trim((string) $order->billing_street) === trim((string) $order->shipping_street) && trim((string) $order->billing_city) === trim((string) $order->shipping_city) && trim((string) $order->billing_state) === trim((string) $order->shipping_state) && preg_replace('/\D+/', '', (string) $order->billing_pincode) === preg_replace('/\D+/', '', (string) $order->shipping_pincode);
    }

    private function customerId(array $data, string $billingName, array $shipping, string $shippingName, ?CheckoutAttempt $paidAttempt = null): int
    {
        // Webhooks have no browser session; use the identity captured when checkout started.
        $user = $paidAttempt ? User::query()->find($paidAttempt->user_id) : auth()->user();
        if ($user) {
            $id = $user->id;
        } else {
            $existing = User::query()
                ->where('email', $data['billing_email'])
                ->orWhere('phone', $data['billing_phone'])
                ->orWhere('phone_number', $data['billing_phone'])
                ->first();
            if ($existing) {
                $id = $existing->id;
            } else {
                $userData = [
                    'name' => $billingName,
                    'email' => $data['billing_email'],
                    'password' => $data['account_password_hash'] ?? Hash::make($data['account_password'] ?? Str::random(16)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $userData['phone'] = $data['billing_phone'];
                $userData['phone_number'] = $data['billing_phone'];
                $userData['user_id'] = 'WEB-'.Str::upper(Str::random(8));
                $userData['is_guest_user'] = ! empty($data['create_account']) ? 0 : 1;
                $id = User::query()
                    ->create($userData)
                    ->getKey();
            }
        }
        $updates = ['updated_at' => now()];
        foreach ([
            'billing_name' => $billingName,
            'billing_phone' => $data['billing_phone'],
            'billing_street' => $data['billing_address'],
            'billing_city' => $data['billing_city'],
            'billing_state' => $data['billing_state'],
            'billing_pincode' => $data['billing_postcode'],
            'shipping_name' => $shippingName,
            'shipping_phone' => $shipping['phone'],
            'shipping_street' => $shipping['address'],
            'shipping_city' => $shipping['city'],
            'shipping_state' => $shipping['state'],
            'shipping_pincode' => $shipping['postcode'],
        ] as $column => $value) {
            $updates[$column] = $value;
        }
        if ($this->canUseUniqueValue('phone', $data['billing_phone'], $id)) {
            $updates['phone'] = $data['billing_phone'];
        }
        if ($this->canUseUniqueValue('phone_number', $data['billing_phone'], $id)) {
            $updates['phone_number'] = $data['billing_phone'];
        }
        User::query()
            ->where('id', $id)
            ->update($updates);

        return $id;
    }

    private function canUseUniqueValue(string $column, string $value, int $userId): bool
    {
        return ! User::query()
            ->where($column, $value)
            ->where('id', '!=', $userId)
            ->exists();
    }

    private function shippingData(array $data): array
    {
        if (! empty($data['same_as_billing'])) {
            return [
                'first_name' => $data['billing_first_name'],
                'last_name' => $data['billing_last_name'] ?? '',
                'phone' => $data['billing_phone'],
                'address' => $data['billing_address'],
                'city' => $data['billing_city'],
                'state' => $data['billing_state'],
                'postcode' => $data['billing_postcode'],
            ];
        }

        return [
            'first_name' => $data['shipping_first_name'],
            'last_name' => $data['shipping_last_name'] ?? '',
            'phone' => $data['shipping_phone'],
            'address' => $data['shipping_address'],
            'city' => $data['shipping_city'],
            'state' => $data['shipping_state'],
            'postcode' => $data['shipping_postcode'],
        ];
    }

    public function nextOrderNumber(): string
    {
        $orders = ProductOrder::query()
            ->where('order_number', 'like', 'KNP-ORD-%')
            ->lockForUpdate()
            ->pluck('order_number');
        $attempts = CheckoutAttempt::query()
            ->where('order_number', 'like', 'KNP-ORD-%')
            ->lockForUpdate()
            ->pluck('order_number');

        $lastSequence = $orders
            ->merge($attempts)
            ->map(function ($orderNumber) {
                return preg_match('/^KNP-ORD-(\d+)$/', (string) $orderNumber, $matches) ? (int) $matches[1] : 0;
            })
            ->max();
        $next = (int) $lastSequence + 1;

        return sprintf('KNP-ORD-%03d', $next);
    }
}
