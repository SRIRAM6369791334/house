<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePromotion extends Model
{
    protected $table = 'home_promotions';

    protected $fillable = [
        'badge_text',
        'main_title',
        'highlight_text',
        'bg_image',
        'link_url',
        'platform',
        'sort_order',
        'status',
        'created_at',
        'updated_at',
    ];
}
