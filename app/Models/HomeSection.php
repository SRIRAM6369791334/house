<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $table = 'home_sections';

    protected $fillable = [
        'section_key',
        'section_group',
        'label',
        'content',
        'highlight_word',
        'char_limit',
        'sort_order',
        'created_at',
        'updated_at',
    ];
}
