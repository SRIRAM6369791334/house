<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkOrder extends Model
{
    protected $table = 'bulkorders';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'phone',
        'message',
        'created_at',
        'updated_at',
    ];
}
