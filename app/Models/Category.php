<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'category_name',
        'category_banner',
        'category_image',
        'category_icon',
        'banner_title',
        'banner_description',
        'status',
        'is_gift',
        'created_at',
        'updated_at',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
