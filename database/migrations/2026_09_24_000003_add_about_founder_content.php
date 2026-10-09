<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('home_sections')) {
            return;
        }

        $fields = [
            ['about_founder_image', 'Founder Photo', '', 255],
            ['about_founder_kicker', 'Section Label', 'THE FOUNDER', 40],
            ['about_founder_title', 'Main Heading', 'From a Dream to a Brand', 100],
            ['about_founder_intro_desc', 'Opening Paragraph', "I'm Kaveri Logesh, the founder of HOUSE OF KNP. It started as a simple thought — why can't men's essentials be more than just ordinary?", 500],
            ['about_founder_story_desc', 'Story Paragraph', 'Driven by passion, countless late nights and a vision to build something unforgettable, HOUSE OF KNP was born.', 500],
            ['about_founder_purpose_desc', 'Purpose Paragraph', "This is not just my brand, it's my purpose.", 300],
            ['about_founder_name', 'Founder Name', 'Kaveri Logesh', 100],
            ['about_founder_role', 'Founder Role', 'FOUNDER, HOUSE OF KNP', 100],
            ['about_founder_year_heading', 'Founded Label', 'FOUNDED IN', 40],
            ['about_founder_year', 'Founded Year', '2024', 20],
            ['about_founder_mission_heading', 'Mission Label', 'OUR MISSION', 40],
            ['about_founder_mission_desc', 'Mission Text', "Redefine men's luxury essentials with timeless design and unmatched quality.", 500],
            ['about_founder_vision_heading', 'Vision Label', 'OUR VISION', 40],
            ['about_founder_vision_desc', 'Vision Text', "To be India's most trusted lifestyle brand for the modern gentleman.", 500],
        ];

        $rows = [];
        foreach ($fields as $index => [$key, $label, $content, $limit]) {
            $rows[] = [
                'section_key' => $key,
                'section_group' => 'About Page - Founder',
                'label' => $label,
                'content' => $content,
                'highlight_word' => null,
                'char_limit' => $limit,
                'sort_order' => 500 + $index,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('home_sections')->insertOrIgnore($rows);
    }

    public function down(): void
    {
        // Preserve any content edited later in the dashboard.
    }
};
