<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function scopeForStorefront(Builder $query): Builder
    {
        return $query->from('products as p')
            ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
            ->leftJoinSub(ProductVariant::query()->priceSummary(), 'v', function ($join) {
                $join->on('v.product_id', '=', 'p.id');
            })
            ->whereNull('p.deleted_at')
            ->select([
                'p.id', 'p.category_id', 'p.product_name', 'p.slug', 'p.product_image',
                'p.product_image_2', 'p.product_specification', 'p.product_description',
                'p.product_mrp_price', 'p.product_regular_price', 'p.cate_name',
                'p.subcate_name', 'p.brand_name', 'p.color', 'p.size', 'p.fit',
                'p.features', 'p.product_details', 'c.category_name',
                'v.offer_price', 'v.mrp_price', 'v.product_gst', 'v.stock_qty',
            ]);
    }

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'brand_id',
        'subcategory_id',
        'product_name',
        'slug',
        'product_quantity',
        'product_mrp_price',
        'product_regular_price',
        'product_description',
        'product_image',
        'product_image_2',
        'product_specification',
        'product_specfication',
        'brand_name',
        'brand_material',
        'brand_type',
        'approval_days',
        'is_gift',
        'created_at',
        'updated_at',
        'unit_value',
        'product_value',
        'cate_name',
        'subcate_name',
        'size_value',
        'deleted_at',
        'meta_title',
        'meta_description',
        'meta_key',
        'color',
        'size',
        'fit',
        'features',
        'product_details',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'prod_id');
    }
}
