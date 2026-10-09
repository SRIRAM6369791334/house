<?php

namespace App\Services;

use App\Models\CheckoutAttempt;
use App\Models\ProductOrder;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class PaymentConfirmationService
{
    public function __construct(private CheckoutOrderService $orders, private CashfreeService $cashfree, private ShiprocketService $shiprocket, private OrderConfirmationMailService $mail, private CheckoutAttemptService $attempts) {}

    public function confirm(string $orderNumber): ?ProductOrder
    {
        $order = ProductOrder::query()
            ->where('order_number', $orderNumber)
            ->first();
        $attempt = $order ? null : CheckoutAttempt::query()->where('order_number', $orderNumber)->first();
        if ((! $order && ! $attempt) || $order?->payment_method === 'cod') {
            throw new RuntimeException('Online order not found.');
        }
        if (! $order || $order->payment_status !== 'paid') {
            $remote = $this->cashfree->getOrder($orderNumber);
            $expectedTotal = $attempt?->total_amount ?? $order->total_amount;
            $expectedCurrency = $attempt?->currency ?? config('services.cashfree.currency', 'INR');
            if (($remote['order_id'] ?? null) !== $orderNumber || ! is_numeric($remote['order_amount'] ?? null) || (int) round((float) $remote['order_amount'] * 100) !== (int) round((float) $expectedTotal * 100) || ($remote['order_currency'] ?? null) !== $expectedCurrency) {
                throw new RuntimeException('Cashfree order details do not match the local order.');
            }
            if (($remote['order_status'] ?? null) !== 'PAID') {
                return null;
            }
            // Commit the financial fact before attempting stock, email or shipping.
            $order = $attempt ? $this->attempts->complete($attempt) : $this->orders->markPaid($orderNumber);
            if (! $order) {
                throw new RuntimeException('Online order not found.');
            }
        }
        try {
            $this->orders->allocatePaidStock($order);
            // Serialize return, modal and webhook callbacks to avoid duplicate shipments.
            ProductOrder::resolveConnection()
                ->transaction(function () use ($order) {
                    $current = ProductOrder::query()
                        ->where('id', $order->id)
                        ->lockForUpdate()
                        ->first();
                    if (empty($current->shiprocket_order_id) && empty($current->shiprocket_shipment_id)) {
                        $response = $this->shiprocket->createOrder($this->orders->shiprocketPayload($current));
                        $this->orders->markShiprocket($current, $response);
                    }
                });
        } catch (Throwable $exception) {
            Log::error('Paid order fulfillment requires attention', ['order_number' => $orderNumber, 'error' => $exception->getMessage()]);
        }
        $this->mail->send($order);

        return $order;
    }
}
