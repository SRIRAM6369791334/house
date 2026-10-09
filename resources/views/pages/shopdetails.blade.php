@extends('layouts.app')

@php
    $price = house_product_price($product);
    $oldPrice = (float) ($product->mrp_price ?? ($product->product_mrp_price ?? 0));
    $discountPercent = ($oldPrice > $price && $oldPrice > 0) ? round((($oldPrice - $price) / $oldPrice) * 100) : 0;
    $description = trim(strip_tags($product->product_specification ?: $product->product_description ?: ''));
    $shortDescription = \Illuminate\Support\Str::limit($description, 220);
    $productUrl = fn($item) => url('single-product') . '?' . http_build_query(['product' => $item->slug ?: $item->id]);
    $images = ($galleryImages ?? collect())->isNotEmpty() ? $galleryImages : collect(house_product_images($product));
    $variantGalleryItems = collect($galleryItems ?? [])->isNotEmpty()
        ? collect($galleryItems)
        : $images->map(fn ($url) => ['variant_id' => null, 'url' => $url]);
    
    $productDetails = is_array($product->product_details ?? null) 
        ? $product->product_details 
        : (is_string($product->product_details ?? null) ? (json_decode($product->product_details, true) ?: []) : []);
    $detailCenterImage = trim((string) ($productDetails['detail_center_image'] ?? ''));
    $detailCenterImageUrl = $detailCenterImage !== ''
        ? house_main_media_url($detailCenterImage, 'images')
        : ($images->first() ?? asset('images/product/01.jpg'));

    $productCategoryName = strtolower(trim((string) ($product->category_name ?? ($product->cate_name ?? ''))));
    $productSlug = strtolower(trim((string)($product->slug ?? '')));
    $productTitle = strtolower(trim((string)($product->product_name ?? '')));
    $categoryType = strtolower(trim((string)($productDetails['category_type'] ?? '')));
    
    $isPerfume = $categoryType === 'perfume' || str_contains($productCategoryName, 'perfume') || str_contains($productCategoryName, 'fragrance') || str_contains($productSlug, 'perfume') || str_contains($productSlug, 'aqua') || str_contains($productSlug, 'good') || str_contains($productTitle, 'parfum') || str_contains($productTitle, 'fragrance') || str_contains($productTitle, 'aqua');
    $isWatch = $categoryType === 'watch' || str_contains($productCategoryName, 'watch') || str_contains($productSlug, 'watch') || str_contains($productTitle, 'watch') || str_contains($productSlug, 'type-');
    $isShirt = $categoryType === 'shirt' || in_array($productCategoryName, ['shirt', 'shirts', 'apparel'], true) || str_contains($productSlug, 'shirt') || str_contains($productTitle, 'shirt') || str_contains($productSlug, 'second-image') || (!$isPerfume && !$isWatch);
    
    $showShirtSizes = $isShirt;
    $showWatchValues = $isWatch;
    $showVariantValues = $showShirtSizes || $isPerfume || $showWatchValues;
@endphp

