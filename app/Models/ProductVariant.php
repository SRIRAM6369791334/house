<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    public function scopePriceSummary(Builder $query): Builder
    {
        return $query->select('product_id')
            ->selectRaw('MIN(offer_price) as offer_price, MIN(mrp_price) as mrp_price, MAX(product_gst) as product_gst, SUM(product_qty) as stock_qty')
            ->groupBy('product_id');
    }

    protected $table = 'product_varient';

    protected $fillable = [
        'categoryid',
        'subcategoryid',
        'product_id',
        'sku',
        'barcode',
        'varient',
        'unit_id',
        'varient_img',
        'varient_name',
        'value',
        'offer_price',
        'mrp_price',
        'product_qty',
        'low_stock',
        'hot_deals',
        'Popular_products',
        'pre_order',
        'pre_note',
        'product_gst',
        'product_hsn',
        'weight',
        'length',
        'breadth',
        'height',
        'subcatename',
        'size_value',
        'varient_details',
        'created_at',
        'updated_at',
        'flash_sale',
        'flash_sale_date',
    ];

    protected function casts(): array
    {
        return [
            'product_qty' => 'integer',
            'offer_price' => 'integer',
            'mrp_price' => 'integer',
            'product_gst' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'pro_ver_id');
    }
}
