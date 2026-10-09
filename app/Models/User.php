<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'phone_number',
        'user_id',
        'is_guest_user',
        'billing_name',
        'billing_phone',
        'billing_street',
        'billing_city',
        'billing_state',
        'billing_pincode',
        'shipping_name',
        'shipping_phone',
        'shipping_street',
        'shipping_city',
        'shipping_state',
        'shipping_pincode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ProductOrder::class, 'user_id');
    }

    public function orderAddresses(): HasMany
    {
        return $this->hasMany(OrderAddress::class, 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }
}
