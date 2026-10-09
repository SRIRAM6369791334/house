<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCategory extends Model
{
    protected $table = 'gift_categories';

    protected $fillable = [
        'category_name',
        'category_image',
        'banner_title',
        'banner_description',
        'status',
        'created_at',
        'updated_at',
    ];
}
