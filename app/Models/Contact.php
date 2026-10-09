<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'subject',
        'message',
        'created_at',
        'updated_at',
    ];
}
