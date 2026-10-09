<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewVideo extends Model
{
    protected $table = 'review_videos';

    protected $fillable = [
        'prod_id',
        'name',
        'rating',
        'video',
        'status',
        'created_at',
        'updated_at',
    ];
}
