<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOrder extends Model
{
    protected $table = 'product_orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'order_id',
        'delivery_person_id',
        'delivery_person_name',
        'delivery_person_phone',
        'is_delivery_assigned',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_door_no',
        'billing_street',
        'billing_area',
        'billing_city',
        'billing_state',
        'billing_pincode',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_door_no',
        'shipping_street',
        'shipping_area',
        'shipping_city',
        'shipping_state',
        'shipping_pincode',
        'subtotal',
        'gst_amount',
        'shipping_charge',
        'coupon_discount',
        'total_amount',
        'payment_status',
        'payment_method',
        'razorpay_order_id',
        'razorpay_payment_id',
        'shiprocket_order_id',
        'shiprocket_shipment_id',
        'awb_code',
        'courier_name',
        'pickup_scheduled',
        'manifest_url',
        'label_url',
        'invoice_url',
        'shiprocket_status',
        'status',
        'cancellation_reason',
        'created_at',
        'updated_at',
        'stock_transferred_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'shipping_charge' => 'decimal:2',
            'coupon_discount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'stock_transferred_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(OrderAddress::class, 'order_id', 'order_number');
    }
}
