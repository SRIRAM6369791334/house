<?php

namespace App\Services;

use App\Models\CheckoutAttempt;
use App\Models\ProductOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class CheckoutAttemptService
{
    public function __construct(private CheckoutOrderService $orders, private CashfreeService $cashfree) {}

    public function start(array $data, Collection $products, ?array $coupon, float $subtotal, float $shipping, float $discount, float $total): CheckoutAttempt
    {
        // Store only a password hash in the encrypted snapshot, never a plaintext password.
        if (! empty($data['account_password'])) {
            $data['account_password_hash'] = Hash::make($data['account_password']);
        }
        unset($data['account_password'], $data['account_password_confirmation']);

        $items = $products->map(fn ($product) => [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'offer_price' => (float) house_product_price($product),
            'product_gst' => house_product_gst_rate($product),
            'cart_qty' => (int) $product->cart_qty,
            'cart_variant_id' => $product->cart_variant_id,
        ])->values()->all();

        $attempt = CheckoutAttempt::query()->create([
            'order_number' => $this->orders->nextOrderNumber(),
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'currency' => config('services.cashfree.currency', 'INR'),
            'payload' => compact('data', 'items', 'coupon', 'subtotal', 'shipping', 'discount', 'total'),
        ]);

        // A restored local database may be behind IDs already reserved at Cashfree.
        // Keep each rejected number in the local sequence and try the next one.
        for ($tries = 0; $tries < 100; $tries++) {
            $gatewayPayload = $this->orders->cashfreePayload((object) [
                'order_number' => $attempt->order_number,
                'total_amount' => $total,
                'user_id' => $attempt->user_id ?? $attempt->order_number,
                'billing_name' => trim($data['billing_first_name'].' '.($data['billing_last_name'] ?? '')),
                'billing_email' => $data['billing_email'],
                'billing_phone' => $data['billing_phone'],
            ]);

            try {
                $remote = $this->cashfree->createOrder($gatewayPayload);
                $attempt->update([
                    'cashfree_order_id' => $remote['cf_order_id'] ?? $remote['order_id'],
                    'payment_session_id' => $remote['payment_session_id'],
                ]);

                return $attempt;
            } catch (CashfreeOrderAlreadyExistsException $exception) {
                $attempt->update(['order_number' => $this->orders->nextOrderNumber()]);
            }
        }

        throw new \RuntimeException('Unable to reserve an unused Cashfree order ID after 100 attempts.');
    }

    /** Called only after server-side verification of Cashfree PAID status and amount. */
    public function complete(CheckoutAttempt $attempt): ProductOrder
    {
        return CheckoutAttempt::resolveConnection()->transaction(function () use ($attempt) {
            $locked = CheckoutAttempt::query()->whereKey($attempt->id)->lockForUpdate()->firstOrFail();
            if ($locked->completed_order_id) {
                return ProductOrder::query()->findOrFail($locked->completed_order_id);
            }

            $snapshot = $locked->payload;
            $order = $this->orders->create(
                $snapshot['data'],
                collect($snapshot['items'])->map(fn ($item) => (object) $item),
                $snapshot['coupon'],
                $snapshot['subtotal'], $snapshot['shipping'], $snapshot['discount'], $snapshot['total'],
                $locked,
            );
            $locked->update(['completed_order_id' => $order->id, 'completed_at' => now()]);

            return $order;
        });
    }
}