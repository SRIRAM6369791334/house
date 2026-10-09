<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSlot extends Model
{
    protected $table = 'product_slots';

    protected $fillable = [
        'delivery_date',
        'order_id',
        'product_id',
        'product_varient_id',
        'product_name',
        'product_rate',
        'gst_amt',
        'gst_per',
        'product_value',
        'quantity',
        'product_total',
        'delivery_status',
        'preorder',
        'dispatch_date',
        'order_delivered_time',
        'deliver_person_id',
        'is_cancelled',
        'cancel_reason',
        'approve_staus',
        'created_at',
        'updated_at',
    ];
}
