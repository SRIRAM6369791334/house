<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $table = 'sub_categories';

    protected $fillable = [
        'subcategory_name',
        'subcategory_image',
        'category_name',
        'category_display',
        'status',
        'is_gift',
        'created_at',
        'updated_at',
    ];
}
