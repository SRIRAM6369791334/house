<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\GiftCategory;
use App\Models\HomePromotion;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ReviewVideo;
use App\Models\Testimonial;
use App\Models\WebImage;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $bannerVideos = WebImage::query()
            ->whereNotNull('video')
            ->where('video', '!=', '')
            ->orderByDesc('id')
            ->get();
        $bannerVideo = $bannerVideos->first();
        $reviewVideos = ReviewVideo::query()
            ->where('status', 1)
            ->whereNotNull('video')
            ->where('video', '!=', '')
            ->orderByDesc('id')
            ->get();
        $testimonials = Testimonial::query()
            ->orderByDesc('id')
            ->get();
        $homeCategories = Category::query()
            ->select('id', 'category_name', 'category_image', 'category_banner')
            ->orderBy('id')
            ->get();
        $homeCategoryProducts = $homeCategories->map(function ($category) {
            $product = Product::query()->forStorefront()->where('p.category_id', $category->id)
                ->orderBy('p.id')
                ->first();
            if (! $product) {
                return null;
            }
            $variantImage = ProductVariant::query()
                ->where('product_id', $product->id)
                ->whereNotNull('varient_img')
                ->where('varient_img', '!=', '')
                ->orderBy('id')
                ->value('varient_img');
            $product->home_image = $variantImage ? house_main_media_url($variantImage, 'images') : house_main_product_image_url($product);
            $product->home_category_name = $category->category_name;
            $product->home_category_id = $category->id;
            $product->home_price = (float) house_product_price($product);
            $product->home_mrp = (float) ($product->mrp_price ?? $product->product_mrp_price ?? $product->home_price);

            return $product;
        })
            ->filter()
            ->values();
        $latestCollectionProducts = Product::query()->forStorefront()->whereExists(function ($query) {
            $query->selectRaw('1')
                ->from('product_varient as latest_variant')
                ->whereColumn('latest_variant.product_id', 'p.id')
                ->where('latest_variant.Popular_products', 1);
        })
            ->orderByDesc('p.id')
            ->get()
            ->map(function ($product) {
                $latestVariant = ProductVariant::query()
                    ->where('product_id', $product->id)
                    ->where('Popular_products', 1)
                    ->orderByDesc('id')
                    ->first();
                $product->latest_image = ! empty($latestVariant?->varient_img) ? house_main_media_url($latestVariant->varient_img, 'images') : house_main_product_image_url($product);
                $product->latest_price = (float) ($latestVariant?->offer_price ?? house_product_price($product));
                $product->latest_mrp = (float) ($latestVariant?->mrp_price ?? $product->mrp_price ?? $product->product_mrp_price ?? $product->latest_price);

                return $product;
            });
        $trendingCollectionProducts = Product::query()->forStorefront()->whereExists(function ($query) {
            $query->selectRaw('1')
                ->from('product_varient as trending_variant')
                ->whereColumn('trending_variant.product_id', 'p.id')
                ->where('trending_variant.hot_deals', 1);
        })
            ->orderByDesc('p.id')
            ->get()
            ->map(function ($product) {
                $trendingVariant = ProductVariant::query()
                    ->where('product_id', $product->id)
                    ->where('hot_deals', 1)
                    ->orderByDesc('id')
                    ->first();
                $product->trending_image = ! empty($trendingVariant?->varient_img) ? house_main_media_url($trendingVariant->varient_img, 'images') : house_main_product_image_url($product);
                $product->trending_price = (float) ($trendingVariant?->offer_price ?? house_product_price($product));
                $product->trending_mrp = (float) ($trendingVariant?->mrp_price ?? $product->mrp_price ?? $product->product_mrp_price ?? $product->trending_price);

                return $product;
            });
        $giftBanners = GiftCategory::query()
            ->where('status', 1)
            ->whereNotNull('category_image')
            ->where('category_image', '!=', '')
            ->orderByDesc('id')
            ->get();
        $giftBanner = $giftBanners->first();
        $giftBannerImage = $giftBanner?->category_image ? house_main_media_url($giftBanner->category_image, 'images') : $homeCategoryProducts->first()?->home_image ?? ($homeCategories->first()?->category_banner ? house_main_media_url($homeCategories->first()->category_banner, 'images') : null) ?? ($homeCategories->first()?->category_image ? house_main_media_url($homeCategories->first()->category_image, 'images') : asset('images/knp/combo_box.png'));
        $giftBannerTotal = $homeCategoryProducts->sum('home_price');
        $giftBannerMrp = $homeCategoryProducts->sum(fn ($product) => $product->home_mrp ?: $product->home_price);
        $giftBannerTitle = $giftBanner?->banner_title ?: ($homeCategoryProducts->isNotEmpty() ? $homeCategoryProducts->pluck('home_category_name')
            ->implode(' + ') : 'The Executive Suite');
        $giftBannerDescription = $giftBanner?->banner_description;
        $instagramImages = HomePromotion::query()
            ->select('id', 'bg_image', 'link_url', 'platform')
            ->whereNotNull('bg_image')
            ->where('bg_image', '!=', '')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
        $latestBlogs = Blog::query()
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
        // --- Dynamic Home Section Content ---
        $homeContent = [];
        $homeSections = HomeSection::query()
            ->orderBy('sort_order')
            ->get();

        foreach ($homeSections as $row) {
            $homeContent[$row->section_key] = $row->content;
            if ($row->highlight_word) {
                $homeContent[$row->section_key.'_html'] = str_replace($row->highlight_word, '<span class="knp-red-text">'.e($row->highlight_word).'</span>', e($row->content));
            } else {
                $homeContent[$row->section_key.'_html'] = e($row->content);
            }
        }

        return view('pages.home', compact('bannerVideo', 'bannerVideos', 'reviewVideos', 'testimonials', 'homeCategories', 'homeCategoryProducts', 'latestCollectionProducts', 'trendingCollectionProducts', 'giftBanners', 'giftBannerImage', 'giftBannerTotal', 'giftBannerMrp', 'giftBannerTitle', 'giftBannerDescription', 'instagramImages', 'latestBlogs', 'homeContent'));
    }
}
