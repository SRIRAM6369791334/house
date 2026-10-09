<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageMetaSetting extends Model
{
    protected $table = 'page_meta_settings';

    protected $fillable = [
        'page_name',
        'page_url',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'canonical_url',
        'og_image',
        'created_at',
        'updated_at',
    ];
}
