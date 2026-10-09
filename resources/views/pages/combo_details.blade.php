<?php
$comboDetails = [];
if (!empty($combo->combo_details)) {
    $comboDetails = is_string($combo->combo_details) ? json_decode($combo->combo_details, true) : (array) $combo->combo_details;
    $comboDetails = is_array($comboDetails) ? $comboDetails : [];
}
$comboSubtitle = !empty($comboDetails['subtitle']) ? $comboDetails['subtitle'] : 'One Gift. Three Statements.';
$comboQuote = !empty($comboDetails['quote']) ? $comboDetails['quote'] : 'Style. Class. Confidence — in one box.';
?>
@extends('layouts.app')

@php
    $comboMicroSpecs = $comboDetails['micro_specs'] ?? null;
    if (!is_array($comboMicroSpecs) || count($comboMicroSpecs) !== 4) {
        $comboMicroSpecs = [
            ['icon' => 'fa-certificate', 'title' => 'Premium Quality', 'value' => 'Finest Materials'],
            ['icon' => 'fa-truck', 'title' => 'Free Shipping', 'value' => 'Pan India'],
            ['icon' => 'fa-refresh', 'title' => 'Easy Returns', 'value' => '7 Days'],
            ['icon' => 'fa-shield', 'title' => 'Secure Payment', 'value' => '100% Protected'],
        ];
    }
    $comboImages = $combo->galleryImages();
    $comboItems = $combo->displayItems();
    $comboPlaceholder = asset('images/logo/logoo.png');
    $comboName = $combo->combo_name ?? 'Signature Box';
    $comboDesc = !empty($combo->product_description) ? trim(strip_tags($combo->product_description)) : ($comboQuote . ' - Curated luxury gift box from House of KNP.');
    $comboImg = $comboImages[0] ?? $comboPlaceholder;
    $canBuildCombo = empty($availabilityIssues);
    if ($canBuildCombo) {
        $defaultShirt = $shirtChoices->first(fn ($choice) => strcasecmp((string) $choice['product']->color, (string) $combo->shirt_color) === 0)
            ?? $shirtChoices->first();
        $defaultPerfume = $perfumeChoices->first();
        $selectedShirtChoice = $shirtChoices->first(fn ($choice) => (int) $choice['product']->id === (int) old('shirt_product_id', $defaultShirt['product']->id)) ?? $defaultShirt;
        $selectedPerfumeChoice = $perfumeChoices->first(fn ($choice) => (int) $choice['product']->id === (int) old('perfume_product_id', $defaultPerfume['product']->id)) ?? $defaultPerfume;
        $selectedShirtId = (int) $selectedShirtChoice['product']->id;
        $selectedPerfumeId = (int) $selectedPerfumeChoice['product']->id;
        $shirtVariantChoices = $shirtChoices->mapWithKeys(fn ($choice) => [$choice['product']->id => $choice['variants']->map(fn ($variant) => [
            'id' => $variant->id,
            'label' => $variant->value ?: $variant->varient ?: $variant->size_value,
            'stock' => (int) $variant->product_qty,
        ])->values()->all()])->all();
        $perfumeVariantChoices = $perfumeChoices->mapWithKeys(fn ($choice) => [$choice['product']->id => $choice['variants']->map(fn ($variant) => [
            'id' => $variant->id,
            'label' => $variant->value ?: $variant->varient ?: $variant->size_value,
            'stock' => (int) $variant->product_qty,
        ])->values()->all()])->all();
        $comboItems = [
            ['name' => !empty($comboDetails['item_1_name']) ? $comboDetails['item_1_name'] : 'Your Shirt', 'details' => 'Choose a color and size', 'image' => !empty($comboDetails['item_1_img']) ? house_main_media_url($comboDetails['item_1_img'], 'images') : null],
            ['name' => !empty($comboDetails['item_2_name']) ? $comboDetails['item_2_name'] : 'Watch', 'details' => $watchChoice['product']->product_name.' (fixed)', 'image' => !empty($comboDetails['item_2_img']) ? house_main_media_url($comboDetails['item_2_img'], 'images') : null],
            ['name' => !empty($comboDetails['item_3_name']) ? $comboDetails['item_3_name'] : 'Your Perfume', 'details' => 'Choose your fragrance', 'image' => !empty($comboDetails['item_3_img']) ? house_main_media_url($comboDetails['item_3_img'], 'images') : null],
        ];
    }
@endphp

