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
@endphp

@section('content')
    <link rel="stylesheet" href="{{ asset('css/shop-premium.css') }}?v=20260716">
    <style>
        .shop-main-area .product-label,
        .shop-main-area .social-icon-wraper {
            display: none;
        }

        .shop-infinite-scroll {
            align-items: center;
            color: #555;
            display: flex;
            font-size: 13px;
            gap: 10px;
            justify-content: center;
            min-height: 90px;
            padding: 24px 0;
            width: 100%;
        }

        .shop-infinite-spinner {
            animation: shopInfiniteSpin .75s linear infinite;
            border: 2px solid #ddd;
            border-radius: 50%;
            border-top-color: #d00000;
            display: none;
            height: 22px;
            width: 22px;
        }

        .shop-infinite-scroll.is-loading .shop-infinite-spinner {
            display: inline-block;
        }

        @keyframes shopInfiniteSpin {
            to {
                transform: rotate(360deg);
            }
        }

        #shop-products {
            scroll-margin-top: 170px;
        }

        .shop-main-area .total-shop-product-grid .single-product {
            border: 0;
            border-radius: 0;
            box-shadow: none;
            padding: 0;
            background: #fff;
        }

        .shop-main-area .total-shop-product-grid .single-product:after {
            display: none;
        }

        .shop-main-area .total-shop-product-grid .single-product:hover {
            border-color: transparent;
            box-shadow: none;
            transform: none;
        }

        .shop-main-area .total-shop-product-grid .product-text {
            padding: 7px 10px 12px;
        }

        .shop-main-area .total-shop-product-grid .prodcut-name {
            margin-top: 0;
        }

        .shop-main-area .total-shop-product-grid .prodcut-ratting-price {
            margin-top: 2px;
        }

        .shop-main-area .single-product:hover .product-overlay:before,
        .shop-main-area .product-overlay:hover:before {
            opacity: 0;
        }

        .shop-main-area .single-product:hover .product-img a img.secondary-image {
            opacity: 0;
        }

        .shop-main-area .product-img a {
            display: block;
        }

        .shop-main-area .product-card-actions {
            display: flex;
            gap: 8px;
            position: absolute;
            right: 12px;
            top: 12px;
            z-index: 5;
        }

        .shop-main-area .product-card-actions a {
            align-items: center;
            background: #fff;
            border: 1px solid #ddd;
            color: #333;
            display: inline-flex;
            height: 36px;
            justify-content: center;
            width: 36px;
            font-size: 22px;
            border-radius: 38px;

        }

        .shop-main-area .product-card-actions a:hover {
            background: #cc3333;
            border-color: #cc3333;
            color: #fff;
        }

        .shop-main-area .prodcut-price .old-price {
            margin-left: 8px;
        }

        .shop-main-area .prodcut-name a {
            color: #333;
            display: block;
            font-weight: 500;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .shop-main-area .price_slider_amount {
            display: grid;
            gap: 10px;
        }

        .shop-main-area .price_slider_amount>input[type="number"] {
            background: #fff;
            border: 1px solid #ddd;
            color: #555;
            font-size: 14px;
            height: 38px;
            padding: 0 12px;
            width: 100%;
        }

        .shop-main-area .price-filter-field {
            display: grid;
            gap: 7px;
        }

        .shop-main-area .price-filter-field label {
            color: #4f4a45;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .8px;
            margin: 0;
            text-transform: uppercase;
        }

        .shop-main-area .price-filter-field input {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            color: #333;
            font-size: 15px;
            height: 48px;
            padding: 0 14px;
            width: 100%;
        }

        .shop-main-area .price_slider_amount>input[type="submit"] {
            float: none;
            height: 38px;
            margin: 0;
            width: max-content;
        }

        .shop-main-area .size-filter,
        .shop-main-area .color-filter,
        .shop-main-area .tag-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .shop-main-area .size-filter>li,
        .shop-main-area .color-filter>li,
        .shop-main-area .tag-filter>li {
            margin: 0;
        }

        .shop-main-area .shop-filter-dropdown>summary {
            align-items: center;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            list-style: none;
            margin: 0;
            padding-right: 4px;
            user-select: none;
        }

        .shop-main-area .shop-filter-dropdown>summary::-webkit-details-marker {
            display: none;
        }

        .shop-main-area .shop-filter-dropdown>summary h5 {
            margin-bottom: 0;
        }

        .shop-main-area .shop-filter-dropdown>summary i {
            color: #222;
            font-size: 18px;
            transition: transform .2s ease;
        }

        .shop-main-area .shop-filter-dropdown[open]>summary i {
            transform: rotate(180deg);
        }

        .shop-main-area .shop-filter-dropdown-content {
            border-top: 1px solid #e7e1dc;
            margin-top: 20px;
            padding-top: 4px;
        }

        .shop-main-area .size-category-group {
            border-bottom: 1px solid #eee9e3;
            margin: 0;
            padding: 0;
        }

        .shop-main-area .size-category-group:last-child { border-bottom: 0; }

        .shop-main-area .size-category-group > summary {
            align-items: center;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            list-style: none;
            padding: 16px 2px;
            user-select: none;
        }

        .shop-main-area .size-category-group > summary::-webkit-details-marker { display: none; }

        .shop-main-area .size-category-name {
            color: #222;
            display: block;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .shop-main-area .size-category-group > summary i {
            color: #222;
            font-size: 16px;
            transition: transform .2s ease;
        }

        .shop-main-area .size-category-group[open] > summary i { transform: rotate(180deg); }
        .shop-main-area .size-category-group > .size-filter { padding: 0 0 16px; }

        .shop-main-area .category-filter-subtitle {
            color: #777;
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.4px;
            margin: 2px 0 10px;
            text-transform: uppercase;
        }

        .shop-main-area .size-category-colors {
            margin: 2px 0 16px !important;
        }
        .shop-main-area .size-filter li a {
            align-items: center;
            background: #fff;
            border: 1px solid #d8d8d8;
            color: #555;
            display: inline-flex;
            border-radius: 7px;
            font-size: 14px;
            height: 40px;
            justify-content: center;
            min-width: 44px;
            padding: 0 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .shop-main-area .size-filter li a:hover,
        .shop-main-area .size-filter li a.active,
        .shop-main-area .tag-filter li a.active {
            background: #171717;
            border-color: #171717;
            color: #fff;
        }

        .shop-main-area .color-filter li a {
            align-items: center;
            border: 1px solid #ccc;
            display: inline-flex;
            border-radius: 50%;
            height: 42px;
            justify-content: center;
            overflow: hidden;
            width: 42px;
        }

        .shop-main-area .color-filter li a.color-swatch {
            box-shadow: inset 0 0 0 4px #fff;
            text-indent: -999px;
        }

        .shop-main-area .color-filter li a.color-swatch:hover,
        .shop-main-area .color-filter li a.color-swatch.active {
            border-color: #171717;
            box-shadow: inset 0 0 0 4px #fff, 0 0 0 2px #171717;
        }

        .shop-main-area .color-filter li a.color-name {
            border-radius: 20px;
            color: #666;
            height: 42px;
            padding: 0 18px;
            width: auto;
        }

        .shop-main-area .color-filter li a.color-name:hover,
        .shop-main-area .color-filter li a.color-name.active {
            background: #171717;
            border-color: #171717;
            color: #fff;
        }

        .shop-main-area .size-aside,
        .shop-main-area .color-aside {
            /* border-top: 1px solid #e7e1dc; */
            padding-top: 30px;
        }

        .shop-main-area #input-amount {
            color: #242220;
            min-width: 78px;
            padding-left: 14px;
            padding-right: 30px;
            width: 78px;
        }

        .shop-main-area .shop-toolbar-filters {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .shop-main-area .product-category-filter select {
            min-width: 190px;
        }

        .shop-main-area .product-cat li a {
            align-items: center;
            display: flex;
            gap: 12px;
            justify-content: space-between;
            padding-left: 0;
        }

        .shop-main-area .catagories-aside .aside-title {
            margin-bottom: 10px;
        }

        .shop-main-area .catagories-aside .product-cat {
            margin-top: 0;
        }

        .shop-main-area .catagories-aside .product-cat li {
            margin: 0;
        }

        .shop-main-area .catagories-aside .product-cat li a {
            line-height: 1.4;
            padding-bottom: 11px;
            padding-top: 11px;
        }

        .shop-main-area .product-cat li a::before {
            display: none;
        }

        .shop-main-area .product-cat li a:hover,
        .shop-main-area .product-cat li a.active {
            padding-left: 0;
        }

        .shop-main-area .category-name {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .shop-main-area .category-count {
            flex: 0 0 auto;
            font-variant-numeric: tabular-nums;
            letter-spacing: .5px;
        }

        .shop-main-area .color-filter .color-image-option {
            background: #f7f5f2;
            border: 2px solid #e0d9d1;
            border-radius: 9px;
            height: 72px;
            overflow: hidden;
            padding: 3px;
            position: relative;
            width: 58px;
        }

        .shop-main-area .color-filter .color-image-option img {
            border-radius: 6px;
            height: 100%;
            object-fit: cover;
            width: 100%;
        }

        .shop-main-area .color-filter .color-image-option span {
            background: linear-gradient(transparent, rgba(0, 0, 0, .78));
            bottom: 5px;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            left: 5px;
            overflow: hidden;
            padding: 18px 5px 6px;
            position: absolute;
            right: 5px;
            text-align: center;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .shop-main-area .color-filter .color-image-option:hover,
        .shop-main-area .color-filter .color-image-option.active {
            background: #fff;
            border-color: #171717;
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px #171717;
        }

        .shop-main-area .color-aside .color-filter {
            margin-top: 18px !important;
        }

        .shop-main-area .filter-empty {
            color: #777;
            margin: 15px 0 0;
        }

        .shop-main-area .recent-single-product .product-img {
            width: 28%;
        }

        .shop-main-area .recent-single-product .product-text {
            width: 72%;
        }

        .shop-main-area .recent-single-product .prodcut-name a {
            font-size: 13px;
        }

        /* Instagram Gallery (copied from home) */
        .instagram-gallery-modern {
            padding: 0;
            line-height: 0;
            overflow: hidden;
            background: #000;
        }

        .instagram-auto-slider {
            margin: 0;
        }

        .instagram-auto-slider .slick-list,
        .instagram-auto-slider .slick-track {
            line-height: 0;
        }

        .instagram-gallery-item {
            display: block;
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        .instagram-gallery-item img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
        }

        .instagram-gallery-item.is-muted img {
            filter: grayscale(100%) contrast(1.1) brightness(0.9);
        }

        .instagram-gallery-item.is-mono img {
            /* filter: grayscale(100%) contrast(1.1); */
        }

        .instagram-gallery-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.22);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .instagram-gallery-icon {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 2;
            width: 54px;
            height: 54px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: rgba(0, 0, 0, 0.26);
            font-size: 25px;
            line-height: 1;
            transform: translate(-50%, -50%);
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .instagram-gallery-item:hover,
        .instagram-gallery-item:focus {
            color: #fff;
        }

        .instagram-gallery-item:hover::after,
        .instagram-gallery-item:focus::after {
            opacity: 1;
        }

        .instagram-gallery-item:hover .instagram-gallery-icon,
        .instagram-gallery-item:focus .instagram-gallery-icon {
            background: var(--knp-accent);
            transform: translate(-50%, -50%) scale(1.06);
        }

        @media (max-width: 991px) {
            .instagram-gallery-item img {
                height: 240px;
            }
        }

        @media (max-width: 767px) {
            .instagram-gallery-item img {
                height: 220px;
            }
        }
    </style>

    <!--breadcumb area start -->
    <style>
        /* Reference catalog layout */
        .breadcumb-area {
            display: none !important
        }

        .shop-main-area {
            background: #fff !important;
            padding: 18px 0 50px !important
        }

        .shop-main-area>.container {
            max-width: none !important;
            padding: 0 18px !important;
            width: 100% !important
        }

        .shop-main-area>.container>.row {
            display: flex !important;
            margin: 0 -10px !important
        }

        .shop-main-area .shop-sidebar,
        .shop-main-area .shop-wraper,
        .shop-main-area .shop-area-top {
            background: #fff !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important
        }

        .shop-main-area .shop-sidebar {
            padding: 0 10px 24px !important
        }

        .shop-reference-filter-title {
            color: #111;
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 15px;
            text-transform: uppercase
        }

        .shop-reference-title h1 {
            color: #080808;
            font: 800 30px/1 Arial, sans-serif;
            letter-spacing: -.6px;
            margin: 0 0 14px;
            text-transform: uppercase
        }

        .shop-main-area .shop-area-top {
            margin: 0 !important;
            padding: 0 0 10px !important
        }

        .shop-toolbar-filters {
            display: flex !important;
            justify-content: flex-end !important
        }

        .product-category-filter,
        .product-show {
            display: none !important
        }

        .shop-main-area .product-type {
            margin-left: auto !important;
            min-width: 260px !important
        }

        .shop-main-area .product-type select {
            background: #fff !important;
            border: 1px solid #ccc !important;
            border-radius: 0 !important;
            height: 44px !important;
            padding: 0 16px !important;
            /* width: 100% !important */
        }

        .shop-main-area .total-showing {
            font-size: 13px !important;
            line-height: 44px !important
        }

        .shop-category-chips {
            display: flex;
            gap: 10px;
            margin: 0 0 18px;
            overflow-x: auto;
            padding-bottom: 4px
        }

        .shop-category-chip {
            border: 1px solid #222;
            color: #111;
            display: inline-flex;
            font-size: 13px;
            line-height: 1;
            padding: 10px 16px;
            white-space: nowrap
        }

        .shop-category-chip.active,
        .shop-category-chip:hover {
            background: #050505;
            color: #fff
        }

        .shop-main-area .shop-total-product-area {
            margin-top: 0 !important
        }

        .total-shop-product-grid {
            display: flex !important;
            margin: 0 -5px !important;
            row-gap: 28px !important
        }

        .total-shop-product-grid>.item {
            flex: 0 0 20% !important;
            max-width: 20% !important;
            padding: 0 5px !important;
            width: 20% !important
        }

        .total-shop-product-grid .single-product {
            height: 100% !important
        }

        .shop-main-area .product-img,
        .shop-main-area .single-prodcut-img {
            background: #f1f1f1 !important;
            overflow: hidden !important
        }

        .shop-main-area .product-img a {
            aspect-ratio: 3/4 !important;
            display: block !important;
            overflow: hidden !important
        }

        .shop-main-area .product-img img {
            height: 100% !important;
            object-fit: cover !important;
            width: 100% !important
        }

        .shop-main-area .product-card-actions {
            display: flex !important;
            flex-direction: column !important;
            gap: 7px !important;
            right: 8px !important;
            top: 8px !important
        }

        .shop-main-area .product-card-actions a {
            background: rgba(255, 255, 255, .88) !important;
            border: 0 !important;
            box-shadow: none !important;
            color: #111 !important;
            height: 32px !important;
            width: 32px !important
        }

        .shop-main-area .product-card-actions a:first-child {
            display: inline-flex !important
        }

        .shop-main-area .product-card-actions a:last-child {
            align-items: center !important;
            display: flex !important;
            justify-content: center !important;
            padding: 0 !important
        }

        .shop-main-area .wishlist-heart-glyph {
            align-items: center !important;
            color: #111 !important;
            display: flex !important;
            font-family: Arial, sans-serif !important;
            font-size: 27px !important;
            font-weight: 400 !important;
            height: 100% !important;
            justify-content: center !important;
            line-height: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
            transform: translateY(-1px) !important;
            width: 100% !important
        }

        .shop-main-area .catagories-aside {
            display: none !important
        }

        .total-shop-product-grid .product-text {
            padding: 8px 2px 0 !important
        }

        .shop-main-area .prodcut-name a {
            color: #111 !important;
            font-size: 14px !important;
            line-height: 1.3 !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important
        }

        .shop-main-area .prodcut-price {
            align-items: baseline !important;
            display: flex !important;
            gap: 6px !important
        }

        .shop-main-area .new-price {
            color: #111 !important;
            font-size: 14px !important;
            font-weight: 700 !important
        }

        .shop-main-area .old-price {
            color: #888 !important;
            font-size: 11px !important;
            margin: 0 !important
        }

        .shop-main-area .single-aside {
            /* border-bottom: 1px solid #ddd !important; */
            margin: 0 !important;
            padding: 16px 0 !important
        }

        .shop-main-area .search-aside {
            padding-top: 0 !important
        }

        .shop-main-area .search-aside .input-box {
            border: 1px solid #ccc !important;
            border-radius: 0 !important
        }

        .shop-main-area .heading-title h5 {
            color: #111 !important;
            font-size: 16px !important;
            font-weight: 500 !important;
            margin: 0 !important;
            text-transform: uppercase !important
        }

        .shop-main-area .product-cat li {
            border-bottom: 1px solid #e4e4e4 !important
        }

        .shop-main-area .product-cat a {
            color: #222 !important;
            padding: 12px 0 !important
        }

        .shop-main-area .product-cat a.active {
            color: #c00 !important;
            font-weight: 700 !important
        }

        .shop-filter-dropdown summary {
            align-items: center !important;
            display: flex !important;
            justify-content: space-between !important
        }

        .price-filter-field input {
            border-radius: 0 !important;
            height: 42px !important
        }

        .price_slider_amount>input[type=submit] {
            background: #050505 !important;
            border: 0 !important;
            color: #fff !important;
            height: 42px !important;
            padding: 0 24px !important
        }

        .shop-filter-actions {
            display: grid;
            gap: 8px;
            grid-template-columns: 1fr 1fr;
            padding-top: 16px
        }

        .shop-filter-actions a,
        .shop-filter-actions button {
            align-items: center;
            background: #fff;
            border: 1px solid #111;
            color: #111;
            display: flex;
            font-size: 13px;
            font-weight: 800;
            height: 48px;
            justify-content: center;
            text-transform: uppercase
        }

        .shop-filter-actions button {
            background: #050505;
            color: #fff
        }

        @media(min-width:992px) {
            .shop-main-area>.container>.row>.col-lg-3 {
                flex: 0 0 20% !important;
                max-width: 20% !important;
                width: 20% !important
            }

            .shop-main-area>.container>.row>.col-lg-9 {
                flex: 0 0 80% !important;
                max-width: 80% !important;
                width: 80% !important
            }
        }

        @media(max-width:1199px) {
            .total-shop-product-grid>.item {
                flex-basis: 25% !important;
                max-width: 25% !important;
                width: 25% !important
            }
        }

        @media(max-width:991px) {
            .shop-main-area>.container>.row {
                flex-direction: column !important
            }

            .shop-main-area>.container>.row>[class*=col-] {
                max-width: 100% !important;
                width: 100% !important
            }

            .shop-main-area .shop-sidebar {
                margin-top: 30px !important
            }

            .total-shop-product-grid>.item {
                flex-basis: 33.333% !important;
                max-width: 33.333% !important;
                width: 33.333% !important
            }
        }

        @media(max-width:575px) {
            .shop-main-area>.container {
                padding: 0 10px !important
            }

            .shop-reference-title h1 {
                font-size: 24px
            }

            .shop-main-area .product-type {
                min-width: 180px !important
            }

            .shop-main-area .total-showing {
                display: none
            }

            .total-shop-product-grid>.item {
                flex-basis: 50% !important;
                max-width: 50% !important;
                width: 50% !important
            }

            .shop-main-area .prodcut-name a {
                font-size: 12px !important
            }
        }

        /* Final responsive alignment matching the supplied catalog reference */
        @media (min-width: 992px) {
            .shop-main-area .shop-wraper {
                position: relative !important;
            }

            .shop-main-area .shop-reference-title {
                align-items: center !important;
                display: flex !important;
                height: 48px !important;
                padding-right: 340px !important;
            }

            .shop-main-area .shop-reference-title h1 {
                margin: 0 !important;
            }

            .shop-main-area .shop-area-top {
                position: absolute !important;
                right: 0 !important;
                top: 0 !important;
                width: 320px !important;
                z-index: 4 !important;
            }

            .shop-main-area .shop-area-top form,
            .shop-main-area .shop-area-top .row,
            .shop-main-area .shop-area-top [class*="col-"] {
                margin: 0 !important;
                max-width: 100% !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .shop-main-area .shop-area-top .col-xl-3 {
                display: none !important;
            }

            .shop-main-area .shop-category-chips {
                margin-top: 12px !important;
            }

            .shop-main-area .shop-sidebar {
                position: sticky !important;
                top: 12px !important;
            }
        }

        @media (min-width: 1200px) and (max-width: 1499.98px) {
            .shop-main-area>.container>.row>.col-lg-3 {
                flex-basis: 23% !important;
                max-width: 23% !important;
                width: 23% !important;
            }

            .shop-main-area>.container>.row>.col-lg-9 {
                flex-basis: 77% !important;
                max-width: 77% !important;
                width: 77% !important;
            }

            .shop-main-area .total-shop-product-grid>.item {
                flex-basis: 25% !important;
                max-width: 25% !important;
                width: 25% !important;
            }
        }

        @media (max-width: 991.98px) {
            .shop-main-area .shop-reference-title {
                margin-bottom: 12px !important;
            }

            .shop-main-area .shop-area-top {
                position: static !important;
                width: 100% !important;
            }

            .shop-main-area .shop-toolbar-filters {
                justify-content: flex-start !important;
            }

            .shop-main-area .product-type {
                margin-left: 0 !important;
                max-width: 320px !important;
                width: 100% !important;
            }

            .shop-main-area .shop-category-chips {
                margin-top: 14px !important;
            }

            .shop-main-area .shop-sidebar {
                position: static !important;
            }
        }

        @media (max-width: 575.98px) {
            .shop-main-area .shop-reference-title h1 {
                font-size: 22px !important;
            }

            .shop-main-area .product-type {
                max-width: none !important;
                min-width: 0 !important;
            }

            .shop-main-area .shop-category-chip {
                font-size: 11px !important;
                padding: 9px 12px !important;
            }

            .shop-main-area .product-card-actions {
                gap: 5px !important;
                right: 5px !important;
                top: 5px !important;
            }

            .shop-main-area .product-card-actions a {
                height: 29px !important;
                width: 29px !important;
            }
        }
    </style>
    <div class="breadcumb-area breadcumb-2 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center">
                <h5>{{ $selectedCategoryDetails->category_name ?? 'Shop' }}</h5>
            </div>
            <ol class="breadcrumb">
                <li class="home"><a title="Go to Home Page" href="/"style="color: #fff;">Home</a></li>
                <li class="active">{{ $selectedCategoryDetails->category_name ?? 'Shop' }}</li>
            </ol>
        </div>
    </div>
    <!--breadcumb area end -->

    <!--shop main area are start-->
    <div class="shop-main-area grid-view_area ptb-70">
        <div class="container">
            <div class="row">
                <!--main-shop-product start-->
                <div class="col-lg-9 col-md-8 order-lg-2 order-md-2 order-1">
                    <div class="shop-wraper">
                        <div class="col-lg-12">
                            {{-- <div class="knp-shop-heading">
                                <div>
                                    <span class="knp-shop-eyebrow">House of KNP Collection</span>
                                    <h1>{{ $selectedCategoryDetails->category_name ?? 'Curated Essentials' }}</h1>
                                </div>
                                <p>{{ $selectedCategoryDetails ? 'Explore ' . $products->total() . ' products selected for this collection.' : 'Premium shirts, watches, fragrances and gifts designed for the modern gentleman.' }}
                                </p>
                            </div> --}}
                            <div class="shop-reference-title">
                                <h1>{{ $selectedCategoryDetails->category_name ?? 'All Products' }}</h1>
                            </div>
                            <div class="shop-area-top">
                                <form id="shop-filter-form" action="{{ url('shop') }}" method="GET">
                                    @if (request('q'))
                                        <input type="hidden" name="q" value="{{ request('q') }}">
                                    @endif
                                    @if (request('min_price'))
                                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                    @endif
                                    @if (request('max_price'))
                                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                    @endif
                                    @if (request('size'))
                                        <input type="hidden" name="size" value="{{ request('size') }}">
                                    @endif
                                    @if (request('color'))
                                        <input type="hidden" name="color" value="{{ request('color') }}">
                                    @endif
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-9 col-md-9">
                                            <div class="shop-toolbar-filters">
                                                <div class="sort product-category-filter">
                                                    <label>Category</label>
                                                    <select id="input-category" name="category" data-category-filter-select>
                                                        <option value="" @selected(!$selectedCategory)>All Products
                                                        </option>
                                                        @foreach ($categories as $category)
                                                            <option
                                                                value="{{ \Illuminate\Support\Str::slug($category->category_name) }}"
                                                                @selected((string) $selectedCategory === (string) $category->id)>
                                                                {{ $category->category_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="sort product-show">
                                                    <label>View</label>
                                                    <select id="input-amount" name="view" onchange="this.form.submit()">
                                                        @foreach ([9, 12, 24, 36] as $view)
                                                            <option value="{{ $view }}"
                                                                @selected((int) request('view', 12) === $view)>
                                                                {{ $view }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="sort product-type">
                                                    {{-- <label>Sort By</label> --}}
                                                    <select id="input-sort" name="sort" onchange="this.form.submit()">
                                                        <option value="" @selected(!request('sort'))>Default</option>
                                                        <option value="name_asc" @selected(request('sort') === 'name_asc')>Name (A - Z)
                                                        </option>
                                                        <option value="name_desc" @selected(request('sort') === 'name_desc')>Name (Z - A)
                                                        </option>
                                                        <option value="price_low" @selected(request('sort') === 'price_low')>Price (Low
                                                            &gt;
                                                            High)</option>
                                                        <option value="price_high" @selected(request('sort') === 'price_high')>Price (High
                                                            &gt;
                                                            Low)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-3 col-lg-3 col-md-3">
                                                <div class="list-grid-view text-center">
                                                    <ul class="nav" role="tablist">
                                                        <li role="presentation"><a class="active" href="#grid" aria-controls="grid" role="tab" data-bs-toggle="tab"><i class="zmdi zmdi-widgets"></i></a></li>
                                                        <li role="presentation"><a href="#list" aria-controls="list" role="tab" data-bs-toggle="tab"><i class="zmdi zmdi-view-list-alt"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div> --}}
                                        <div class="col-xl-3 d-lg-none d-xl-block d-none">
                                            <div class="total-showing text-end">
                                                Showing - <span>{{ $products->firstItem() ?? 0 }}</span> to
                                                <span>{{ $products->lastItem() ?? 0 }}</span> Of Total
                                                <span>{{ $products->total() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="shop-category-chips" aria-label="Product categories">
                            <a class="shop-category-chip {{ $selectedCategory ? '' : 'active' }}"
                                href="{{ url('shop') }}">All</a>
                            @foreach ($categories as $category)
                                <a class="shop-category-chip {{ (string) $selectedCategory === (string) $category->id ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['category' => \Illuminate\Support\Str::slug($category->category_name), 'page' => null, 'size' => null, 'color' => null, 'color_product' => null]) }}">{{ $category->category_name }}</a>
                            @endforeach
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <div class="shop-total-product-area clearfix mt-35">
                                <div class="tab-content">
                                    <!--tab grid are start-->
                                    <div role="tabpanel" class="tab-pane fade show active" id="grid">
                                        <div class="total-shop-product-grid row" id="shop-products">
                                            @forelse($products as $product)
                                                @php
                                                    $images = $productImages($product);
                                                    $price = $productPrice($product);
                                                    $compareAt = $oldPrice($product);
                                                @endphp
                                                <div class="col-lg-3 col-md-6 item">
                                                    <div class="single-product">
                                                        <div class="product-img">
                                                            <div class="single-prodcut-img product-overlay pos-rltv">
                                                                <a href="{{ $productUrl($product) }}">
                                                                    <img alt="{{ $product->product_name }}"
                                                                        src="{{ $images[0] }}" class="primary-image">
                                                                    <img alt="{{ $product->product_name }}"
                                                                        src="{{ $images[1] }}" class="secondary-image">
                                                                </a>
                                                                <div class="product-card-actions">
                                                                    <a href="{{ route('cart.add', $product->id) }}"
                                                                        title="Add To Cart"><i
                                                                            class="fa fa-cart-plus"></i></a>
                                                                    <a href="{{ route('wishlist.add', $product->id) }}"
                                                                        title="Wishlist"><span class="wishlist-heart-glyph"
                                                                            aria-hidden="true">&#9825;</span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="product-text">
                                                            <div class="prodcut-name"><a
                                                                    href="{{ $productUrl($product) }}">{{ $product->product_name }}</a>
                                                            </div>
                                                            <div class="prodcut-ratting-price">
                                                                <div class="prodcut-price">
                                                                    <div class="new-price">{!! $money($price) !!}</div>
                                                                    @if ($compareAt)
                                                                        <div class="old-price">
                                                                            <del>{!! $money($compareAt) !!}</del>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-lg-12">
                                                    <p>No products found.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                    <!--shop grid are end-->

                                    <!--shop product list start-->
                                    <div role="tabpanel" class="tab-pane fade" id="list">
                                        <div class="total-shop-product-list row">
                                            @foreach ($products as $product)
                                                @php
                                                    $images = $productImages($product);
                                                    $price = $productPrice($product);
                                                    $compareAt = $oldPrice($product);
                                                @endphp
                                                <div class="col-lg-12 item">
                                                    <div class="single-product single-product-list">
                                                        <div class="product-img">
                                                            <div class="single-prodcut-img product-overlay pos-rltv">
                                                                <a href="{{ $productUrl($product) }}">
                                                                    <img alt="{{ $product->product_name }}"
                                                                        src="{{ $images[0] }}" class="primary-image">
                                                                    <img alt="{{ $product->product_name }}"
                                                                        src="{{ $images[1] }}"
                                                                        class="secondary-image">
                                                                </a>
                                                                <div class="product-card-actions">
                                                                    <a href="{{ route('cart.add', $product->id) }}"
                                                                        title="Add To Cart"><i
                                                                            class="fa fa-cart-plus"></i></a>
                                                                    <a href="{{ route('wishlist.add', $product->id) }}"
                                                                        title="Wishlist"><span
                                                                            class="wishlist-heart-glyph"
                                                                            aria-hidden="true">&#9825;</span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="product-text prodcut-text-list fix">
                                                            <div class="prodcut-name list-name montserrat"><a
                                                                    href="{{ $productUrl($product) }}">{{ $product->product_name }}</a>
                                                            </div>
                                                            <div class="prodcut-ratting-price">
                                                                <div class="prodcut-ratting list-ratting">
                                                                    @for ($i = 0; $i < 5; $i++)
                                                                        <a href="#"><i class="fa fa-star-o"></i></a>
                                                                    @endfor
                                                                </div>
                                                                <div class="prodcut-price list-price">
                                                                    <div class="new-price">{!! $money($price) !!}</div>
                                                                    @if ($compareAt)
                                                                        <div class="old-price">
                                                                            <del>{!! $money($compareAt) !!}</del>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="list-product-content">
                                                                <p>{{ $description($product) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!--shop product list end-->

                                    <div class="shop-infinite-scroll" data-shop-infinite-scroll
                                        data-next-url="{{ $products->hasMorePages() ? $products->nextPageUrl() : '' }}"
                                        @if (!$products->hasMorePages()) hidden @endif>
                                        <span class="shop-infinite-spinner" aria-hidden="true"></span>
                                        <span data-shop-load-status role="status" aria-live="polite">Scroll for more
                                            products</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--main-shop-product end-->

                <!--shop sidebar start-->
                <div class="col-lg-3 col-md-4 order-lg-1 order-md-1 order-2">
                    <div class="shop-sidebar">
                        <h2 class="shop-reference-filter-title">Filters</h2>
                        {{-- <aside class="single-aside search-aside search-box">
                            <form action="{{ url('shop') }}" method="GET">
                                @if ($selectedCategory)
                                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                                @endif
                                @if (request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                                @if (request('view'))
                                    <input type="hidden" name="view" value="{{ request('view') }}">
                                @endif
                                @if (request('min_price'))
                                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                @endif
                                @if (request('max_price'))
                                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                @endif
                                @if (request('size'))
                                    <input type="hidden" name="size" value="{{ request('size') }}">
                                @endif
                                @if (request('color'))
                                    <input type="hidden" name="color" value="{{ request('color') }}">
                                @endif
                                <div class="input-box">
                                    <input class="single-input" name="q" value="{{ request('q') }}"
                                        placeholder="Search...." type="text">
                                    <button class="src-btn sb-2"><i class="fa fa-search"></i></button>
                                </div>
                                                                @if (request('color_product'))
                                        <input type="hidden" name="color_product" value="{{ request('color_product') }}">
                                    @endif</form>
                        </aside> --}}

                        <aside class="single-aside catagories-aside">
                            <div class="heading-title aside-title pos-rltv">
                                <h5 class="uppercase">categories</h5>
                            </div>
                            <div id="cat-treeview" class="product-cat">
                                <ul>
                                    <li class="closed">
                                        <a class="{{ $selectedCategory ? '' : 'active' }}" href="{{ url('shop') }}">
                                            <span class="category-name">All Products</span>
                                        </a>
                                    </li>
                                    @foreach ($categories as $category)
                                        <li class="closed">
                                            <a class="{{ (string) $selectedCategory === (string) $category->id ? 'active' : '' }}"
                                                href="{{ url('shop?category=' . \Illuminate\Support\Str::slug($category->category_name)) }}">
                                                <span class="category-name">{{ $category->category_name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </aside>

                        @if (($sizeOptionsByCategory ?? collect())->isNotEmpty())
                            <aside class="single-aside size-aside">
                                    <div class="shop-category-filters">
                                        @foreach ($sizeOptionsByCategory as $categoryName => $sizes)
                                            @php
                                                $categoryColors = ($colorOptionsByCategory ?? collect())->get($categoryName, collect());
                                                $categoryHasSelectedColor = $categoryColors->contains(fn ($color) => (string) $color['product_id'] === (string) request('color_product'));
                                            @endphp
                                            <details class="size-category-group" @if ($sizes->contains(fn ($size) => (string) $size === (string) request('size')) || $categoryHasSelectedColor) open @endif>
                                                <summary>
                                                    <span class="size-category-name">{{ $categoryName }}</span>
                                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                </summary>
                                                <span class="category-filter-subtitle">
                                                    @if (\Illuminate\Support\Str::contains(strtolower($categoryName), 'perfume'))
                                                        Qty
                                                    @elseif (\Illuminate\Support\Str::contains(strtolower($categoryName), 'watch'))
                                                        Model
                                                    @else
                                                        Size
                                                    @endif
                                                </span>
                                                <ul class="size-filter">
                                                    @foreach ($sizes as $size)
                                                        <li>
                                                            <a href="{{ request()->fullUrlWithQuery(['category' => $selectedCategory, 'size' => $size, 'page' => null]) }}"
                                                                class="{{ request('size') === $size ? 'active' : '' }}">
                                                                {{ $size }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @if ($categoryColors->isNotEmpty() && ! \Illuminate\Support\Str::contains(strtolower($categoryName), 'watch'))
                                                    <span class="category-filter-subtitle">Color</span>
                                                    <ul class="color-filter size-category-colors">
                                                        @foreach ($categoryColors as $color)
                                                            <li>
                                                                <a href="{{ request()->fullUrlWithQuery(['color' => $color['value'] !== '' ? $color['value'] : null, 'color_product' => $color['product_id'], 'page' => null]) }}"
                                                                    class="color-image-option {{ (string) request('color_product') === (string) $color['product_id'] ? 'active' : '' }}"
                                                                    title="{{ $color['label'] }}">
                                                                    <img src="{{ $color['image'] }}" alt="{{ $color['product_name'] }} - {{ $color['label'] }}">
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </details>
                                        @endforeach
                                    </div>
                            </aside>
                        @endif

                        <aside class="single-aside price-aside fix">
                            <div class="heading-title aside-title pos-rltv">
                                <h5 class="uppercase">price</h5>
                            </div>
                            <div class="price_filter">
                                <form action="{{ url('shop') }}" method="GET">
                                    @if ($selectedCategory)
                                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                                    @endif
                                    @if (request('q'))
                                        <input type="hidden" name="q" value="{{ request('q') }}">
                                    @endif
                                    @if (request('sort'))
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                    @endif
                                    @if (request('view'))
                                        <input type="hidden" name="view" value="{{ request('view') }}">
                                    @endif
                                    @if (request('size'))
                                        <input type="hidden" name="size" value="{{ request('size') }}">
                                    @endif
                                    @if (request('color'))
                                        <input type="hidden" name="color" value="{{ request('color') }}">
                                    @endif
                                    <div class="price_slider_amount">
                                        <div class="price-filter-field">
                                            <label for="shop-min-price">Min Price</label>
                                            <input id="shop-min-price" type="number" name="min_price"
                                                value="{{ request('min_price', (int) ($priceRange->min_price ?? 0)) }}"
                                                placeholder="Min Price" min="0" />
                                        </div>
                                        <div class="price-filter-field">
                                            <label for="shop-max-price">Max Price</label>
                                            <input id="shop-max-price" type="number" name="max_price"
                                                value="{{ request('max_price', (int) ($priceRange->max_price ?? 0)) }}"
                                                placeholder="Max Price" min="0" />
                                        </div>
                                        <input type="submit" value="Filter" />
                                    </div>
                                </form>
                            </div>
                        </aside>


                        <div class="shop-filter-actions"><a href="{{ url('shop') }}">Clear</a><button type="submit"
                                form="shop-filter-form">Apply ({{ $products->total() }})</button></div>

                        {{-- <aside class="single-aside tag-aside">
                                <div class="heading-title aside-title pos-rltv">
                                    <h5 class="uppercase">Product Tags</h5>
                                </div>
                                <ul class="tag-filter mt-30">
                                    @foreach ($categories->where('products_count', '>', 0)->take(8) as $category)
                                        <li><a class="{{ (string) request('category') === (string) $category->id ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['category' => \Illuminate\Support\Str::slug($category->category_name), 'page' => null]) }}">{{ $category->category_name }}</a></li>
                                    @endforeach
                                </ul>
                            </aside> --}}

                        {{-- <aside class="single-aside product-aside">
                                <div class="heading-title aside-title pos-rltv">
                                    <h5 class="uppercase">Recent Product</h5>
                                </div>
                                <div class="recent-prodcut-wraper total-rectnt-slider">
                                    @forelse($recentProducts as $product)
                                        @php
                                            $images = $productImages($product);
                                            $price = $productPrice($product);
                                        @endphp
                                        <div class="single-product recent-single-product">
                                            <div class="product-img">
                                                <div class="single-prodcut-img pos-rltv">
                                                    <a href="{{ $productUrl($product) }}">
                                                        <img alt="{{ $product->product_name }}" src="{{ $images[0] }}" class="primary-image">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="product-text">
                                                            <div class="prodcut-name"><a href="{{ $productUrl($product) }}">{{ $product->product_name }}</a></div>
                                                <div class="prodcut-ratting-price">
                                                    <div class="prodcut-price">
                                                        <div class="new-price">{!! $money($price) !!}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No recent products found.</p>
                                    @endforelse
                                </div>
                            </aside> --}}

                        @if (!empty($shopBanner?->image))
                            <aside class="single-aside add-aside">
                                <a href="{{ url('shop') }}">
                                    <img src="{{ house_web_image_url($shopBanner->image) }}"
                                        alt="{{ $shopBanner->title ?? 'Shop banner' }}">
                                </a>
                            </aside>
                        @endif
                    </div>
                </div>
                <!--shop sidebar end-->
            </div>
        </div>
    </div>
    <!--shop main area are end-->

    {{-- Instagram gallery from admin/home_promotions --}}
    @include('partials.instagram-gallery')


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.querySelector('[data-category-filter-select]');
            if (!categorySelect) return;

            categorySelect.addEventListener('change', function() {
                ['size', 'color', 'color_product', 'min_price', 'max_price'].forEach(function(name) {
                    const field = categorySelect.form.querySelector(`[name="${name}"]`);
                    if (field) field.remove();
                });

                categorySelect.form.submit();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grid = document.getElementById('shop-products');
            const loader = document.querySelector('[data-shop-infinite-scroll]');
            const status = loader?.querySelector('[data-shop-load-status]');
            if (!grid || !loader || !loader.dataset.nextUrl) return;

            let loading = false;
            const observer = new IntersectionObserver(async function(entries) {
                if (!entries.some(entry => entry.isIntersecting) || loading || !loader.dataset.nextUrl)
                    return;
                loading = true;
                loader.classList.add('is-loading');
                if (status) status.textContent = 'Loading more products...';

                try {
                    const response = await fetch(loader.dataset.nextUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const html = await response.text();
                    const nextDocument = new DOMParser().parseFromString(html, 'text/html');
                    const nextItems = nextDocument.querySelectorAll('#shop-products > .item');
                    const nextLoader = nextDocument.querySelector('[data-shop-infinite-scroll]');
                    const fragment = document.createDocumentFragment();
                    nextItems.forEach(item => fragment.appendChild(item));
                    grid.appendChild(fragment);
                    loader.dataset.nextUrl = nextLoader?.dataset.nextUrl || '';

                    if (!loader.dataset.nextUrl) {
                        observer.disconnect();
                        loader.hidden = true;
                    } else if (status) {
                        status.textContent = 'Scroll for more products';
                    }
                } catch (error) {
                    if (status) status.textContent = 'Could not load products. Scroll to retry.';
                } finally {
                    loading = false;
                    loader.classList.remove('is-loading');
                }
            }, {
                rootMargin: '600px 0px',
                threshold: 0.01
            });

            observer.observe(loader);
        });
    </script>

@endsection
