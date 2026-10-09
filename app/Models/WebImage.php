<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebImage extends Model
{
    protected $table = 'web_images';

    protected $fillable = [
        'image',
        'video',
        'title',
        'subtitle',
        'content',
        'created_at',
        'updated_at',
    ];
}
