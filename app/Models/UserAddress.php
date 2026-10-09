<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    protected $table = 'user_addresses';

    protected $fillable = [
        'address_username',
        'address_first_name',
        'address_last_name',
        'user_id',
        'address_line_one',
        'address_line_two',
        'landmark',
        'area_id',
        'area_name',
        'city',
        'city_id',
        'state_id',
        'pincode',
        'pincode_id',
        'district',
        'state',
        'phone_code',
        'address_phone_number',
        'address_type_id',
        'address_type_name',
        'is_default',
        'address_type_others_name',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
