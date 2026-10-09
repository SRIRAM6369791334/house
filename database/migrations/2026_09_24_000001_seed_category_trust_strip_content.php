<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('home_sections') || ! Schema::hasTable('categories')) {
            return;
        }

        // Reuse the category copy already present in the product detail view.
        $content = [
            'shirt' => [
                ['10,000+', 'Happy Gentlemen'],
                ['4.9/5 Quality', 'Client Satisfaction'],
                ['Premium', 'Luxury Packaging'],
                ['Easy Returns', '15 Day Return Policy'],
                ['100% Authentic', 'Original Products'],
            ],
            'watch' => [
                ['10,000+', 'Gentlemen Trust Us'],
                ['4.9/5 Quality', 'Client Satisfaction'],
                ['Easy Returns', '15 Day Return Policy'],
                ['Secure Packaging', 'Premium Watch Box'],
                ['Dedicated Support', "We're Here For You"],
            ],
            'perfume' => [
                ['Crafted with', 'Premium Ingredients'],
                ['IFRA Certified', 'International Standards'],
                ['Non-Irritating', 'Safe on Skin'],
                ['Luxury Packaging', 'Perfect for Gifting'],
                ['Proudly Made in India', 'With Passion'],
            ],
        ];

        foreach (DB::table('categories')->select('id', 'category_name')->get() as $category) {
            $name = strtolower((string) $category->category_name);
            $type = str_contains($name, 'shirt') ? 'shirt'
                : (str_contains($name, 'watch') ? 'watch'
                    : ((str_contains($name, 'perfume') || str_contains($name, 'fragrance')) ? 'perfume' : null));
            if ($type === null) {
                continue;
            }

            foreach ($content[$type] as $index => [$title, $subtitle]) {
                $slot = $index + 1;
                foreach (['title' => $title, 'subtitle' => $subtitle] as $field => $value) {
                    $key = "trust_category_{$category->id}_{$slot}_{$field}";
                    $existing = DB::table('home_sections')->where('section_key', $key)->first();
                    if ($existing && trim((string) $existing->content) !== '') {
                        continue;
                    }
                    if ($existing) {
                        DB::table('home_sections')->where('section_key', $key)
                            ->update(['content' => $value, 'updated_at' => now()]);
                    } else {
                        DB::table('home_sections')->insert([
                            'section_key' => $key,
                            'section_group' => mb_substr('Trust Strip - '.$category->category_name, 0, 50),
                            'label' => 'Item '.$slot.' - '.($field === 'title' ? 'Heading' : 'Description'),
                            'content' => $value,
                            'highlight_word' => null,
                            'char_limit' => $field === 'title' ? 40 : 70,
                            'sort_order' => 2000 + ((int) $category->id * 20) + ($slot * 2) + ($field === 'subtitle' ? 1 : 0),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        // Keep content that may have been edited in the dashboard.
    }
};
