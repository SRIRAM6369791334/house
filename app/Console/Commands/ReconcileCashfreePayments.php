<?php

namespace App\Console\Commands;

use App\Models\CheckoutAttempt;
use App\Models\ProductOrder;
use App\Services\PaymentConfirmationService;
use Illuminate\Console\Command;
use Throwable;

class ReconcileCashfreePayments extends Command
{
    protected $signature = 'payments:reconcile {order_number? : Verify one order, or all pending online orders}';

    protected $description = 'Verify Cashfree payments and recover missed callbacks';

    public function handle(PaymentConfirmationService $payments): int
    {
        $failed = false;
        if ($number = $this->argument('order_number')) {
            try {
                $paid = $payments->confirm($number);
                $this->line($number.': '.($paid ? 'paid' : 'not yet paid'));

                return self::SUCCESS;
            } catch (Throwable $exception) {
                $this->error($number.': '.$exception->getMessage());

                return self::FAILURE;
            }
        }
        $queries = [
            CheckoutAttempt::query()->whereNull('completed_order_id'),
            ProductOrder::query()->whereIn('payment_method', ['card', 'upi'])->where('payment_status', 'pending'),
        ];
        foreach ($queries as $query) {
            $query->orderBy('id')->chunkById(100, function ($orders) use ($payments, &$failed) {
                foreach ($orders as $order) {
                    try {
                        $paid = $payments->confirm($order->order_number);
                        $this->line($order->order_number.': '.($paid ? 'paid' : 'not yet paid'));
                    } catch (Throwable $exception) {
                        $failed = true;
                        $this->error($order->order_number.': '.$exception->getMessage());
                    }
                }
            });
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
