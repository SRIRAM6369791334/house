<?php

namespace App\Http\Controllers;

use App\Models\ReviewVideo;
use App\Models\HomeSection;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function about()
    {
        $aboutContent = [];
        try {
            if (Schema::hasTable('home_sections')) {
                $aboutContent = HomeSection::query()
                    ->where('section_key', 'like', 'about_founder_%')
                    ->pluck('content', 'section_key')
                    ->all();
            }
        } catch (\Throwable $exception) {
            // The current founder copy remains available while content storage is unavailable.
        }
        $reviewVideos = ReviewVideo::query()
            ->where('status', 1)
            ->whereNotNull('video')
            ->where('video', '!=', '')
            ->orderByDesc('id')
            ->get();

        return view('pages.about', compact('reviewVideos', 'aboutContent'));
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function termsCondition()
    {
        return view('pages.terms-condition');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function returnRefundPolicy()
    {
        return view('pages.return-refund-policy');
    }

    public function shippingPolicy()
    {
        return view('pages.shipping-policy');
    }

    public function exchangePolicy()
    {
        return view('pages.exchange-policy');
    }

    public function automaticWatchExchangePolicy()
    {
        return view('pages.automatic-watch-exchange-policy');
    }

    public function cookiesPolicy()
    {
        return view('pages.cookies-policy');
    }
}
