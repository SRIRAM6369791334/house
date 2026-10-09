<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    protected $fillable = [
        'unit_name',
        'short_name',
        'created_at',
        'updated_at',
    ];
}
