@extends('layouts.app')

@php
    $money = function ($value) {
        return house_money($value);
    };

    $productImages = function ($product) {
        return house_product_images($product);
    };

    $productPrice = function ($product) {
        return house_product_price($product);
    };

    $oldPrice = function ($product) use ($productPrice) {
        $current = (float) $productPrice($product);
        $mrp = (float) ($product->mrp_price ?? ($product->product_mrp_price ?? 0));

        return $mrp > $current ? $mrp : null;
    };

    $productUrl = fn($product) => url('single-product') .
        '?' .
        http_build_query([
            'product' => $product->slug ?: $product->id,
        ]);

    $description = function ($product) {
        $text = $product->product_description ?: $product->product_specification ?: '';
        return \Illuminate\Support\Str::limit(strip_tags($text), 220);
    };

    $reqCat = strtolower(request('category'));
    if ($reqCat == 'fragrance') {
        $reqCat = 'perfume';
    }

    $pageTitle = 'Luxury Satin Shirts';
    $catObj = null;
    if(isset($selectedCategory) && $selectedCategory) {
        $catObj = collect($categories)->firstWhere('id', $selectedCategory) ?? collect($categories)->firstWhere('slug', $selectedCategory) ?? collect($categories)->first(fn($c) => \Illuminate\Support\Str::slug($c->category_name) === strtolower($selectedCategory));
        if($catObj) {
            $pageTitle = $catObj->category_name;
            $pageDesc = 'Explore the exclusive ' . $catObj->category_name . ' collection at House of KNP. Handcrafted luxury, premium fabrics, and timeless style.';
        }
    }

    $seoTitle = $catObj ? ($catObj->category_name . ' Collection — Luxury Menswear | House of KNP') : 'Shop — Luxury Mens Fashion | House of KNP';
    $seoDesc = $catObj ? ('Discover handcrafted ' . $catObj->category_name . ' for modern gentlemen at House of KNP. Premium craftsmanship, flawless fit and free shipping in India.') : 'Explore House of KNP premium collections of luxury shirts, automatic watches, perfumes, and bespoke gifting.';
    $seoCanonical = $catObj ? url('/category/' . \Illuminate\Support\Str::slug($catObj->category_name)) : url('/shop');
    $seoKeywords = $catObj ? ($catObj->category_name . ', mens ' . strtolower($catObj->category_name) . ', luxury ' . strtolower($catObj->category_name) . ', House of KNP') : 'House of KNP shop, mens style, luxury shirts, watches, perfumes';
    $shopBaseUrl = $catObj ? url('category/' . \Illuminate\Support\Str::slug($catObj->category_name)) : url('shop');
@endphp

@section('meta_title', $seoTitle)
@section('meta_description', $seoDesc)
@section('meta_keywords', $seoKeywords)
@section('canonical_url', $seoCanonical)

@section('content')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ url('/') }}"
    },
    {
      "@@type": "ListItem",
      "position": 2,
      "name": "Shop",
      "item": "{{ url('/shop') }}"
    }
    @if(isset($catObj) && $catObj)
    ,{
      "@@type": "ListItem",
      "position": 3,
      "name": "{{ $catObj->category_name }}",
      "item": "{{ url('/category/' . \Illuminate\Support\Str::slug($catObj->category_name)) }}"
    }
    @endif
  ]
}
</script>
<style>
    :root {
        --knp-red: #B40016;
        --knp-dark: #111111;
        --knp-light: #F9F9F9;
        --knp-gray: #EAEAEA;
    }
