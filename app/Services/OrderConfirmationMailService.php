<?php

namespace App\Services;

use App\Models\OrderItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderConfirmationMailService
{
    public function send(object $order): bool
    {
        if ($order->payment_method !== 'cod' && $order->payment_status !== 'paid') {
            return false;
        }
        $email = trim((string) ($order->billing_email ?? $order->shipping_email ?? ''));
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $cacheKey = 'order-confirmation-email:'.($order->id ?? $order->order_number);
        if (! Cache::add($cacheKey, true, now()->addDays(7))) {
            return true;
        }
        try {
            $items = OrderItem::query()
                ->where('order_id', $order->id)
                ->orderBy('id')
                ->get();
            Mail::send('emails.order-confirmation', compact('order', 'items'), function ($message) use ($email, $order) {
                $message->to($email, (string) ($order->billing_name ?? 'Customer'))
                    ->subject('Order Confirmed - '.$order->order_number);
            });

            return true;
        } catch (Throwable $exception) {
            Cache::forget($cacheKey);
            Log::error('Order confirmation email failed', [
                'order_number' => $order->order_number ?? null,
                'email' => $email,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
