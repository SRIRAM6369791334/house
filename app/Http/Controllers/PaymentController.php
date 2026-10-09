<?php

namespace App\Http\Controllers;

use App\Models\CheckoutAttempt;
use App\Models\ProductOrder;
use App\Services\CashfreeService;
use App\Services\PaymentConfirmationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentController extends Controller
{
    public function verify(Request $request, PaymentConfirmationService $payments)
    {
        $data = $request->validate(['order_number' => ['required', 'string', 'max:100']]);
        $localOrder = ProductOrder::query()
            ->where('order_number', $data['order_number'])
            ->first();
        $localOrder ??= CheckoutAttempt::query()->where('order_number', $data['order_number'])->first();
        abort_unless($localOrder && $this->ownsOrder($request, $localOrder), 404);
        try {
            $order = $payments->confirm($data['order_number']);
        } catch (Throwable $exception) {
            $this->logFailure($data['order_number'], $exception);

            return response()->json(['message' => 'Unable to verify payment. Please retry confirmation; do not pay again.'], 503);
        }
        if (! $order) {
            return response()->json([
                'success' => false,
                'pending' => true,
                'message' => 'Payment confirmation is still pending. Please wait; do not pay again if your account was debited.',
            ], 202);
        }
        $isCurrentCheckout = $this->isCurrentCheckout($request, $order);
        $this->rememberOrder($request, $order);

        return response()->json([
            'success' => true,
            'message' => 'Payment successful. Order placed successfully.',
            'order_number' => $order->order_number,
            'thankyou_url' => $isCurrentCheckout ? url('thankyou') : url('account'),
        ]);
    }

    public function returnFromCashfree(Request $request, PaymentConfirmationService $payments)
    {
        $orderNumber = $request->query('order_id');
        if (! is_string($orderNumber) || $orderNumber === '') {
            return redirect('checkout')->withErrors(['payment' => 'Cashfree did not return an order id.']);
        }
        // Verify even if the browser lost its session, without exposing customer details.
        try {
            $order = $payments->confirm($orderNumber);
        } catch (Throwable $exception) {
            $this->logFailure($orderNumber, $exception);

            return redirect('checkout')->withErrors(['payment' => 'Unable to verify payment. Do not pay again if your account was debited.']);
        }
        if (! $order) {
            return redirect('checkout')->withErrors(['payment' => 'Payment confirmation is pending. Please check your orders before paying again.']);
        }
        if (! $this->ownsOrder($request, $order)) {
            return redirect('login')->with('success', 'Payment verified. Sign in to view your order.');
        }
        if (! $this->isCurrentCheckout($request, $order)) {
            return redirect('account')->with('success', 'Payment successful.');
        }
        $this->rememberOrder($request, $order);

        return redirect('thankyou')->with('success', 'Payment successful. Order placed successfully.');
    }

    public function webhook(Request $request, CashfreeService $cashfree, PaymentConfirmationService $payments)
    {
        abort_unless($cashfree->validWebhook($request->getContent(), (string) $request->header('x-webhook-timestamp'), (string) $request->header('x-webhook-signature')), 401);
        if ($request->input('type') !== 'PAYMENT_SUCCESS_WEBHOOK' || $request->input('data.payment.payment_status') !== 'SUCCESS') {
            return response()->json(['received' => true]);
        }
        $data = $request->validate(['data.order.order_id' => ['required', 'string', 'max:100']]);
        $orderNumber = $data['data']['order']['order_id'];
        try {
            if (! $payments->confirm($orderNumber)) {
                return response()->json(['message' => 'Awaiting payment confirmation.'], 503);
            }
        } catch (Throwable $exception) {
            $this->logFailure($orderNumber, $exception);

            return response()->json(['message' => 'Payment confirmation will be retried.'], 503);
        }

        return response()->json(['received' => true]);
    }

    private function ownsOrder(Request $request, object $order): bool
    {
        return $request->session()
            ->get('pending_payment_order') === $order->order_number
            || $request->session()->get('last_order.number') === $order->order_number
            || $request->user() && (int) $request->user()->id === (int) $order->user_id;
    }

    private function isCurrentCheckout(Request $request, object $order): bool
    {
        $current = $request->session()->get('pending_payment_order') ?? $request->session()->get('last_order.number');

        return $current === $order->order_number;
    }

    private function rememberOrder(Request $request, object $order): void
    {
        // A delayed callback for an older order must not empty a newer checkout's cart.
        if (! $this->isCurrentCheckout($request, $order)) {
            return;
        }
        $request->session()
            ->put('last_order', [
                'number' => $order->order_number,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'subtotal' => (float) $order->subtotal,
                'shipping_amount' => (float) $order->shipping_charge,
                'discount' => (float) $order->coupon_discount,
                'coupon_code' => $request->session()
                    ->get('checkout_coupon.code'),
                'total' => (float) $order->total_amount,
            ]);
        $request->session()
            ->forget(['cart', 'checkout_coupon', 'pending_payment_order']);
    }

    private function logFailure(string $orderNumber, Throwable $exception): void
    {
        Log::error('Cashfree payment confirmation failed', ['order_number' => $orderNumber, 'error' => $exception->getMessage()]);
    }
}
