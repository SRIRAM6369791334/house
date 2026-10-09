<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_orders')) {
            return;
        }

        Schema::table('product_orders', function (Blueprint $table) {
            $table->timestamp('stock_transferred_at')->nullable();
        });

        // The previous checkout already deducted stock for paid and COD orders.
        DB::table('product_orders')->where(function ($query) {
            $query->where('payment_status', 'paid')->orWhere('payment_method', 'cod');
        })->update(['stock_transferred_at' => now()]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('product_orders', 'stock_transferred_at')) {
            Schema::table('product_orders', fn (Blueprint $table) => $table->dropColumn('stock_transferred_at'));
        }
    }
};
