<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    protected $table = 'productstocks';

    protected $fillable = [
        'productid',
        'category_id',
        'subcategory_id',
        'pro_ver_id',
        'productname',
        'overallstock',
        'availablestock',
        'salestock',
        'low_stocks',
        'last_stockupdate_date',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'availablestock' => 'integer',
            'salestock' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'productid');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'pro_ver_id');
    }
}
