<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'para',
        'image',
        'created_at',
        'updated_at',
        'firstname',
    ];
}
