<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAddress extends Model
{
    protected $table = 'product_order_user_addresses';

    protected $fillable = [
        'user_id',
        'order_id',
        'firstname',
        'secondname',
        'address_line_one',
        'address_line_two',
        'landmark',
        'area_id',
        'city',
        'state',
        'pincode',
        'phonecode',
        'address_phone_number',
        'address_type_id',
        'address_type_name',
        'address_type_others_name',
        'created_at',
        'updated_at',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(ProductOrder::class, 'order_id', 'order_number');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
