<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutAttempt extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'total_amount', 'currency', 'payload',
        'cashfree_order_id', 'payment_session_id', 'completed_order_id', 'completed_at',
    ];

    protected $hidden = ['payload', 'payment_session_id'];

    protected function casts(): array
    {
        return [
            'payload' => 'encrypted:array',
            'total_amount' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }
}
