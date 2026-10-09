<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blog';

    protected $fillable = [
        'title',
        'image',
        'description',
        'date',
        'url_name',
        'created_at',
        'updated_at',
        'blog_category_id',
        'meta_title',
        'meta_description',
        'meta_key',
    ];
}
