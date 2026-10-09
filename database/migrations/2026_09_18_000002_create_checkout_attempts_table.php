<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkout_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 100)->unique();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3);
            $table->longText('payload');
            $table->string('cashfree_order_id')->nullable();
            $table->text('payment_session_id')->nullable();
            $table->unsignedBigInteger('completed_order_id')->nullable()->unique();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_attempts');
    }
};
