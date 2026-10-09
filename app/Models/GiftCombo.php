<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftCombo extends Model
{
    protected $table = 'gift_combos';

    protected $fillable = [
        'gift_category_id',
        'gift_subcategory_id',
        'combo_name',
        'combo_image',
        'combo_image_2',
        'combo_image_3',
        'variant_name',
        'mrp_price',
        'offer_price',
        'additional_payment',
        'created_at',
        'updated_at',
        'stock_quantity',
        'weight',
        'unit_id',
        'product_value',
        'fit',
        'features',
        'low_stock',
        'gst',
        'trending_collection',
        'popular_prod',
        'product_description',
        'combo_details',
        'shirt_color',
        'shirt_size',
        'perfume_ml',
        'watch_model',
        'thumbnail_images',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GiftCategory::class, 'gift_category_id');
    }

    public function galleryImages(): array
    {
        $images = [];
        foreach ([$this->combo_image, $this->combo_image_2, $this->combo_image_3, $this->thumbnail_images] as $raw) {
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            $values = is_array($decoded) ? $decoded : [$raw];
            foreach ($values as $value) {
                if (is_string($value) && trim($value) !== '') {
                    $images[] = house_main_media_url(trim($value), 'images');
                }
            }
        }

        return array_values(array_unique($images));
    }

    public function displayItems(): array
    {
        $details = is_string($this->combo_details) ? json_decode($this->combo_details, true) : $this->combo_details;
        $details = is_array($details) ? $details : [];
        $color = house_color_label($this->shirt_color);
        $volume = trim((string) $this->perfume_ml);
        $volume = is_numeric($volume) ? $volume.' ml' : $volume;
        $specs = [
            1 => array_filter([$color ? 'Color: '.$color : null, $this->shirt_size ? 'Sizes: '.$this->shirt_size : null, $this->fit ? 'Fit: '.$this->fit : null]),
            2 => array_filter([$this->watch_model ? 'Model: '.$this->watch_model : null]),
            3 => array_filter([$volume ? 'Volume: '.$volume : null]),
        ];
        $items = [];
        foreach ([1 => 'Shirt', 2 => 'Watch', 3 => 'Perfume'] as $index => $label) {
            $name = trim((string) ($details['item_'.$index.'_name'] ?? ''));
            $image = trim((string) ($details['item_'.$index.'_img'] ?? ''));
            if ($name !== '' || $specs[$index] !== [] || $image !== '') {
                $items[] = [
                    'name' => $name ?: $label,
                    'details' => implode(' / ', $specs[$index]),
                    'image' => $image !== '' ? house_main_media_url($image, 'images') : null,
                ];
            }
        }

        return $items;
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'gift_subcategory_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'combo_id');
    }
}