@section('meta_title', ($product->product_name ?? 'Product') . ' | House of KNP')
@section('meta_description', !empty($shortDescription) ? $shortDescription : ('Explore ' . ($product->product_name ?? 'Product') . ' from House of KNP luxury menswear.'))
@section('meta_image', house_main_product_image_url($product))
@section('meta_type', 'product')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Montserrat:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap');

    :root {
        --knp-red: #B40016;
        --knp-red-hover: #8B0011;
        --knp-red-vibrant: #D90429;
        --knp-red-glow: rgba(180, 0, 22, 0.35);
        --knp-black: #080809;
        --knp-obsidian: #0E0E11;
        --knp-coal: #16161A;
        --knp-white: #FFFFFF;
        --knp-off-white: #FAFAF9;
        --knp-cream: #F5F4F0;
        --knp-gray-light: #F0EFEA;
        --knp-muted: #666666;
        --knp-gold: #C9A227;
        --knp-ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    .lux-product-page {
        font-family: 'Montserrat', 'Manrope', -apple-system, sans-serif;
        color: var(--knp-black);
        background: #FFFFFF;
        overflow-x: hidden;
    }

    .lux-serif {
        font-family: 'Cormorant Garamond', Georgia, serif !important;
    }

    /* 1. Breadcrumbs */
    .lux-breadcrumbs {
        padding: 16px clamp(20px, 4vw, 50px);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ECEAE5;
        background: #FFFFFF;
        font-size: 12px;
        color: var(--knp-muted);
    }
    .lux-breadcrumbs a {
        color: var(--knp-black);
        text-decoration: none;
        transition: color 0.2s;
    }
    .lux-breadcrumbs a:hover {
        color: var(--knp-red);
    }

    /* 2. Hero Section */
    .lux-hero-section {
        padding: clamp(30px, 4vw, 50px) 0 clamp(40px, 5vw, 60px);
    }

    /* Left Gallery */
    .lux-gallery-wrap {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }
    .lux-thumbnails-rail {
        width: 72px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .lux-thumb-item {
        width: 72px;
        height: 108px;
        aspect-ratio: 2 / 3;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #E5E5E5;
        background: {{ $isPerfume ? '#0E0E11' : '#F5F5F5' }};
        cursor: pointer;
        transition: all 0.3s var(--knp-ease);
        padding: 3px;
    }
    .lux-thumb-item.active, .lux-thumb-item:hover {
        border-color: var(--knp-red);
        box-shadow: 0 4px 12px var(--knp-red-glow);
    }
    .lux-thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        border-radius: 3px;
    }
    .lux-watch-video-btn {
        width: 72px;
        background: #FFFFFF;
        border: 1px solid #E0E0E0;
        border-radius: 6px;
        padding: 10px 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        cursor: pointer;
        text-decoration: none;
        color: var(--knp-black);
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: 4px;
        transition: all 0.3s;
    }
    .lux-watch-video-btn i {
        font-size: 16px;
        color: var(--knp-red);
    }
    .lux-watch-video-btn:hover {
        border-color: var(--knp-red);
        background: #FFF5F5;
        color: var(--knp-red);
    }

    .lux-main-display {
        flex: 0 0 auto;
        width: min(100%, calc(570px * 2 / 3));
        height: 570px;
        max-height: 570px;
        max-width: 100%;
        aspect-ratio: 2 / 3;
        position: relative;
        background: {{ $isPerfume ? '#0E0E11' : '#F8F8F8' }};
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }
    .lux-main-display img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        transition: transform 0.5s var(--knp-ease);
    }
    .lux-main-display:hover img {
        transform: scale(1.03);
    }
    .lux-expand-icon {
        position: absolute;
        bottom: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(6px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        cursor: pointer;
        transition: all 0.3s;
    }
    .lux-gallery-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--knp-black);
        font-size: 14px;
        cursor: pointer;
        z-index: 10;
        transition: all 0.25s var(--knp-ease);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    }
    .lux-gallery-nav-btn:hover {
        background: var(--knp-red);
        color: #FFFFFF;
        border-color: var(--knp-red);
        transform: translateY(-50%) scale(1.08);
        box-shadow: 0 6px 20px var(--knp-red-glow);
    }
    .lux-gallery-prev {
        left: 14px;
    }
    .lux-gallery-next {
        right: 14px;
    }


    /* Right Info */
    .lux-badge-tag {
        display: inline-block;
        color: var(--knp-red);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .lux-product-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(30px, 3.2vw, 42px);
        font-weight: 600;
        line-height: 1.18;
        color: var(--knp-black);
        margin: 0 0 10px;
    }
    .lux-rating-proof {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
        font-size: 13px;
    }
    .lux-stars {
        color: var(--knp-red);
        letter-spacing: 2px;
    }
    .lux-proof-text {
        color: var(--knp-muted);
        font-weight: 600;
    }

    .lux-pricing-block {
        display: flex;
        align-items: baseline;
        gap: 16px;
        margin-bottom: 6px;
    }
    .lux-price {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(34px, 3vw, 42px);
        font-weight: 700;
        color: var(--knp-red);
        line-height: 1;
    }
    .lux-mrp {
        font-size: 16px;
        color: #8E8E93;
        text-decoration: line-through;
        font-weight: 500;
    }
    .lux-discount-pill {
        background: var(--knp-red);
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 4px;
        letter-spacing: 0.5px;
    }
    .lux-tax-note {
        font-size: 12px;
        color: var(--knp-muted);
        margin-bottom: 22px;
    }

    /* 4 Micro Specs */
    .lux-micro-specs-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        padding: 16px 0;
        border-top: 1px solid #ECEAE5;
        border-bottom: 1px solid #ECEAE5;
        margin-bottom: 22px;
    }
    .lux-micro-spec-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .lux-micro-spec-item i {
        font-size: 20px;
        color: var(--knp-red);
        flex-shrink: 0;
    }
    .lux-micro-spec-info {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }
    .lux-micro-spec-info strong {
        font-size: 11px;
        font-weight: 700;
        color: var(--knp-black);
    }
    .lux-micro-spec-info span {
        font-size: 10px;
        color: var(--knp-muted);
    }

    .lux-short-description {
        font-size: 13px;
        color: #555555;
        line-height: 1.6;
        margin-bottom: 22px;
    }

    /* Color Swatches */
    .lux-swatch-section {
        margin-bottom: 20px;
    }
    .lux-swatch-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--knp-black);
        margin-bottom: 10px;
    }
    .lux-swatch-title span {
        font-weight: normal;
        color: var(--knp-muted);
    }
    .lux-swatch-gallery {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    .lux-color-swatch-circle, .lux-product-color-link {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        position: relative;
        border: 2px solid transparent;
        transition: all 0.2s;
        box-shadow: 0 0 0 1px #DDD;
    }
    .lux-color-swatch-circle.active, .lux-color-swatch-circle:hover,
    .lux-product-color-link.active, .lux-product-color-link:hover {
        border-color: #FFFFFF;
        box-shadow: 0 0 0 2px var(--knp-red);
        transform: scale(1.1);
    }

    /* Size Selector */
    .lux-size-section {
        margin-bottom: 24px;
    }
    .lux-size-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .lux-size-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--knp-black);
        margin: 0;
    }
    .lux-size-guide-btn {
        background: transparent;
        border: none;
        font-size: 12px;
        font-weight: 600;
        color: var(--knp-muted);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
    }
    .lux-size-guide-btn:hover {
        color: var(--knp-red);
    }
    .lux-size-pills {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .lux-size-pill {
        min-width: 48px;
        height: 44px;
        padding: 0 14px;
        border: 1px solid #D5D5D5;
        background: #FFFFFF;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        color: var(--knp-black);
        transition: all 0.2s;
    }
    .lux-size-pill.active, .lux-size-pill:hover {
        border-color: var(--knp-red);
        color: var(--knp-red);
        background: #FFF8F8;
    }

    /* Actions Row */
    .lux-actions-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .lux-qty-pill {
        display: flex;
        align-items: center;
        border: 1px solid #D5D5D5;
        border-radius: 6px;
        height: 48px;
        background: #FFFFFF;
    }
    .lux-qty-btn {
        width: 38px;
        height: 100%;
        border: none;
        background: transparent;
        font-size: 18px;
        cursor: pointer;
        color: var(--knp-black);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .lux-qty-input {
        width: 38px;
        height: 100%;
        border: none;
        text-align: center;
        font-weight: 700;
        font-size: 15px;
        color: var(--knp-black);
    }
    .lux-btn-addcart {
        flex: 1;
        height: 48px;
        background: #FFFFFF;
        border: 1.5px solid var(--knp-black);
        color: var(--knp-black);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.3s;
    }
    .lux-btn-addcart:hover {
        background: var(--knp-black);
        color: #FFFFFF;
    }
    .lux-btn-buynow {
        flex: 1.1;
        height: 48px;
        background: var(--knp-red);
        border: 1.5px solid var(--knp-red);
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 15px var(--knp-red-glow);
    }
    .lux-btn-buynow:hover {
        background: var(--knp-red-hover);
        border-color: var(--knp-red-hover);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .lux-wishlist-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--knp-muted);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-left: auto;
        transition: color 0.2s;
    }
    .lux-wishlist-link:hover {
        color: var(--knp-red);
    }

    /* Delivery Countdown */
    .lux-delivery-box {
        background: #F8F8FA;
        border-radius: 8px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 13px;
        color: var(--knp-muted);
        border: 1px solid #EBEAE5;
    }
    .lux-delivery-box i {
        font-size: 24px;
        color: var(--knp-red);
    }

    /* 3. 4-Column Feature Ribbon */
    .lux-feature-ribbon {
        background: #FAFAF9;
        border-top: 1px solid #ECEAE5;
        border-bottom: 1px solid #ECEAE5;
        padding: 24px 0;
        margin: 40px 0;
    }
    .lux-feature-ribbon-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .lux-ribbon-item {
        display: flex;
        align-items: center;
        gap: 14px;
        justify-content: center;
    }
    .lux-ribbon-item + .lux-ribbon-item {
        border-left: 1px solid #ECEAE5;
    }
    .lux-ribbon-item i {
        font-size: 26px;
        color: var(--knp-red);
    }
    .lux-ribbon-text strong {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--knp-black);
    }
    .lux-ribbon-text span {
        font-size: 11px;
        color: var(--knp-muted);
    }

    /* 4. Tabbed Detailed Infographics Section */
    .lux-tabs-section {
        padding: 20px 0 60px;
    }
    .lux-tabs-nav {
        display: flex;
        justify-content: center;
        gap: clamp(16px, 4vw, 40px);
        border-bottom: 1px solid #ECEAE5;
        padding-bottom: 14px;
        margin-bottom: 40px;
        list-style: none;
        flex-wrap: wrap;
    }
    .lux-tabs-nav a {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--knp-muted);
        text-decoration: none;
        padding-bottom: 14px;
        position: relative;
        transition: all 0.2s;
    }
    .lux-tabs-nav a.active, .lux-tabs-nav a:hover {
        color: var(--knp-red);
    }
    .lux-tabs-nav a.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2.5px;
        background: var(--knp-red);
    }

    /* Tab 1: Fragrance Scent Profile / Shirt Model Specs */
    .lux-infographic-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 28px;
        font-weight: 700;
        line-height: 1.25;
        color: var(--knp-black);
        margin-bottom: 18px;
        text-transform: uppercase;
    }
    .lux-infographic-text {
        font-size: 13px;
        line-height: 1.7;
        color: #555555;
        margin-bottom: 20px;
    }
    .lux-bullets-list {
        list-style: disc !important;
        padding-left: 20px !important;
        margin-top: 15px !important;
        margin-bottom: 0 !important;
    }
    .lux-bullets-list li {
        list-style-type: disc !important;
        display: list-item !important;
        color: var(--knp-red) !important;
        margin-bottom: 8px !important;
        line-height: 1.5 !important;
    }
    .lux-bullets-list li span {
        color: #444444 !important;
        font-size: 13px !important;
    }

    /* Scent Dots Chart */
    .lux-scent-chart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #F0EFEA;
    }
    .lux-scent-chart-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--knp-black);
    }
    .lux-scent-dots {
        display: flex;
        gap: 6px;
    }
    .lux-scent-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #E5E5E5;
    }
    .lux-scent-dot.filled {
        background: var(--knp-red);
    }

    /* Specifications Table */
    .lux-specs-table {
        width: 100%;
        font-size: 12px;
    }
    .lux-specs-table tr {
        border-bottom: 1px solid #F0EFEA;
    }
    .lux-specs-table td {
        padding: 10px 0;
    }
    .lux-specs-table td:first-child {
        color: var(--knp-muted);
        font-weight: 600;
        width: 45%;
    }
    .lux-specs-table td:last-child {
        color: var(--knp-black);
        font-weight: 700;
    }

    /* Shirt Model Specs Card */
    .lux-model-card {
        background: #FAFAF9;
        border: 1px solid #ECEAE5;
        border-radius: 10px;
        padding: 20px;
    }
    .lux-model-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 22px;
    }
    .lux-model-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
    }
    .lux-model-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--knp-black);
        margin-bottom: 2px;
    }
    .lux-model-measurements {
        font-size: 11px;
        color: var(--knp-muted);
    }
    .lux-slider-indicator-block {
        margin-bottom: 20px;
    }
    .lux-slider-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--knp-black);
        margin-bottom: 8px;
    }
    .lux-slider-track {
        height: 2px;
        background: #E5E5E5;
        position: relative;
        margin: 10px 0;
    }
    .lux-slider-point {
        position: absolute;
        top: -5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--knp-red);
        box-shadow: 0 0 0 3px rgba(180, 0, 22, 0.2);
    }
    .lux-slider-levels {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        color: var(--knp-muted);
    }
    .lux-slider-levels .active-level {
        color: var(--knp-red);
        font-weight: 800;
    }

    /* 5. "YOU MAY ALSO LIKE" Section */
    .lux-related-section {
        padding: 60px 0;
        background: #FAFAF9;
        border-top: 1px solid #ECEAE5;
    }
    .lux-section-header-center {
        text-align: center;
        margin-bottom: 36px;
    }
    .lux-section-title-gem {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 32px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--knp-black);
        margin-bottom: 12px;
    }
    .lux-head-divider {
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }
    .lux-divider-line {
        width: 45px;
        height: 1.5px;
        background: rgba(180, 0, 22, 0.4);
    }
    .lux-divider-gem {
        width: 6px;
        height: 6px;
        background: var(--knp-red);
        transform: rotate(45deg);
    }

    .lux-related-carousel-wrap {
        position: relative;
    }
    .lux-related-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 18px;
    }
    .lux-related-card {
        background: #FFFFFF;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #ECEAE5;
        transition: all 0.3s;
        position: relative;
    }
    .lux-related-card:hover {
        border-color: var(--knp-red);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transform: translateY(-4px);
    }
    .lux-related-media {
        position: relative;
        aspect-ratio: 3 / 4;
        background: #F5F5F5;
        overflow: hidden;
    }
    .lux-related-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }
    .lux-related-card:hover .lux-related-media img {
        transform: scale(1.05);
    }
    .lux-related-wishlist {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(4px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--knp-black);
        text-decoration: none;
        transition: all 0.2s;
        z-index: 2;
    }
    .lux-related-wishlist:hover {
        background: var(--knp-red);
        color: #FFFFFF;
    }
    .lux-related-body {
        padding: 14px 12px;
        text-align: center;
    }
    .lux-related-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--knp-black);
        text-decoration: none;
        display: block;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .lux-related-desc {
        font-size: 11px;
        color: var(--knp-muted);
        margin-bottom: 8px;
    }
    .lux-related-price-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .lux-rel-new {
        font-size: 14px;
        font-weight: 800;
        color: var(--knp-red);
    }
    .lux-rel-old {
        font-size: 11px;
        color: #8E8E93;
        text-decoration: line-through;
    }

    /* 6. Trust Badges Strip */
    .lux-trust-strip {
        background: #FFFFFF;
        border-top: 1px solid #ECEAE5;
        padding: 30px 0;
    }
    .lux-trust-grid {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .lux-trust-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .lux-trust-item i {
        font-size: 24px;
        color: var(--knp-red);
    }
    .lux-trust-info strong {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--knp-black);
    }
    .lux-trust-info span {
        font-size: 11px;
        color: var(--knp-muted);
    }

    /* Size Chart Modal */
    .lux-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .lux-modal-content {
        background: #FFFFFF;
        border-radius: 12px;
        max-width: 650px;
        width: 100%;
        padding: 24px;
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
    }
    .lux-modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    background: transparent;
    border: none;
    font-size: 30px;
    cursor: pointer;
    color: #000;
}

    @media (max-width: 991px) {
        .lux-gallery-wrap { flex-direction: column-reverse; align-items: center; gap: 14px; }
        .lux-thumbnails-rail { flex-direction: row; width: 100%; justify-content: center; overflow-x: auto; gap: 8px; }
        .lux-thumb-item { width: 56px; height: 84px; aspect-ratio: 2 / 3; flex-shrink: 0; }
        .lux-watch-video-btn { width: 56px; height: 84px; flex-shrink: 0; margin-top: 0; }
        .lux-main-display { width: 100%; max-width: 360px; height: auto; aspect-ratio: 2 / 3; max-height: 540px; margin: 0 auto; }
        .lux-micro-specs-grid { grid-template-columns: repeat(2, 1fr); }
        .lux-feature-ribbon-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .lux-ribbon-item + .lux-ribbon-item { border-left: none; }
        .lux-related-grid { grid-template-columns: repeat(3, 1fr); }
        .lux-actions-row { flex-direction: column; width: 100%; gap: 15px; }
        .lux-btn-addcart, .lux-btn-buynow { width: 100%; height: 55px !important; flex: none !important; font-size: 14px !important; }
        .lux-qty-pill { height: 55px !important; width: 160px; justify-content: space-between; margin: 0 auto; }
        .lux-qty-btn { width: 50px !important; font-size: 22px !important; }
        .lux-qty-input { width: 60px !important; font-size: 18px !important; }
    }
    @media (max-width: 575px) {
        .lux-related-grid { grid-template-columns: repeat(2, 1fr); }
        .lux-feature-ribbon-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="lux-product-page">

    <!-- 1. Breadcrumbs -->
    <div class="lux-breadcrumbs">
        <div>
            <a href="{{ url('/') }}">Home</a> &nbsp;&gt;&nbsp; 
            <a href="{{ url('/shop') }}">{{ ucwords($productCategoryName ?: 'Catalog') }}</a> &nbsp;&gt;&nbsp; 
            <span style="color: var(--knp-red); font-weight: 600;">{{ $product->product_name }}</span>
        </div>
        <!-- <div style="font-weight: 700;">
            <a href="{{ url('/shop') }}">&lt; PREV</a> &nbsp;&nbsp;|&nbsp;&nbsp; 
            <a href="{{ url('/shop') }}">NEXT &gt;</a>
        </div> -->
    </div>

    <!-- 2. Main Product Hero -->
    <section class="lux-hero-section">
        <div class="container-fluid" style="max-width: 1280px; padding: 0 clamp(16px, 3vw, 40px);">
            <div class="row g-4 align-items-start">

                <!-- Left: Gallery -->
                <div class="col-lg-5 col-xl-5">
                    <div class="lux-gallery-wrap">
                        
                        <!-- Thumbnails -->
                        <div class="lux-thumbnails-rail">
                            @foreach ($variantGalleryItems as $galleryItem)
                                <div class="lux-thumb-item {{ $loop->first ? 'active' : '' }}" 
                                     data-variant-id="{{ $galleryItem['variant_id'] }}"
                                     onclick="changeProductImage(this, '{{ $galleryItem['url'] }}')">
                                    <img src="{{ $galleryItem['url'] }}" alt="{{ $product->product_name }}"
                                         onerror="this.onerror=null;this.src='{{ asset('images/product/01.jpg') }}';">
                                </div>
                            @endforeach

                        </div>

                        <!-- Main Image Frame -->
                        <div class="lux-main-display">
                            <button type="button" class="lux-gallery-nav-btn lux-gallery-prev" onclick="stepGalleryImage(-1)" aria-label="Previous Image">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <img id="productMainDisplayImg" src="{{ $variantGalleryItems->first()['url'] ?? asset('images/product/01.jpg') }}" 
                                 alt="{{ $product->product_name }}"
                                 onerror="this.onerror=null;this.src='{{ asset('images/product/01.jpg') }}';">
                            <button type="button" class="lux-gallery-nav-btn lux-gallery-next" onclick="stepGalleryImage(1)" aria-label="Next Image">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                            <div class="lux-expand-icon" onclick="openFullscreenImage()" title="Expand Fullscreen">
                                <i class="fa fa-expand"></i>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right: Info & Purchase -->
                <div class="col-lg-7 col-xl-7 ps-lg-4">
                    <span class="lux-badge-tag">Best Seller</span>
                    <h1 class="lux-product-title">{{ $product->product_name }}</h1>

                    <!-- Social Proof -->
                    <div class="lux-rating-proof">
                        @if ($reviewCount > 0)
                            <span class="lux-stars" style="color:var(--knp-gold); font-size:13px; letter-spacing:1px;">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= round($avgRating ?? 5))
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </span>
                            <span class="lux-proof-text">{{ number_format($avgRating ?? 5, 1) }} ({{ $reviewCount }} {{ \Illuminate\Support\Str::plural('Review', $reviewCount) }}) &nbsp;|&nbsp; 100% Authentic Luxury</span>
                        @else
                            <a href="#tabReviews" onclick="switchLuxTab(event, 'tabReviews')" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; cursor:pointer;" title="Click to write the first review">
                                <span class="lux-stars" style="color:var(--knp-gold); font-size:13px; letter-spacing:1px;">★★★★★</span>
                                <span class="lux-proof-text" style="color:#555;">New In Atelier &nbsp;|&nbsp; <span style="color:var(--knp-red); text-decoration:underline; font-weight:600;">Be the first to review</span></span>
                            </a>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="lux-pricing-block">
                        <div class="lux-price" id="productCurrentPrice">₹{{ number_format($price) }}</div>
                        @if($oldPrice > $price)
                            <div class="lux-mrp" id="productOldPrice"><del>MRP ₹{{ number_format($oldPrice) }}</del></div>
                            @if($discountPercent > 0)
                                <span class="lux-discount-pill">{{ $discountPercent }}% OFF</span>
                            @endif
                        @endif
                    </div>
                    <div class="lux-tax-note">Inclusive of all taxes</div>

                    <!-- 4 Micro Specs Grid -->
                    <div class="lux-micro-specs-grid">
                        @if(!empty($productDetails['micro_specs']) && is_array($productDetails['micro_specs']))
                            @foreach($productDetails['micro_specs'] as $ms)
                                <div class="lux-micro-spec-item">
                                    <i class="fa {{ $ms['icon'] ?? 'fa-check-circle' }}"></i>
                                    <div class="lux-micro-spec-info">
                                        <strong>{{ $ms['title'] ?? '' }}</strong>
                                        <span>{{ ($ms['title'] ?? '') === 'Easy Returns' && ($ms['value'] ?? '') === '15 Days' ? '7 Days' : ($ms['value'] ?? '') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @elseif($isPerfume)
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-clock-o"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Long Lasting</strong>
                                    <span>8+ Hours</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-flask"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Premium Quality</strong>
                                    <span>Imported Oil</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-leaf"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Skin Friendly</strong>
                                    <span>Dermatologically Tested</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-sun-o"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Perfect For</strong>
                                    <span>Day &amp; Night</span>
                                </div>
                            </div>
                        @elseif($isWatch)
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-shield"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>1 Year Warranty</strong>
                                    <span>On Machinery</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-certificate"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Premium Quality</strong>
                                    <span>Original Automatic</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-truck"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Free Shipping</strong>
                                    <span>Pan India</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-refresh"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Easy Returns</strong>
                                    <span>7 Days</span>
                                </div>
                            </div>
                        @else
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-certificate"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Premium Quality</strong>
                                    <span>Finest Materials</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-refresh"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Easy Returns</strong>
                                    <span>Hassle Free</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-shield"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Secure Payment</strong>
                                    <span>100% Protected</span>
                                </div>
                            </div>
                            <div class="lux-micro-spec-item">
                                <i class="fa fa-star-o"></i>
                                <div class="lux-micro-spec-info">
                                    <strong>Craftsmanship</strong>
                                    <span>Artisan Finish</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Short Description -->
                    @php
                        $rawDesc = trim(strip_tags($product->product_specification ?: $product->product_description ?: ''));
                        $isDummyOrDuplicate = empty($rawDesc) 
                            || strlen($rawDesc) <= 4 
                            || strcasecmp($rawDesc, trim($product->product_name ?? '')) === 0
                            || in_array(strtolower($rawDesc), ['demo', 'test', 'sample', 'temp', 'mmmmmmmm'], true);
                    @endphp
                    @if(!$isDummyOrDuplicate)
                        <div class="lux-short-description">
                            {{ $rawDesc }}
                        </div>
                    @endif

                    @if($isWatch && !empty($productDetails['bullet_points']) && is_array($productDetails['bullet_points']))
                        <ul class="lux-watch-bullets" style="list-style: none; padding: 0; margin: 16px 0; display: flex; flex-direction: column; gap: 8px;">
                            @foreach($productDetails['bullet_points'] as $bp)
                                <li style="font-size: 13px; color: #444; display: flex; align-items: center; gap: 10px;">
                                    <span style="display: inline-block; width: 6px; height: 6px; background: var(--knp-red); border-radius: 50%; flex-shrink: 0;"></span>
                                    <span>{{ $bp }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <!-- Colors for this product and matching products -->
                    @if(!$isPerfume && !$isWatch && ($colorOptions->isNotEmpty() || $otherColorProducts->isNotEmpty() || filled($product->color)))
                        @php
                            $currentColor = $colorOptions->first()['label'] ?? $productColorLabel;
                            $shownColors = $colorOptions->pluck('label')->map(fn ($label) => mb_strtolower($label));
                        @endphp
                        <div class="lux-swatch-section">
                            <div class="lux-swatch-title">
                                COLOR: <span id="selectedColorLabel">{{ $currentColor }}</span>
                            </div>
                            <div class="lux-swatch-gallery" aria-label="Product Colors">
                                @foreach($colorOptions as $c)
                                    <div class="lux-color-swatch-circle {{ $loop->first ? 'active' : '' }}" 
                                         title="{{ $c['label'] }}"
                                         data-color-value="{{ $c['value'] }}"
                                         data-color-label="{{ $c['label'] }}"
                                         style="background: {{ $c['swatch'] ?? house_color_swatch($c['value'] ?? '') }};"
                                         onclick="selectLuxColor(this)">
                                    </div>
                                @endforeach
                                @if($colorOptions->isEmpty() && filled($product->color))
                                    <span class="lux-product-color-link active" title="{{ $currentColor }}" aria-label="Current color: {{ $currentColor }}"
                                          style="background: {{ house_color_swatch($product->color) }};"></span>
                                    @php $shownColors->push(mb_strtolower($currentColor)); @endphp
                                @endif
                                @foreach($otherColorProducts as $option)
                                    @continue($shownColors->contains(mb_strtolower($option['label'])))
                                    <a class="lux-product-color-link" href="{{ $productUrl($option['product']) }}"
                                       title="{{ $option['product']->product_name }} — {{ $option['label'] }}"
                                       aria-label="View {{ $option['product']->product_name }} in {{ $option['label'] }}"
                                       style="background: {{ $option['swatch'] }};"></a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Product variant selector (if available) -->
                    @if(isset($sizeOptions) && $sizeOptions->isNotEmpty())
                        <div class="lux-size-section">
                            <div class="lux-size-head">
                                <h4 class="lux-size-title"><span id="watchModelSelectionLabel">{{ $isWatch ? ((($productDetails['watch_spec_visibility']['Model'] ?? true) === false) ? 'VARIANT' : strtoupper($productDetails['watch_spec_labels']['Model'] ?? 'Model')) : ($isPerfume ? 'VOLUME' : 'SIZE') }}</span>: <span id="selectedSizeLabel" style="font-weight:normal; color:var(--knp-muted);">{{ $sizeOptions->first() }}</span></h4>
                                @if(!$isPerfume && !$isWatch)
                                    <button type="button" class="lux-size-guide-btn" onclick="toggleSizeGuideModal(true)">
                                        <i class="fa fa-ruler-horizontal"></i> Size Guide
                                    </button>
                                @endif
                            </div>
                            <div class="lux-size-pills">
                                @foreach($sizeOptions as $s)
                                    <div class="lux-size-pill {{ $loop->first ? 'active' : '' }}" 
                                         data-size-value="{{ $s }}"
                                         onclick="selectLuxSize(this, this.dataset.sizeValue)">
                                        {{ $s }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($isWatch)
                        <div class="lux-size-section" id="dynWatchModelDetail" @if(($productDetails['watch_spec_visibility']['Model'] ?? true) === false) style="display:none;" @endif>
                            <h4 class="lux-size-title"><span id="watchModelDetailsLabel">{{ strtoupper($productDetails['watch_spec_labels']['Model'] ?? 'Model') }}</span>: <span style="font-weight:normal; color:var(--knp-muted);">{{ $productDetails['specs_table']['Model'] ?? $productDetails['model'] ?? $product->product_name }}</span></h4>
                        </div>
                    @endif

                    <!-- Actions & Quantity -->
                    <div class="lux-actions-row">
                        <div class="lux-qty-pill">
                            <button type="button" class="lux-qty-btn" onclick="updateLuxQty(-1)">-</button>
                            <input type="text" class="lux-qty-input" id="luxQuantityDisplay" value="1" readonly>
                            <button type="button" class="lux-qty-btn" onclick="updateLuxQty(1)">+</button>
                        </div>

                        <a id="productAddToCart" href="{{ route('cart.add', $product->id) }}" 
                           class="lux-btn-addcart" onclick="ajaxAddToCart(this.href, event)">
                            <i class="fa fa-shopping-cart"></i> Add To Cart
                        </a>

                        <a id="productBuyNow" href="{{ route('buy.now', $product->id) }}" 
                           class="lux-btn-buynow">
                            Buy Now
                        </a>

                        <a id="productAddToWishlist" href="{{ route('wishlist.add', $product->id) }}" 
                           class="lux-wishlist-link" onclick="ajaxAddToWishlist(this.href, event)">
                            <i class="fa fa-heart-o"></i> Add to Wishlist
                        </a>
                    </div>

                    <!-- Delivery Countdown Box -->
                    <!-- <div class="lux-delivery-box">
                        <i class="fa fa-truck"></i>
                        <div>
                            Order within <strong id="luxDeliveryTimer" style="color: var(--knp-black);">2h 15m 30s</strong> and get it by<br>
                            <strong style="color: var(--knp-black);">Friday, 24 May</strong> (Express Delivery)
                        </div>
                    </div> -->

                </div>

            </div>
        </div>
    </section>

    <!-- 3. 4-Column Feature Ribbon -->
    <div class="lux-feature-ribbon">
        <div class="container-fluid" style="max-width: 1360px; padding: 0 clamp(16px, 3vw, 40px);">
            <div class="lux-feature-ribbon-grid" id="dynamicFeatureRibbon">
                @if(!empty($productDetails['feature_ribbon']) && is_array($productDetails['feature_ribbon']))
                    @foreach($productDetails['feature_ribbon'] as $ribbon)
                        <div class="lux-ribbon-item">
                            <i class="fa {{ $ribbon['icon'] ?? 'fa-check' }}"></i>
                            <div class="lux-ribbon-text">
                                <strong>{{ $ribbon['title'] ?? '' }}</strong>
                                <span>{{ $ribbon['desc'] ?? '' }}</span>
                            </div>
                        </div>
                    @endforeach
                @elseif($isPerfume)
                    <div class="lux-ribbon-item">
                        <i class="fa fa-shield"></i>
                        <div class="lux-ribbon-text">
                            <strong>100% Authentic</strong>
                            <span>Original Products</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-lock"></i>
                        <div class="lux-ribbon-text">
                            <strong>Secure Payment</strong>
                            <span>100% Protected</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-gift"></i>
                        <div class="lux-ribbon-text">
                            <strong>Premium Packaging</strong>
                            <span>Luxury Perfume Box</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-refresh"></i>
                        <div class="lux-ribbon-text">
                            <strong>Easy Returns</strong>
                            <span>15 Days Return Policy</span>
                        </div>
                    </div>
                @elseif($isWatch)
                    <div class="lux-ribbon-item">
                        <i class="fa fa-shield"></i>
                        <div class="lux-ribbon-text">
                            <strong>100% Authentic</strong>
                            <span>Original Products</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-lock"></i>
                        <div class="lux-ribbon-text">
                            <strong>Secure Payment</strong>
                            <span>100% Protected</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-gift"></i>
                        <div class="lux-ribbon-text">
                            <strong>Premium Packaging</strong>
                            <span>Luxury Watch Box</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-users"></i>
                        <div class="lux-ribbon-text">
                            <strong>Trusted by 10,000+</strong>
                            <span>Happy Customers</span>
                        </div>
                    </div>
                @else
                    <div class="lux-ribbon-item">
                        <i class="fa fa-snowflake-o"></i>
                        <div class="lux-ribbon-text">
                            <strong>Premium Satin Fabric</strong>
                            <span>Smooth, lustrous &amp; luxurious feel</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-street-view"></i>
                        <div class="lux-ribbon-text">
                            <strong>Perfect Fit</strong>
                            <span>Tailored to perfection</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-shopping-bag"></i>
                        <div class="lux-ribbon-text">
                            <strong>Easy Care</strong>
                            <span>Machine wash &amp; wrinkle resistant</span>
                        </div>
                    </div>
                    <div class="lux-ribbon-item">
                        <i class="fa fa-globe"></i>
                        <div class="lux-ribbon-text">
                            <strong>Made in India</strong>
                            <span>Proudly designed &amp; crafted</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 4. Detailed Infographics Tabbed Section -->
    <section class="lux-tabs-section">
        <div class="container-fluid" style="max-width: 1360px; padding: 0 clamp(16px, 3vw, 40px);">
            
            <!-- Tabs Nav -->
            <ul class="lux-tabs-nav" id="luxTabsNav">
                @php
                    $tabLabels = $productDetails['tab_labels'] ?? [];
                    if (empty($tabLabels)) {
                        if ($isPerfume) {
                            $tabLabels = ['tab1' => 'About The Fragrance', 'tab2' => 'Scent Notes', 'tab3' => 'How To Use', 'tab4' => 'Ingredients'];
                        } elseif ($isWatch) {
                            $tabLabels = ['tab1' => 'Description', 'tab2' => 'Specifications', 'tab3' => 'Shipping & Returns', 'tab4' => 'Warranty'];
                        } else {
                            $tabLabels = ['tab1' => 'Description', 'tab2' => 'Details', 'tab3' => 'Size & Fit', 'tab4' => 'Shipping & Returns'];
                        }
                    }
                @endphp
                <li><a href="#tabDesc" class="active" onclick="switchLuxTab(event, 'tabDesc')" id="luxTabLabel1">{{ $tabLabels['tab1'] ?? 'Description' }}</a></li>
                <li><a href="#tabNotes" onclick="switchLuxTab(event, 'tabNotes')" id="luxTabLabel2">{{ $tabLabels['tab2'] ?? 'Details' }}</a></li>
                <li><a href="#tabHow" onclick="switchLuxTab(event, 'tabHow')" id="luxTabLabel3">{{ $tabLabels['tab3'] ?? 'Shipping & Returns' }}</a></li>
                <li><a href="#tabIngredients" onclick="switchLuxTab(event, 'tabIngredients')" id="luxTabLabel4">{{ $tabLabels['tab4'] ?? 'Shipping & Returns' }}</a></li>
                <li><a href="#tabReviews" id="luxReviewsTabBtn" onclick="switchLuxTab(event, 'tabReviews')">Reviews ({{ $reviewCount }})</a></li>
            </ul>

            <!-- Tab Content -->
            <div class="lux-tab-panes">
                
                <!-- Tab 1: Description / About -->
                <div class="lux-tab-pane" id="tabDesc">
                    <div class="row g-5 align-items-center">
                        
                        <!-- Column 1: Narrative -->
                        <div class="col-lg-4">
                            @if($isPerfume)
                                <div class="lux-infographic-title" id="dynInfoHeadline">{!! nl2br(e($productDetails['headline'] ?? "INSPIRED BY THE OCEAN.\nMADE FOR PRESENCE.")) !!}</div>
                                <div class="lux-infographic-text" id="dynInfoNarrative">
                                    {{ $productDetails['narrative'] ?? ($product->product_name . " is a masculine aquatic fragrance that opens with a burst of freshness, settles into a smooth heart and leaves a lasting trail of confidence. Perfect for the modern gentleman who owns every moment.") }}
                                </div>
                            @elseif($isWatch)
                                <div class="lux-infographic-title" id="dynInfoHeadline">{!! nl2br(e($productDetails['headline'] ?? "TIME CRAFTED FOR THOSE WHO LEAD.")) !!}</div>
                                <div class="lux-infographic-text" id="dynInfoNarrative">
                                    {{ $productDetails['narrative'] ?? ($description ?: "The KNP Automatic Watch 904L is built for the modern gentleman who values precision, presence and performance. Designed with premium materials and powered by a reliable automatic movement, this watch is a statement of class and confidence.") }}
                                </div>
                                <ul class="lux-bullets-list" id="dynInfoBullets">
                                    @if(!empty($productDetails['bullet_points']) && is_array($productDetails['bullet_points']))
                                        @foreach($productDetails['bullet_points'] as $bp)
                                            <li><span>{{ $bp }}</span></li>
                                        @endforeach
                                    @endif
                                </ul>
                            @else
                                <div class="lux-infographic-title" id="dynInfoHeadline">{!! nl2br(e($productDetails['headline'] ?? "ELEGANCE IN EVERY THREAD")) !!}</div>
                                <div class="lux-infographic-text" id="dynInfoNarrative">
                                    {{ $productDetails['narrative'] ?? ($description ?: 'Crafted from premium satin fabric, this piece defines elegance and sophistication. The smooth texture and rich finish make it perfect for formal occasions, business events, and evening outings.') }}
                                </div>
                                <ul class="lux-bullets-list" id="dynInfoBullets">
                                    @if(!empty($productDetails['bullet_points']) && is_array($productDetails['bullet_points']))
                                        @foreach($productDetails['bullet_points'] as $bp)
                                            <li><span>{{ $bp }}</span></li>
                                        @endforeach
                                    @else
                                        <li><span>Premium satin finish</span></li>
                                        <li><span>Tailored slim fit structure</span></li>
                                        <li><span>Spread collar with durable stitching</span></li>
                                        <li><span>Easy care &amp; wrinkle resistant</span></li>
                                    @endif
                                </ul>
                            @endif
                        </div>

                        <!-- Column 2: Visual Graphic -->
                        <div class="col-lg-4 text-center">
                            <img src="{{ $detailCenterImageUrl }}" 
                                 id="dynGraphicImg"
                                 alt="{{ $product->product_name }}" 
                                 style="max-width: 100%; max-height: 280px; object-fit: contain; border-radius: 8px;">
                            <p style="font-size: 11px; font-weight: 700; color: var(--knp-muted); margin-top: 10px; text-transform: uppercase;">
                                {{ $isPerfume ? 'Eau De Parfum Presentation' : ($isWatch ? 'Precision Engineering' : 'Premium Fabric Closeup') }}
                            </p>
                        </div>

                        <!-- Column 3: Scent Dots or Model Specs or Watch Specs -->
                        <div class="col-lg-4">
                            @if($isPerfume)
                                @php
                                    $scentProfile = $productDetails['scent_profile'] ?? [
                                        'Fresh' => 3, 'Aquatic' => 4, 'Woody' => 3, 'Citrus' => 3, 'Aromatic' => 2
                                    ];
                                    $specsTable = $productDetails['specs_table'] ?? [
                                        'Concentration' => 'Eau De Parfum',
                                        'Volume' => '50ml',
                                        'Longevity' => '8+ Hours',
                                        'Occasion' => 'Day & Night',
                                        'Gender' => 'For Men',
                                        'Country of Origin' => 'India'
                                    ];
                                @endphp
                                <div id="dynInfoScentDots">
                                    @foreach($scentProfile as $label => $dots)
                                        @continue(($productDetails['scent_visibility'][$label] ?? true) === false)
                                        <div class="lux-scent-chart-item" @if($loop->last) style="margin-bottom: 16px;" @endif>
                                            <span class="lux-scent-chart-label">{{ $label }}</span>
                                            <div class="lux-scent-dots">
                                                @for($d = 1; $d <= 5; $d++)
                                                    <span class="lux-scent-dot {{ $d <= (int)$dots ? 'filled' : '' }}"></span>
                                                @endfor
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <table class="lux-specs-table" id="dynInfoSpecsTable">
                                    @foreach($specsTable as $k => $v)
                                        <tr>
                                            <td>{{ $k }}</td>
                                            <td>{{ $v }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @elseif($isWatch)
                                @php
                                    $watchSpecs = $productDetails['specs_table'] ?? [
                                        'Model' => $product->product_name ?: 'KNP Automatic 904L',
                                        'Movement' => 'Japanese Automatic Movement',
                                        'Case Material' => '904L Stainless Steel',
                                        'Case Diameter' => '41mm',
                                        'Glass' => 'Sapphire Crystal',
                                        // 'Water Resistance' => '100 Meters',
                                        'Strap' => '904L Stainless Steel',
                                        'Warranty' => '1 Year Warranty on Machinery'
                                    ];
                                @endphp
                                <table class="lux-specs-table" id="dynInfoSpecsTable">
                                    @foreach($watchSpecs as $k => $v)
                                        @continue(($productDetails['watch_spec_visibility'][$k] ?? true) === false)
                                        <tr>
                                            <td>{{ $productDetails['watch_spec_labels'][$k] ?? $k }}</td>
                                            <td>{{ $v }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                @php
                                    $modelSpecs = $productDetails['model_specs'] ?? [
                                        'wearing_size' => 'L Size',
                                        'height' => '6\'1"',
                                        'chest' => '40"',
                                        'waist' => '32"',
                                        'fit_level' => 'Slim',
                                        'thickness_level' => 'Medium'
                                    ];
                                    $fitLevel = strtolower($modelSpecs['fit_level'] ?? 'slim');
                                    $fitLeft = ($fitLevel === 'regular') ? '16%' : (($fitLevel === 'super slim') ? '84%' : '50%');
                                    $thicknessLevel = strtolower($modelSpecs['thickness_level'] ?? 'medium');
                                    $thicknessLeft = ($thicknessLevel === 'light') ? '16%' : (($thicknessLevel === 'heavy') ? '84%' : '50%');
                                    $shirtVisibility = $productDetails['shirt_field_visibility'] ?? [];
                                    $visibleMeasurements = [];
                                    if (($shirtVisibility['height'] ?? true) !== false) $visibleMeasurements[] = 'Height: ' . ($modelSpecs['height'] ?? '6\'1"');
                                    if (($shirtVisibility['chest'] ?? true) !== false) $visibleMeasurements[] = 'Chest: ' . ($modelSpecs['chest'] ?? '40"');
                                    if (($shirtVisibility['waist'] ?? true) !== false) $visibleMeasurements[] = 'Waist: ' . ($modelSpecs['waist'] ?? '32"');
                                    $showModelHeader = ($shirtVisibility['wearing_size'] ?? true) !== false || count($visibleMeasurements) > 0;
                                    $showModelCard = $showModelHeader || ($shirtVisibility['fit_level'] ?? true) !== false || ($shirtVisibility['thickness_level'] ?? true) !== false;
                                @endphp
                                <div class="lux-model-card" id="dynModelCard" @if(!$showModelCard) style="display:none;" @endif>
                                    <div class="lux-model-header" id="dynModelHeader" @if(!$showModelHeader) style="display:none;" @endif>
                                        <img src="{{ asset('images/team/01.jpg') }}" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'" class="lux-model-avatar" alt="Model">
                                        <div>
                                            <div class="lux-model-title" id="dynModelWearingSize" @if(($shirtVisibility['wearing_size'] ?? true) === false) style="display:none;" @endif>Model is wearing: {{ $modelSpecs['wearing_size'] ?? 'L Size' }}</div>
                                            <div class="lux-model-measurements" id="dynModelMeasurements" @if(!$visibleMeasurements) style="display:none;" @endif>{{ implode(' | ', $visibleMeasurements) }}</div>
                                        </div>
                                    </div>

                                    <div class="lux-slider-indicator-block" id="dynModelFitBlock" @if(($shirtVisibility['fit_level'] ?? true) === false) style="display:none;" @endif>
                                        <div class="lux-slider-label">Fit</div>
                                        <div class="lux-slider-track">
                                            <div class="lux-slider-point" id="dynModelFitPoint" style="left: {{ $fitLeft }};"></div>
                                        </div>
                                        <div class="lux-slider-levels" id="dynModelFitLevels">
                                            <span class="{{ $fitLevel === 'regular' ? 'active-level' : '' }}">Regular</span>
                                            <span class="{{ $fitLevel === 'slim' ? 'active-level' : '' }}">Slim</span>
                                            <span class="{{ $fitLevel === 'super slim' ? 'active-level' : '' }}">Super Slim</span>
                                        </div>
                                    </div>

                                    <div class="lux-slider-indicator-block" id="dynModelThicknessBlock" @if(($shirtVisibility['thickness_level'] ?? true) === false) style="display:none;" @endif>
                                        <div class="lux-slider-label">Thickness</div>
                                        <div class="lux-slider-track">
                                            <div class="lux-slider-point" id="dynModelThicknessPoint" style="left: {{ $thicknessLeft }};"></div>
                                        </div>
                                        <div class="lux-slider-levels" id="dynModelThicknessLevels">
                                            <span class="{{ $thicknessLevel === 'light' ? 'active-level' : '' }}">Light</span>
                                            <span class="{{ $thicknessLevel === 'medium' ? 'active-level' : '' }}">Medium</span>
                                            <span class="{{ $thicknessLevel === 'heavy' ? 'active-level' : '' }}">Heavy</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Tab 2: Notes / Specifications / Details -->
                <div class="lux-tab-pane" id="tabNotes" style="display:none;">
                    @if($isPerfume)
                        @php
                            $notes = $productDetails['scent_notes'] ?? [
                                'top' => 'Bergamot, Sea Salt, Crisp Citrus & Green Apple',
                                'heart' => 'Aquatic Accord, Lavender, Geranium & Cardamom',
                                'base' => 'Amberwood, Cedarwood, Oakmoss & White Musk'
                            ];
                        @endphp
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div style="background:#FAFAF9; padding:20px; border-radius:8px; border:1px solid #ECEAE5;">
                                    <strong style="color:var(--knp-red); text-transform:uppercase; font-size:12px; letter-spacing:1px; display:block; margin-bottom:8px;">Top Notes</strong>
                                    <p style="font-size:13px; color:#555; margin:0;">{{ $notes['top'] ?? 'Bergamot, Sea Salt, Crisp Citrus & Green Apple' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div style="background:#FAFAF9; padding:20px; border-radius:8px; border:1px solid #ECEAE5;">
                                    <strong style="color:var(--knp-red); text-transform:uppercase; font-size:12px; letter-spacing:1px; display:block; margin-bottom:8px;">Heart Notes</strong>
                                    <p style="font-size:13px; color:#555; margin:0;">{{ $notes['heart'] ?? 'Aquatic Accord, Lavender, Geranium & Cardamom' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div style="background:#FAFAF9; padding:20px; border-radius:8px; border:1px solid #ECEAE5;">
                                    <strong style="color:var(--knp-red); text-transform:uppercase; font-size:12px; letter-spacing:1px; display:block; margin-bottom:8px;">Base Notes</strong>
                                    <p style="font-size:13px; color:#555; margin:0;">{{ $notes['base'] ?? 'Amberwood, Cedarwood, Oakmoss & White Musk' }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($isWatch)
                        <div style="background:#FAFAF9; padding:30px; border-radius:8px; border:1px solid #ECEAE5; max-width:800px; margin:0 auto; font-size:13px; line-height:1.7; color:#555;">
                            <h4 style="color:var(--knp-black); font-size:18px; font-weight:700; margin-bottom:14px;">Technical Specifications</h4>
                            <p>{{ $productDetails['narrative'] ?? ($description ?: 'Engineered with high-precision automatic mechanical movement. Encased in 904L surgical-grade stainless steel with scratch-resistant sapphire crystal glass.') }}</p>
                            @if(!empty($productDetails['specs_table']) && is_array($productDetails['specs_table']))
                                <table class="lux-specs-table" id="dynWatchSpecsTable" style="margin-top:16px;">
                                    @foreach($productDetails['specs_table'] as $k => $v)
                                        @continue(($productDetails['watch_spec_visibility'][$k] ?? true) === false)
                                        <tr>
                                            <td style="font-weight:600; width:40%;">{{ $productDetails['watch_spec_labels'][$k] ?? $k }}</td>
                                            <td>{{ $v }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                        </div>
                    @else
                        <div style="background:#FAFAF9; padding:30px; border-radius:8px; border:1px solid #ECEAE5; max-width:800px; margin:0 auto; font-size:13px; line-height:1.7; color:#555;">
                            <h4 style="color:var(--knp-black); font-size:18px; font-weight:700; margin-bottom:14px;">Fabric &amp; Craftsmanship Details</h4>
                            <p>{{ $description ?: 'Tailored from rich satin cotton blend designed for maximum comfort and an impeccable sharp drape. Features reinforced stitching on collars and cuffs.' }}</p>
                            @if(!empty($productDetails['specs_table']) && is_array($productDetails['specs_table']))
                                <table class="lux-specs-table" id="dynShirtSpecsTable" style="margin-top:16px;">
                                    @foreach($productDetails['specs_table'] as $k => $v)
                                        @php $shirtKey = ['Fabric' => 'fabric', 'Fit' => 'fit_level', 'Collar' => 'collar', 'Sleeve' => 'sleeve'][$k] ?? null; @endphp
                                        @continue($shirtKey && ($shirtVisibility[$shirtKey] ?? true) === false)
                                        <tr>
                                            <td style="font-weight:600; width:40%;">{{ $k }}</td>
                                            <td>{{ $v }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Tab 3: How to Use / Shipping / Size & Fit -->
                <div class="lux-tab-pane" id="tabHow" style="display:none;">
                    <div style="background:#FAFAF9; padding:30px; border-radius:8px; border:1px solid #ECEAE5; max-width:800px; margin:0 auto; font-size:13px; line-height:1.7; color:#555;">
                        @if($isPerfume)
                            <h4 style="color:var(--knp-black); font-size:18px; font-weight:700; margin-bottom:14px;">Recommended Application &amp; Care</h4>
                            <p>{!! nl2br(e($productDetails['how_to_use'] ?? "For optimum longevity and fragrance diffusion, spray directly onto pulse points: neck, wrists, and behind the ears. Avoid rubbing after application to keep the fragrance pyramid intact.\n\nStore in a cool, dry place away from direct sunlight to preserve the aromatic notes.")) !!}</p>
                        @elseif($isWatch)
                            <h4 style="color:var(--knp-black); font-size:18px; font-weight:700; margin-bottom:14px;">Shipping &amp; Delivery</h4>
                            <p>{!! nl2br(e($productDetails['shipping_info'] ?? "Complimentary express shipping across India. All watches are safely packaged in shock-proof luxury presentation boxes with full transit insurance.\n\nDelivery time: Metro cities 2-3 business days, rest of India 3-5 business days. Real-time tracking link will be provided via SMS and email upon dispatch.")) !!}</p>
                        @else
                            <h4 style="color:var(--knp-black); font-size:18px; font-weight:700; margin-bottom:14px;">Size Guide &amp; Fit Recommendations</h4>
                            <p>{!! nl2br(e($productDetails['size_guide'] ?? "Our satin shirts follow modern slim-fit sizing. If you prefer a relaxed or loose fit, we recommend ordering one size up.\n\nPlease refer to our interactive Size Guide for detailed chest, shoulder, and length measurements before selecting your size.")) !!}</p>
                        @endif
                    </div>
                </div>

                <!-- Tab 4: Ingredients / Warranty / Shipping -->
                <div class="lux-tab-pane" id="tabIngredients" style="display:none;">
                    <div style="background:#FAFAF9; padding:30px; border-radius:8px; border:1px solid #ECEAE5; max-width:800px; margin:0 auto; font-size:13px; line-height:1.7; color:#555;">
                        @if($isPerfume)
                            <h4 style="color:var(--knp-black); font-size:18px; font-weight:700; margin-bottom:14px;">Ingredients &amp; Compliance</h4>
                            <p>{!! nl2br(e($productDetails['ingredients'] ?? "Alcohol Denat., Fragrance (Parfum), Aqua (Water), Limonene, Linalool, Citronellol, Coumarin, Geraniol, Citral. Formulated in accordance with IFRA international fragrance safety standards.")) !!}</p>
                        @elseif($isWatch)
                            <h4 style="color:var(--knp-black); font-size:18px; font-weight:700; margin-bottom:14px;">Warranty &amp; Service Coverage</h4>
                            <p>{!! nl2br(e($productDetails['warranty_info'] ?? "1 Year Comprehensive Warranty covering mechanical movement and manufacturing defects. Dedicated customer support & service across India.\n\nWarranty card included inside the collector box. For warranty claims or questions, contact support@houseofknp.com.")) !!}</p>
                        @else
                            <h4 style="color:var(--knp-black); font-size:18px; font-weight:700; margin-bottom:14px;">Shipping &amp; Hassle-Free Returns</h4>
                            <p>{!! nl2br(e($productDetails['shipping_returns'] ?? "Enjoy free standard shipping on all prepaid orders. We offer an easy 15-day return and exchange policy on unworn apparel with original tags attached.\n\nReturn pickups are arranged directly from your doorstep with instant exchange or store credit.")) !!}</p>
                        @endif
                    </div>
                </div>

                <!-- Tab 5: Reviews -->
                <div class="lux-tab-pane" id="tabReviews" style="display:none;">
                    <div class="row g-4">
                        <div class="col-lg-7">
                            @if ($reviews->isNotEmpty())
                                <div style="display:flex; flex-direction:column; gap:16px;">
                                    @foreach ($reviews as $review)
                                        <div style="border:1px solid #ECEAE5; padding:20px; border-radius:8px; background:#FAFAF9;">
                                            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                                <strong style="color:var(--knp-black); font-size:14px;">{{ $review->name ?: 'Customer' }}</strong>
                                                <span style="color:var(--knp-red);">
                                                    @for ($rating = 1; $rating <= 5; $rating++)
                                                        <i class="fa {{ $rating <= (int) $review->ratings ? 'fa-star' : 'fa-star-o' }}"></i>
                                                    @endfor
                                                </span>
                                            </div>
                                            <p style="font-size:13px; color:#555; margin:0; line-height:1.6;">{{ $review->review }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p style="color:var(--knp-muted); font-size:14px;">No reviews yet. Be the first to review this product!</p>
                            @endif
                        </div>

                        <div class="col-lg-5">
                            <div style="border:1px solid #ECEAE5; padding:24px; border-radius:8px; background:#FAFAF9;">
                                <h4 style="font-size:15px; font-weight:800; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px; color:var(--knp-black);">Verified Customer Review</h4>

                                @if (session('review_success'))
                                    <div style="background:#e8f5e9; border:1px solid #c8e6c9; color:#2e7d32; padding:14px; border-radius:6px; font-size:13px; margin-bottom:16px;">
                                        <i class="fa fa-check-circle" style="margin-right:6px;"></i>
                                        {{ session('review_success') }}
                                    </div>
                                @endif

                                @if ($errors->has('review'))
                                    <div style="background:#ffebee; border:1px solid #ffcdd2; color:#c62828; padding:14px; border-radius:6px; font-size:13px; margin-bottom:16px;">
                                        <i class="fa fa-exclamation-circle" style="margin-right:6px;"></i>
                                        {{ $errors->first('review') }}
                                    </div>
                                @endif

                                @guest
                                    <div style="text-align:center; padding: 20px 10px;">
                                        <div style="width:48px; height:48px; border-radius:50%; background:rgba(153,0,0,0.08); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; color:var(--knp-red); font-size:20px;">
                                            <i class="fa fa-lock"></i>
                                        </div>
                                        <p style="font-size:13px; color:#555; margin-bottom:14px; line-height:1.5;">Please login to your House of KNP account to submit a verified product review.</p>
                                        <a href="{{ route('login') }}" class="lux-btn-buynow" style="display:inline-block; text-decoration:none; padding:10px 24px; font-size:12px; border-radius:4px;">Login to Review</a>
                                    </div>
                                @else
                                    @if ($userReview)
                                        @if ($userReview->status == 1)
                                            <div style="background:#fff; border:1px solid #e0e0e0; padding:18px; border-radius:6px;">
                                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                                    <span style="background:#2e7d32; color:#fff; font-size:11px; padding:3px 8px; border-radius:4px; font-weight:600;">✓ Verified Buyer Review</span>
                                                    <span style="color:var(--knp-red);">
                                                        @for ($rating = 1; $rating <= 5; $rating++)
                                                            <i class="fa {{ $rating <= (int) $userReview->ratings ? 'fa-star' : 'fa-star-o' }}"></i>
                                                        @endfor
                                                    </span>
                                                </div>
                                                <p style="font-size:13px; color:#333; margin:0; line-height:1.6;">"{{ $userReview->review }}"</p>
                                            </div>
                                        @else
                                            <div style="background:#fff; border:1px solid #ffe082; padding:20px; border-radius:6px; text-align:center;">
                                                <i class="fa fa-clock-o" style="font-size:26px; color:#f57f17; margin-bottom:8px;"></i>
                                                <h5 style="font-size:14px; font-weight:700; color:#333; margin-bottom:6px;">Review Under Moderation</h5>
                                                <p style="font-size:12px; color:#666; margin:0; line-height:1.5;">Thank you! Your review has been submitted and is currently being verified by our curation team before going live.</p>
                                            </div>
                                        @endif
                                    @elseif ($hasPurchased && !$isDelivered)
                                        <div style="background:#fff; border:1px solid #ECEAE5; padding:24px 20px; border-radius:6px; text-align:center;">
                                            <div style="width:48px; height:48px; border-radius:50%; background:rgba(201,162,39,0.12); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; color:var(--knp-gold); font-size:20px;">
                                                <i class="fa fa-truck"></i>
                                            </div>
                                            <h5 style="font-size:14px; font-weight:700; color:#222; margin-bottom:6px;">Delivery In Progress</h5>
                                            <p style="font-size:12px; color:#666; line-height:1.6; margin-bottom:14px;">Your order has been placed and is currently in transit. You can submit your verified product review as soon as your package has been delivered.</p>
                                            <a href="{{ url('/account#orders') }}" class="lux-btn-buynow" style="display:inline-block; font-size:11px; text-decoration:none; padding:9px 20px; border-radius:4px;">Track Order in Account</a>
                                        </div>
                                    @elseif (! $canReview)
                                        <div style="background:#fff; border:1px solid #ECEAE5; padding:20px; border-radius:6px; text-align:center;">
                                            <div style="width:48px; height:48px; border-radius:50%; background:rgba(0,0,0,0.04); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; color:#666; font-size:18px;">
                                                <i class="fa fa-shopping-bag"></i>
                                            </div>
                                            <h5 style="font-size:14px; font-weight:700; color:#222; margin-bottom:6px;">Verified Purchase Required</h5>
                                            <p style="font-size:12px; color:#666; line-height:1.6; margin-bottom:14px;">To guarantee authentic client feedback, only verified buyers who have purchased and received this product can leave a review.</p>
                                            <button type="button" onclick="document.getElementById('productAddToCartBtn')?.click()" class="lux-btn-buynow" style="font-size:12px; padding:8px 18px; border-radius:4px;">Add to Bag &amp; Order</button>
                                        </div>
                                    @else
                                        <form action="{{ route('product.review.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div style="margin-bottom:14px;">
                                                <label style="font-size:12px; font-weight:700; display:block; margin-bottom:6px;">Your Rating</label>
                                                <select name="ratings" style="width:100%; height:40px; border:1px solid #D5D5D5; border-radius:4px; padding:0 10px; font-size:13px;" required>
                                                    <option value="5">★★★★★ (5/5 Exceptional)</option>
                                                    <option value="4">★★★★☆ (4/5 Very Good)</option>
                                                    <option value="3">★★★☆☆ (3/5 Good)</option>
                                                    <option value="2">★★☆☆☆ (2/5 Fair)</option>
                                                    <option value="1">★☆☆☆☆ (1/5 Poor)</option>
                                                </select>
                                            </div>
                                            <div style="margin-bottom:14px;">
                                                <label style="font-size:12px; font-weight:700; display:block; margin-bottom:6px;">Your Review</label>
                                                <textarea name="review" style="width:100%; border:1px solid #D5D5D5; border-radius:4px; padding:10px; min-height:100px; font-size:13px;" placeholder="Share your experience with fit, fabric, craftsmanship..." required></textarea>
                                            </div>
                                            <button type="submit" class="lux-btn-buynow" style="width:100%; border:none; cursor:pointer; font-size:13px; letter-spacing:1px; text-transform:uppercase;">Submit Review</button>
                                        </form>
                                    @endif
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 5. "YOU MAY ALSO LIKE" Section -->
    @if ($relatedProducts->isNotEmpty())
        <section class="lux-related-section">
            <div class="container-fluid" style="max-width: 1360px; padding: 0 clamp(16px, 3vw, 40px);">
                <div class="lux-section-header-center">
                    <h3 class="lux-section-title-gem">You May Also Like</h3>
                    <div class="lux-head-divider">
                        <span class="lux-divider-line"></span>
                        <span class="lux-divider-gem"></span>
                        <span class="lux-divider-line"></span>
                    </div>
                </div>

                <div class="lux-related-grid">
                    @foreach ($relatedProducts->take(5) as $rel)
                        @php
                            $relImgs = house_product_images($rel);
                            $relPrice = house_product_price($rel);
                            $relOldPrice = (float) ($rel->mrp_price ?? ($rel->product_mrp_price ?? 0));
                        @endphp
                        <div class="lux-related-card">
                            <div class="lux-related-media">
                                <a href="{{ $productUrl($rel) }}">
                                    <img src="{{ $relImgs[0] ?? asset('images/product/01.jpg') }}" alt="{{ $rel->product_name }}">
                                </a>
                                <a href="{{ route('wishlist.add', $rel->id) }}" class="lux-related-wishlist" onclick="ajaxAddToWishlist(this.href, event)">
                                    <i class="fa fa-heart-o"></i>
                                </a>
                            </div>
                            <div class="lux-related-body">
                                <a href="{{ $productUrl($rel) }}" class="lux-related-name">{{ $rel->product_name }}</a>
                                <div class="lux-related-desc">{{ $rel->category_name ?? 'Collection' }}</div>
                                <div class="lux-related-price-row">
                                    <span class="lux-rel-new">₹{{ number_format($relPrice) }}</span>
                                    @if ($relOldPrice > $relPrice)
                                        <span class="lux-rel-old">₹{{ number_format($relOldPrice) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 6. Trust Badges Strip -->
    {{-- <div class="lux-trust-strip">
        <div class="container-fluid" style="max-width: 1360px; padding: 0 clamp(16px, 3vw, 40px);">
            <div class="lux-trust-grid" id="dynamicTrustBadges">
                @if($isPerfume)
                    <div class="lux-trust-item">
                        <i class="fa fa-flask"></i>
                        <div class="lux-trust-info">
                            <strong>Crafted with</strong>
                            <span>Premium Ingredients</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-certificate"></i>
                        <div class="lux-trust-info">
                            <strong>IFRA Certified</strong>
                            <span>International Standards</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-leaf"></i>
                        <div class="lux-trust-info">
                            <strong>Non-Irritating</strong>
                            <span>Safe on Skin</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-gift"></i>
                        <div class="lux-trust-info">
                            <strong>Luxury Packaging</strong>
                            <span>Perfect for Gifting</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-globe"></i>
                        <div class="lux-trust-info">
                            <strong>Proudly Made in India</strong>
                            <span>With Passion</span>
                        </div>
                    </div>
                @elseif($isWatch)
                    <div class="lux-trust-item">
                        <i class="fa fa-users"></i>
                        <div class="lux-trust-info">
                            <strong>10,000+</strong>
                            <span>Gentlemen Trust Us</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-star"></i>
                        <div class="lux-trust-info">
                            <strong>4.9/5 Quality</strong>
                            <span>Client Satisfaction</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-refresh"></i>
                        <div class="lux-trust-info">
                            <strong>Easy Returns</strong>
                            <span>15 Day Return Policy</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-gift"></i>
                        <div class="lux-trust-info">
                            <strong>Secure Packaging</strong>
                            <span>Premium Watch Box</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-headphones"></i>
                        <div class="lux-trust-info">
                            <strong>Dedicated Support</strong>
                            <span>We're Here For You</span>
                        </div>
                    </div>
                @else
                    <div class="lux-trust-item">
                        <i class="fa fa-users"></i>
                        <div class="lux-trust-info">
                            <strong>10,000+</strong>
                            <span>Happy Gentlemen</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-star"></i>
                        <div class="lux-trust-info">
                            <strong>4.9/5 Quality</strong>
                            <span>Client Satisfaction</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-gift"></i>
                        <div class="lux-trust-info">
                            <strong>Premium</strong>
                            <span>Luxury Packaging</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-refresh"></i>
                        <div class="lux-trust-info">
                            <strong>Easy Returns</strong>
                            <span>15 Day Return Policy</span>
                        </div>
                    </div>
                    <div class="lux-trust-item">
                        <i class="fa fa-check-circle"></i>
                        <div class="lux-trust-info">
                            <strong>100% Authentic</strong>
                            <span>Original Products</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div> --}}

</div>

@if(!$isPerfume && !$isWatch)
    <!-- Size Guide Modal -->
    <div id="luxSizeModal" class="lux-modal-backdrop" style="display: none;">
        <div class="lux-modal-content">
            <button type="button" class="lux-modal-close" onclick="toggleSizeGuideModal(false)">&times;</button>
            <h3 style="font-family:'Cormorant Garamond', Georgia, serif; font-size:24px; font-weight:700; margin-bottom:16px;">Size Guide</h3>
            <img src="{{ asset('images/size.png') }}" onerror="this.src='https://via.placeholder.com/600x400?text=Size+Chart'" style="width:100%; border-radius:6px;" alt="Size Guide">
        </div>
    </div>
@endif

@endsection

@section('scripts')
<script>
    const variants = @json($variants);
    const defaultProductDetails = @json($productDetails);
    let currentQty = 1;

    function changeProductImage(element, url) {
        document.querySelectorAll('.lux-thumb-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        element.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
        const mainImg = document.getElementById('productMainDisplayImg');
        if (mainImg) {
            mainImg.style.opacity = '0.3';
            mainImg.style.transform = 'scale(0.98)';
            mainImg.src = url;
            setTimeout(() => { 
                mainImg.style.opacity = '1'; 
                mainImg.style.transform = 'scale(1)';
            }, 150);
        }
    }

    function stepGalleryImage(direction) {
        const thumbItems = Array.from(document.querySelectorAll('.lux-thumb-item'));
        if (!thumbItems.length) return;
        
        let activeIdx = thumbItems.findIndex(el => el.classList.contains('active'));
        if (activeIdx === -1) activeIdx = 0;
        
        let newIdx = activeIdx + direction;
        if (newIdx < 0) newIdx = thumbItems.length - 1;
        if (newIdx >= thumbItems.length) newIdx = 0;
        
        thumbItems[newIdx].click();
    }

    // Keyboard Arrow Navigation
    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        if (e.key === 'ArrowLeft') stepGalleryImage(-1);
        if (e.key === 'ArrowRight') stepGalleryImage(1);
    });

    function selectLuxColor(element) {
        document.querySelectorAll('.lux-color-swatch-circle').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        const label = element.dataset.colorLabel;
        const labelEl = document.getElementById('selectedColorLabel');
        if (labelEl && label) labelEl.textContent = label;
        syncVariantSelection();
    }

    function selectLuxSize(element, size) {
        document.querySelectorAll('.lux-size-pill').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        const labelEl = document.getElementById('selectedSizeLabel');
        if (labelEl) labelEl.textContent = size;
        syncVariantSelection();
    }

    function updateLuxQty(delta) {
        const display = document.getElementById('luxQuantityDisplay');
        currentQty = Math.max(1, currentQty + delta);
        if (display) display.value = currentQty;
        syncVariantSelection();
    }

    function escapeSpecHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        })[char]);
    }

    function renderDynamicVariantSpecs(pd) {
        if (!pd) pd = defaultProductDetails;
        if (typeof pd === 'string') {
            try { pd = JSON.parse(pd); } catch (e) { pd = defaultProductDetails; }
        }
        if (!pd) return;

        if (pd.category_type === 'watch' || document.getElementById('dynWatchSpecsTable')) {
            const modelLabel = String(pd.watch_spec_labels?.Model || 'Model').toUpperCase();
            const selectionLabel = document.getElementById('watchModelSelectionLabel');
            const detailsLabel = document.getElementById('watchModelDetailsLabel');
            const modelDetail = document.getElementById('dynWatchModelDetail');
            if (selectionLabel) selectionLabel.textContent = pd.watch_spec_visibility?.Model === false ? 'VARIANT' : modelLabel;
            if (detailsLabel) detailsLabel.textContent = modelLabel;
            if (modelDetail) modelDetail.style.display = pd.watch_spec_visibility?.Model === false ? 'none' : '';
        }

        // Update tab labels dynamically
        if (pd.tab_labels && typeof pd.tab_labels === 'object') {
            const t1 = document.getElementById('luxTabLabel1');
            const t2 = document.getElementById('luxTabLabel2');
            const t3 = document.getElementById('luxTabLabel3');
            const t4 = document.getElementById('luxTabLabel4');
            if (t1 && pd.tab_labels.tab1) t1.textContent = pd.tab_labels.tab1;
            if (t2 && pd.tab_labels.tab2) t2.textContent = pd.tab_labels.tab2;
            if (t3 && pd.tab_labels.tab3) t3.textContent = pd.tab_labels.tab3;
            if (t4 && pd.tab_labels.tab4) t4.textContent = pd.tab_labels.tab4;
        }

        const headlineEl = document.getElementById('dynInfoHeadline');
        if (headlineEl && pd.headline) {
            headlineEl.innerHTML = pd.headline.replace(/\n/g, '<br>');
        }

        const narrativeEl = document.getElementById('dynInfoNarrative');
        if (narrativeEl && pd.narrative) {
            narrativeEl.textContent = pd.narrative;
        }

        const bulletsEl = document.getElementById('dynInfoBullets');
        if (bulletsEl && Array.isArray(pd.bullet_points) && pd.bullet_points.length > 0) {
            bulletsEl.innerHTML = pd.bullet_points.map(bp => `<li><span>${bp}</span></li>`).join('');
        }

        const specsEl = document.getElementById('dynInfoSpecsTable');
        const watchSpecsEl = document.getElementById('dynWatchSpecsTable');
        const shirtSpecsEl = document.getElementById('dynShirtSpecsTable');
        if ((specsEl || watchSpecsEl || shirtSpecsEl) && pd.specs_table && typeof pd.specs_table === 'object') {
            const shirtSpecKeys = { Fabric: 'fabric', Fit: 'fit_level', Collar: 'collar', Sleeve: 'sleeve' };
            const rows = Object.entries(pd.specs_table)
                .filter(([key]) => pd.watch_spec_visibility?.[key] !== false &&
                    (shirtSpecKeys[key] === undefined || pd.shirt_field_visibility?.[shirtSpecKeys[key]] !== false))
                .map(([key, value]) => {
                    const label = pd.watch_spec_labels?.[key] || key;
                    return `<tr><td style="font-weight:600; width:40%;">${escapeSpecHtml(label)}</td><td>${escapeSpecHtml(value)}</td></tr>`;
                }).join('');
            if (specsEl) specsEl.innerHTML = rows;
            if (watchSpecsEl) watchSpecsEl.innerHTML = rows;
            if (shirtSpecsEl) shirtSpecsEl.innerHTML = rows;
        }

        const scentEl = document.getElementById('dynInfoScentDots');
        if (scentEl && pd.scent_profile && typeof pd.scent_profile === 'object') {
            let dotsHtml = '';
            for (const [label, count] of Object.entries(pd.scent_profile)) {
                if (pd.scent_visibility?.[label] === false) continue;
                let dots = '';
                for (let d = 1; d <= 5; d++) {
                    dots += `<span class="lux-scent-dot ${d <= count ? 'filled' : ''}"></span>`;
                }
                dotsHtml += `<div class="lux-scent-chart-item"><span class="lux-scent-chart-label">${escapeSpecHtml(label)}</span><div class="lux-scent-dots">${dots}</div></div>`;
            }
            scentEl.innerHTML = dotsHtml;
        }

        if (pd.model_specs && typeof pd.model_specs === 'object') {
            const ms = pd.model_specs;
            const visible = pd.shirt_field_visibility || {};
            const showSize = visible.wearing_size !== false;
            const sizeEl = document.getElementById('dynModelWearingSize');
            if (sizeEl) {
                sizeEl.textContent = 'Model is wearing: ' + (ms.wearing_size || 'L Size');
                sizeEl.style.display = showSize ? '' : 'none';
            }
            const measEl = document.getElementById('dynModelMeasurements');
            const measurements = [];
            if (visible.height !== false) measurements.push('Height: ' + (ms.height || '6\'1"'));
            if (visible.chest !== false) measurements.push('Chest: ' + (ms.chest || '40"'));
            if (visible.waist !== false) measurements.push('Waist: ' + (ms.waist || '32"'));
            if (measEl) {
                measEl.textContent = measurements.join(' | ');
                measEl.style.display = measurements.length ? '' : 'none';
            }
            const showHeader = showSize || measurements.length > 0;
            const header = document.getElementById('dynModelHeader');
            if (header) header.style.display = showHeader ? '' : 'none';
            const fitBlock = document.getElementById('dynModelFitBlock');
            if (fitBlock) fitBlock.style.display = visible.fit_level === false ? 'none' : '';
            const thicknessBlock = document.getElementById('dynModelThicknessBlock');
            if (thicknessBlock) thicknessBlock.style.display = visible.thickness_level === false ? 'none' : '';
            const modelCard = document.getElementById('dynModelCard');
            if (modelCard) modelCard.style.display = (showHeader || visible.fit_level !== false || visible.thickness_level !== false) ? '' : 'none';
            const fit = (ms.fit_level || 'slim').toLowerCase();
            const fitLeft = fit === 'regular' ? '16%' : (fit === 'super slim' ? '84%' : '50%');
            const fitPoint = document.getElementById('dynModelFitPoint');
            if (fitPoint) fitPoint.style.left = fitLeft;
            const fitLevels = document.getElementById('dynModelFitLevels');
            if (fitLevels) {
                fitLevels.innerHTML = `
                    <span class="${fit === 'regular' ? 'active-level' : ''}">Regular</span>
                    <span class="${fit === 'slim' ? 'active-level' : ''}">Slim</span>
                    <span class="${fit === 'super slim' ? 'active-level' : ''}">Super Slim</span>
                `;
            }
            const thk = (ms.thickness_level || 'medium').toLowerCase();
            const thkLeft = thk === 'light' ? '16%' : (thk === 'heavy' ? '84%' : '50%');
            const thkPoint = document.getElementById('dynModelThicknessPoint');
            if (thkPoint) thkPoint.style.left = thkLeft;
            const thkLevels = document.getElementById('dynModelThicknessLevels');
            if (thkLevels) {
                thkLevels.innerHTML = `
                    <span class="${thk === 'light' ? 'active-level' : ''}">Light</span>
                    <span class="${thk === 'medium' ? 'active-level' : ''}">Medium</span>
                    <span class="${thk === 'heavy' ? 'active-level' : ''}">Heavy</span>
                `;
            }
        }

        // Dynamic Feature Ribbon update
        const featureEl = document.getElementById('dynamicFeatureRibbon');
        if (featureEl && pd.feature_ribbon && Array.isArray(pd.feature_ribbon)) {
            const ribbonItems = featureEl.querySelectorAll('.lux-ribbon-item');
            pd.feature_ribbon.forEach((item, index) => {
                if (ribbonItems[index]) {
                    const strong = ribbonItems[index].querySelector('strong');
                    const span = ribbonItems[index].querySelector('span');
                    if (strong && item.title) strong.textContent = item.title;
                    if (span && item.desc) span.textContent = item.desc;
                }
            });
        }

        // Dynamic Trust Badges update
        const trustEl = document.getElementById('dynamicTrustBadges');
        if (trustEl && pd.trust_badges && Array.isArray(pd.trust_badges)) {
            const trustItems = trustEl.querySelectorAll('.lux-trust-item');
            pd.trust_badges.forEach((item, index) => {
                if (trustItems[index]) {
                    const strong = trustItems[index].querySelector('strong');
                    const span = trustItems[index].querySelector('span');
                    if (strong && item.title) strong.textContent = item.title;
                    if (span && item.desc) span.textContent = item.desc;
                }
            });
        }
    }

    function syncVariantSelection() {
        const activeColor = document.querySelector('.lux-color-swatch-circle.active')?.dataset.colorValue || '';
        const activeSize = document.querySelector('.lux-size-pill.active')?.dataset.sizeValue || '';
        
        let matchingVariant = variants.find(v => {
            const vSize = String(v.value || v.varient || v.size_value || '').trim();
            let details = v.varient_details || {};
            if (typeof details === 'string') {
                try { details = JSON.parse(details || '{}'); } catch (error) { details = {}; }
            }
            const vColor = String(details.color_name || v.varient_name || '').trim();
            return (!activeSize || vSize === activeSize) && (!activeColor || vColor === activeColor);
        }) || variants.find(v => {
            const vSize = String(v.value || v.varient || v.size_value || '').trim();
            return !activeSize || vSize === activeSize;
        }) || variants[0] || null;

        if (matchingVariant) {
            const offerPrice = Number.parseFloat(matchingVariant.offer_price || 0);
            const mrpPrice = Number.parseFloat(matchingVariant.mrp_price || 0);
            const currentPrice = offerPrice > 0 ? offerPrice : mrpPrice;

            const priceEl = document.getElementById('productCurrentPrice');
            if (priceEl && currentPrice > 0) {
                priceEl.textContent = `₹${Math.round(currentPrice).toLocaleString('en-IN')}`;
            }

            // Update Dynamic Specifications for this variant
            if (matchingVariant.varient_details) {
                renderDynamicVariantSpecs(matchingVariant.varient_details);
            } else {
                renderDynamicVariantSpecs(defaultProductDetails);
            }

            const addToCart = document.getElementById('productAddToCart');
            const buyNow = document.getElementById('productBuyNow');
            const addToWishlist = document.getElementById('productAddToWishlist');

            if (addToCart) {
                const url = new URL(addToCart.href, window.location.origin);
                url.searchParams.set('quantity', currentQty);
                if (matchingVariant.id) url.searchParams.set('variant_id', matchingVariant.id);
                addToCart.href = url.toString();
            }
            if (buyNow) {
                const url = new URL(buyNow.href, window.location.origin);
                url.searchParams.set('quantity', currentQty);
                if (matchingVariant.id) url.searchParams.set('variant_id', matchingVariant.id);
                buyNow.href = url.toString();
            }
            if (addToWishlist) {
                const url = new URL(addToWishlist.href, window.location.origin);
                if (matchingVariant.id) url.searchParams.set('variant_id', matchingVariant.id);
                addToWishlist.href = url.toString();
            }
        }
    }

    function switchLuxTab(event, tabId) {
        event.preventDefault();
        document.querySelectorAll('.lux-tabs-nav a').forEach(a => a.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.querySelectorAll('.lux-tab-pane').forEach(pane => pane.style.display = 'none');
        const target = document.getElementById(tabId);
        if (target) target.style.display = 'block';
    }

    function toggleSizeGuideModal(open) {
        const modal = document.getElementById('luxSizeModal');
        if (modal) modal.style.display = open ? 'flex' : 'none';
    }

    function openFullscreenImage() {
        const mainImg = document.getElementById('productMainDisplayImg');
        if (mainImg) {
            window.open(mainImg.src, '_blank');
        }
    }

    // Dynamic Live Delivery Countdown
    (function() {
        let totalSeconds = 2 * 3600 + 15 * 60 + 30;
        const timerEl = document.getElementById('luxDeliveryTimer');
        if (!timerEl) return;
        setInterval(function() {
            if (totalSeconds > 0) totalSeconds--;
            const h = Math.floor(totalSeconds / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;
            timerEl.textContent = `${h}h ${m}m ${s < 10 ? '0' : ''}${s}s`;
        }, 1000);
    })();

    // Initial variant sync on load
    document.addEventListener('DOMContentLoaded', function() {
        syncVariantSelection();
        if (['#tabReviews', '#reviews', '#review'].includes(window.location.hash)) {
            const btn = document.getElementById('luxReviewsTabBtn');
            if (btn) {
                btn.click();
                const target = document.getElementById('tabReviews');
                if (target) {
                    setTimeout(() => target.scrollIntoView({ behavior: 'smooth', block: 'start' }), 200);
                }
            }
        }
    });
    syncVariantSelection();
</script>
@endsection
