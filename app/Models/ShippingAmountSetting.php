<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAmountSetting extends Model
{
    protected $table = 'shipping_amount_settings';

    protected $fillable = [
        'minimum_amount',
        'shipping_amount',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'minimum_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
        ];
    }
}
