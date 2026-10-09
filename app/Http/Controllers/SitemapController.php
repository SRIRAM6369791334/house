<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SitemapController extends Controller
{
    public function show()
    {
        if (! Schema::hasTable('website_sitemaps')) {
            abort(404);
        }

        $xml = DB::table('website_sitemaps')->where('id', 1)->value('xml');
        if ($xml === null) {
            abort(404);
        }

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
