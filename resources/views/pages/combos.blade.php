@extends('layouts.app')
@php
    $minPrice = $combos->min(function ($c) {
        return $c->offer_price > 0 ? $c->offer_price : $c->mrp_price; }) ?? 0;
    $maxPrice = $combos->max(function ($c) {
        return $c->offer_price > 0 ? $c->offer_price : $c->mrp_price; }) ?? 20000;
    if ($minPrice == $maxPrice) {
        $minPrice = 0;
    }
@endphp
@section('content')
    <style>
        .combo-catalog-page {
            background: #fff;
            color: #111;
            font-family: 'Inter', sans-serif;
        }

        .hero-section {
            position: relative;
            background: #fff;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .hero-content {
            position: absolute;
            left: 10%;
            z-index: 10;
            max-width: 400px;
        }

        .hero-eyebrow {
            color: #990000;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: #111;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-desc {
            color: #555;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .hero-btn {
            background: #990000;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .hero-image {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .feature-row {
            display: flex;
            justify-content: space-around;
            padding: 30px 10%;
            border-bottom: 1px solid #eee;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            color: #990000;
            font-size: 24px;
        }

        .feature-text {
            display: flex;
            flex-direction: column;
        }

        .feature-title {
            font-size: 12px;
            font-weight: 700;
            color: #111;
        }

        .feature-sub {
            font-size: 10px;
            color: #777;
        }

        .catalog-container {
            display: flex;
            padding: 40px 5%;
            gap: 40px;
        }

        .sidebar {
            width: 250px;
            flex-shrink: 0;
        }

        .breadcrumb-bar {
            font-size: 12px;
            color: #777;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-group {
            margin-bottom: 30px;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 1px;
            color: #111;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-list li {
            margin-bottom: 12px;
            font-size: 13px;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-list li.active {
            color: #990000;
            font-weight: 600;
        }

        .sidebar-list input[type='checkbox'] {
            accent-color: #990000;
            width: 16px;
            height: 16px;
        }

        .color-swatch-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .color-swatch-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #555;
        }

        .color-circle {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1px solid #ddd;
        }

        .product-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .product-card {
            border: 1px solid #eee;
            padding: 15px;
            text-align: left;
            position: relative;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #990000;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 4px 8px;
            letter-spacing: 1px;
            z-index: 2;
            text-transform: uppercase;
        }

        .wishlist-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #ccc;
            font-size: 18px;
            z-index: 2;
            cursor: pointer;
        }

        .product-img-wrapper {
            /* background: #111; */
            margin: -15px -15px 15px -15px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-img {
            width: 100%;
            height: auto;
            aspect-ratio: 1/1;
            object-fit: contain;
        }

        .product-title {
            font-size: 14px;
            font-weight: 700;
            color: #111;
            margin-bottom: 4px;
        }

        .product-edition {
            font-size: 12px;
            color: #777;
            margin-bottom: 10px;
        }

        .product-icons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            color: #aaa;
            font-size: 14px;
        }

        .product-price {
            font-size: 18px;
            font-weight: 800;
            color: #111;
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 5px;
        }

        .product-price del {
            font-size: 12px;
            color: #999;
            font-weight: 400;
        }

        .product-rating {
            color: #990000;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .product-rating span {
            color: #777;
            font-size: 11px;
            margin-left: 4px;
        }

        .customize-btn {
            display: block;
            width: 100%;
            border: 1px solid #990000;
            color: #990000;
            text-align: center;
            padding: 12px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: 0.3s;
            margin-top: auto;
        }

        .customize-btn:hover {
            color: #fff;
        }

        .clear-filters {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            color: #111;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
            background: transparent;
            cursor: pointer;
        }
    </style>

    <div class="combo-catalog-page">
        @php
            $defaultComboHero = 'box.png';
            $comboBannerSrc = !empty($currentCategory?->category_banner)
                ? house_category_banner_url($currentCategory->category_banner, $defaultComboHero)
                : asset('images/banner/' . $defaultComboHero);
        @endphp
        <div class="hero-section">
            <img src="{{ $comboBannerSrc }}" class="hero-image"
                onerror="this.src='{{ asset('images/banner/' . $defaultComboHero) }}'" alt="Signature Box">
        </div>

        {{-- <div class="feature-row">
            <div class="feature-item">
                <i class="fa fa-shopping-bag feature-icon"></i>
                <div class="feature-text">
                    <span class="feature-title">Premium Products</span>
                    <span class="feature-sub">Handpicked for you</span>
                </div>
            </div>
            <div class="feature-item">
                <i class="fa fa-gift feature-icon"></i>
                <div class="feature-text">
                    <span class="feature-title">Elegant Packaging</span>
                    <span class="feature-sub">Made to impress</span>
                </div>
            </div>
            <div class="feature-item">
                <i class="fa fa-pencil feature-icon"></i>
                <div class="feature-text">
                    <span class="feature-title">Personalized Note</span>
                    <span class="feature-sub">Make it special</span>
                </div>
            </div>
            <div class="feature-item">
                <i class="fa fa-truck feature-icon"></i>
                <div class="feature-text">
                    <span class="feature-title">Safe Delivery</span>
                    <span class="feature-sub">100% Secure</span>
                </div>
            </div>
        </div> --}}

        <div class="catalog-container">
            <div class="sidebar">
                <div class="breadcrumb-bar" style="margin-bottom:20px;">
                    Home &nbsp;&gt;&nbsp; Signature Box
                </div>

                <div class="sidebar-group">
                    <div class="sidebar-title">CATEGORIES <i class="fa fa-minus"></i></div>
                    <ul class="sidebar-list">
                        <li class="filter-category active" data-val=""
                            style="cursor:pointer; color:#990000; font-weight:600;">All Signature Boxes
                            ({{ count($combos) }})</li>
                        @foreach($subcategories as $sub)
                            <li class="filter-category" data-val="{{ $sub->id }}" style="cursor:pointer;">
                                {{ $sub->subcategory_name }} ({{ $combos->where('gift_subcategory_id', $sub->id)->count() }})
                            </li>
                        @endforeach
                    </ul>
                </div>

                @php
                    $includesOptions = [];
                    foreach ($combos as $c) {
                        $inc = [];
                        if ($c->shirt_size)
                            $inc[] = 'shirt';
                        if ($c->watch_model)
                            $inc[] = 'watch';
                        if ($c->perfume_ml)
                            $inc[] = 'perfume';

                        if (count($inc) > 0) {
                            $key = implode(',', $inc);
                            $labelParts = [];
                            if ($c->shirt_size)
                                $labelParts[] = 'Shirt';
                            if ($c->watch_model)
                                $labelParts[] = 'Watch';
                            if ($c->perfume_ml)
                                $labelParts[] = 'Perfume';
                            $label = implode(' + ', $labelParts);

                            if (!isset($includesOptions[$key])) {
                                $includesOptions[$key] = ['label' => $label, 'count' => 0];
                            }
                            $includesOptions[$key]['count']++;
                        }
                    }
                @endphp

                <!--@if(count($includesOptions) > 0)-->
                <!--    <div class="sidebar-group">-->
                <!--        <div class="sidebar-title">BOX INCLUDES <i class="fa fa-minus"></i></div>-->
                <!--        <ul class="sidebar-list">-->
                <!--            @foreach($includesOptions as $val => $opt)-->
                <!--                <li><input type="checkbox" class="filter-includes" value="{{ $val }}"> {{ $opt['label'] }}-->
                <!--                    ({{ $opt['count'] }})</li>-->
                <!--            @endforeach-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--@endif-->

                <!--@if($watchSeries->count() > 0)-->
                <!--    <div class="sidebar-group">-->
                <!--        <div class="sidebar-title">WATCH SERIES <i class="fa fa-minus"></i></div>-->
                <!--        <ul class="sidebar-list">-->
                <!--            <li><input type="checkbox" class="filter-watch-all" value="" checked> All Watches</li>-->
                <!--            @foreach($watchSeries as $watch)-->
                <!--                <li><input type="checkbox" class="filter-watch" value="{{ $watch }}"> {{ $watch }}</li>-->
                <!--            @endforeach-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--@endif-->

                @if($shirtColors->count() > 0)
                    @php
                        function getColorName($hex)
                        {
                            $hex = strtolower($hex);
                            $colors = [
                                '#000000' => 'Black',
                                '#ffffff' => 'White',
                                '#ff0000' => 'Red',
                                '#008000' => 'Green',
                                '#0000ff' => 'Blue',
                                '#ffff00' => 'Yellow',
                                '#ffa500' => 'Orange',
                                '#800080' => 'Purple',
                                '#ffc0cb' => 'Pink',
                                '#808080' => 'Gray',
                                '#a52a2a' => 'Brown',
                                '#000080' => 'Navy',
                                '#808000' => 'Olive',
                                '#00ffff' => 'Cyan',
                                '#ff00ff' => 'Magenta',
                                '#c0c0c0' => 'Silver',
                                '#ffd700' => 'Gold'
                            ];
                            return $colors[$hex] ?? strtoupper($hex);
                        }
                    @endphp
                    <!--<div class="sidebar-group">-->
                    <!--    <div class="sidebar-title">COLOR <i class="fa fa-minus"></i></div>-->
                    <!--    <div class="color-swatch-list" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">-->
                    <!--        <label class="color-swatch-item"-->
                    <!--            style="cursor:pointer; display:flex; align-items:center; gap:10px;">-->
                    <!--            <input type="checkbox" class="filter-color-all" value="" checked style="display:none;">-->
                    <!--            <div class="color-circle all-color-circle"-->
                    <!--                style="width:20px; height:20px; border-radius:50%; background: conic-gradient(#ff0000, #ffff00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000); border:1px solid #ddd; transition: 0.2s;">-->
                    <!--            </div>-->
                    <!--            <span style="font-size:13px; color:#555;">All</span>-->
                    <!--        </label>-->
                    <!--        @foreach($shirtColors as $color)-->
                    <!--            <label class="color-swatch-item"-->
                    <!--                style="cursor:pointer; display:flex; align-items:center; gap:10px;">-->
                    <!--                <input type="checkbox" class="filter-color" value="{{ $color }}" style="display:none;">-->
                    <!--                @if(preg_match('/^#[0-9a-fA-F]{6}$/', $color))-->
                    <!--                    <div class="color-circle"-->
                    <!--                        style="width:20px; height:20px; border-radius:50%; background:{{ $color }}; border:1px solid #ddd; transition: 0.2s;">-->
                    <!--                    </div>-->
                    <!--                @else-->
                    <!--                    <div class="color-circle"-->
                    <!--                        style="width:20px; height:20px; border-radius:50%; background:{{ strtolower(str_replace(' ', '', $color)) }}; border:1px solid #ddd; transition: 0.2s;">-->
                    <!--                    </div>-->
                    <!--                @endif-->
                    <!--                <span style="font-size:13px; color:#555; white-space:nowrap;">{{ getColorName($color) }}</span>-->
                    <!--            </label>-->
                    <!--        @endforeach-->
                    <!--    </div>-->
                    <!--</div>-->
                    <style>
                        .color-swatch-item input:checked+.color-circle {
                            box-shadow: 0 0 0 2px #fff, 0 0 0 2px #111;
                            border-color: #111 !important;
                        }
                    </style>
                @endif

                <div class="sidebar-group">
                    <div class="sidebar-title">PRICE RANGE <i class="fa fa-minus"></i></div>
                    <div id="price-slider" style="margin: 15px 10px 25px 10px;"></div>
                    <div style="display:flex; justify-content:space-between; font-size:11px; color:#777;">
                        <span id="price-min-val">₹{{ number_format($minPrice) }}</span>
                        <span id="price-max-val">₹{{ number_format($maxPrice) }}+</span>
                    </div>
                </div>

                <!--@if(count($occasions) > 0)-->
                <!--    <div class="sidebar-group">-->
                <!--        <div class="sidebar-title">OCCASION <i class="fa fa-minus"></i></div>-->
                <!--        <ul class="sidebar-list">-->
                <!--            @foreach($occasions as $occasion)-->
                <!--                <li><input type="checkbox" class="filter-occasion" value="{{ $occasion }}"> {{ $occasion }}</li>-->
                <!--            @endforeach-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--@endif-->

                <button class="clear-filters">CLEAR FILTERS <i class="fa fa-refresh"></i></button>
            </div>

            <div style="flex:1;">
                <div class="breadcrumb-bar" style="justify-content:flex-end;">
                    <span>Showing <span id="visible-count">{{ count($combos) }}</span> of {{ count($combos) }}
                        products</span>
                    <select
                        style="border:none; outline:none; font-size:12px; font-weight:600; cursor:pointer; background:transparent;">
                        <option>Best Selling</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>

                <div class="product-grid">
                    @forelse($combos as $combo)
                        @php
                            $includes = [];
                            if ($combo->shirt_size)
                                $includes[] = 'shirt';
                            if ($combo->watch_model)
                                $includes[] = 'watch';
                            if ($combo->perfume_ml)
                                $includes[] = 'perfume';
                            $includesStr = implode(',', $includes);
                        @endphp
                        <div class="product-card" data-category="{{ $combo->gift_subcategory_id }}"
                            data-watch="{{ $combo->watch_model }}" data-color="{{ $combo->shirt_color }}"
                            data-price="{{ $combo->offer_price > 0 ? $combo->offer_price : $combo->mrp_price }}"
                            data-includes="{{ $includesStr }}" data-features="{{ $combo->features }}">
                            @if($combo->trending_collection)
                                <div class="product-badge">TRENDING</div>
                            @elseif($combo->popular_prod)
                                <div class="product-badge" style="background:#e00000;">POPULAR</div>
                            @else
                                <div class="product-badge" style="background:#111;">LIMITED</div>
                            @endif
                            <i class="fa fa-heart-o wishlist-icon"></i>
                            <div class="product-img-wrapper">
                                @if($combo->combo_image)
                                    <img src="{{ rtrim(env('MAIN_URL'), '/') }}/images/{{ $combo->combo_image }}"
                                        class="product-img" alt="{{ $combo->combo_name }}">
                                @else
                                    <img src="{{ asset('images/product/01.jpg') }}" class="product-img" alt="Box">
                                @endif
                            </div>
                            <div class="product-title">{{ $combo->combo_name }}</div>
                            <div class="product-edition">
                                {{ $combo->gift_category_name ? $combo->gift_category_name : 'Gift Box' }}</div>
                            <div class="product-icons">
                                @if($combo->shirt_size || $combo->shirt_color)<i class="fa fa-shopping-bag"
                                title="Shirt"></i>@endif
                                @if($combo->watch_model)<i class="fa fa-clock-o" title="Watch"></i>@endif
                                @if($combo->perfume_ml)<i class="fa fa-tint" title="Perfume"></i>@endif
                            </div>
                            <div class="product-price">
                                ₹{{ number_format($combo->offer_price > 0 ? $combo->offer_price : $combo->mrp_price) }}
                                @if($combo->offer_price > 0 && $combo->offer_price < $combo->mrp_price)
                                    <span
                                        style="font-size: 13px; color: #999; text-decoration: line-through; margin-left: 8px; font-weight: 500;">
                                        ₹{{ number_format($combo->mrp_price) }}
                                    </span>
                                @endif
                            </div>
                            <div class="product-rating"><span
                                    style="color: #990000; font-weight: 600; font-size: 11px; letter-spacing: 0.5px;">★
                                    SIGNATURE CURATION</span></div>
                            <a href="{{ url('combos/' . $combo->id) }}" class="customize-btn">CUSTOMIZE BOX &rarr;</a>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align:center; padding: 50px;">
                            <h3>No Combo Products Available</h3>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <style>
        .noUi-connect {
            background: #990000;
        }

        .noUi-handle {
            border-radius: 50%;
            background: #fff;
            border: 2px solid #990000;
            box-shadow: none;
            cursor: pointer;
        }

        .noUi-handle:before,
        .noUi-handle:after {
            display: none;
        }

        .noUi-horizontal .noUi-handle {
            width: 16px;
            height: 16px;
            right: -8px;
            top: -6px;
        }

        .noUi-horizontal {
            height: 4px;
            border: none;
            background: #ddd;
            box-shadow: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var currentCategory = "";
            var slider = document.getElementById('price-slider');
            noUiSlider.create(slider, {
                start: [{{ $minPrice }}, {{ $maxPrice }}],
                connect: true,
                range: { 'min': {{ $minPrice }}, 'max': {{ $maxPrice }} },
                format: { to: function (v) { return Math.round(v); }, from: function (v) { return Number(v); } }
            });

            var minVal = document.getElementById('price-min-val');
            var maxVal = document.getElementById('price-max-val');

            slider.noUiSlider.on('update', function (values, handle) {
                if (handle === 0) minVal.innerHTML = '₹' + parseInt(values[0]).toLocaleString('en-IN');
                if (handle === 1) maxVal.innerHTML = '₹' + parseInt(values[1]).toLocaleString('en-IN') + '+';
                filterProducts();
            });

            document.querySelectorAll('.filter-category').forEach(function (el) {
                el.addEventListener('click', function () {
                    document.querySelectorAll('.filter-category').forEach(function (c) {
                        c.classList.remove('active');
                        c.style.color = '';
                        c.style.fontWeight = '';
                    });
                    this.classList.add('active');
                    this.style.color = '#990000';
                    this.style.fontWeight = '600';
                    currentCategory = this.getAttribute('data-val') || "";
                    filterProducts();
                });
            });

            document.querySelectorAll('.filter-watch, .filter-color, .filter-occasion, .filter-includes').forEach(function (el) {
                el.addEventListener('change', function () {
                    if (this.classList.contains('filter-watch') && this.checked) {
                        var allW = document.querySelector('.filter-watch-all');
                        if (allW) allW.checked = false;
                    }
                    if (this.classList.contains('filter-color') && this.checked) {
                        var allC = document.querySelector('.filter-color-all');
                        if (allC) allC.checked = false;
                    }
                    filterProducts();
                });
            });

            var watchAll = document.querySelector('.filter-watch-all');
            if (watchAll) {
                watchAll.addEventListener('change', function () {
                    if (this.checked) {
                        document.querySelectorAll('.filter-watch').forEach(function (el) { el.checked = false; });
                    }
                    filterProducts();
                });
            }

            var colorAll = document.querySelector('.filter-color-all');
            if (colorAll) {
                colorAll.addEventListener('change', function () {
                    if (this.checked) {
                        document.querySelectorAll('.filter-color').forEach(function (el) { el.checked = false; });
                    }
                    filterProducts();
                });
            }

            // Initialize category from URL if present
            const urlParams = new URLSearchParams(window.location.search);
            const catFromUrl = urlParams.get('category') || urlParams.get('sub');
            if (catFromUrl) {
                const targetCat = Array.from(document.querySelectorAll('.filter-category')).find(el =>
                    (el.getAttribute('data-val') || '').toLowerCase() === catFromUrl.toLowerCase() ||
                    el.textContent.toLowerCase().includes(catFromUrl.toLowerCase())
                );
                if (targetCat) {
                    document.querySelectorAll('.filter-category').forEach(c => {
                        c.classList.remove('active');
                        c.style.color = '';
                        c.style.fontWeight = '';
                    });
                    targetCat.classList.add('active');
                    targetCat.style.color = '#990000';
                    targetCat.style.fontWeight = '600';
                    currentCategory = targetCat.getAttribute('data-val') || "";
                }
            }

            document.querySelector('.clear-filters').addEventListener('click', function () {
                document.querySelectorAll('.filter-watch, .filter-color, .filter-occasion, .filter-includes').forEach(function (el) {
                    el.checked = false;
                });
                var watchAll = document.querySelector('.filter-watch-all');
                if (watchAll) watchAll.checked = true;
                var colorAll = document.querySelector('.filter-color-all');
                if (colorAll) colorAll.checked = true;

                if (slider && slider.noUiSlider) {
                    slider.noUiSlider.set([{{ $minPrice }}, {{ $maxPrice }}]);
                }

                // Clean URL while retaining active category
                if (window.history.replaceState) {
                    var newUrl = window.location.pathname + (currentCategory ? '?category=' + encodeURIComponent(currentCategory) : '');
                    window.history.replaceState(null, '', newUrl);
                }

                filterProducts();
            });

            function filterProducts() {
                var watchChecked = Array.from(document.querySelectorAll('.filter-watch:checked')).map(function (el) { return el.value; });
                var colorChecked = Array.from(document.querySelectorAll('.filter-color:checked')).map(function (el) { return el.value; });
                var occasionChecked = Array.from(document.querySelectorAll('.filter-occasion:checked')).map(function (el) { return el.value; });
                var includesChecked = Array.from(document.querySelectorAll('.filter-includes:checked')).map(function (el) { return el.value.split(','); });

                var priceValues = slider.noUiSlider.get();
                var minP = parseInt(priceValues[0]);
                var maxP = parseInt(priceValues[1]);

                var visibleCount = 0;
                document.querySelectorAll('.product-card').forEach(function (card) {
                    var category = card.getAttribute('data-category') || "";
                    var watch = card.getAttribute('data-watch') || "";
                    var color = card.getAttribute('data-color') || "";
                    var price = parseInt(card.getAttribute('data-price')) || 0;
                    var includesArr = (card.getAttribute('data-includes') || "").split(',');
                    var features = card.getAttribute('data-features') || "";

                    var show = true;
                    if (currentCategory !== "" && category !== currentCategory) show = false;
                    if (watchChecked.length > 0 && !watchChecked.includes(watch)) show = false;
                    if (colorChecked.length > 0 && !colorChecked.includes(color)) show = false;

                    if (includesChecked.length > 0) {
                        var hasIncludes = false;
                        includesChecked.forEach(function (reqItems) {
                            var allReqPresent = true;
                            if (reqItems.length !== includesArr.length || (includesArr.length === 1 && includesArr[0] === "")) {
                                allReqPresent = false;
                            } else {
                                reqItems.forEach(function (item) {
                                    if (!includesArr.includes(item)) allReqPresent = false;
                                });
                            }
                            if (allReqPresent) hasIncludes = true;
                        });
                        if (!hasIncludes) show = false;
                    }

                    if (occasionChecked.length > 0) {
                        var hasOccasion = false;
                        occasionChecked.forEach(function (occ) {
                            if (features.includes(occ)) hasOccasion = true;
                        });
                        if (!hasOccasion) show = false;
                    }

                    if (price < minP || price > maxP) show = false;

                    card.style.display = show ? 'flex' : 'none';
                    if (show) visibleCount++;
                });

                var vCountEl = document.getElementById('visible-count');
                if (vCountEl) vCountEl.innerText = visibleCount;
            }
        });
    </script>

@endsection