<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'codename',
        'mini_amt',
        'discounttype',
        'discount',
        'start_date',
        'end_date',
        'default_id',
        'coupon_status',
        'created_at',
        'updated_at',
    ];
}
