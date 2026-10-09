<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'prod_id',
        'combo_id',
        'prod_var_id',
        'review',
        'status',
        'ratings',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'ratings' => 'integer',
            'status' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'prod_id');
    }

    public function combo(): BelongsTo
    {
        return $this->belongsTo(GiftCombo::class, 'combo_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