@section('meta_title', $comboName . ' | House of KNP')
@section('meta_description', $comboDesc)
@section('meta_image', $comboImg)
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
        --knp-surface: #1E1E24;
        --knp-white: #FFFFFF;
        --knp-off-white: #FAFAF9;
        --knp-cream: #F5F4F0;
        --knp-gray-light: #F0EFEA;
        --knp-muted: #666666;
        --knp-gray-dark: #3F3F46;
        --knp-gold: #C9A227;
        --knp-gold-pale: #E8D5A3;
        --knp-ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    .combo-luxury-page {
        font-family: 'Montserrat', 'Manrope', -apple-system, sans-serif;
        color: var(--knp-black);
        background: #FFFFFF;
        overflow-x: hidden;
    }

    .knp-serif, .combo-title, .combo-section-title, .combo-price {
        font-family: 'Cormorant Garamond', Georgia, serif !important;
    }

    /* Breadcrumbs */
    .combo-breadcrumbs {
        padding: 16px clamp(20px, 4vw, 50px);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ECEAE5;
        background: #FFFFFF;
        font-size: 12px;
        color: var(--knp-muted);
    }
    .combo-breadcrumbs a {
        color: var(--knp-black);
        text-decoration: none;
        transition: color 0.2s;
    }
    .combo-breadcrumbs a:hover {
        color: var(--knp-red);
    }

    /* Main Product Hero Layout */
    .combo-hero-section {
        padding: clamp(30px, 4vw, 50px) 0 clamp(40px, 5vw, 60px);
    }

    /* Gallery (Left) */
    .combo-gallery-container {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .combo-thumbnails-rail {
        width: 80px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .combo-thumb-item {
        width: 80px;
        height: 80px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #E5E5E5;
        /* background: #111; */
        cursor: pointer;
        transition: all 0.3s var(--knp-ease);
        padding: 4px;
    }
    .combo-thumb-item.active, .combo-thumb-item:hover {
        border-color: var(--knp-red);
        box-shadow: 0 4px 12px var(--knp-red-glow);
    }
    .combo-thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 3px;
    }

    .combo-watch-video-btn {
        width: 80px;
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
    .combo-watch-video-btn i {
        font-size: 16px;
        color: var(--knp-red);
    }
    .combo-watch-video-btn:hover {
        border-color: var(--knp-red);
        background: #FFF5F5;
        color: var(--knp-red);
    }

    .combo-main-display {
        flex: 1;
        min-width: 0;
        position: relative;
        /* background: #0E0E11; */
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .combo-main-display img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.5s var(--knp-ease);
    }
    .combo-main-display:hover img {
        transform: scale(1.03);
    }

    .combo-gallery-nav-btn {
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
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    }
    .combo-gallery-nav-btn:hover {
        background: var(--knp-red);
        color: #FFFFFF;
        border-color: var(--knp-red);
        transform: translateY(-50%) scale(1.08);
        box-shadow: 0 6px 20px var(--knp-red-glow);
    }
    .combo-gallery-prev {
        left: 14px;
    }
    .combo-gallery-next {
        right: 14px;
    }

    .combo-360-badge {
        position: absolute;
        bottom: 18px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.65);
        color: #FFFFFF;
        backdrop-filter: blur(8px);
        padding: 6px 16px;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .combo-360-badge:hover {
        background: rgba(180, 0, 22, 0.8);
        border-color: var(--knp-red);
    }

    /* Product Info (Right) */
    .combo-badge-tag {
        display: inline-block;
        color: var(--knp-red);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .combo-title {
        font-size: clamp(32px, 3.5vw, 44px);
        font-weight: 600;
        line-height: 1.15;
        color: var(--knp-black);
        margin: 0 0 6px;
    }

    .combo-subtitle {
        font-size: 15px;
        font-weight: 500;
        color: var(--knp-muted);
        margin-bottom: 14px;
    }

    .combo-rating-proof {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        font-size: 13px;
    }
    .combo-stars {
        color: var(--knp-red);
        letter-spacing: 2px;
    }
    .combo-proof-text {
        color: var(--knp-muted);
        font-weight: 600;
    }

    .combo-pricing-block {
        display: flex;
        align-items: baseline;
        gap: 16px;
        margin-bottom: 6px;
    }
    .combo-price {
        font-size: clamp(34px, 3vw, 42px);
        font-weight: 700;
        color: var(--knp-red);
        line-height: 1;
    }
    .combo-mrp {
        font-size: 16px;
        color: #8E8E93;
        text-decoration: line-through;
        font-weight: 500;
    }
    .combo-discount-pill {
        background: var(--knp-red);
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 4px;
        letter-spacing: 0.5px;
    }
    .combo-tax-note {
        font-size: 12px;
        color: var(--knp-muted);
        margin-bottom: 24px;
    }

    /* 4 Micro Specs */
    .combo-micro-specs-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        padding: 16px 0;
        border-top: 1px solid #ECEAE5;
        border-bottom: 1px solid #ECEAE5;
        margin-bottom: 26px;
    }
    .combo-micro-spec-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .combo-micro-spec-item i {
        font-size: 20px;
        color: var(--knp-red);
        flex-shrink: 0;
    }
    .combo-micro-spec-info {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }
    .combo-micro-spec-info strong {
        font-size: 11px;
        font-weight: 700;
        color: var(--knp-black);
    }
    .combo-micro-spec-info span {
        font-size: 10px;
        color: var(--knp-muted);
    }

    /* Combination Selection Row */
    .combo-select-header {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--knp-black);
        margin-bottom: 14px;
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .combo-select-header::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #ECEAE5;
    }

    .combo-combination-cards {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 28px;
    }
    .combo-mini-card {
        flex: 1;
        min-width: 0;
        overflow-wrap: anywhere;
        border: 1px solid #E5E5E5;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        background: #FAFAFA;
        transition: all 0.3s;
    }
    .combo-mini-card:hover {
        border-color: var(--knp-red);
        background: #FFFFFF;
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }
    .combo-mini-card img {
        width: 54px;
        height: 54px;
        object-fit: contain;
        margin-bottom: 8px;
    }
    .combo-mini-card-title {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--knp-black);
        margin-bottom: 2px;
    }
    .combo-mini-card-sub {
        font-size: 9px;
        color: var(--knp-muted);
    }
    .combo-choice-panel { border: 1px solid #e5e5e5; border-radius: 10px; background: #fafafa; padding: 20px; margin-bottom: 22px; }
    .combo-choice-panel h3 { font-size: 16px; font-weight: 800; margin: 0 0 15px; }
    .combo-choice-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .combo-choice-field label { display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
    .combo-choice-field select { width: 100%; min-height: 44px; padding: 8px 10px; border: 1px solid #ccc; border-radius: 5px; background: #fff; color: #111; }
    .combo-color-field { grid-column: 1 / -1; }
    .combo-color-options { display: flex; flex-wrap: wrap; gap: 10px; }
    .combo-color-option { display: inline-flex; align-items: center; gap: 8px; padding: 7px 10px; border: 1px solid #d6d6d6; border-radius: 6px; background: #fff; color: #222; cursor: pointer; font-size: 11px; font-weight: 700; }
    .combo-color-option.active, .combo-color-option:focus-visible { border-color: var(--knp-red); box-shadow: 0 0 0 1px var(--knp-red); }
    .combo-color-dot { display: inline-block; width: 23px; height: 23px; flex: none; border: 1px solid #aaa; border-radius: 50%; }
    .combo-fixed-watch { grid-column: 1 / -1; font-size: 12px; color: #444; padding: 11px 12px; background: #fff; border: 1px solid #e5e5e5; border-radius: 5px; }
    @media (max-width: 575px) { .combo-choice-grid { grid-template-columns: 1fr; } }
    .combo-card-plus {
        font-size: 18px;
        font-weight: 700;
        color: #A0A0A0;
    }

    /* Actions */
    .combo-actions-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .combo-qty-selector {
        display: flex;
        align-items: center;
        border: 1px solid #D5D5D5;
        border-radius: 6px;
        height: 50px;
        background: #FFFFFF;
    }
    .combo-qty-btn {
        width: 40px;
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
    .combo-qty-input {
        width: 40px;
        height: 100%;
        border: none;
        text-align: center;
        font-weight: 700;
        font-size: 15px;
        color: var(--knp-black);
    }

    .btn-combo-customize {
        flex: 1;
        height: 50px;
        background: #FFFFFF;
        border: 1px solid var(--knp-black);
        color: var(--knp-black);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s;
        cursor: pointer;
    }
    .btn-combo-customize:hover {
        background: var(--knp-black);
        color: #FFFFFF;
    }

    .btn-combo-addcart {
        flex: 1.2;
        height: 50px;
        background: var(--knp-red);
        border: 1px solid var(--knp-red);
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
        cursor: pointer;
        box-shadow: 0 4px 15px var(--knp-red-glow);
    }
    .btn-combo-addcart:hover {
        background: var(--knp-red-hover);
        border-color: var(--knp-red-hover);
        color: #FFFFFF;
        transform: translateY(-2px);
    }

    .combo-wishlist-btn {
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
    .combo-wishlist-btn:hover {
        color: var(--knp-red);
    }

    /* Delivery Timer */
    .combo-delivery-box {
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
    .combo-delivery-box i {
        font-size: 24px;
        color: var(--knp-red);
    }

    /* 4-Column Feature Ribbon */
    .combo-feature-ribbon {
        background: #FAFAF9;
        border-top: 1px solid #ECEAE5;
        border-bottom: 1px solid #ECEAE5;
        padding: 24px 0;
        margin: 40px 0;
    }
    .combo-feature-ribbon-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .combo-ribbon-item {
        display: flex;
        align-items: center;
        gap: 14px;
        justify-content: center;
    }
    .combo-ribbon-item + .combo-ribbon-item {
        border-left: 1px solid #ECEAE5;
    }
    .combo-ribbon-item i {
        font-size: 26px;
        color: var(--knp-red);
    }
    .combo-ribbon-text strong {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--knp-black);
    }
    .combo-ribbon-text span {
        font-size: 11px;
        color: var(--knp-muted);
    }

    /* 4. Tabbed Detailed Section */
    .combo-tabs-section {
        padding: 20px 0 60px;
    }
    .combo-tabs-nav {
        display: flex;
        justify-content: center;
        gap: clamp(16px, 4vw, 40px);
        border-bottom: 1px solid #ECEAE5;
        padding-bottom: 14px;
        margin-bottom: 40px;
        list-style: none;
        flex-wrap: wrap;
        padding-left: 0;
    }
    .combo-tabs-nav li {
        list-style: none;
        margin: 0;
    }
    .combo-tabs-nav a {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--knp-muted);
        text-decoration: none;
        padding-bottom: 14px;
        position: relative;
        transition: all 0.2s;
        display: inline-block;
    }
    .combo-tabs-nav a.active, .combo-tabs-nav a:hover {
        color: var(--knp-red);
    }
    .combo-tabs-nav a.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2.5px;
        background: var(--knp-red);
    }
    .combo-tab-pane {
        animation: fadeInComboTab 0.3s ease-in-out;
    }
    @keyframes fadeInComboTab {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Middle Details Section: What's Inside + Make It Personal */
    .combo-details-section {
        padding: 40px 0 60px;
    }
    .combo-section-title {
        font-size: 26px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--knp-black);
        letter-spacing: 1px;
        margin-bottom: 24px;
        position: relative;
        padding-bottom: 10px;
    }
    .combo-section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background: var(--knp-red);
    }

    /* What's Inside Cards */
    .combo-inside-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }
    .combo-inside-card {
        background: #0E0E11;
        border-radius: 10px;
        overflow: hidden;
        text-align: center;
        color: #FFFFFF;
        border: 1px solid #222;
        transition: transform 0.4s;
    }
    .combo-inside-card:hover {
        transform: translateY(-4px);
    }
    .combo-inside-card-img {
        width: 100%;
        height: 180px;
        background: #16161A;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .combo-inside-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }
    .combo-inside-card:hover .combo-inside-card-img img {
        transform: scale(1.05);
    }
    .combo-inside-card-body {
        padding: 16px 12px;
    }
    .combo-inside-card-body strong {
        display: block;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .combo-inside-card-body span {
        font-size: 11px;
        color: #A0A0A0;
    }
    .combo-inside-quote {
        font-size: 13px;
        color: var(--knp-muted);
        line-height: 1.6;
        text-align: center;
        margin-top: 14px;
    }

    /* Make It Personal Card */
    .combo-personal-options-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .combo-personal-option-item {
        border: 1px solid #E5E5E5;
        border-radius: 10px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.3s;
        background: #FFFFFF;
        text-decoration: none;
        color: var(--knp-black);
    }
    .combo-personal-option-item:hover {
        border-color: var(--knp-red);
        background: #FFF9F9;
        box-shadow: 0 4px 14px rgba(180, 0, 22, 0.08);
    }
    .combo-personal-opt-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .combo-personal-opt-left i {
        font-size: 22px;
        color: var(--knp-red);
        width: 28px;
    }
    .combo-personal-opt-info strong {
        display: block;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .combo-personal-opt-info span {
        font-size: 12px;
        color: var(--knp-muted);
    }
    .combo-personal-opt-arrow {
        color: var(--knp-muted);
        font-size: 16px;
    }

    .combo-personal-mockup-card {
        background: #F4F2EE;
        border-radius: 12px;
        padding: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        min-height: 280px;
        margin-top: 16px;
        box-shadow: inset 0 2px 6px rgba(0,0,0,0.05);
    }
    .combo-note-paper {
        background: #FFFFFF;
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        padding: 24px 30px;
        text-align: center;
        max-width: 260px;
        transform: rotate(-3deg);
    }
    .combo-note-paper p {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 20px;
        font-style: italic;
        color: #111;
        line-height: 1.3;
        margin: 0;
    }
    .combo-polaroid-photo {
        position: absolute;
        bottom: 20px;
        right: 40px;
        background: #FFFFFF;
        padding: 6px 6px 18px;
        border-radius: 4px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.18);
        transform: rotate(6deg);
        width: 110px;
    }
    .combo-polaroid-photo img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 2px;
    }

    /* Video Reviews Carousel (Loved by Gentlemen) */
    .combo-video-reviews-section {
        padding: 60px 0;
        background: #FFFFFF;
        border-top: 1px solid #ECEAE5;
    }
    .combo-video-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 30px;
    }
    .combo-video-header h3 {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 32px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
    }
    .combo-video-header a {
        font-size: 12px;
        font-weight: 700;
        color: var(--knp-black);
        text-decoration: none;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: color 0.2s;
    }
    .combo-video-header a:hover {
        color: var(--knp-red);
    }

    .combo-video-cards-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
    }
    .combo-video-card {
        display: flex;
        flex-direction: column;
    }
    .combo-video-thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 8px;
        overflow: hidden;
        background: #111;
        margin-bottom: 12px;
        cursor: pointer;
    }
    .combo-video-thumb img,
    .combo-video-thumb video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }
    .combo-video-thumb:hover img,
    .combo-video-thumb:hover video {
        transform: scale(1.06);
    }
    .combo-video-play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 38px;
        height: 38px;
        background: rgba(0, 0, 0, 0.5);
        border: 2px solid #FFFFFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        font-size: 14px;
        transition: all 0.3s;
    }
    .combo-video-thumb:hover .combo-video-play-btn {
        background: var(--knp-red);
        border-color: var(--knp-red);
        transform: translate(-50%, -50%) scale(1.1);
    }
    .combo-video-runtime {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(0, 0, 0, 0.7);
        color: #FFFFFF;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 3px;
    }
    .combo-video-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--knp-black);
        margin-bottom: 4px;
    }
    .combo-video-stars {
        color: var(--knp-red);
        font-size: 11px;
        letter-spacing: 2px;
    }

    /* Trust Strip */
    .combo-trust-strip {
        background: #FAFAF9;
        border-top: 1px solid #ECEAE5;
        padding: 30px 0;
    }
    .combo-trust-grid {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .combo-trust-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .combo-trust-item i {
        font-size: 24px;
        color: var(--knp-red);
    }
    .combo-trust-info strong {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--knp-black);
    }
    .combo-trust-info span {
        font-size: 11px;
        color: var(--knp-muted);
    }

    @media (max-width: 991px) {
        .combo-gallery-container { flex-direction: column-reverse; }
        .combo-main-display { width: 100%; flex: none; }
        .combo-thumbnails-rail { flex-direction: row; width: 100%; overflow-x: auto; }
        .combo-thumb-item { flex-shrink: 0; }
        .combo-micro-specs-grid { grid-template-columns: repeat(2, 1fr); }
        .combo-feature-ribbon-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .combo-ribbon-item + .combo-ribbon-item { border-left: none; }
        .combo-inside-cards-grid { grid-template-columns: 1fr; }
        .combo-video-cards-grid { grid-template-columns: repeat(3, 1fr); }
        .combo-actions-row { flex-direction: column; width: 100%; gap: 15px; }
        .btn-combo-customize, .btn-combo-addcart { width: 100%; height: 55px !important; flex: none !important; font-size: 14px !important; }
        .combo-qty-selector { height: 55px !important; width: 160px; justify-content: space-between; margin: 0 auto 16px auto; }
        .combo-qty-btn { width: 50px !important; font-size: 22px !important; }
        .combo-qty-input { width: 60px !important; font-size: 18px !important; }
    }
    @media (max-width: 575px) {
        .combo-video-cards-grid { grid-template-columns: repeat(2, 1fr); }
        .combo-feature-ribbon-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="combo-luxury-page">

    <!-- 1. Breadcrumbs -->
    <div class="combo-breadcrumbs">
        <div>
            <a href="{{ url('/') }}">Home</a> &nbsp;&gt;&nbsp; 
            <a href="{{ url('/combos') }}">{{ $combo->gift_category_name ?: 'Signature Box' }}</a> &nbsp;&gt;&nbsp; 
            <span style="color: var(--knp-red); font-weight: 600;">{{ $combo->combo_name }}</span>
        </div>
        <div style="font-weight: 700;">
            <a href="{{ url('/combos') }}">&lt; PREV</a> &nbsp;&nbsp;|&nbsp;&nbsp; 
            <a href="{{ url('/combos') }}">NEXT &gt;</a>
        </div>
    </div>

    <!-- 2. Main Product Hero -->
    <section class="combo-hero-section">
        <div class="container-fluid" style="max-width: 1360px; padding: 0 clamp(16px, 3vw, 40px);">
            <div class="row g-5">
                
                <!-- Left: Gallery -->
                <div class="col-lg-6">
                    <div class="combo-gallery-container">
                        
                        <!-- Vertical Thumbnails -->
                        <div class="combo-thumbnails-rail">
                            @foreach($comboImages as $image)
                                <button type="button" class="combo-thumb-item {{ $loop->first ? 'active' : '' }}" onclick="changeComboImage(this, this.dataset.image)" data-image="{{ $image }}" aria-label="View image {{ $loop->iteration }} of {{ $comboName }}">
                                    <img src="{{ $image }}" alt="{{ $comboName }} - image {{ $loop->iteration }}" onerror="this.onerror=null; this.src='{{ $comboPlaceholder }}'">
                                </button>
                            @endforeach

                        </div>

                        <!-- Main Big View -->
                        <div class="combo-main-display">
                            <button type="button" class="combo-gallery-nav-btn combo-gallery-prev" onclick="stepComboImage(-1)" aria-label="Previous Image">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <img id="comboMainImage" src="{{ $comboImg }}" alt="{{ $comboName }}" onerror="this.onerror=null; this.src='{{ $comboPlaceholder }}'">
                            <button type="button" class="combo-gallery-nav-btn combo-gallery-next" onclick="stepComboImage(1)" aria-label="Next Image">
                                <i class="fa fa-chevron-right"></i>
                            </button>

                        </div>

                    </div>
                </div>

                <!-- Right: Product Info & Actions -->
                <div class="col-lg-6">
                    <span class="combo-badge-tag">Most Loved Gift</span>
                    <h1 class="combo-title">{{ $combo->combo_name }}</h1>
                    <div class="combo-subtitle">{{ $comboSubtitle }}</div>

                    <!-- Social Proof -->
                    <div class="combo-rating-proof">
                        @if (($comboReviewCount ?? 0) > 0)
                            <a href="#comboReviews" onclick="switchComboTab(event, 'comboReviews')" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; cursor:pointer;" title="View client reviews">
                                <span class="combo-stars" style="color:var(--knp-gold); font-size:13px; letter-spacing:1px;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= round($comboAvgRating ?? 5) ? '★' : '☆' }}
                                    @endfor
                                </span>
                                <span class="combo-proof-text">{{ number_format($comboAvgRating ?? 5, 1) }} ({{ $comboReviewCount }} {{ \Illuminate\Support\Str::plural('Review', $comboReviewCount) }}) &nbsp;|&nbsp; 100% Authentic Luxury</span>
                            </a>
                        @else
                            <a href="#comboReviews" onclick="switchComboTab(event, 'comboReviews')" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; cursor:pointer;" title="Click to write the first review">
                                <span class="combo-stars" style="color:var(--knp-gold); font-size:13px; letter-spacing:1px;">★★★★★</span>
                                <span class="combo-proof-text" style="color:#555;">Curated Trio Suite &nbsp;|&nbsp; <span style="color:var(--knp-red); text-decoration:underline; font-weight:600;">Be the first to review</span></span>
                            </a>
                        @endif
                    </div>

                    <!-- Pricing -->
                    @php
                        $comboPrice = $combo->offer_price > 0 ? $combo->offer_price : $combo->mrp_price;
                        $comboMrp = $combo->mrp_price;
                        $comboDiscount = ($comboMrp > $comboPrice && $comboMrp > 0) ? round((($comboMrp - $comboPrice) / $comboMrp) * 100) : 0;
                    @endphp
                    <div class="combo-pricing-block">
                        <div class="combo-price">₹{{ number_format($comboPrice) }}</div>
                        @if($comboDiscount > 0)
                            <div class="combo-mrp">MRP ₹{{ number_format($comboMrp) }}</div>
                            <span class="combo-discount-pill">{{ $comboDiscount }}% OFF</span>
                        @endif
                    </div>
                    <div class="combo-tax-note">Inclusive of all taxes</div>

                    <!-- 4 Micro Specs Grid -->
                    <div class="combo-micro-specs-grid">
                        @foreach($comboMicroSpecs as $spec)
                            <div class="combo-micro-spec-item">
                                <i class="fa {{ $spec['icon'] ?? 'fa-certificate' }}"></i>
                                <div class="combo-micro-spec-info">
                                    <strong>{{ $spec['title'] ?? '' }}</strong>
                                    <span>{{ ($spec['title'] ?? '') === 'Easy Returns' && ($spec['value'] ?? '') === '15 Days' ? '7 Days' : ($spec['value'] ?? '') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Combination Selection Preview Row -->
                    @if(count($comboItems))
                    <div class="combo-select-header">Included in Your Box</div>
                    <div class="combo-combination-cards">
                        @foreach($comboItems as $item)
                            <div class="combo-mini-card">
                                @if(!empty($item['image']))
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" loading="lazy">
                                @endif
                                <div class="combo-mini-card-title">{{ $item['name'] }}</div>
                                <div class="combo-mini-card-sub">{{ $item['details'] }}</div>
                            </div>
                            @if(!$loop->last)
                                <div class="combo-card-plus">+</div>
                            @endif
                        @endforeach
                    </div>
                    @endif

                    <!-- Customer choices and order actions -->
                    <form action="{{ url('/combo/checkout') }}" method="POST" id="comboCheckoutForm">
                        @csrf
                        <input type="hidden" name="combo_id" value="{{ $combo->id }}">
                        <input type="hidden" name="quantity" id="comboQuantityInput" value="{{ old('quantity', 1) }}">

                        @if($canBuildCombo)
                            <div class="combo-choice-panel" id="comboChoices">
                                <h3>Choose Your Shirt &amp; Perfume</h3>
                                <div class="combo-choice-grid">
                                    <div class="combo-choice-field combo-color-field">
                                        <label>Shirt color: <span id="selectedComboShirtColor">{{ $selectedShirtChoice['color_label'] }}</span></label>
                                        <input type="hidden" id="comboShirtProduct" name="shirt_product_id" value="{{ $selectedShirtId }}">
                                        <div class="combo-color-options" role="group" aria-label="Choose shirt color">
                                            @foreach($shirtChoices as $choice)
                                                <button type="button" class="combo-color-option {{ $selectedShirtId === (int) $choice['product']->id ? 'active' : '' }}"
                                                        data-product-id="{{ $choice['product']->id }}" data-color-label="{{ $choice['color_label'] }}"
                                                        aria-pressed="{{ $selectedShirtId === (int) $choice['product']->id ? 'true' : 'false' }}"
                                                        onclick="selectComboShirtColor(this)">
                                                    <span class="combo-color-dot" style="background: {{ $choice['swatch'] }};"></span>
                                                    <span>{{ $choice['color_label'] }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="combo-choice-field">
                                        <label for="comboShirtVariant">Shirt size</label>
                                        <select id="comboShirtVariant" name="shirt_variant_id" required></select>
                                    </div>
                                    <div class="combo-choice-field">
                                        <label for="comboPerfumeProduct">Perfume</label>
                                        <select id="comboPerfumeProduct" name="perfume_product_id" required>
                                            @foreach($perfumeChoices as $choice)
                                                <option value="{{ $choice['product']->id }}" @selected($selectedPerfumeId === (int) $choice['product']->id)>{{ $choice['product']->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="combo-choice-field">
                                        <label for="comboPerfumeVariant">Perfume volume</label>
                                        <select id="comboPerfumeVariant" name="perfume_variant_id" required></select>
                                    </div>
                                    <div class="combo-fixed-watch"><strong>Included watch:</strong> {{ $watchChoice['product']->product_name }} ({{ $combo->watch_model }})</div>
                                </div>
                                <p style="font-size:11px; color:#666; margin:12px 0 0;">Your selections are saved with this box in checkout. Box price stays {{ house_money($comboPrice) }}.</p>
                            </div>
                        @else
                            <div style="color:var(--knp-red); font-weight:700;">
                                <p>This box cannot be ordered yet:</p>
                                <ul>
                                    @foreach($availabilityIssues as $issue)
                                        <li>{{ $issue }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if($errors->has('combo'))
                            <p style="color:var(--knp-red); font-weight:700;">{{ $errors->first('combo') }}</p>
                        @endif

                        <div class="combo-actions-row">
                            <!-- Quantity -->
                            <div class="combo-qty-selector">
                                <button type="button" class="combo-qty-btn" onclick="updateComboQty(-1)">-</button>
                                <input type="text" class="combo-qty-input" id="comboQtyDisplay" value="{{ old('quantity', 1) }}" readonly>
                                <button type="button" class="combo-qty-btn" onclick="updateComboQty(1)">+</button>
                            </div>

                            <!-- Buttons -->
                            <button type="submit" class="btn-combo-customize" name="next" value="checkout" @disabled(!$canBuildCombo)>
                                <i class="fa fa-bolt"></i> Continue to Checkout
                            </button>

                            <button type="submit" class="btn-combo-addcart" id="comboAddToCartBtn" name="next" value="cart" @disabled(!$canBuildCombo)>
                                <i class="fa fa-shopping-bag"></i> Add To Cart
                            </button>

                            <a href="{{ url('wishlist') }}" class="combo-wishlist-btn">
                                <i class="fa fa-heart-o"></i> Add to Wishlist
                            </a>
                        </div>
                    </form>

                    <!-- Delivery Countdown Box -->
                    <!-- <div class="combo-delivery-box">
                        <i class="fa fa-truck"></i>
                        <div>
                            Order within <strong id="comboCountdownTimer" style="color: var(--knp-black);">2h 15m 30s</strong> and get it by<br>
                            <strong style="color: var(--knp-black);">Friday, 24 May</strong> (Express Delivery)
                        </div>
                    </div> -->

                </div>

            </div>
        </div>
    </section>

    <!-- 3. 4-Column Feature Ribbon -->
    <div class="combo-feature-ribbon">
        <div class="container-fluid" style="max-width: 1360px; padding: 0 clamp(16px, 3vw, 40px);">
            <div class="combo-feature-ribbon-grid">
                <div class="combo-ribbon-item">
                    <i class="fa fa-gift"></i>
                    <div class="combo-ribbon-text">
                        <strong>Premium Packaging</strong>
                        <span>Luxury Magnetic Box</span>
                    </div>
                </div>
                <div class="combo-ribbon-item">
                    <i class="fa fa-heart-o"></i>
                    <div class="combo-ribbon-text">
                        <strong>Perfect For Gifting</strong>
                        <span>For Every Occasion</span>
                    </div>
                </div>
                <div class="combo-ribbon-item">
                    <i class="fa fa-pencil-square-o"></i>
                    <div class="combo-ribbon-text">
                        <strong>Personalized Note</strong>
                        <span>Make It Special</span>
                    </div>
                </div>
                <div class="combo-ribbon-item">
                    <i class="fa fa-shield"></i>
                    <div class="combo-ribbon-text">
                        <strong>100% Authentic</strong>
                        <span>Original Products</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Detailed Infographics Tabbed Section -->
    <section class="combo-tabs-section">
        <div class="container-fluid" style="max-width: 1360px; padding: 0 clamp(16px, 3vw, 40px);">
            
            <!-- Tabs Nav -->
            <ul class="combo-tabs-nav" id="comboTabsNav">
                <li><a href="#comboInside" class="active" onclick="switchComboTab(event, 'comboInside')" id="comboTabLabel1">What's Inside</a></li>
                <li><a href="#comboOccasion" onclick="switchComboTab(event, 'comboOccasion')" id="comboTabLabel2">Details &amp; Occasion</a></li>
                <li><a href="#comboShipping" onclick="switchComboTab(event, 'comboShipping')" id="comboTabLabel3">Shipping &amp; Packaging</a></li>
                <li><a href="#comboReviews" id="comboReviewsTabBtn" onclick="switchComboTab(event, 'comboReviews')">Reviews ({{ $comboReviewCount ?? 0 }})</a></li>
            </ul>

            <!-- Tab Content -->
            <div class="combo-tab-panes">
                
                <!-- Tab 1: What's Inside & Personalization -->
                <div class="combo-tab-pane" id="comboInside">
                    <div class="row g-5">
                        <!-- Left: What's Inside Cards -->
                        <div class="col-lg-6">
                            <div class="combo-section-title">What's Inside</div>
                            <div class="combo-inside-cards-grid">
                                @foreach($comboItems as $item)
                                    <div class="combo-inside-card">
                                        @if(!empty($item['image']))
                                            <div class="combo-inside-card-img">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" loading="lazy">
                                            </div>
                                        @endif
                                        <div class="combo-inside-card-body">
                                            <strong>{{ $item['name'] }}</strong>
                                            <span>{{ $item['details'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="combo-inside-quote">
                                Each element is handpicked to complement your presence.<br>
                                <strong>{{ $comboQuote }}</strong>
                            </div>
                        </div>

                        <!-- Right: Make It Personal -->
                        <div class="col-lg-6">
                            <div class="combo-section-title">Make It Personal</div>
                            <div class="combo-personal-options-list" style="margin-top: 30px;">
                                <div class="combo-personal-option-item" style="cursor: default; padding: 25px; display: flex; align-items: center; justify-content: space-between; background: #131317; border-radius: 12px; border: 1px solid #222;">
                                    <div class="combo-personal-opt-left" style="display: flex; align-items: center; gap: 20px;">
                                        <i class="fa fa-whatsapp" style="color: #25D366; font-size: 32px;"></i>
                                        <div class="combo-personal-opt-info">
                                            <strong style="font-size: 18px; color: #fff;">Customized Combo Box?</strong>
                                            <span style="color: #bbb; display: block; margin-top: 5px;">If you want to customize products, items, or sizes, contact us on WhatsApp!</span>
                                        </div>
                                    </div>
                                    <div class="combo-personal-opt-arrow">
                                        <a href="https://wa.me/916374390907" target="_blank" class="btn btn-sm" style="background: #25D366; color: #000; font-weight: bold; border-radius: 6px; ">Chat Now</a>
                                    </div>
                                </div>

                                <div style="background:#FAFAF9; border:1px solid #ECEAE5; border-radius:12px; padding:24px; margin-top:16px;">
                                    <h5 style="font-size:15px; font-weight:700; color:var(--knp-black); margin-bottom:10px; text-transform:uppercase; letter-spacing:1px;">
                                        <i class="fa fa-gift" style="color:var(--knp-red); margin-right:8px;"></i> Complimentary Luxury Gift Note
                                    </h5>
                                    <p style="font-size:13px; color:#666; line-height:1.6; margin:0;">
                                        Every Signature Box includes a personalized handwritten wax-sealed greeting card. Enter your recipient's name and bespoke message at checkout for an unforgettable unboxing impression.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Details & Occasion -->
                <div class="combo-tab-pane" id="comboOccasion" style="display:none;">
                    <div style="background:#FAFAF9; padding:clamp(20px, 3vw, 36px); border-radius:12px; border:1px solid #ECEAE5; max-width:960px; margin:0 auto; font-size:13px; line-height:1.7; color:#555;">
                        <h4 style="color:var(--knp-black); font-size:20px; font-weight:700; margin-bottom:14px; text-transform:uppercase; letter-spacing:1px;">
                            {{ !empty($combo->combo_name) ? $combo->combo_name . ' — ' : '' }}The Art of Effortless Presence
                        </h4>
                        
                        <p style="font-size:14px; color:#444; line-height:1.75;">
                            {!! !empty($combo->product_description) ? nl2br(e($combo->product_description)) : 'House of KNP Signature Gift Combos are architected for gentlemen who believe excellence is in the details. Each box unites three foundational style pillars designed to harmonize effortlessly:' !!}
                        </p>

                        @if(!empty($combo->features))
                            <div style="margin: 18px 0 22px; padding: 14px 18px; background: #FFFFFF; border: 1px solid #E5E5E5; border-left: 3px solid var(--knp-red); border-radius: 6px;">
                                <strong style="color:var(--knp-black); font-size:12px; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:4px;">
                                    <i class="fa fa-bookmark" style="color:var(--knp-red); margin-right:6px;"></i> Curated Highlights
                                </strong>
                                <span style="color:#444; font-size:13px;">{{ $combo->features }}</span>
                            </div>
                        @endif
                        
                        <div class="row g-4 my-2">
                            @foreach($comboItems as $item)
                                <div class="col-md-4">
                                    <div class="combo-inside-card-body">
                                        <strong>{{ $item['name'] }}</strong>
                                        <span>{{ $item['details'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Shipping & Packaging -->
                <div class="combo-tab-pane" id="comboShipping" style="display:none;">
                    <div style="background:#FAFAF9; padding:clamp(20px, 3vw, 36px); border-radius:12px; border:1px solid #ECEAE5; max-width:960px; margin:0 auto; font-size:13px; line-height:1.7; color:#555;">
                        <h4 style="color:var(--knp-black); font-size:20px; font-weight:700; margin-bottom:14px; text-transform:uppercase; letter-spacing:1px;">Packaging, Delivery &amp; Guarantee</h4>
                        <div class="row g-4 my-2">
                            <div class="col-md-6">
                                <div style="background:#fff; border:1px solid #E5E5E5; border-radius:8px; padding:20px; height:100%;">
                                    <strong style="color:var(--knp-black); font-size:14px; display:block; margin-bottom:6px;">
                                        <i class="fa fa-archive" style="color:var(--knp-red); margin-right:8px;"></i>
                                        {{ $combo->unit_name ?: 'Signature Rigid Presentation Box' }}
                                    </strong>
                                    <p style="margin:0; font-size:12px; color:#666; line-height:1.6;">
                                        Constructed with heavyweight matte rigid board with concealed closures, high-density velvet-lined protective foam beds, and metallic foil branding.
                                        @if(!empty($combo->weight))
                                            <br><span style="display:inline-block; margin-top:6px; font-weight:600; color:var(--knp-black);">Package Weight: Approx. {{ $combo->weight }} kg</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:#fff; border:1px solid #E5E5E5; border-radius:8px; padding:20px; height:100%;">
                                    <strong style="color:var(--knp-black); font-size:14px; display:block; margin-bottom:6px;"><i class="fa fa-truck" style="color:var(--knp-red); margin-right:8px;"></i> Express Pan-India Courier</strong>
                                    <p style="margin:0; font-size:12px; color:#666; line-height:1.6;">Complimentary priority shipping on all prepaid combo orders. Metro delivery within 2–3 business days; all other regions within 3–5 business days with live tracking.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:#fff; border:1px solid #E5E5E5; border-radius:8px; padding:20px; height:100%;">
                                    <strong style="color:var(--knp-black); font-size:14px; display:block; margin-bottom:6px;"><i class="fa fa-refresh" style="color:var(--knp-red); margin-right:8px;"></i> 15-Day Size &amp; Style Exchanges</strong>
                                    <p style="margin:0; font-size:12px; color:#666; line-height:1.6;">If the shirt size requires adjustment after gifting, our concierge will arrange a seamless doorstep exchange with zero hassle.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:#fff; border:1px solid #E5E5E5; border-radius:8px; padding:20px; height:100%;">
                                    <strong style="color:var(--knp-black); font-size:14px; display:block; margin-bottom:6px;"><i class="fa fa-shield" style="color:var(--knp-red); margin-right:8px;"></i> 100% Transit Protection</strong>
                                    <p style="margin:0; font-size:12px; color:#666; line-height:1.6;">Every shipment is sealed in tamper-evident security wrapping and insured in full until securely delivered into your hands.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Reviews -->
                <div class="combo-tab-pane" id="comboReviews" style="display:none;">
                    <div class="row g-4">
                        <div class="col-lg-7">
                            @if (isset($comboReviews) && $comboReviews->isNotEmpty())
                                <div style="display:flex; flex-direction:column; gap:16px;">
                                    @foreach ($comboReviews as $review)
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
                                        <a href="{{ route('login') }}" class="btn btn-sm" style="display:inline-block; text-decoration:none;  font-size:12px; border-radius:4px; background:var(--knp-red); color:#fff; font-weight:700; text-transform:uppercase; letter-spacing:1px;">Login to Review</a>
                                    </div>
                                @else
                                    @if ($userComboReview)
                                        @if ($userComboReview->status == 1)
                                            <div style="background:#fff; border:1px solid #e0e0e0; padding:18px; border-radius:6px;">
                                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                                    <span style="background:#2e7d32; color:#fff; font-size:11px; padding:3px 8px; border-radius:4px; font-weight:600;">✓ Verified Buyer Review</span>
                                                    <span style="color:var(--knp-red);">
                                                        @for ($rating = 1; $rating <= 5; $rating++)
                                                            <i class="fa {{ $rating <= (int) $userComboReview->ratings ? 'fa-star' : 'fa-star-o' }}"></i>
                                                        @endfor
                                                    </span>
                                                </div>
                                                <p style="font-size:13px; color:#333; margin:0; line-height:1.6;">"{{ $userComboReview->review }}"</p>
                                            </div>
                                        @else
                                            <div style="background:#fff; border:1px solid #ffe082; padding:20px; border-radius:6px; text-align:center;">
                                                <i class="fa fa-clock-o" style="font-size:26px; color:#f57f17; margin-bottom:8px;"></i>
                                                <h5 style="font-size:14px; font-weight:700; color:#333; margin-bottom:6px;">Review Under Moderation</h5>
                                                <p style="font-size:12px; color:#666; margin:0; line-height:1.5;">Thank you! Your review has been submitted and is currently being verified by our curation team before going live.</p>
                                            </div>
                                        @endif
                                    @elseif ($hasOrderedCombo && !$comboOrderDelivered)
                                        <div style="background:#fff; border:1px solid #ECEAE5; padding:24px 20px; border-radius:8px; text-align:center;">
                                            <div style="width:48px; height:48px; border-radius:50%; background:rgba(201,162,39,0.12); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; color:var(--knp-gold); font-size:20px;">
                                                <i class="fa fa-truck"></i>
                                            </div>
                                            <h5 style="font-size:14px; font-weight:800; color:var(--knp-black); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Delivery In Progress</h5>
                                            <p style="font-size:12px; color:#666; line-height:1.6; margin-bottom:14px;">Your order has been placed and is currently in progress. You can write your verified review once your signature box has been delivered.</p>
                                            <a href="{{ url('/account#orders') }}" class="btn btn-sm" style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:1px; padding:9px 20px; border-radius:4px; background:var(--knp-black); color:#fff; text-decoration:none; display:inline-block;">Track Order in Account</a>
                                        </div>
                                    @elseif (! $canReviewCombo)
                                        <div style="background:#fff; border:1px solid #ECEAE5; padding:20px; border-radius:6px; text-align:center;">
                                            <div style="width:48px; height:48px; border-radius:50%; background:rgba(0,0,0,0.04); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; color:#666; font-size:18px;">
                                                <i class="fa fa-shopping-bag"></i>
                                            </div>
                                            <h5 style="font-size:14px; font-weight:700; color:#222; margin-bottom:6px;">Verified Purchase Required</h5>
                                            <p style="font-size:12px; color:#666; line-height:1.6; margin-bottom:14px;">To guarantee authentic client feedback, only verified buyers who have purchased and received this gift box can leave a review.</p>
                                            <button type="button" onclick="document.getElementById('comboAddToCartBtn')?.click()" class="btn btn-sm" style="font-size:12px; padding:8px 18px; border-radius:4px; background:var(--knp-red); color:#fff; font-weight:700;">Add to Bag &amp; Order</button>
                                        </div>
                                    @else
                                        <form action="{{ route('combo.review.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="combo_id" value="{{ $combo->id }}">
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
                                                <textarea name="review" style="width:100%; border:1px solid #D5D5D5; border-radius:4px; padding:10px; min-height:100px; font-size:13px;" placeholder="Share your experience with quality, presentation, unboxing..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm" style="width:100%; background:var(--knp-red); color:#fff; font-weight:700; padding:10px; border-radius:4px; border:none; cursor:pointer; font-size:13px; letter-spacing:1px; text-transform:uppercase;">Submit Review</button>
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

    <!-- 5. Loved By Gentlemen Video Reviews -->
    <section class="combo-video-reviews-section" id="videoReviews">
        <div class="container-fluid" style="max-width: 1360px; padding: 0 clamp(16px, 3vw, 40px);">
            <div class="combo-video-header">
                <h3>Loved By Gentlemen</h3>
                <span style="font-size:12px; color:var(--knp-gold); font-weight:700; letter-spacing:1px; text-transform:uppercase;">
                    <i class="fa fa-play-circle" style="margin-right:4px;"></i> Verified Video Reviews
                </span>
            </div>

            <div class="combo-video-cards-grid">
                @if(isset($reviewVideos) && count($reviewVideos) > 0)
                    @foreach($reviewVideos->take(5) as $video)
                        <div class="combo-video-card" onclick="openVideoModal('{{ house_main_media_url($video->video, 'images') }}')" style="cursor:pointer;" title="Click to watch review by {{ $video->name ?? 'House of KNP Gentleman' }}">
                            <div class="combo-video-thumb">
                                <video src="{{ house_main_media_url($video->video, 'images') }}" preload="metadata" muted playsinline onloadedmetadata="setComboVideoDuration(this)"></video>
                                <div class="combo-video-play-btn"><i class="fa fa-play"></i></div>
                                <span class="combo-video-runtime">--:--</span>
                            </div>
                            <div class="combo-video-name">{{ $video->name ?? 'House of KNP Gentleman' }}</div>
                            <div class="combo-video-stars" style="color:var(--knp-gold); font-size:12px; letter-spacing:1px;">
                                @php $cRating = $video->rating ?? 5; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $cRating ? '★' : '☆' }}
                                @endfor
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="combo-video-card" onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')" style="cursor:pointer;">
                        <div class="combo-video-thumb">
                            <img src="{{ asset('images/product/01.jpg') }}" alt="Customer Review">
                            <div class="combo-video-play-btn"><i class="fa fa-play"></i></div>
                            <span class="combo-video-runtime">0:32</span>
                        </div>
                        <div class="combo-video-name">House of KNP Gentleman</div>
                        <div class="combo-video-stars" style="color:var(--knp-gold); font-size:12px;">★★★★★</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Video Modal Player -->
    <div id="reviewVideoModal" style="display:none; position:fixed; z-index:99999; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.88); align-items:center; justify-content:center;">
        <div style="position:relative; max-width:640px; width:92%; background:#000; border-radius:10px; overflow:hidden; box-shadow:0 12px 48px rgba(0,0,0,0.7); border:1px solid rgba(255,255,255,0.15);">
            <button type="button" onclick="closeVideoModal()" style="position:absolute; top:12px; right:14px; background:rgba(0,0,0,0.7); border:1px solid rgba(255,255,255,0.3); color:#fff; font-size:24px; width:36px; height:36px; border-radius:50%; cursor:pointer; z-index:10; display:flex; align-items:center; justify-content:center;" aria-label="Close video">&times;</button>
            <video id="modalVideoPlayer" controls autoplay playsinline style="width:100%; height:auto; max-height:80vh; display:block;"></video>
        </div>
    </div>

    <!-- 6. Trust Badges Strip -->
  

</div>
@endsection

@section('scripts')
<script>
    @if($canBuildCombo)
    const comboShirtVariants = @json($shirtVariantChoices);
    const comboPerfumeVariants = @json($perfumeVariantChoices);
    const comboWatchStock = {{ (int) $watchChoice['variant']->product_qty }};
    const comboBoxStock = {{ (int) $combo->stock_quantity }};
    function populateComboVariants(productSelectId, variantSelectId, choices, preferredId = '') {
        const productSelect = document.getElementById(productSelectId);
        const variantSelect = document.getElementById(variantSelectId);
        if (!productSelect || !variantSelect) return;
        const previous = preferredId || variantSelect.value;
        variantSelect.replaceChildren();
        (choices[productSelect.value] || []).forEach(variant => {
            const option = new Option(variant.label, variant.id);
            option.dataset.stock = variant.stock;
            variantSelect.add(option);
        });
        if ([...variantSelect.options].some(option => option.value === String(previous))) variantSelect.value = String(previous);
        updateComboChoicePreview();
    }
    function updateComboChoicePreview() {
        const shirt = document.getElementById('comboShirtProduct');
        const size = document.getElementById('comboShirtVariant');
        const perfume = document.getElementById('comboPerfumeProduct');
        const volume = document.getElementById('comboPerfumeVariant');
        const cards = document.querySelectorAll('.combo-combination-cards .combo-mini-card-sub');
        if (cards[0] && shirt && size) cards[0].textContent = `${document.querySelector('.combo-color-option.active')?.dataset.colorLabel || ''} / ${size.selectedOptions[0]?.textContent || ''}`;
        if (cards[2] && perfume && volume) cards[2].textContent = `${perfume.selectedOptions[0]?.textContent || ''} / ${volume.selectedOptions[0]?.textContent || ''}`;
        const max = Math.min(10, comboBoxStock, comboWatchStock, Number(size?.selectedOptions[0]?.dataset.stock || 10), Number(volume?.selectedOptions[0]?.dataset.stock || 10));
        const qty = document.getElementById('comboQtyDisplay');
        if (qty && Number(qty.value) > max) updateComboQty(max - Number(qty.value));
    }
    function selectComboShirtColor(button) {
        document.getElementById('comboShirtProduct').value = button.dataset.productId;
        document.querySelectorAll('.combo-color-option').forEach(option => {
            const active = option === button;
            option.classList.toggle('active', active);
            option.setAttribute('aria-pressed', String(active));
        });
        document.getElementById('selectedComboShirtColor').textContent = button.dataset.colorLabel;
        populateComboVariants('comboShirtProduct', 'comboShirtVariant', comboShirtVariants);
    }
    document.getElementById('comboPerfumeProduct')?.addEventListener('change', () => populateComboVariants('comboPerfumeProduct', 'comboPerfumeVariant', comboPerfumeVariants));
    document.getElementById('comboShirtVariant')?.addEventListener('change', updateComboChoicePreview);
    document.getElementById('comboPerfumeVariant')?.addEventListener('change', updateComboChoicePreview);
    populateComboVariants('comboShirtProduct', 'comboShirtVariant', comboShirtVariants, @json(old('shirt_variant_id', $defaultShirt['variants']->first()->id)));
    populateComboVariants('comboPerfumeProduct', 'comboPerfumeVariant', comboPerfumeVariants, @json(old('perfume_variant_id', $defaultPerfume['variants']->first()->id)));
    @endif
    function changeComboImage(element, url) {
        document.querySelectorAll('.combo-thumb-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        element.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
        const mainImg = document.getElementById('comboMainImage');
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

    function stepComboImage(direction) {
        const thumbItems = Array.from(document.querySelectorAll('.combo-thumb-item'));
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
        if (e.key === 'ArrowLeft') stepComboImage(-1);
        if (e.key === 'ArrowRight') stepComboImage(1);
    });

    function updateComboQty(delta) {
        const display = document.getElementById('comboQtyDisplay');
        const input = document.getElementById('comboQuantityInput');
        let val = parseInt(display.value, 10) || 1;
        const max = typeof comboWatchStock === 'number'
            ? Math.min(10, comboBoxStock, comboWatchStock, Number(document.getElementById('comboShirtVariant')?.selectedOptions[0]?.dataset.stock || 10), Number(document.getElementById('comboPerfumeVariant')?.selectedOptions[0]?.dataset.stock || 10))
            : 10;
        val = Math.min(max, Math.max(1, val + delta));
        display.value = val;
        if (input) input.value = val;
    }

    // Dynamic Live Delivery Countdown
    (function() {
        let totalSeconds = 2 * 3600 + 15 * 60 + 30;
        const timerEl = document.getElementById('comboCountdownTimer');
        if (!timerEl) return;
        setInterval(function() {
            if (totalSeconds > 0) totalSeconds--;
            const h = Math.floor(totalSeconds / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;
            timerEl.textContent = `${h}h ${m}m ${s < 10 ? '0' : ''}${s}s`;
        }, 1000);
    })();

    /* --------------------------------------------------------------------------
       COMBO VIDEO REVIEWS MODAL CONTROLLER
       -------------------------------------------------------------------------- */
    function openVideoModal(videoSrc) {
        const modal = document.getElementById('reviewVideoModal');
        const player = document.getElementById('modalVideoPlayer');
        if (modal && player) {
            player.src = videoSrc;
            modal.style.display = 'flex';
            player.play().catch(() => {});
        }
    }

    function closeVideoModal() {
        const modal = document.getElementById('reviewVideoModal');
        const player = document.getElementById('modalVideoPlayer');
        if (modal && player) {
            player.pause();
            player.src = '';
            modal.style.display = 'none';
        }
    }

    function setComboVideoDuration(video) {
        if (!Number.isFinite(video.duration)) return;
        const minutes = Math.floor(video.duration / 60);
        const seconds = String(Math.floor(video.duration % 60)).padStart(2, '0');
        const duration = minutes + ':' + seconds;
        const card = video.closest('.combo-video-card');
        if (card) {
            const rt = card.querySelector('.combo-video-runtime');
            if (rt) rt.textContent = duration;
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const videoModal = document.getElementById('reviewVideoModal');
        if (videoModal) {
            videoModal.addEventListener('click', function(e) {
                if (e.target === this) closeVideoModal();
            });
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeVideoModal();
        });

        // Tab Switching & Hash Navigation
        if (['#comboReviews', '#reviews', '#tabReviews', '#review'].includes(window.location.hash)) {
            const btn = document.getElementById('comboReviewsTabBtn');
            if (btn) {
                btn.click();
                const target = document.getElementById('comboReviews');
                if (target) {
                    setTimeout(() => target.scrollIntoView({ behavior: 'smooth', block: 'start' }), 200);
                }
            }
        }
    });

    function switchComboTab(event, tabId) {
        if (event) event.preventDefault();
        document.querySelectorAll('.combo-tabs-nav a').forEach(a => a.classList.remove('active'));
        const navLink = document.querySelector(`.combo-tabs-nav a[href="#${tabId}"]`) || (event ? event.currentTarget : null);
        if (navLink) navLink.classList.add('active');
        document.querySelectorAll('.combo-tab-pane').forEach(pane => pane.style.display = 'none');
        const target = document.getElementById(tabId);
        if (target) {
            target.style.display = 'block';
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }
    }
</script>
@endsection

