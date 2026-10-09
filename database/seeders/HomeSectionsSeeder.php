<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeSectionsSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('home_sections')) {
            $this->command->warn('home_sections table does not exist. Run migrations first.');
            return;
        }

        $sections = [
            // --- Curated Collections ---
            [
                'section_key'    => 'collections_kicker',
                'section_group'  => 'Collections',
                'label'          => 'Collections - Kicker Text',
                'content'        => 'Curated Collections',
                'highlight_word' => null,
                'char_limit'     => 25,
                'sort_order'     => 1,
            ],
            [
                'section_key'    => 'collections_heading',
                'section_group'  => 'Collections',
                'label'          => 'Collections - Heading',
                'content'        => 'DISCOVER YOUR STYLE',
                'highlight_word' => 'STYLE',
                'char_limit'     => 30,
                'sort_order'     => 2,
            ],
            [
                'section_key'    => 'collections_desc',
                'section_group'  => 'Collections',
                'label'          => 'Collections - Description',
                'content'        => 'Explore our bespoke artisanal watches, bespoke apparel & signature fragrances',
                'highlight_word' => null,
                'char_limit'     => 100,
                'sort_order'     => 3,
            ],

            // --- Best Sellers ---
            [
                'section_key'    => 'bestsellers_kicker',
                'section_group'  => 'Best Sellers',
                'label'          => 'Best Sellers - Kicker Text',
                'content'        => 'House Icons',
                'highlight_word' => null,
                'char_limit'     => 25,
                'sort_order'     => 4,
            ],
            [
                'section_key'    => 'bestsellers_heading',
                'section_group'  => 'Best Sellers',
                'label'          => 'Best Sellers - Heading',
                'content'        => 'BEST SELLERS',
                'highlight_word' => 'SELLERS',
                'char_limit'     => 30,
                'sort_order'     => 5,
            ],

            // --- Philosophy ---
            [
                'section_key'    => 'philosophy_kicker',
                'section_group'  => 'Philosophy',
                'label'          => 'Philosophy - Kicker Text',
                'content'        => 'Maison Philosophy',
                'highlight_word' => null,
                'char_limit'     => 25,
                'sort_order'     => 6,
            ],
            [
                'section_key'    => 'philosophy_title_1',
                'section_group'  => 'Philosophy',
                'label'          => 'Philosophy - Title Line 1',
                'content'        => 'CRAFTED FOR BOARDROOMS.',
                'highlight_word' => null,
                'char_limit'     => 35,
                'sort_order'     => 7,
            ],
            [
                'section_key'    => 'philosophy_title_2',
                'section_group'  => 'Philosophy',
                'label'          => 'Philosophy - Title Line 2',
                'content'        => 'DESIGNED FOR CELEBRATIONS.',
                'highlight_word' => null,
                'char_limit'     => 35,
                'sort_order'     => 8,
            ],
            [
                'section_key'    => 'philosophy_title_3',
                'section_group'  => 'Philosophy',
                'label'          => 'Philosophy - Title Line 3',
                'content'        => 'MADE TO BE REMEMBERED.',
                'highlight_word' => null,
                'char_limit'     => 35,
                'sort_order'     => 9,
            ],
            [
                'section_key'    => 'philosophy_desc',
                'section_group'  => 'Philosophy',
                'label'          => 'Philosophy - Description',
                'content'        => "At HOUSE OF KNP, we don't follow passing trends.\nWe forge timeless sartorial statements and precision horology\nfor gentlemen who lead with quiet confidence and leave an indelible legacy.",
                'highlight_word' => null,
                'char_limit'     => 200,
                'sort_order'     => 10,
            ],
            [
                'section_key'    => 'philosophy_signature',
                'section_group'  => 'Philosophy',
                'label'          => 'Philosophy - Signature Name',
                'content'        => 'Kamini Logesh',
                'highlight_word' => null,
                'char_limit'     => 25,
                'sort_order'     => 11,
            ],
            [
                'section_key'    => 'philosophy_designation',
                'section_group'  => 'Philosophy',
                'label'          => 'Philosophy - Designation',
                'content'        => 'FOUNDER, HOUSE OF KNP',
                'highlight_word' => null,
                'char_limit'     => 35,
                'sort_order'     => 12,
            ],

            // --- Testimonials ---
            [
                'section_key'    => 'testimonials_kicker',
                'section_group'  => 'Testimonials',
                'label'          => 'Testimonials - Kicker Text',
                'content'        => 'Voices of Distinction',
                'highlight_word' => null,
                'char_limit'     => 25,
                'sort_order'     => 13,
            ],
            [
                'section_key'    => 'testimonials_heading',
                'section_group'  => 'Testimonials',
                'label'          => 'Testimonials - Heading',
                'content'        => 'WHAT GENTLEMEN SAY',
                'highlight_word' => 'GENTLEMEN',
                'char_limit'     => 30,
                'sort_order'     => 14,
            ],
            [
                'section_key'    => 'testimonials_desc',
                'section_group'  => 'Testimonials',
                'label'          => 'Testimonials - Description',
                'content'        => 'Real stories from collectors, leaders, and modern gentlemen',
                'highlight_word' => null,
                'char_limit'     => 100,
                'sort_order'     => 15,
            ],

            // --- Social ---
            [
                'section_key'    => 'social_kicker',
                'section_group'  => 'Social',
                'label'          => 'Social - Kicker Text',
                'content'        => 'The Social Atelier',
                'highlight_word' => null,
                'char_limit'     => 25,
                'sort_order'     => 16,
            ],
            [
                'section_key'    => 'social_heading',
                'section_group'  => 'Social',
                'label'          => 'Social - Heading',
                'content'        => 'FOLLOW @HOUSEOFKNP',
                'highlight_word' => '@HOUSEOFKNP',
                'char_limit'     => 30,
                'sort_order'     => 17,
            ],
            [
                'section_key'    => 'social_desc',
                'section_group'  => 'Social',
                'label'          => 'Social - Description',
                'content'        => 'Step inside our world of haute horlogerie, bespoke tailoring, and sensory luxury',
                'highlight_word' => null,
                'char_limit'     => 100,
                'sort_order'     => 18,
            ],

            // --- Journal ---
            [
                'section_key'    => 'journal_kicker',
                'section_group'  => 'Journal',
                'label'          => 'Journal - Kicker Text',
                'content'        => 'The Horology & Scent Chronicles',
                'highlight_word' => null,
                'char_limit'     => 35,
                'sort_order'     => 19,
            ],
            [
                'section_key'    => 'journal_heading',
                'section_group'  => 'Journal',
                'label'          => 'Journal - Heading',
                'content'        => 'FROM THE JOURNAL',
                'highlight_word' => 'JOURNAL',
                'char_limit'     => 30,
                'sort_order'     => 20,
            ],
        ];

        foreach ($sections as $section) {
            DB::table('home_sections')->updateOrInsert(
                ['section_key' => $section['section_key']],
                array_merge($section, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
