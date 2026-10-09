<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('website_sitemaps', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->longText('xml');
            $table->timestamps();
        });
    }

    public function test_public_sitemap_returns_shared_database_xml(): void
    {
        $xml = '<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>https://www.houseofknp.com/</loc></url></urlset>';
        DB::table('website_sitemaps')->insert([
            'id' => 1,
            'xml' => $xml,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee($xml, false);
    }

    public function test_public_sitemap_is_missing_until_uploaded(): void
    {
        $this->get('/sitemap.xml')->assertNotFound();
    }
}