.hero-image {
    width: 100%;
    height: auto;
    object-fit: cover;
}
    .shop-wrapper {
        font-family: 'Manrope', sans-serif;
        color: var(--knp-dark);
        background: #FFFFFF;
        width: 100%;
        overflow-x: hidden;
    }

    .knp-serif {
        font-family: 'Cormorant Garamond', Georgia, serif;
    }

    .section-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* 1. HERO SECTION */
    .shop-hero {
        position: relative;
        width: 100%;
        background: #FFF;
        /* height: 440px; */
        display: flex;
        align-items: center;
        overflow: hidden;
    }
    .shop-hero-bg {
        position: absolute;
        top: 0;
        right: 0;
        width: 65%;
        height: 100%;
        z-index: 1;
    }
    .shop-hero-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    .shop-hero-content-container {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
        display: flex;
    }
    .shop-hero-content {
        background: #FFF;
        padding: 80px 150px 80px 20px;
        width: 55%;
        clip-path: polygon(0 0, 100% 0, 80% 100%, 0% 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 440px;
    }
    .hero-kicker {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--knp-red);
        margin-bottom: 15px;
        display: block;
    }
    .hero-title {
        font-size: 52px;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 15px;
        color: var(--knp-dark);
    }
    .hero-desc {
        font-size: 15px;
        line-height: 1.8;
        color: #444;
        margin-bottom: 30px;
        max-width: 400px;
    }
    .hero-btn {
        display: inline-flex;
        align-items: center;
        background: #FFF;
        color: var(--knp-red);
        border: 1px solid var(--knp-red);
        padding: 12px 25px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        border-radius: 2px;
        width: max-content;
        transition: 0.3s ease;
    }
    .hero-btn:hover {
        background: var(--knp-red);
        color: #FFF;
    }

    /* 2. TOP BAR */
    .shop-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid var(--knp-gray);
        margin-bottom: 40px;
    }
    .breadcrumb {
        font-size: 12px;
        color: #888;
        background: transparent;
        padding: 0;
        margin: 0;
    }
    .breadcrumb a {
        color: #888;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        color: var(--knp-red);
    }
    .breadcrumb span {
        margin: 0 5px;
    }
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 12px;
        color: #666;
    }
    .sort-select {
        border: 1px solid var(--knp-gray);
        padding: 8px 15px;
        border-radius: 4px;
        font-size: 12px;
        outline: none;
        background: #FFF;
        font-weight: 700;
    }

    /* 3. MAIN LAYOUT */
    .shop-layout {
        display: flex;
        gap: 40px;
        align-items: flex-start;
    }
    .shop-products {
        flex: 1;
        min-width: 0;
        width: 100%;
    }

    /* SIDEBAR */
    .shop-sidebar {
        flex: 0 0 260px;
        background: #F9F9F9;
        padding: 25px 20px;
        border-radius: 8px;
        border: 1px solid #EAEAEA;
    }
    .filter-widget {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--knp-gray);
    }
    .filter-widget:last-child {
        border-bottom: none;
    }
    .fw-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .fw-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .fw-list li {
        margin-bottom: 12px;
    }
    .fw-list li a {
        font-size: 13px;
        color: #555;
        text-decoration: none;
        display: flex;
        justify-content: space-between;
    }
    .fw-list li a:hover, .fw-list li a.active {
        color: var(--knp-red);
        font-weight: 700;
    }

    /* COLORS */
    .color-swatches {
        display: flex;
        flex-direction: column;
        gap: 6px;
        max-height: 280px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .color-swatches::-webkit-scrollbar {
        width: 4px;
    }
    .color-swatches::-webkit-scrollbar-thumb {
        background: #DDD;
        border-radius: 4px;
    }
    .color-swatches::-webkit-scrollbar-thumb:hover {
        background: #BBB;
    }
    .color-swatch-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #555;
        width: 100%;
        cursor: pointer;
        padding: 6px 8px;
        border-radius: 6px;
        transition: all 0.2s ease;
        user-select: none;
    }
    .color-swatch-item:hover {
        background: rgba(0, 0, 0, 0.04);
        color: var(--knp-dark);
    }
    .color-swatch-item.active {
        background: rgba(180, 0, 22, 0.06);
        color: var(--knp-red);
        font-weight: 700;
    }
    .c-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
        border: 1.5px solid rgba(0, 0, 0, 0.15);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease;
    }
    .color-swatch-item:hover .c-dot {
        transform: scale(1.15);
    }
    .c-dot.active {
        border: 2px solid var(--knp-red);
        box-shadow: 0 0 0 2px rgba(180, 0, 22, 0.25);
    }
    .color-swatch-name {
        font-size: 12.5px;
        color: inherit;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* SIZES */
    .size-boxes {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .size-box {
        width: 40px;
        height: 40px;
        border: 1px solid var(--knp-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #555;
        text-decoration: none;
        border-radius: 4px;
        transition: 0.3s ease;
    }
    .size-box:hover, .size-box.active {
        background: var(--knp-dark);
        color: #FFF;
        border-color: var(--knp-dark);
    }

    /* CHECKBOXES */
    .checkbox-list label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #555;
        margin-bottom: 12px;
        cursor: pointer;
    }
    .checkbox-list input[type="checkbox"] {
        accent-color: var(--knp-red);
        width: 16px;
        height: 16px;
    }

    /* PRICE SLIDER */
    .price-range-wrapper {
        padding-top: 10px;
    }
    .price-slider-input {
        width: 100%;
        accent-color: var(--knp-red);
    }
    .price-range-labels {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #666;
        margin-top: 10px;
    }

    .clear-filters-btn {
        display: block;
        width: 100%;
        padding: 12px;
        text-align: center;
        border: 1px solid #EAA;
        color: var(--knp-red);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        border-radius: 4px;
        transition: 0.3s ease;
    }
    .clear-filters-btn:hover {
        background: var(--knp-red);
        color: #FFF;
    }

    /* GRID */
    .shop-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        padding-bottom: 0;
    }
    .product-card {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: var(--knp-dark);
        position: relative;
        border: 1px solid #EAEAEA;
        border-radius: 8px;
        overflow: hidden;
        background: #FFF;
    }
    .product-img-wrapper {
        position: relative;
        background: var(--knp-light);
        width: 100%;
        aspect-ratio: 2 / 3;
        border-radius: 0;
        overflow: hidden;
        margin-bottom: 0;
    }
    .product-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
    }
    .product-img-wrapper .product-image-secondary {
        position: absolute;
        inset: 0;
        opacity: 0;
    }
    .product-card:hover .product-img-wrapper .product-image-primary {
        opacity: 0;
    }
    .product-card:hover .product-img-wrapper .product-image-secondary {
        opacity: 1;
    }
    .product-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .badge-red {
        background: var(--knp-red);
        color: #FFF;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 4px 8px;
        border-radius: 2px;
        letter-spacing: 1px;
    }
    .badge-dark {
        background: var(--knp-dark);
        color: #FFF;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 4px 8px;
        border-radius: 2px;
        letter-spacing: 1px;
    }
    .wishlist-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        color: #FFF;
        cursor: pointer;
    }
    .wishlist-btn svg {
        width: 20px;
        height: 20px;
        fill: transparent;
        stroke: #FFF;
        stroke-width: 2;
        transition: 0.2s ease;
        filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.5));
    }
    .wishlist-btn:hover svg {
        fill: var(--knp-red);
        stroke: var(--knp-red);
    }

    .product-colors {
        display: flex;
        gap: 5px;
        margin-bottom: 8px;
    }
    .pc-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid #CCC;
    }
    .product-info {
        padding: 10px 10px 10px 10px;
    }

    .product-title {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 4px;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #000;
    }
    .product-subtitle {
        font-size: 14px;
        color: #888;
        margin-bottom: 8px;
    }
    .product-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        color: #888;
        margin-bottom: 10px;
    }
    .stars {
        color: var(--knp-red);
        font-size: 12px;
    }
    .product-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    .product-price {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .current-price {
        font-size: 18px;
        font-weight: 800;
        color: #000;
    }
    .old-price {
        font-size: 12px;
        color: #999;
        text-decoration: line-through;
    }
    .add-to-bag {
        color: var(--knp-red);
        border: 1px solid rgba(180, 0, 22, 0.3);
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: 0.3s ease;
        background: #FFF;
    }
    .add-to-bag:hover {
        background: var(--knp-red);
        color: #FFF;
        border-color: var(--knp-red);
    }

    /* 5. BOTTOM FEATURES */
    .features-strip {
        background: var(--knp-light);
        padding: 40px 0;
        margin-top: 40px;
        border-top: 1px solid var(--knp-gray);
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
    }
    .feature-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .feature-icon {
        color: var(--knp-red);
    }
    .feature-text h5 {
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 3px;
        text-transform: uppercase;
    }
    .feature-text p {
        font-size: 12px;
        color: #666;
        margin: 0;
    }

    .mobile-filter-btn {
        display: none;
        background: transparent;
        border: 1px solid var(--knp-dark);
        color: var(--knp-dark);
        padding: 8px 15px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.3s ease;
    }
    .mobile-filter-btn:hover {
        background: var(--knp-dark);
        color: #FFF;
    }

    @media (max-width: 1200px) {
        .shop-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }

    @media (max-width: 991px) {
        .shop-hero { flex-direction: column; height: auto; min-height: auto; }
        .shop-hero-content { width: 100%; clip-path: none; padding: 40px 20px; min-height: auto; }
        .shop-hero-bg { position: relative; width: 100%; height: auto; }
        .shop-hero-bg img { height: auto; object-fit: contain; }

        .shop-layout { flex-direction: column; }
        .shop-sidebar { width: 100%; display: none; margin-bottom: 20px; }
        .shop-sidebar.active { display: block; }
        .mobile-filter-btn { display: inline-flex; align-items: center; justify-content: center; gap: 5px; }
        .shop-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 15px; }
        .features-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767px) {
        .features-grid { grid-template-columns: minmax(0, 1fr); }
        
        .shop-topbar { flex-direction: column; align-items: stretch; gap: 15px; }
        .mobile-filter-btn { width: 100%; padding: 12px; }
        .topbar-right { display: none; }
        
        /* Scale down product cards for 2-column mobile layout */
        .product-title { font-size: 13px; margin-bottom: 2px; }
        .product-subtitle { font-size: 11px; margin-bottom: 4px; }
        .current-price { font-size: 14px; }
        .old-price { font-size: 10px; }
        .add-to-bag { width: 28px; height: 28px; }
        .badge-red, .badge-dark { font-size: 8px; padding: 3px 5px; }

        .hero-title { font-size: 36px; }
        #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
            display: none !important;
        }
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        margin-top: 32px;
        margin-bottom: 32px;
    }
    .pagination-wrapper .pagination-summary {
        margin: 0;
        color: #777;
        font-size: 12px;
    }
    .pagination-wrapper .pagination {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .pagination-wrapper .page-link {
        display: inline-flex;
        min-width: 38px;
        height: 38px;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2dbd3;
        border-radius: 6px;
        color: #242220;
        font-size: 13px;
        text-decoration: none;
    }
    .pagination-wrapper .page-item.active .page-link,
    .pagination-wrapper .page-link:hover {
        border-color: var(--knp-red);
        background: var(--knp-red);
        color: #fff;
    }
    .pagination-wrapper .page-item.disabled .page-link {
        color: #aaa;
        background: #f8f8f8;
        cursor: default;
    }
</style>

<div class="shop-wrapper">

    <!-- 1. HERO SECTION -->
    <section class="shop-hero">
        @php
            $catStr = strtolower(request('category'));
            $defaultHeroImg = 'shop.png';
            if ($catStr == 'fragrance' || str_contains(strtolower($pageTitle), 'fragrance')) {
                $defaultHeroImg = 'perfuem.png';
            } elseif ($catStr == 'watches' || str_contains(strtolower($pageTitle), 'watch')) {
                $defaultHeroImg = 'watch.png';
            } elseif ($catStr == 'shirts' || str_contains(strtolower($pageTitle), 'shirt')) {
                $defaultHeroImg = 'shirt.png';
            } elseif ($catStr == 'signature-box' || str_contains(strtolower($pageTitle), 'box')) {
                $defaultHeroImg = 'box.png';
            }

            $heroBannerSrc = !empty($currentCategory?->category_banner) 
                ? house_category_banner_url($currentCategory->category_banner, $defaultHeroImg) 
                : asset('images/banner/' . $defaultHeroImg);
        @endphp
        <div class="hero-section">
            <img src="{{ $heroBannerSrc }}" onerror="this.src='{{ asset('images/banner/' . $defaultHeroImg) }}'" alt="{{ $pageTitle }}" class="hero-image">
        </div>
        <div class="shop-hero-content-container" style="display: none;">
            <div class="shop-hero-content"
                 @if(strtolower(request('category')) == 'fragrance' || $pageTitle == 'Fragrance' || $pageTitle == 'Fragrances')
                    style="clip-path: none; background: linear-gradient(to right, #FFF 60%, transparent 100%); width: 60%; padding-right: 50px;"
                 @endif>
                <span class="hero-kicker">
                    @if(strtolower(request('category')) == 'watches' || $pageTitle == 'Watches')
                        TIME THAT DEFINES YOU.
                    @else
                        CRAFTED TO COMMAND ATTENTION
                    @endif
                </span>
                <h1 class="hero-title knp-serif">
                    @if($pageTitle == 'Shirts' || $pageTitle == 'Luxury Satin Shirts')
                        Luxury <span style="color: var(--knp-red); font-family: inherit; font-weight: inherit;">Satin Shirts</span>
                    @elseif(strtolower(request('category')) == 'fragrance' || $pageTitle == 'Fragrance' || $pageTitle == 'Fragrances')
                        Signature <span style="color: var(--knp-red); font-family: inherit; font-weight: inherit;">Fragrances</span>
                    @elseif(strtolower(request('category')) == 'watches' || $pageTitle == 'Watches')
                        Luxury <span style="color: var(--knp-red); font-family: inherit; font-weight: inherit;">Watches</span>
                    @else
                        {{ $pageTitle }}
                    @endif
                </h1>
                @if($pageTitle == 'Shirts' || $pageTitle == 'Luxury Satin Shirts' || strtolower(request('category')) == 'fragrance' || $pageTitle == 'Fragrance' || $pageTitle == 'Fragrances' || strtolower(request('category')) == 'watches' || $pageTitle == 'Watches')
                    <div style="width: 40px; height: 2px; background-color: var(--knp-red); margin-top: -5px; margin-bottom: 20px;"></div>
                @endif
                <p class="hero-desc">
                    @if($pageTitle == 'Shirts' || $pageTitle == 'Luxury Satin Shirts')
                        Premium satin. Flawless fit.<br>
                        Made for men who lead with style.
                    @elseif(strtolower(request('category')) == 'fragrance' || $pageTitle == 'Fragrance' || $pageTitle == 'Fragrances')
                        Scents that speak before you do.<br>
                        Crafted to leave a lasting impression.
                    @elseif(strtolower(request('category')) == 'watches' || $pageTitle == 'Watches')
                        Crafted with precision. Designed for presence.<br>
                        Explore our collection of automatic timepieces.
                    @else
                        {{ $pageDesc }}
                    @endif
                </p>
                @if(strtolower(request('category')) == 'fragrance' || $pageTitle == 'Fragrance' || $pageTitle == 'Fragrances')
                    <a href="#products-start" class="hero-btn" style="background-color: var(--knp-red); color: #FFF; margin-bottom: 30px; border-color: var(--knp-red);">EXPLORE COLLECTION &rarr;</a>

                    <div style="display: flex; gap: 25px; margin-top: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--knp-red)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            <div style="line-height: 1.3;">
                                <div style="font-size: 11px; font-weight: 700; color: var(--knp-dark);">Premium Ingredients</div>
                                <div style="font-size: 10px; color: #777;">Sourced Globally</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--knp-red)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <div style="line-height: 1.3;">
                                <div style="font-size: 11px; font-weight: 700; color: var(--knp-dark);">Long Lasting</div>
                                <div style="font-size: 10px; color: #777;">8-10 Hours</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--knp-red)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"></path></svg>
                            <div style="line-height: 1.3;">
                                <div style="font-size: 11px; font-weight: 700; color: var(--knp-dark);">Skin Friendly</div>
                                <div style="font-size: 10px; color: #777;">Dermatologically Tested</div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="#products-start" class="hero-btn">EXPLORE COLLECTION &rarr;</a>
                @endif
            </div>
        </div>
    </section>

    <div class="section-container" id="products-start">
        <!-- 2. TOP BAR -->
        <div class="shop-topbar">
            <div class="breadcrumb">
                <a href="{{ url('/') }}">Home</a> <span>&rsaquo;</span>
                @if($catObj)
                    <a href="{{ url('shop') }}">Shop</a> <span>&rsaquo;</span> <strong>{{ $pageTitle }}</strong>
                @else
                    <strong>Shop</strong>
                @endif
            </div>
            
            <button type="button" class="mobile-filter-btn" onclick="document.querySelector('.shop-sidebar').classList.toggle('active')">
                <i class="fa fa-filter"></i> Filters
            </button>

            <div class="topbar-right">
                <span>Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} products</span>
                <form action="{{ $shopBaseUrl }}" method="GET" id="sort-form">
                    @if(!$catObj && request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('sub'))
                        <input type="hidden" name="sub" value="{{ request('sub') }}">
                    @endif
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request('color'))
                        <input type="hidden" name="color" value="{{ request('color') }}">
                    @endif
                    @if(request('size'))
                        <input type="hidden" name="size" value="{{ request('size') }}">
                    @endif
                    @if(request('fit'))
                        <input type="hidden" name="fit" value="{{ request('fit') }}">
                    @endif
                    @if(request('strap'))
                        <input type="hidden" name="strap" value="{{ request('strap') }}">
                    @endif
                    @if(request('scent'))
                        <input type="hidden" name="scent" value="{{ request('scent') }}">
                    @endif
                    @if(request('volume'))
                        <input type="hidden" name="volume" value="{{ request('volume') }}">
                    @endif
                    @if(request('features'))
                        <input type="hidden" name="features" value="{{ request('features') }}">
                    @endif
                    @if(request('max_price'))
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @endif
                    <select name="sort" class="sort-select" onchange="submitCleanFilterForm(this.form)">
                        <option value="">Best Selling</option>
                        <option value="price_asc" {{ in_array(request('sort'), ['price_asc', 'price_low']) ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ in_array(request('sort'), ['price_desc', 'price_high']) ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- 3. MAIN LAYOUT -->
        <div class="shop-layout">
            <!-- SIDEBAR -->
            <aside class="shop-sidebar">
                <form action="{{ $shopBaseUrl }}" method="GET" id="filter-form">
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if(!$catObj && request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('sub'))
                        <input type="hidden" name="sub" value="{{ request('sub') }}">
                    @endif
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- CATEGORIES -->
                    <div class="filter-widget">
                        <div class="fw-title">CATEGORIES <span>&minus;</span></div>
                          <ul class="fw-list">
                              @if(!$catObj)
                                  <li style="display: block; width: 100%; margin-bottom: 15px;"><a href="{{ url('shop') }}" class="active" style="color: var(--knp-red); font-weight: 700;">All Products</a></li>
                                  @foreach ($categories as $category)
                                      <li style="display: block; width: 100%; margin-bottom: 15px;">
                                          <a href="{{ url('category/' . \Illuminate\Support\Str::slug($category->category_name)) }}"
                                             style="color: var(--knp-dark); font-weight: 500;">
                                              {{ $category->category_name }}
                                              <span>({{ $category->products_count ?? 0 }})</span>
                                          </a>
                                      </li>
                                  @endforeach
                              @else
                                  <li style="display: block; width: 100%; margin-bottom: 15px;">
                                      <a href="{{ url('shop') }}" style="color: #777; font-size: 0.95em;">&larr; All Products</a>
                                  </li>
                                  @foreach ($categories as $category)
                                      @if($catObj->id == $category->id || $reqCat == \Illuminate\Support\Str::slug($category->category_name))
                                          <li style="display: block; width: 100%; margin-bottom: 15px;">
                                              <a href="{{ url('category/' . \Illuminate\Support\Str::slug($category->category_name)) }}"
                                                 class="{{ !request('sub') ? 'active' : '' }}"
                                                 style="color: {{ !request('sub') ? 'var(--knp-red)' : 'var(--knp-dark)' }}; font-weight: 700;">
                                                  All {{ $category->category_name }}
                                                  <span>({{ $category->products_count ?? 0 }})</span>
                                              </a>
                                          </li>
                                          @if(isset($allSubcategoriesGrouped[$category->id]))
                                              @foreach($allSubcategoriesGrouped[$category->id] as $subcat)
                                                  <li style="display: block; width: 100%; margin-bottom: 15px;">
                                                      <a href="{{ url('category/' . \Illuminate\Support\Str::slug($category->category_name) . '?sub=' . \Illuminate\Support\Str::slug($subcat->subcategory_name)) }}"
                                                         class="{{ request('sub') == \Illuminate\Support\Str::slug($subcat->subcategory_name) ? 'active' : '' }}"
                                                         style="font-size: 0.95em; font-weight: 500; color: {{ request('sub') == \Illuminate\Support\Str::slug($subcat->subcategory_name) ? 'var(--knp-red)' : 'var(--knp-dark)' }};">
                                                          {{ $subcat->subcategory_name }}
                                                          <span>({{ $subcat->products_count ?? 0 }})</span>
                                                      </a>
                                                  </li>
                                              @endforeach
                                          @endif
                                      @endif
                                  @endforeach
                              @endif
                          </ul>
                    </div>

                    {{-- ===================================================
                         CATEGORY-SPECIFIC FILTERS (USER APPROVED)
                         =================================================== --}}

                    @if($isShirtCategory)
                        {{-- 👔 1. SHIRTS: COLOR, SIZE, FIT TYPE, PRICE RANGE --}}

                        <!-- COLOR (Shirts) -->
                        @if($colorOptions->isNotEmpty())
                        <div class="filter-widget">
                            <div class="fw-title">COLOR <span>&minus;</span></div>
                            <input type="hidden" name="color" id="filter-color" value="{{ request('color') }}" {{ !request('color') ? 'disabled' : '' }}>
                            <div class="color-swatches">
                                <div class="color-swatch-item {{ !request('color') ? 'active' : '' }}" onclick="selectColorFilter('');">
                                    <span class="c-dot {{ !request('color') ? 'active' : '' }}" style="background: linear-gradient(135deg, red, blue, green);"></span>
                                    <span class="color-swatch-name">All</span>
                                </div>
                                @foreach($colorOptions as $colorOption)
                                    @php
                                        $isColorActive = (request('color') == $colorOption['value']);
                                    @endphp
                                    <div class="color-swatch-item {{ $isColorActive ? 'active' : '' }}" role="button" tabindex="0" data-color="{{ $colorOption['value'] }}" onclick="selectColorFilter(this.dataset.color);" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); selectColorFilter(this.dataset.color); }">
                                        <span class="c-dot {{ $isColorActive ? 'active' : '' }}" style="background: {{ $colorOption['swatch'] }};"></span>
                                        <span class="color-swatch-name">{{ $colorOption['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- SIZE (Shirts) -->
                        @if($sizeOptions->isNotEmpty())
                        <div class="filter-widget">
                            <div class="fw-title">SIZE <span>&minus;</span></div>
                            <p style="font-size: 12px; color: var(--knp-red); font-weight: 700; margin-bottom: 10px;">
                                <a href="#" onclick="selectSizeFilter(''); return false;" class="{{ !request('size') ? 'active' : '' }}" style="color: inherit; text-decoration: none;">All Sizes</a>
                            </p>
                            <input type="hidden" name="size" id="filter-size" value="{{ request('size') }}" {{ !request('size') ? 'disabled' : '' }}>
                            <div class="size-boxes">
                                @foreach($sizeOptions as $size)
                                    <a href="#" class="size-box {{ request('size') == $size ? 'active' : '' }}" onclick="selectSizeFilter('{{ $size }}'); return false;">{{ $size }}</a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- FIT TYPE (Shirts) -->
                        <div class="filter-widget">
                            <div class="fw-title">FIT TYPE <span>&minus;</span></div>
                            <div class="checkbox-list">
                                <label>
                                    <input type="checkbox" name="fit" value="" {{ !request('fit') ? 'checked' : '' }} onchange="toggleFitFilter(this, '')">
                                    <span style="color:var(--knp-red); font-weight:700;">All Fits</span>
                                </label>
                                @foreach($shirtFitOptions as $fit)
                                    @php
                                        $isFitChecked = (request('fit') == $fit || strtolower(request('fit')) == strtolower(str_replace(' Fit', '', $fit)));
                                    @endphp
                                    <label>
                                        <input type="checkbox" name="fit" value="{{ $fit }}" {{ $isFitChecked ? 'checked' : '' }} onchange="toggleFitFilter(this, '{{ $fit }}')">
                                        {{ $fit }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    @elseif($isWatchCategory)
                        {{-- ⌚ 2. WATCHES: STRAP TYPE, PRICE RANGE --}}

                        <!-- STRAP TYPE (Watches) -->
                        <div class="filter-widget">
                            <div class="fw-title">STRAP TYPE <span>&minus;</span></div>
                            <div class="checkbox-list">
                                <label>
                                    <input type="checkbox" name="strap" value="" {{ !request('strap') ? 'checked' : '' }} onchange="toggleStrapFilter(this, '')">
                                    <span style="color:var(--knp-red); font-weight:700;">All Straps</span>
                                </label>
                                @foreach($watchStrapOptions as $strap)
                                    @php
                                        $strapLabel = $strap;
                                        if (strtolower($strap) === 'lether') {
                                            $strapLabel = 'Leather Strap';
                                        }
                                    @endphp
                                    <label>
                                        <input type="checkbox" name="strap" value="{{ $strap }}" {{ request('strap') == $strap ? 'checked' : '' }} onchange="toggleStrapFilter(this, '{{ $strap }}')">
                                        {{ $strapLabel }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    @elseif($isPerfumeCategory)
                        {{-- 🌟 3. PERFUMES: SCENT VIBE, VOLUME, PRICE RANGE --}}

                        <!-- SCENT VIBE (Perfumes) -->
                        <div class="filter-widget">
                            <div class="fw-title">SCENT VIBE <span>&minus;</span></div>
                            <p style="font-size: 12px; color: var(--knp-red); font-weight: 700; margin-bottom: 10px;">
                                <a href="#" onclick="selectScentFilter(''); return false;" class="{{ !request('scent') ? 'active' : '' }}" style="color: inherit; text-decoration: none;">All Scents</a>
                            </p>
                            <input type="hidden" name="scent" id="filter-scent" value="{{ request('scent') }}" {{ !request('scent') ? 'disabled' : '' }}>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @foreach($perfumeScentOptions as $scent)
                                    @php
                                        $isScentActive = (strtolower(request('scent')) == strtolower($scent['key']));
                                    @endphp
                                    <a href="#" 
                                       class="size-box {{ $isScentActive ? 'active' : '' }}" 
                                       style="display: inline-flex; align-items: center; gap: 6px; width: auto; padding: 7px 14px; font-size: 13px; font-weight: 600; text-decoration: none; border-radius: 4px; transition: all 0.2s ease;" 
                                       onclick="selectScentFilter('{{ $scent['key'] }}'); return false;">
                                        <span>{{ $scent['icon'] }}</span>
                                        <span>{{ $scent['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- VOLUME (Perfumes) -->
                        <div class="filter-widget">
                            <div class="fw-title">VOLUME <span>&minus;</span></div>
                            <p style="font-size: 12px; color: var(--knp-red); font-weight: 700; margin-bottom: 10px;">
                                <a href="#" onclick="selectVolumeFilter(''); return false;" class="{{ !request('volume') ? 'active' : '' }}" style="color: inherit; text-decoration: none;">All Volumes</a>
                            </p>
                            <input type="hidden" name="volume" id="filter-volume" value="{{ request('volume') }}" {{ !request('volume') ? 'disabled' : '' }}>
                            <div class="size-boxes">
                                @foreach($perfumeVolumeOptions as $vol)
                                    <a href="#" class="size-box {{ request('volume') == $vol ? 'active' : '' }}" onclick="selectVolumeFilter('{{ $vol }}'); return false;">{{ $vol }}</a>
                                @endforeach
                            </div>
                        </div>

                    @else
                        {{-- 🌐 4. ALL PRODUCTS / GENERIC SHOP --}}
                        @if($colorOptions->isNotEmpty())
                        <div class="filter-widget">
                            <div class="fw-title">COLOR <span>&minus;</span></div>
                            <input type="hidden" name="color" id="filter-color" value="{{ request('color') }}" {{ !request('color') ? 'disabled' : '' }}>
                            <div class="color-swatches">
                                <div class="color-swatch-item {{ !request('color') ? 'active' : '' }}" onclick="selectColorFilter('');">
                                    <span class="c-dot {{ !request('color') ? 'active' : '' }}" style="background: linear-gradient(135deg, red, blue, green);"></span>
                                    <span class="color-swatch-name">All</span>
                                </div>
                                @foreach($colorOptions as $colorOption)
                                    @php
                                        $isColorActive = (request('color') == $colorOption['value']);
                                    @endphp
                                    <div class="color-swatch-item {{ $isColorActive ? 'active' : '' }}" role="button" tabindex="0" data-color="{{ $colorOption['value'] }}" onclick="selectColorFilter(this.dataset.color);" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); selectColorFilter(this.dataset.color); }">
                                        <span class="c-dot {{ $isColorActive ? 'active' : '' }}" style="background: {{ $colorOption['swatch'] }};"></span>
                                        <span class="color-swatch-name">{{ $colorOption['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    @endif

                    <!-- PRICE RANGE (Always Available for All Categories) -->
                    <div class="filter-widget">
                        <div class="fw-title">PRICE RANGE <span>&minus;</span></div>
                        <div class="price-range-wrapper">
                            @php
                                $minDb = (int) ($priceRange->min_price ?? 0);
                                $maxDb = (int) ($priceRange->max_price ?? 5000);
                                if($maxDb <= $minDb) $maxDb = $minDb + 1000;
                                $currentMax = request('max_price', $maxDb);
                            @endphp
                            <input type="range" name="max_price" min="{{ $minDb }}" max="{{ $maxDb }}" value="{{ $currentMax }}" class="price-slider-input" onchange="submitCleanFilterForm(this.form)">
                            <div class="price-range-labels">
                                <span>&#8377;{{ number_format($minDb) }}</span>
                                <span>&#8377;<span id="price-val">{{ number_format($currentMax) }}</span>+</span>
                            </div>
                        </div>
                        <script>
                            document.querySelector('.price-slider-input').addEventListener('input', function(e) {
                                document.getElementById('price-val').innerText = parseInt(e.target.value).toLocaleString();
                            });
                        </script>
                    </div>

                    <a href="{{ $shopBaseUrl }}" class="clear-filters-btn">CLEAR FILTERS &#x21BA;</a>
                </form>
            </aside>

            <script>
            function submitCleanFilterForm(form) {
                if (!form) form = document.getElementById('filter-form');
                if (!form) return;
                const elements = form.querySelectorAll('input, select');
                elements.forEach(el => {
                    if (el.type === 'checkbox' || el.type === 'radio') {
                        if (!el.checked || !el.value || el.value.trim() === '') {
                            el.disabled = true;
                        }
                    } else if (!el.value || el.value.trim() === '') {
                        el.disabled = true;
                    }
                });
                form.submit();
            }

            function toggleFitFilter(checkbox, val) {
                const form = document.getElementById('filter-form');
                if (!form) return;
                const allFitInputs = form.querySelectorAll('input[name="fit"]');
                if (val === '' || !checkbox.checked) {
                    allFitInputs.forEach(i => { i.checked = (i.value === ''); });
                } else {
                    allFitInputs.forEach(i => { i.checked = (i === checkbox); });
                }
                submitCleanFilterForm(form);
            }

            function toggleStrapFilter(checkbox, val) {
                const form = document.getElementById('filter-form');
                if (!form) return;
                const allStrapInputs = form.querySelectorAll('input[name="strap"]');
                if (val === '' || !checkbox.checked) {
                    allStrapInputs.forEach(i => { i.checked = (i.value === ''); });
                } else {
                    allStrapInputs.forEach(i => { i.checked = (i === checkbox); });
                }
                submitCleanFilterForm(form);
            }

            function selectColorFilter(color) {
                const input = document.getElementById('filter-color');
                if (input) {
                    if (color) {
                        input.value = color;
                        input.disabled = false;
                    } else {
                        input.value = '';
                        input.disabled = true;
                    }
                }
                submitCleanFilterForm(document.getElementById('filter-form'));
            }

            function selectSizeFilter(size) {
                const input = document.getElementById('filter-size');
                if (input) {
                    if (size) {
                        input.value = size;
                        input.disabled = false;
                    } else {
                        input.value = '';
                        input.disabled = true;
                    }
                }
                submitCleanFilterForm(document.getElementById('filter-form'));
            }

            function selectScentFilter(scent) {
                const input = document.getElementById('filter-scent');
                if (input) {
                    if (scent) {
                        input.value = scent;
                        input.disabled = false;
                    } else {
                        input.value = '';
                        input.disabled = true;
                    }
                }
                submitCleanFilterForm(document.getElementById('filter-form'));
            }

            function selectVolumeFilter(volume) {
                const input = document.getElementById('filter-volume');
                if (input) {
                    if (volume) {
                        input.value = volume;
                        input.disabled = false;
                    } else {
                        input.value = '';
                        input.disabled = true;
                    }
                }
                submitCleanFilterForm(document.getElementById('filter-form'));
            }
            </script>

            <!-- PRODUCT GRID AND PAGINATION -->
            <div class="shop-products">
            <div class="shop-grid">
                @forelse($products as $index => $product)
                    @php
                        $images = $productImages($product);
                        $currentPrice = $productPrice($product);
                        $mrp = $oldPrice($product);
                        $badge = '';
                        if($index === 0) $badge = 'BEST SELLER';
                        elseif($index === 2) $badge = 'NEW';
                        elseif($index === 4) $badge = 'POPULAR';
                        
                        $wishlistSession = session('wishlist', []);
                        $cartSession = session('cart', []);
                        $inWishlist = isset($wishlistSession[$product->id]);
                        $inCart = isset($cartSession[$product->id]);
                    @endphp
                    <a href="{{ $productUrl($product) }}" class="product-card">
                        <div class="product-img-wrapper">
                            @if(count($images) > 0)
                                <img class="product-image-primary" src="{{ $images[0] }}" alt="{{ $product->product_name }}" onerror="this.src='{{ asset('images/product/01.jpg') }}'">
                                @if(count($images) > 1)
                                    <img class="product-image-secondary" src="{{ $images[1] }}" alt="{{ $product->product_name }} - alternate view" onerror="this.src='{{ asset('images/product/01.jpg') }}'">
                                @endif
                            @else
                                <img class="product-image-primary" src="{{ asset('images/product/01.jpg') }}" alt="{{ $product->product_name }}" onerror="this.src='{{ asset('images/product/01.jpg') }}'">
                            @endif

                            <div class="product-badges">
                                @if($badge)
                                    <span class="badge-red">{{ $badge }}</span>
                                @endif
                            </div>

                            <button type="button" class="wishlist-btn {{ $inWishlist ? 'active' : '' }}" aria-label="Add to Wishlist" onclick="event.preventDefault(); ajaxAddToWishlist('{{ route('wishlist.add', $product->id) }}', event);">
                                <svg viewBox="0 0 24 24" @if($inWishlist) style="fill: var(--knp-red); stroke: var(--knp-red);" @endif><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                            </button>

                        </div>

                        <div class="product-info">
                            {{-- <div class="product-colors">
                                @if(isset($product->color) && $product->color)
                                    <span class="pc-dot" style="background: {{ str_starts_with($product->color, '#') ? $product->color : '#000' }};" title="Color"></span>
                                @endif
                            </div> --}}
                            <h3 class="product-title">{{ $product->product_name }}</h3>
                            <div class="product-subtitle">
                                @if($reqCat == 'perfume')
                                    {{ $product->size ?? '50ML' }}
                                @elseif($reqCat == 'watches' || $reqCat == 'watch')
                                    {{ $product->size ?? 'Standard' }}
                                @else
                                    {{ $product->fit ?? 'Regular Fit' }}
                                @endif
                            </div>

                            @php
                                $pStats = isset($productReviewStats) ? ($productReviewStats[$product->id] ?? null) : null;
                                $pCount = $pStats ? (int) $pStats->count : 0;
                                $pAvg = $pStats ? round((float) $pStats->avg_rating, 1) : 0;
                            @endphp

                            <div class="product-rating">
                                @if ($pCount > 0)
                                    <div class="stars">
                                        @for ($si = 1; $si <= 5; $si++)
                                            {!! $si <= round($pAvg) ? '&#9733;' : '&#9734;' !!}
                                        @endfor
                                    </div>
                                    <span>({{ $pCount }})</span>
                                @else
                                    <span style="font-size: 11px; color: #888; font-weight: 500; letter-spacing: 0.5px;">★ Atelier Curated</span>
                                @endif
                            </div>

                            <div class="product-bottom">
                                <div class="product-price">
                                    <span class="current-price">{!! $money($currentPrice) !!}</span>
                                    @if($mrp)
                                        <span class="old-price">{!! $money($mrp) !!}</span>
                                    @endif
                                </div>
                                <div class="add-to-bag {{ $inCart ? 'active' : '' }}" onclick="event.preventDefault(); ajaxAddToCart('{{ route('cart.add', $product->id) }}', event);" @if($inCart) style="background:var(--knp-red); color:#fff; border-color:var(--knp-red);" @endif>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="grid-column: 1/-1; padding: 50px; text-align: center;">
                        <h3>No products found</h3>
                        <p>Try adjusting your filters or search terms.</p>
                    </div>
                @endforelse
            </div>

            @if ($products->hasPages())
                <div class="pagination-wrapper">
                    <p class="pagination-summary">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products</p>
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            @endif
            </div>
        </div>
    </div>

</div>

@endsection
