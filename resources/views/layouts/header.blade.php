@php
    $hasQuantity = fn($item) => (is_array($item) ? (int) ($item['quantity'] ?? 0) : (int) $item) > 0;
    $validProductCount = fn($items) => collect($items)->filter($hasQuantity)->count();
    $cartCount = $validProductCount(session('cart', []));
    $wishlistCount = $validProductCount(session('wishlist', []));
    $headerCategories = \App\Models\Category::query()->from('categories as c')
        ->whereNotNull('c.category_name')
        ->where('c.category_name', '!=', '')
        ->orderBy('c.id')
        ->get(['c.id', 'c.category_name', 'c.category_image', 'c.category_icon']);
    $headerCatCount = $headerCategories->count();
    $successType = session('success_type');
    $successActionUrl = $successType === 'wishlist' ? url('wishlist') : url('cart');
    $successActionText = $successType === 'wishlist' ? 'View Wishlist' : 'View Cart';
    $successTitle = $successType === 'wishlist' ? 'Added to wishlist' : 'Added to cart';
    $successIcon = $successType === 'wishlist' ? 'fa-heart' : 'fa-shopping-cart';
    $activeNav = match (true) {
        request()->path() === '/' => 'home',
        request()->is('about') => 'about',
        request()->is('shop', 'single-product', 'combos', 'cart', 'wishlist', 'checkout*') => 'shop',
        request()->is('blog', 'blog/*') => 'blog',
        request()->is('bulk-order') => 'bulk-order',
        request()->is('contact') => 'contact',
        default => null,
    };
    $today = now()->toDateString();
    $activeCoupons = \App\Models\Coupon::query()
        ->where(function ($query) use ($today) {
            $query->whereNull('start_date')->orWhereDate('start_date', '<=', $today);
        })
        ->where(function ($query) use ($today) {
            $query->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
        })
        ->orderByDesc('id')
        ->get();

    try {
        if (!class_exists(\App\Models\ScrollingBar::class) && file_exists(app_path('Models/ScrollingBar.php'))) {
            require_once app_path('Models/ScrollingBar.php');
        }
        $announcementSlides = \Illuminate\Support\Facades\DB::table('scrolling_bars')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    } catch (\Throwable $e) {
        $announcementSlides = collect([
            (object)['message' => 'FREE SHIPPING ON ALL ORDERS', 'icon' => 'fa fa-truck', 'link' => null],
            (object)['message' => 'IGNITE YOUR PRESENCE', 'icon' => 'fa fa-fire', 'link' => null],
            (object)['message' => 'BEST QUALITY AVAILABLE', 'icon' => 'fa fa-gift', 'link' => null],
        ]);
    }
@endphp

<header class="header-area header-wrapper{{ request()->is('/') ? ' is-home-header' : '' }}"
    style="{{ request()->is('/') ? 'position:absolute;top:0;left:0;width:100%;background:transparent;z-index:9999;' : '' }}">
    <style>
        .header-area.header-wrapper {
            background: #fff;
            position: relative;
            width: 100%;
            z-index: 9999;
        }
        .is-home-header .knp-premium-topbar:first-child {
            /* keep top bar red */
        }
        /* On home page the main nav background stays transparent until scroll */
        .is-home-header #sticky-header {
            background: transparent !important;
        }

        #sticky-header {
            position: relative;
            z-index: 9998;
        }

        .knp-main-navigation.sticky {
            background-color: #ffffff !important;
            box-shadow: none !important;
            left: auto !important;
            position: relative !important;
            top: auto !important;
            width: 100% !important;
            z-index: 9999;
        }

        /* Allow category mega-menu to overflow outside the nav row */
        #sticky-header,
        #sticky-header .container,
        #sticky-header .row,
        #sticky-header .col-lg-6,
        #sticky-header .col-md-6 {
            overflow: visible !important;
        }

        @media (min-width: 768px) {
            .header-wrapper .knp-header-desktop-row>.col-md-3 {
                display: block !important;
                flex: 0 0 25% !important;
                width: 25% !important;
            }

            .header-wrapper .knp-header-desktop-row>.col-md-6 {
                display: block !important;
                flex: 0 0 50% !important;
                width: 50% !important;
            }

            .header-wrapper .knp-main-navigation .knp-logo-column {
                display: block !important;
                flex: 0 0 25% !important;
                width: 25% !important;
            }

            .header-wrapper .knp-main-navigation .col-lg-7,
            .header-wrapper .knp-main-navigation .col-md-6.d-md-block {
                display: block !important;
                flex: 0 0 50% !important;
                width: 50% !important;
            }

            /* Search bar column - right side of navbar */
            .header-wrapper .knp-main-navigation .col-lg-3.col-md-3 {
                display: flex !important;
                flex: 0 0 25% !important;
                width: 25% !important;
                align-items: center;
                justify-content: flex-end;
            }

            .header-wrapper .knp-main-navigation .d-md-none {
                display: none !important;
            }
        }

        .offcanvas-backdrop {
            z-index: 100000;
        }

        .header-nav-link {
            border-bottom: 2px solid transparent;
            color: #2e2e2e;
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            padding-bottom: 4px;
            text-decoration: none;
            text-transform: uppercase;
            transition: color .3s ease, border-color .3s ease;
        }

        .header-nav-link:hover,
        .header-nav-link.active {
            border-bottom-color: #cc0000;
            color: #cc0000;
        }

        .header-nav-link.active {
            font-weight: 700;
        }

        /* Category Dropdown */
        .knp-cat-dropdown-wrap {
            /* No relative positioning here so the child menu can span full width */
            display: inline-flex;
            align-items: center;
        }

        .knp-cat-toggle {
            align-items: center;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            color: #2e2e2e;
            cursor: pointer;
            display: inline-flex;
            font-size: 12px;
            font-weight: 600;
            gap: 5px;
            letter-spacing: 2px;
            padding: 0 0 4px 0;
            text-transform: uppercase;
            transition: color .3s ease, border-color .3s ease;
        }

        .knp-cat-toggle:hover,
        .knp-cat-toggle.active {
            border-bottom-color: #cc0000;
            color: #cc0000;
        }

        .knp-cat-toggle .knp-cat-arrow {
            display: inline-block;
            font-size: 9px;
            transition: transform .25s ease;
        }

        .knp-cat-dropdown-wrap.open .knp-cat-arrow {
            transform: rotate(180deg);
        }

        /* Compact category panel, centred below the navigation */
        .knp-cat-menu-panel {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
            display: none;
            left: 0;
            right: 0;
            margin: 0 auto;
            padding: 16px 30px;
            position: absolute;
            top: 100%;
            /* Just below header, without gap */
            width: min(calc(100% - 30px), 780px);
            max-width: none;
            z-index: 99999;
        }

        .knp-cat-dropdown-wrap.open .knp-cat-menu-panel {
            display: block;
            animation: knpCatFadeIn .2s ease-out;
        }

        .knp-cat-menu {
            display: flex;
            flex-wrap: wrap;
            gap: 16px clamp(10px, 2vw, 30px);
            justify-content: center;
            list-style: none;
            margin: 0 auto;
            max-width: 720px;
            padding: 0;
            width: 100%;
        }

        @keyframes knpCatFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Each category card */
        .knp-cat-menu li {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 0 0 clamp(90px, 8vw, 120px);
            gap: 0;
        }

        .knp-cat-menu li a {
            align-items: center;
            border-radius: 8px;
            color: #222;
            display: flex;
            flex-direction: column;
            font-size: 10px;
            font-weight: 600;
            gap: 5px;
            letter-spacing: .8px;
            padding: 2px 4px;
            text-align: center;
            text-decoration: none;
            text-transform: uppercase;
            transition: background .18s, color .18s;
            width: 100%;
        }

        .knp-cat-menu li a:hover,
        .knp-cat-menu li a.active {
            background: #fff5f5;
            color: #cc0000;
        }

        .knp-cat-img-wrap {
            align-items: center;
            background: #fdfdfd;
            border: 1px solid #eaeaea;
            border-radius: 50%;
            display: flex;
            height: 80px;
            /* Slightly larger for mega-menu */
            justify-content: center;
            overflow: hidden;
            transition: border-color .3s ease, box-shadow .3s ease, transform .3s ease;
            width: 80px;
            /* Slightly larger for mega-menu */
        }

        .knp-cat-menu li a:hover .knp-cat-img-wrap,
        .knp-cat-menu li a.active .knp-cat-img-wrap {
            border-color: #cc0000;
            box-shadow: 0 4px 15px rgba(204, 0, 0, .15);
            transform: translateY(-3px);
        }

        .knp-cat-img-wrap img {
            height: 100%;
            object-fit: contain;
            /* Prevents image from being cut off */
            width: 100%;
        }

        .knp-cat-img-wrap .knp-cat-placeholder {
            color: #bbb;
            font-size: 24px;
        }

        .knp-cat-label {
            display: block;
            line-height: 1.3;
            max-width: 90px;
            word-break: break-word;
        }

        .mobile-nav-link {
            color: #efefef;
            display: block;
            font-size: 13px;
            letter-spacing: 2px;
            text-decoration: none;
            text-transform: uppercase;
        }

        .mobile-nav-link.active {
            color: #cc0000;
            font-weight: 700;
        }

        .knp-mobile-nav-list li {
            display: block;
            width: 100%;
        }

        .knp-mobile-drawer {
            background: #fff;
            color: #1a1a1a;
            width: min(82vw, 310px) !important;
            z-index: 100001;
        }

        .knp-mobile-drawer .offcanvas-header {
            align-items: center;
            /* border-bottom: 1px solid #eee; */
            padding: 18px 18px 14px;
        }

        .knp-drawer-actions {
            align-items: center;
            display: flex;
            gap: 20px;
        }

        .knp-drawer-logo {
            display: inline-flex;
            text-decoration: none;
        }

        .knp-drawer-logo img {
            height: 52px;
            object-fit: contain;
            width: auto;
        }

        .knp-drawer-action {
            color: #1a1a1a;
            display: inline-flex;
            font-size: 18px;
            position: relative;
            text-decoration: none;
        }

        .knp-drawer-count {
            align-items: center;
            background: #cc0000;
            border: 2px solid #fff;
            border-radius: 50%;
            color: #fff;
            display: inline-flex;
            font-size: 8px;
            font-weight: 800;
            height: 17px;
            justify-content: center;
            position: absolute;
            right: -10px;
            top: -10px;
            width: 17px;
        }

        .knp-drawer-close {
            align-items: center;
            background: #111111;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            color: #ffffff;
            display: inline-flex;
            font-size: 15px;
            height: 36px;
            width: 36px;
            min-height: 36px;
            min-width: 36px;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .knp-drawer-close:hover {
            background: #cc0000;
            border-color: #cc0000;
            color: #ffffff;
            transform: rotate(90deg);
        }

        .knp-mobile-drawer .offcanvas-body {
            overflow-y: auto;
            padding: 16px 18px 24px;
        }

        .knp-drawer-account-links {
            align-items: center;
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .knp-drawer-account-links a,
        .knp-drawer-account-links button {
            align-items: center;
            background: #fff5f5;
            border: 1px solid rgba(204, 0, 0, .22);
            border-radius: 5px;
            color: #222;
            display: inline-flex;
            font-size: 10px;
            font-weight: 800;
            gap: 6px;
            justify-content: center;
            min-height: 36px;
            padding: 8px 10px;
            text-decoration: none;
            text-transform: uppercase;
            width: 50%;
        }

        .knp-drawer-account-links i {
            color: #cc0000;
        }

        .knp-drawer-promo {
            background: #1a1a1a;
            border-left: 3px solid #cc0000;
            border-radius: 4px;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .8px;
            line-height: 1.5;
            margin-bottom: 7px;
            padding: 9px 10px;
            text-transform: uppercase;
        }

        .knp-drawer-promo i {
            color: #cc0000;
            margin-right: 6px;
        }

        .knp-mobile-offer {
            overflow: hidden;
            white-space: nowrap;
            width: 100%;
        }

        .knp-mobile-offer-track {
            align-items: center;
            animation: knpMobileOfferTicker 14s linear infinite;
            display: flex;
            width: max-content;
            will-change: transform;
        }

        .knp-mobile-offer-item {
            color: #fff;
            flex: 0 0 auto;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            padding-right: 55px;
            text-transform: uppercase;
        }

        .knp-mobile-offer-item i {
            margin-right: 6px;
        }

        @keyframes knpMobileOfferTicker {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .knp-drawer-search {
            align-items: center;
            border: 1px solid #e6e6e6;
            border-radius: 5px;
            display: flex;
            margin: 14px 0;
            overflow: hidden;
        }

        .knp-drawer-search input {
            border: 0;
            font-size: 12px;
            min-width: 0;
            outline: none;
            padding: 12px 10px;
            width: 100%;
        }

        .knp-drawer-search button {
            background: transparent;
            border: 0;
            color: #111;
            font-size: 18px;
            padding: 9px 12px;
        }

        .knp-drawer-menu,
        .knp-drawer-menu li {
            display: block !important;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .knp-drawer-menu > li > a {
            align-items: center;
            border: 1px solid #e8e8e8;
            border-radius: 6px;
            color: #222;
            display: flex;
            font-size: 12px;
            font-weight: 600;
            gap: 11px;
            margin-bottom: 6px;
            padding: 11px 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .knp-drawer-menu > li > a i {
            color: #cc0000;
            text-align: center;
            width: 15px;
        }

        .knp-drawer-menu > li > a.active {
            background: #fff5f5;
            border-color: rgba(204, 0, 0, .35);
            color: #cc0000;
        }

        @media (max-width: 767.98px) {
            .header-wrapper .container {
                max-width: none;
                padding-left: 16px;
                padding-right: 16px;
                width: 100%;
            }

            .knp-header-top {
                display: block !important;
                padding: 8px 0 !important;
            }

            .knp-header-desktop-row {
                display: none !important;
            }

            .knp-header-top .row,
            .knp-main-navigation .row {
                --bs-gutter-x: 16px;
            }

            .knp-account-links {
                align-items: center;
                flex-wrap: nowrap;
                gap: 10px !important;
            }

            .knp-account-links a,
            .knp-account-links button {
                font-size: 9px !important;
                letter-spacing: .8px !important;
                white-space: nowrap;
            }

            .knp-header-actions {
                gap: 12px !important;
            }

            .knp-header-actions a {
                font-size: 9px !important;
                gap: 5px !important;
                white-space: nowrap;
            }

            .knp-main-navigation {
                padding: 10px 0 !important;
            }

            .knp-main-navigation .row {
                min-height: 52px;
                position: relative;
            }

            .knp-logo-column {
                left: 50%;
                position: absolute;
                text-align: center;
                top: 50%;
                transform: translate(-50%, -50%);
                width: auto;
            }

            .knp-header-logo {
                height: 46px !important;
                max-width: 120px;
                width: auto;
            }

            .knp-mobile-toggle {
                align-items: center;
                display: inline-flex;
                height: 40px;
                justify-content: center;
                padding: 0 !important;
                width: 44px;
            }

            .knp-mobile-header-actions {
                align-items: center;
                display: flex !important;
                gap: 18px;
                justify-content: flex-end;
                padding-right: 10px;
            }

            .knp-mobile-header-action {
                color: #171717;
                display: inline-flex;
                font-size: 19px;
                position: relative;
                text-decoration: none;
            }

            .knp-mobile-header-action .knp-drawer-count {
                right: -10px;
                top: -9px;
            }
        }

        @media (min-width: 768px) {
            .knp-mobile-nav {
                display: none !important;
            }
        }

        .coupon-ticker {
            display: block;
            min-height: 18px;
            overflow: hidden;
            position: relative;
            white-space: nowrap;
            width: 100%;
        }

        .coupon-ticker-track {
            display: inline-flex;
            gap: 0;
            transform: translateX(0);
            width: max-content;
            will-change: transform;
            animation: couponTickerRightToLeft 10s linear infinite;
        }

        .coupon-ticker-item {
            color: rgba(255, 255, 255, 0.95);
            flex: 0 0 auto;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            padding-right: 90px;
            text-transform: uppercase;
        }

        .coupon-ticker+span {
            display: none !important;
        }

        @keyframes couponTickerRightToLeft {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-25%);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .knp-mobile-offer-track {
                animation: none;
            }

            .knp-mobile-offer-item:nth-child(n+2) {
                display: none;
            }

            .coupon-ticker-track {
                animation: none;
                justify-content: center;
                transform: none;
                width: 100%;
            }

            .coupon-ticker-item {
                padding-right: 0;
            }

            .coupon-ticker-item:nth-child(n+2) {
                display: none;
            }
        }

        .knp-user-menu-item {
            position: relative;
            z-index: 10010;
        }

        .knp-user-menu,
        .knp-user-menu form {
            margin: 0;
        }

        .knp-user-menu summary {
            align-items: center;
            color: rgba(255, 255, 255, .95);
            cursor: pointer;
            display: inline-flex;
            font-size: 12px;
            gap: 7px;
            letter-spacing: 1.5px;
            list-style: none;
            padding: 0;
            text-transform: uppercase;
            user-select: none;
        }

        .knp-user-menu summary::-webkit-details-marker {
            display: none;
        }

        .knp-user-menu-arrow {
            transition: transform .2s ease;
        }

        .knp-user-menu[open] .knp-user-menu-arrow {
            transform: rotate(180deg);
        }

        .knp-user-dropdown {
            background: #fff;
            border: 1px solid #e7e7e7;
            border-radius: 7px;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .22);
            left: 0;
            min-width: 175px;
            overflow: hidden;
            padding: 6px;
            position: absolute;
            top: calc(100% + 13px);
            z-index: 10020;
        }

        .knp-user-dropdown a,
        .knp-user-dropdown button {
            align-items: center;
            background: #fff;
            border: 0;
            border-radius: 5px;
            color: #222;
            display: flex;
            font-size: 11px;
            font-weight: 700;
            gap: 9px;
            letter-spacing: 1px;
            padding: 11px 12px;
            text-align: left;
            text-decoration: none;
            text-transform: uppercase;
            width: 100%;
        }

        .knp-user-dropdown a:hover,
        .knp-user-dropdown button:hover {
            background: #f4f4f4;
            color: #cc0000;
        }

        /* Neutral quantity controls across product, cart and wishlist pages. */
        .cart-plus-minus .qtybutton,
        .cart-plus-minus .qtybutton:hover,
        .cart-plus-minus .qtybutton:focus,
        .cart-plus-minus .qtybutton:active {
            background: #fff !important;
            border-color: #d5d5d5 !important;
            box-shadow: none !important;
            color: #555 !important;
            outline: none !important;
        }

        .cart-plus-minus .qtybutton:hover {
            background: #f3f3f3 !important;
            color: #111 !important;
        }

        .cart-plus-minus .qtybutton[aria-disabled="true"] {
            background: #f7f7f7 !important;
            color: #aaa !important;
        }

        .knp-coupon-only-bar {
            align-items: center;
            background: #292929 !important;
            border: 0 !important;
            display: flex;
            height: 40px;
            overflow: hidden;
            padding: 0 42px 0 0 !important;
            position: relative;
            width: 100%;
        }

        .knp-coupon-only-bar .coupon-ticker {
            min-height: 40px;
            width: 100%;
        }

        .knp-coupon-only-bar .coupon-ticker-track {
            align-items: center;
            animation-duration: 24s;
            height: 40px;
        }

        .knp-coupon-only-bar .coupon-ticker-item {
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0;
            padding-right: clamp(70px, 11vw, 220px);
            text-transform: none;
        }

        .knp-coupon-close {
            align-items: center;
            background: transparent;
            border: 0;
            color: rgba(255, 255, 255, .86);
            cursor: pointer;
            display: flex;
            font-size: 18px;
            height: 40px;
            justify-content: center;
            padding: 0;
            position: absolute;
            right: 12px;
            top: 0;
            width: 28px;
            z-index: 2;
        }

        @media(max-width:767px) {
            .knp-coupon-only-bar {
                height: 34px;
                padding-right: 34px !important;
            }

            .knp-coupon-only-bar .coupon-ticker,
            .knp-coupon-only-bar .coupon-ticker-track {
                height: 34px;
                min-height: 34px;
            }

            .knp-coupon-only-bar .coupon-ticker-item {
                font-size: 10px;
                padding-right: 58px;
            }

            .knp-coupon-close {
                height: 34px;
                right: 5px;
            }
        }

        .knp-reference-menu-toggle {
            background: transparent;
            border: 0;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 5px;
            justify-content: center;
            left: 18px;
            padding: 10px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 12;
        }

        .knp-reference-menu-toggle span {
            background: #000;
            display: block;
            height: 1.5px;
            width: 21px;
        }

        .knp-reference-drawer-backdrop {
            background: rgba(0, 0, 0, .26);
            inset: 0;
            position: fixed;
            z-index: 100040;
        }

        .knp-reference-drawer {
            background: #fff;
            bottom: 0;
            box-shadow: 12px 0 38px rgba(0, 0, 0, .12);
            color: #111;
            display: flex;
            flex-direction: column;
            left: 0;
            max-width: calc(100vw - 18px);
            position: fixed;
            top: 0;
            transform: translateX(-102%);
            transition: transform .38s cubic-bezier(.22, .75, .22, 1);
            width: 625px;
            z-index: 100050;
        }

        .knp-reference-drawer.is-open {
            transform: translateX(0);
        }

        .knp-reference-drawer-head {
            align-items: center;
            border-bottom: 1px solid #ddd;
            display: flex;
            height: 82px;
            justify-content: space-between;
            padding: 0 40px;
        }

        .knp-reference-drawer-head>span {
            font-size: 24px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .knp-reference-drawer-close {
            background: transparent;
            border: 0;
            cursor: pointer;
            height: 44px;
            position: relative;
            width: 44px;
        }

        .knp-reference-drawer-close span {
            background: #222;
            height: 1.5px;
            left: 7px;
            position: absolute;
            top: 21px;
            width: 34px;
        }

        .knp-reference-drawer-close span:first-child {
            transform: rotate(45deg);
        }

        .knp-reference-drawer-close span:last-child {
            transform: rotate(-45deg);
        }

        .knp-reference-drawer-nav {
            overflow-y: auto;
            padding: 30px 40px;
        }

        .knp-reference-drawer-nav>a,
        .knp-reference-drawer-nav summary {
            align-items: center;
            border-bottom: 1px solid #d8d8d8;
            color: #111;
            cursor: pointer;
            display: flex;
            font-size: 17px;
            justify-content: space-between;
            letter-spacing: 3px;
            list-style: none;
            padding: 20px 0;
            text-decoration: none;
            text-transform: uppercase;
        }

        .knp-reference-drawer-nav summary::-webkit-details-marker {
            display: none;
        }

        .knp-reference-drawer-nav summary span {
            height: 20px;
            position: relative;
            width: 20px;
        }

        .knp-reference-drawer-nav summary span:before,
        .knp-reference-drawer-nav summary span:after {
            background: #333;
            content: '';
            height: 1.5px;
            left: 0;
            position: absolute;
            top: 9px;
            width: 20px;
        }

        .knp-reference-drawer-nav summary span:after {
            transform: rotate(90deg);
            transition: transform .2s;
        }

        .knp-reference-drawer-nav details[open] summary span:after {
            transform: rotate(0);
        }

        .knp-reference-product-links {
            background: #f8f8f8;
            display: grid;
            grid-template-columns: 1fr 1fr;
            padding: 10px 18px 16px;
        }

        .knp-reference-product-links a {
            color: #333;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 9px 5px;
            text-decoration: none;
            text-transform: uppercase;
        }

        .knp-reference-drawer-footer {
            display: flex;
            flex-direction: column;
            gap: 30px;
            margin-top: auto;
            padding: 25px 40px 34px;
        }

        .knp-reference-drawer-footer a,
        .knp-reference-drawer-footer span {
            color: #111;
            font-size: 17px;
            letter-spacing: .5px;
            text-decoration: none;
        }

        .knp-reference-drawer-footer span {
            letter-spacing: 3px;
        }

        body.knp-drawer-open {
            overflow: hidden;
        }

        @media(max-width:767px) {
            .knp-reference-menu-toggle {
                left: 5px
            }

            .knp-reference-drawer {
                width: 100%
            }

            .knp-reference-drawer-head {
                height: 70px;
                padding: 0 24px
            }

            .knp-reference-drawer-head>span {
                font-size: 20px
            }

            .knp-reference-drawer-nav {
                padding: 20px 24px
            }

            .knp-reference-drawer-nav>a,
            .knp-reference-drawer-nav summary {
                font-size: 14px;
                padding: 17px 0
            }

            .knp-reference-drawer-footer {
                padding: 20px 24px 28px
            }

            .knp-main-navigation .col-3.d-block.d-md-none.text-start {
                display: none !important
            }
        }

        @media (min-width: 768px) {
            .knp-main-navigation>.container>.row {
                justify-content: space-between;
            }
        }

        .knp-reference-actions {
            align-items: center;
            display: flex;
            gap: 22px;
            margin-left: auto;
            padding: 0 18px;
            position: relative;
            z-index: 15;
        }

        .knp-reference-icon-button {
            align-items: center;
            background: transparent;
            border: 0;
            color: #111;
            cursor: pointer;
            display: inline-flex;
            height: 42px;
            justify-content: center;
            padding: 0;
            position: relative;
            text-decoration: none;
            width: 30px;
        }

        .knp-reference-icon-button i {
            font-size: 25px;
            font-weight: 300;
            line-height: 1;
        }

        .knp-reference-actions .fa-search {
            font-size: 26px;
            -webkit-text-stroke: .3px currentColor;
        }

        .knp-reference-actions .fa-user-o,
        .knp-reference-actions .fa-user {
            border: 1.5px solid currentColor;
            border-radius: 50%;
            font-size: 20px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            width: 30px;
        }

        .knp-reference-actions .fa-shopping-bag {
            font-size: 24px;
        }

        .knp-reference-bag>span {
            align-items: center;
            background: #b00012;
            border-radius: 50%;
            color: #fff;
            display: flex;
            font-size: 8px;
            font-weight: 700;
            height: 15px;
            justify-content: center;
            position: absolute;
            right: -3px;
            top: 3px;
            width: 15px;
        }

        .knp-reference-search-wrap {
            position: relative;
        }

        .knp-reference-search-popover {
            align-items: center;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .13);
            display: flex;
            opacity: 0;
            padding: 7px;
            pointer-events: none;
            position: absolute;
            right: 0;
            top: calc(100% + 9px);
            transform: translateY(-7px);
            transition: .2s ease;
            visibility: hidden;
            width: 270px;
        }

        .knp-reference-search-wrap.is-open .knp-reference-search-popover {
            opacity: 1;
            pointer-events: auto;
            transform: none;
            visibility: visible;
        }

        .knp-reference-search-popover input {
            border: 0;
            color: #222;
            flex: 1;
            font-size: 13px;
            min-width: 0;
            outline: 0;
            padding: 9px 10px;
        }

        .knp-reference-search-popover button {
            background: #111;
            border: 0;
            color: #fff;
            cursor: pointer;
            height: 36px;
            width: 40px;
        }

        .knp-reference-search-popover button i {
            font-size: 14px;
        }

        .knp-mobile-header-actions {
            display: none !important;
        }

        @media(max-width:767px) {
            .knp-reference-actions {
                gap: 13px;
                padding: 0 7px
            }

            .knp-reference-icon-button {
                width: 27px
            }

            .knp-reference-icon-button i {
                font-size: 21px
            }

            .knp-reference-actions .fa-search {
                font-size: 22px
            }

            .knp-reference-search-popover {
                position: fixed;
                right: 12px;
                top: 108px;
                width: calc(100vw - 24px)
            }
        }

        .knp-main-navigation>.container>.row {
            position: relative;
        }

        .knp-reference-actions {
            margin-left: auto !important;
        }

        .knp-reference-actions .fa-heart-o {
            font-size: 27px;
        }

        .knp-reference-wishlist>span {
            align-items: center;
            background: #8b4938;
            border-radius: 50%;
            color: #fff;
            display: flex;
            font-size: 8px;
            font-weight: 700;
            height: 15px;
            justify-content: center;
            position: absolute;
            right: -4px;
            top: 2px;
            width: 15px;
        }

        @media(min-width:768px) {
            .knp-reference-actions {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%)
            }
        }

        /* Final explicit header alignment: left menu/logo, right actions. */
        .knp-main-navigation>.container {
            max-width: none !important;
            padding-left: 28px !important;
            padding-right: 28px !important;
            width: 100% !important;
        }

        .knp-reference-header-row {
            align-items: center;
            display: flex !important;
            min-height: 100px;
            position: relative;
            width: 100%;
        }

        .knp-reference-header-row .knp-reference-menu-toggle {
            flex: 0 0 auto;
            left: auto !important;
            margin: 0 15px 0 0;
            order: 1;
            position: relative !important;
            top: auto !important;
            transform: none !important;
        }

        .knp-reference-header-row .knp-logo-column {
            display: flex !important;
            flex: 0 0 auto !important;
            left: auto !important;
            margin: 0 !important;
            max-width: none !important;
            order: 1;
            position: relative !important;
            text-align: left !important;
            top: auto !important;
            transform: none !important;
            width: auto !important;
        }

        .knp-reference-header-row .knp-header-logo {
            height: 68px !important;
            max-height: 68px !important;
            width: auto !important;
        }

        .knp-reference-header-row .knp-reference-actions {
            flex: 0 0 auto;
            margin-left: auto !important;
            order: 3;
            padding: 0 !important;
            position: relative !important;
            right: auto !important;
            top: auto !important;
            transform: none !important;
        }

        .knp-reference-header-row>.col-3,
        .knp-reference-header-row>.knp-mobile-header-actions {
            display: none !important;
        }

        @media(max-width:767px) {
            .knp-main-navigation>.container {
                padding-left: 12px !important;
                padding-right: 12px !important
            }

            .knp-reference-header-row {
                min-height: 68px
            }

            .knp-reference-header-row .knp-reference-menu-toggle {
                margin-right: 7px
            }

            .knp-reference-header-row .knp-header-logo {
                height: 48px !important;
                max-height: 48px !important
            }

            .knp-reference-header-row .knp-reference-actions {
                gap: 9px !important
            }

            .knp-reference-header-row .knp-reference-icon-button {
                width: 25px !important
            }
        }

        /* Full-screen category search overlay. */
        .knp-reference-search-popover {
            background: #fff !important;
            bottom: 0;
            box-shadow: none !important;
            display: block;
            left: 0;
            opacity: 0;
            overflow-y: auto;
            padding: 62px 24px 48px !important;
            pointer-events: none;
            position: fixed;
            right: 0 !important;
            top: 0 !important;
            transform: translateY(-18px) !important;
            transition: opacity .22s ease, transform .22s ease, visibility .22s;
            visibility: hidden;
            width: 100% !important;
            z-index: 10050;
        }

        .knp-reference-search-wrap.is-open .knp-reference-search-popover {
            opacity: 1;
            pointer-events: auto;
            transform: none !important;
            visibility: visible;
        }

        body.knp-search-open {
            overflow: hidden;
        }

        .knp-search-inner {
            margin: 0 auto;
            max-width: 1230px;
        }

        .knp-search-form-row {
            align-items: center;
            display: flex;
            gap: 22px;
            margin: 0 auto 38px;
            max-width: 850px;
        }

        .knp-search-form {
            align-items: center;
            border: 1px solid #222;
            display: flex;
            flex: 1;
            height: 62px;
        }

        .knp-search-form>i {
            color: #222;
            font-size: 24px;
            margin-left: 25px;
        }

        .knp-search-form input {
            background: #fff !important;
            border: 0 !important;
            color: #222 !important;
            flex: 1;
            font-size: 18px !important;
            height: 60px !important;
            min-width: 0;
            outline: 0;
            padding: 0 16px !important;
        }

        .knp-search-form input::placeholder {
            color: #999;
            opacity: 1;
        }

        .knp-search-form button {
            background: transparent !important;
            border: 0 !important;
            color: #222 !important;
            cursor: pointer;
            font-size: 15px;
            height: 60px !important;
            padding: 0 22px;
            width: auto !important;
        }

        .knp-reference-search-popover .knp-search-close {
            background: transparent !important;
            border: 0 !important;
            color: #222 !important;
            cursor: pointer;
            flex: 0 0 42px;
            height: 42px !important;
            padding: 0 !important;
            position: relative;
            width: 42px !important;
        }

        .knp-search-close::before,
        .knp-search-close::after {
            background: #222;
            content: "";
            height: 1px;
            left: 5px;
            position: absolute;
            top: 20px;
            width: 32px;
        }

        .knp-search-close::before {
            transform: rotate(45deg);
        }

        .knp-search-close::after {
            transform: rotate(-45deg);
        }

        .knp-search-heading {
            color: #111;
            font-size: 24px;
            font-weight: 400;
            letter-spacing: 4px;
            margin: 0 0 20px;
            text-align: center;
            text-transform: uppercase;
        }

        .knp-search-category-grid {
            display: grid;
            gap: 28px 20px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .knp-search-category-card {
            color: #111;
            display: block;
            text-align: center;
            text-decoration: none !important;
        }

        .knp-search-category-image {
            width: 100%;
            aspect-ratio: 1/1;
            background: #f5f3f3;
            display: block;
            overflow: hidden;
            position: relative;
        }

        .knp-search-category-image img {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .knp-search-category-card:hover .knp-search-category-image img {
            transform: scale(1.035);
        }

        .knp-search-category-name {
            display: block;
            font-size: 18px;
            margin-top: 8px;
            text-transform: uppercase;
        }

        .knp-search-empty {
            color: #777;
            grid-column: 1/-1;
            text-align: center;
        }

        @media(max-width:767px) {
            .knp-reference-search-popover {
                bottom: 0 !important;
                left: 0 !important;
                padding: 24px 15px 36px !important;
                right: 0 !important;
                top: 0 !important;
                transform: translateY(-18px) !important;
                width: 100% !important;
            }

            .knp-reference-search-wrap.is-open .knp-reference-search-popover {
                transform: none !important;
            }

            .knp-search-form-row {
                gap: 8px;
                margin-bottom: 28px;
            }

            .knp-search-form {
                height: 50px;
            }

            .knp-search-form>i {
                font-size: 18px;
                margin-left: 14px;
            }

            .knp-search-form input {
                font-size: 15px !important;
                height: 48px !important;
                padding: 0 10px !important;
            }

            .knp-search-form button {
                height: 48px !important;
                padding: 0 12px;
            }

            .knp-reference-search-popover .knp-search-close {
                flex-basis: 36px;
                height: 36px !important;
                width: 36px !important;
            }

            .knp-search-close::before,
            .knp-search-close::after {
                left: 5px;
                top: 17px;
                width: 27px;
            }

            .knp-search-heading {
                font-size: 19px;
                letter-spacing: 3px;
            }

            .knp-search-category-grid {
                gap: 22px 12px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .knp-search-category-name {
                font-size: 14px;
            }
        }

        /* Keep every desktop action icon equally visible. */
        .knp-reference-actions .knp-reference-icon-button,
        .knp-reference-actions .knp-reference-icon-button i {
            color: #111 !important;
            -webkit-text-fill-color: #111 !important;
            opacity: 1 !important;
        }    </style> <!-- Coupon-only announcement ticker -->
    <!-- Premium Top Bar Announcement Carousel -->
    <style>
        .knp-premium-topbar.knp-topbar-carousel-container {
            background-color: #d30000;
            color: #FFFFFF;
            width: 100%;
            height: 38px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            user-select: none;
            z-index: 10000;
        }

        .knp-topbar-carousel-viewport {
            width: 100%;
            max-width: 900px;
            height: 100%;
            overflow: hidden;
            position: relative;
            margin: 0 auto;
        }

        .knp-topbar-carousel-track {
            display: flex;
            height: 100%;
            width: 100%;
            transition: transform 0.45s cubic-bezier(0.25, 1, 0.5, 1);
            will-change: transform;
        }

        .knp-topbar-carousel-slide {
            flex: 0 0 100%;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 42px;
            box-sizing: border-box;
        }

        .knp-topbar-carousel-content {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #FFFFFF;
            white-space: nowrap;
        }

        .knp-topbar-carousel-content i {
            font-size: 13px;
        }

        .knp-topbar-carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #FFFFFF;
            opacity: 0.8;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
            z-index: 5;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .knp-topbar-carousel-arrow:hover {
            opacity: 1;
            transform: translateY(-50%) scale(1.2);
        }

        .knp-topbar-carousel-arrow:focus {
            outline: none;
            opacity: 1;
        }

        .knp-topbar-carousel-arrow.prev {
            left: clamp(10px, 3vw, 40px);
        }

        .knp-topbar-carousel-arrow.next {
            right: clamp(10px, 3vw, 40px);
        }

        @media (max-width: 767.98px) {
            .knp-premium-topbar.knp-topbar-carousel-container {
                height: 34px;
            }
            .knp-topbar-carousel-slide {
                padding: 0 34px;
            }
            .knp-topbar-carousel-content {
                font-size: 10px;
                letter-spacing: 1.4px;
                font-weight: 700;
                gap: 6px;
            }
            .knp-topbar-carousel-content i {
                font-size: 11px;
            }
            .knp-topbar-carousel-arrow {
                width: 26px;
                height: 26px;
            }
            .knp-topbar-carousel-arrow.prev {
                left: 8px;
            }
            .knp-topbar-carousel-arrow.next {
                right: 8px;
            }
        }
    </style>

    @if($announcementSlides->isNotEmpty())
    <div class="knp-premium-topbar knp-topbar-carousel-container" role="region" aria-label="Announcements">
        <button type="button" class="knp-topbar-carousel-arrow prev" aria-label="Previous announcement">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>

        <div class="knp-topbar-carousel-viewport">
            <div class="knp-topbar-carousel-track">
                @foreach($announcementSlides as $slide)
                    <div class="knp-topbar-carousel-slide">
                        <span class="knp-topbar-carousel-content">
                            @if(($slide->action_type ?? null) === 'coupon' && !empty($slide->coupon_code))
                                <button type="button" class="knp-copy-announcement-coupon" data-coupon-code="{{ $slide->coupon_code }}" aria-label="Copy coupon code {{ $slide->coupon_code }}" title="Click to copy coupon code" style="border:0;background:transparent;color:inherit;font:inherit;letter-spacing:inherit;text-transform:inherit;cursor:pointer;display:inline-flex;align-items:center;gap:inherit;padding:0;">
                                    @if(!empty($slide->icon))
                                        <i class="{{ $slide->icon }}" aria-hidden="true"></i>
                                    @endif
                                    <span data-coupon-message>{{ $slide->message }}</span>
                                </button>
                            @elseif(!empty($slide->link) && (($slide->action_type ?? 'link') !== 'coupon'))
                                <a href="{{ $slide->link }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: inherit;">
                                    @if(!empty($slide->icon))
                                        <i class="{{ $slide->icon }}" aria-hidden="true"></i>
                                    @endif
                                    <span>{{ $slide->message }}</span>
                                </a>
                            @else
                                @if(!empty($slide->icon))
                                    <i class="{{ $slide->icon }}" aria-hidden="true"></i>
                                @endif
                                <span>{{ $slide->message }}</span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="button" class="knp-topbar-carousel-arrow next" aria-label="Next announcement">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    </div>
    @endif

    <script>
        (function () {
            document.addEventListener('click', async function (event) {
                var button = event.target.closest('.knp-copy-announcement-coupon');
                if (!button) return;
                var code = button.dataset.couponCode;
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(code);
                    } else {
                        var temporary = document.createElement('textarea');
                        temporary.value = code;
                        temporary.style.position = 'fixed';
                        temporary.style.opacity = '0';
                        document.body.appendChild(temporary);
                        temporary.select();
                        if (!document.execCommand('copy')) throw new Error('Copy failed');
                        temporary.remove();
                    }
                    var label = button.querySelector('[data-coupon-message]');
                    var original = label.textContent;
                    label.textContent = 'Copied: ' + code;
                    window.setTimeout(function () { label.textContent = original; }, 2500);
                } catch (error) {
                    window.prompt('Copy coupon code:', code);
                }
            });
            function initTopbarCarousel() {
                var container = document.querySelector('.knp-topbar-carousel-container');
                if (!container || container.getAttribute('data-carousel-initialized') === 'true') return;
                container.setAttribute('data-carousel-initialized', 'true');

                var track = container.querySelector('.knp-topbar-carousel-track');
                var prevBtn = container.querySelector('.knp-topbar-carousel-arrow.prev');
                var nextBtn = container.querySelector('.knp-topbar-carousel-arrow.next');
                if (!track) return;

                var originalSlides = Array.from(track.children);
                var slideCount = originalSlides.length;
                if (slideCount <= 1) {
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    return;
                }

                // Clone first and last slide for seamless infinite loop
                var firstClone = originalSlides[0].cloneNode(true);
                var lastClone = originalSlides[slideCount - 1].cloneNode(true);
                firstClone.setAttribute('aria-hidden', 'true');
                lastClone.setAttribute('aria-hidden', 'true');

                track.appendChild(firstClone);
                track.insertBefore(lastClone, originalSlides[0]);

                var allSlides = Array.from(track.children);
                var totalSlides = allSlides.length;

                var currentIndex = 1; // Start at original slide 0
                var isTransitioning = false;
                var autoplayInterval = 3800; // 3.8 seconds per slide
                var timer = null;

                function updatePosition(animate) {
                    if (animate) {
                        track.style.transition = 'transform 0.45s cubic-bezier(0.25, 1, 0.5, 1)';
                    } else {
                        track.style.transition = 'none';
                    }
                    track.style.transform = 'translate3d(-' + (currentIndex * 100) + '%, 0, 0)';
                }

                // Initial position
                updatePosition(false);

                function goToNext() {
                    if (isTransitioning) return;
                    isTransitioning = true;
                    currentIndex++;
                    updatePosition(true);
                }

                function goToPrev() {
                    if (isTransitioning) return;
                    isTransitioning = true;
                    currentIndex--;
                    updatePosition(true);
                }

                track.addEventListener('transitionend', function () {
                    isTransitioning = false;
                    if (currentIndex >= totalSlides - 1) {
                        currentIndex = 1;
                        updatePosition(false);
                    } else if (currentIndex <= 0) {
                        currentIndex = slideCount;
                        updatePosition(false);
                    }
                });

                function startAutoplay() {
                    stopAutoplay();
                    timer = setInterval(goToNext, autoplayInterval);
                }

                function stopAutoplay() {
                    if (timer) {
                        clearInterval(timer);
                        timer = null;
                    }
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        goToNext();
                        startAutoplay();
                    });
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        goToPrev();
                        startAutoplay();
                    });
                }

                container.addEventListener('mouseenter', stopAutoplay);
                container.addEventListener('mouseleave', startAutoplay);

                document.addEventListener('visibilitychange', function () {
                    if (document.hidden) {
                        stopAutoplay();
                    } else {
                        startAutoplay();
                    }
                });

                var touchStartX = 0;
                var touchEndX = 0;
                container.addEventListener('touchstart', function (e) {
                    stopAutoplay();
                    touchStartX = e.touches[0].clientX;
                }, { passive: true });

                container.addEventListener('touchend', function (e) {
                    touchEndX = e.changedTouches[0].clientX;
                    var diff = touchStartX - touchEndX;
                    if (Math.abs(diff) > 30) {
                        if (diff > 0) {
                            goToNext();
                        } else {
                            goToPrev();
                        }
                    }
                    startAutoplay();
                }, { passive: true });

                startAutoplay();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTopbarCarousel);
            } else {
                initTopbarCarousel();
            }
        })();
    </script>

    <!-- Main Navigation -->
    <div id="sticky-header" class="knp-main-navigation {{ request()->is('/') ? 'is-home' : '' }}" style="background-color: {{ request()->is('/') ? 'transparent' : '#ffffff' }}; position: relative; width: 100%; z-index: 9998; border-bottom: 1px solid {{ request()->is('/') ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)' }}; transition: background 0.35s ease, box-shadow 0.35s ease;">
        <style>
            /* Force transparent background on home page to override external CSS */
            body .header-area.header-wrapper.is-home-header,
            body .header-area.is-home-header #sticky-header,
            body .header-area.is-home-header .knp-main-navigation {
                background: transparent !important;
            }
            body .header-area.is-home-header {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                z-index: 9999 !important;
            }

            /* Scrim for home transparent header to ensure contrast against light slides */
            body .header-area.header-wrapper.is-home-header {
                background: linear-gradient(180deg, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.3) 65%, rgba(0,0,0,0) 100%) !important;
            }
            body .header-area.header-wrapper.is-home-header.sticky,
            body .header-area.header-wrapper.is-home-header #sticky-header.sticky {
                background: #ffffff !important;
            }

            /* Home page nav: white text on transparent bg */
            .knp-main-navigation.is-home .nav-link-item,
            .knp-main-navigation.is-home .nav-icon-item,
            .knp-main-navigation.is-home .knp-mobile-toggle,
            .knp-main-navigation.is-home .knp-mobile-toggle i { color: #ffffff !important; border-color: rgba(255,255,255,0.45) !important; }
            
            /* When sticky (scrolled on home) */
            .knp-main-navigation.is-home.sticky { background-color: #ffffff !important; box-shadow: 0 2px 18px rgba(0,0,0,0.10) !important; }
            .knp-main-navigation.is-home.sticky .nav-link-item,
            .knp-main-navigation.is-home.sticky .nav-icon-item,
            .knp-main-navigation.is-home.sticky .knp-mobile-toggle,
            .knp-main-navigation.is-home.sticky .knp-mobile-toggle i { color: #111 !important; border-color: rgba(17,17,17,0.3) !important; }

            /* Default text color for normal pages */
            .knp-main-navigation:not(.is-home) .nav-link-item,
            .knp-main-navigation:not(.is-home) .nav-icon-item { color: #111 !important; }

            @media (max-width: 767.98px) {
                .knp-reference-header-row {
                    min-height: 56px !important;
                    padding: 6px 0 !important;
                    position: relative !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: space-between !important;
                }
                .knp-reference-header-row .knp-header-left-actions {
                    display: flex !important;
                    align-items: center !important;
                    gap: clamp(8px, 2.5vw, 14px) !important;
                    z-index: 10 !important;
                    flex: 0 0 auto !important;
                }
                .knp-reference-header-row .knp-logo-column {
                    position: absolute !important;
                    left: 50% !important;
                    top: 50% !important;
                    transform: translate(-50%, -50%) !important;
                    text-align: center !important;
                    z-index: 5 !important;
                    margin: 0 !important;
                    width: auto !important;
                    pointer-events: auto !important;
                }
                .knp-reference-header-row .knp-header-logo {
                    height: clamp(38px, 8vw, 44px) !important;
                    max-height: 44px !important;
                    max-width: 110px !important;
                    object-fit: contain !important;
                }
                .knp-reference-header-row .knp-reference-actions {
                    display: flex !important;
                    align-items: center !important;
                    gap: clamp(8px, 2.5vw, 14px) !important;
                    margin-left: auto !important;
                    z-index: 10 !important;
                    flex: 0 0 auto !important;
                }
                .knp-reference-header-row .knp-reference-actions .nav-icon-item svg,
                .knp-reference-header-row .knp-header-left-actions .nav-icon-item svg {
                    width: 19px !important;
                    height: 19px !important;
                }
            }
            @media (max-width: 374px) {
                .knp-reference-header-row .knp-header-logo {
                    height: 36px !important;
                    max-height: 36px !important;
                    max-width: 95px !important;
                }
                .knp-reference-header-row .knp-header-left-actions {
                    gap: 6px !important;
                }
                .knp-reference-header-row .knp-reference-actions {
                    gap: 8px !important;
                }
                .knp-reference-header-row .knp-reference-actions a[href*="account"],
                .knp-reference-header-row .knp-reference-actions a[href*="login"] {
                    display: none !important;
                }
            }
            
            /* Luxury Hover Effect for Desktop Nav */
            .nav-link-item {
                position: relative;
            }
            .nav-link-item::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 0;
                width: 0%;
                height: 1px;
                background-color: currentColor;
                transition: width 0.4s cubic-bezier(0.25, 1, 0.5, 1);
            }
            .nav-link-item:hover::after {
                width: 100%;
            }
        </style>
        <div class="container-fluid" style="padding: 0 clamp(20px, 4vw, 40px);">
            <div class="knp-reference-header-row" style="display:flex; justify-content:space-between; align-items:center; min-height: 100px;">
                
                <!-- Left Actions (Mobile Only: Hamburger Menu + Search) -->
                <div class="knp-header-left-actions d-flex d-lg-none" style="align-items:center; gap: clamp(8px, 2.5vw, 14px); flex: 0 0 auto; z-index: 10;">
                    <button class="navbar-toggler knp-mobile-toggle" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Open navigation"
                        style="background: none; border: none; padding: 4px; color: inherit; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; transition: all 0.3s;">
                        <i class="fa fa-bars" style="font-size: 18px;"></i>
                    </button>
                    <a href="#" class="nav-icon-item knp-reference-search-toggle" aria-expanded="false" aria-controls="knpReferenceSearch" aria-label="Search" style="display:flex; align-items:center; justify-content:center; transition: color 0.3s; width: 28px; height: 28px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </a>
                </div>

                <!-- Center Logo (Centered on mobile, left on desktop) -->
                <div class="knp-logo-column" style="flex: 0 0 auto;">
                    <a href="{{ url('/') }}" style="text-decoration: none; display: inline-block;">
                        <img class="knp-header-logo" src="{{ asset('images/logo/logoo.png') }}" alt="House of KNP"
                            style="height: clamp(75px, 8vw, 110px); object-fit: contain; transition: filter 0.3s ease;">
                    </a>
                </div>

                <!-- Desktop Center Menu -->
                <style>
                    @media (max-width: 991px) { .knp-desktop-menu { display: none !important; } }
                    @media (min-width: 992px) { .knp-desktop-menu { display: flex !important; } }
                </style>
                <div class="knp-desktop-menu" style="order: 2; gap: clamp(15px, 2vw, 35px); align-items: center; margin: 0 auto;">
                    @php $navColor = request()->is('/') ? '#FFF' : '#111'; @endphp
                    @foreach ($headerCategories as $navCat)
                        @php
                            $catSlug = \Illuminate\Support\Str::slug($navCat->category_name);
                            $catLink = ($catSlug === 'signature-box' || str_contains($catSlug, 'combo'))
                                ? url('combos')
                                : url('category/' . $catSlug);
                            $isActive = (request()->is('category/' . $catSlug) || request('category') === $catSlug) || (($catSlug === 'signature-box' || str_contains($catSlug, 'combo')) && request()->is('combos*'));
                        @endphp
                        <a href="{{ $catLink }}" class="nav-link-item" style="text-decoration: none; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; transition: color 0.3s; color: {{ $isActive ? 'var(--knp-red)' : $navColor }} !important;">{{ $navCat->category_name }}</a>
                    @endforeach
                    <a href="{{ url('blog') }}" class="nav-link-item" style="text-decoration: none; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; transition: color 0.3s; color: {{ request()->is('blog*') ? 'var(--knp-red)' : $navColor }} !important;">JOURNAL</a>
                    <a href="{{ url('bulk-order') }}" class="nav-link-item" style="text-decoration: none; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; transition: color 0.3s; color: {{ request()->is('bulk order') ? 'var(--knp-red)' : $navColor }} !important;">Bulk Order</a>
                    <a href="{{ url('about') }}" class="nav-link-item" style="text-decoration: none; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; transition: color 0.3s; color: {{ request()->is('about') ? 'var(--knp-red)' : $navColor }} !important;">ABOUT</a>
                </div>

                <!-- Right Actions -->
                <div class="knp-reference-actions" style="display:flex; gap: clamp(15px, 2vw, 25px); align-items:center; flex: 0 0 auto;">
                    <!-- Desktop Search (hidden on mobile, shown on desktop) -->
                    <a href="#" class="nav-icon-item knp-reference-search-toggle d-none d-lg-flex" aria-expanded="false" aria-controls="knpReferenceSearch" aria-label="Search" style="align-items:center; justify-content:center; transition: color 0.3s;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </a>
                    <a href="{{ auth()->check() ? url('account') : url('login') }}" class="nav-icon-item" aria-label="Account" style="display:flex; align-items:center; justify-content:center; transition: color 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </a>
                    <a href="{{ url('wishlist') }}" class="nav-icon-item" aria-label="Wishlist" style="position: relative; display:flex; align-items:center; justify-content:center; transition: color 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        <span data-count-target="wishlist" style="position:absolute; top:-6px; right:-8px; background:#111111; color:#FFF; font-size:9px; font-weight:700; border-radius:50%; width:16px; height:16px; display:flex; align-items:center; justify-content:center; border: 1px solid #FFF;">{{ $wishlistCount }}</span>
                    </a>
                    <a href="{{ url('cart') }}" class="nav-icon-item" aria-label="Cart" style="position: relative; display:flex; align-items:center; justify-content:center; transition: color 0.3s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        <span data-count-target="cart" style="position:absolute; top:-6px; right:-8px; background:#111111; color:#FFF; font-size:9px; font-weight:700; border-radius:50%; width:16px; height:16px; display:flex; align-items:center; justify-content:center; border: 1px solid #FFF;">{{ $cartCount }}</span>
                    </a>
                </div>
                
                <!-- Search Popover -->
                <div id="knpReferenceSearch" class="knp-reference-search-popover" role="dialog" aria-modal="true" aria-label="Search products and browse categories">
                    <div class="knp-search-inner">
                        <div class="knp-search-form-row">
                            <form class="knp-search-form" action="{{ url('shop') }}" method="GET">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                <input type="search" name="q" value="{{ request('q') }}" placeholder="Find a product..." required minlength="2">
                                <button type="submit">Search</button>
                            </form>
                            <button type="button" class="knp-search-close" aria-label="Close search"></button>
                        </div>
                        <h2 class="knp-search-heading">All Categories</h2>
                        <div class="knp-search-category-grid">
                            @forelse ($headerCategories as $searchCategory)
                                <a class="knp-search-category-card" href="{{ url('category/' . \Illuminate\Support\Str::slug($searchCategory->category_name)) }}">
                                    <span class="knp-search-category-image">
                                        <img src="{{ house_category_image_url($searchCategory->category_image) }}" alt="{{ $searchCategory->category_name }}" loading="lazy">
                                    </span>
                                    <span class="knp-search-category-name">{{ $searchCategory->category_name }}</span>
                                </a>
                            @empty
                                <p class="knp-search-empty">No categories available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Small-screen navigation drawer -->
    <div class="offcanvas offcanvas-start knp-mobile-drawer d-md-none" tabindex="-1" id="mobileNav"
        aria-labelledby="mobileNavLabel">
        <div class="offcanvas-header">
            <a class="knp-drawer-logo" id="mobileNavLabel" href="{{ url('/') }}"
                aria-label="House of KNP home">
                <img src="{{ asset('images/logo/logoo.png') }}" alt="House of KNP">
            </a>
            <button type="button" class="knp-drawer-close" data-bs-dismiss="offcanvas"
                aria-label="Close navigation">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="offcanvas-body">
            <div class="knp-drawer-account-links">
                @auth
                    <a href="{{ url('account') }}"><i class="fa fa-user"></i> Account</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:flex; margin:0; width:50%;">
                        @csrf
                        <button type="submit" style="width:100%;"><i class="fa fa-sign-out"></i> Logout</button>
                    </form>
                @else
                    <a href="{{ url('login') }}"><i class="fa fa-user"></i> Login</a>
                    <a href="{{ url('register') }}"><i class="fa fa-user-plus"></i> Register</a>
                @endauth
            </div>

            @foreach ($activeCoupons as $mobileCoupon)
                <div class="knp-drawer-promo">
                    <i class="fa fa-gift"></i> Use Code {{ $mobileCoupon->codename }} - Save
                    {{ (int) $mobileCoupon->discounttype === 2 ? $mobileCoupon->discount . '%' : 'Rs. ' . $mobileCoupon->discount }}
                </div>
            @endforeach
            <!-- <div class="knp-drawer-promo"><i class="fa fa-truck"></i> Free Delivery on Orders Above Rs. 999</div> -->

            <form class="knp-drawer-search" action="{{ url('shop') }}" method="GET"
                onsubmit="const q=this.elements.q;q.value=q.value.trim();if(q.value.length<2){q.setCustomValidity('Enter at least 2 characters to search.');q.reportValidity();return false;}q.setCustomValidity('');">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products..."
                    aria-label="Search products" required minlength="2" maxlength="100" pattern=".*\S.*"
                    title="Enter at least 2 characters to search.">
                <button type="submit" aria-label="Search"><i class="fa fa-search"></i></button>
            </form>

            <nav aria-label="Mobile navigation">
                <ul class="knp-drawer-menu">
                    <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}"><i
                                class="fa fa-home"></i> Home</a></li>
                    <li><a href="{{ url('about') }}" class="{{ request()->is('about') ? 'active' : '' }}"><i
                                class="fa fa-info-circle"></i> About</a></li>
                    @foreach ($headerCategories as $navCat)
                        @php
                            $catSlug = \Illuminate\Support\Str::slug($navCat->category_name);
                            $catLink = ($catSlug === 'signature-box' || str_contains($catSlug, 'combo'))
                                ? url('combos')
                                : url('category/' . $catSlug);
                            $isActive = (request()->is('category/' . $catSlug) || request('category') === $catSlug) || (($catSlug === 'signature-box' || str_contains($catSlug, 'combo')) && request()->is('combos*'));
                            // Common icon for all categories (or custom from DB if provided)
                            $catIcon = !empty($navCat->category_icon) ? $navCat->category_icon : 'fa-tag';
                        @endphp
                        <li><a href="{{ $catLink }}" class="{{ $isActive ? 'active' : '' }}"><i
                                    class="fa {{ $catIcon }}"></i> {{ $navCat->category_name }}</a></li>
                    @endforeach
                    <li><a href="{{ url('bulk-order') }}" class="{{ request()->is('bulk-order') ? 'active' : '' }}"><i
                                class="fa fa-briefcase"></i> Bulk Orders</a></li>
                    <li><a href="{{ url('blog') }}" class="{{ request()->is('blog*') ? 'active' : '' }}"><i
                                class="fa fa-file-text-o"></i> Blog</a></li>
                    <li><a href="{{ url('contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}"><i
                                class="fa fa-envelope-o"></i> Contact</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <script>
        document.addEventListener('click', function(event) {
            const closeButton = event.target.closest('.knp-coupon-close');
            if (!closeButton) return;
            closeButton.closest('.knp-coupon-only-bar')?.remove();
        });
    </script>
    <div class="knp-reference-drawer-backdrop" data-knp-drawer-close hidden></div>
    <aside class="knp-reference-drawer" id="knpReferenceDrawer" aria-hidden="true"
        aria-labelledby="knpReferenceDrawerTitle">
        <div class="knp-reference-drawer-head">
            <span id="knpReferenceDrawerTitle">Menu</span>
            <button type="button" class="knp-reference-drawer-close" data-knp-drawer-close
                aria-label="Close menu"><span></span><span></span></button>
        </div>
        <nav class="knp-reference-drawer-nav" aria-label="Quick links">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('about') }}">About Us</a>
            <details class="knp-reference-shop-menu">
                <summary>Shop <span></span></summary>
                <div class="knp-reference-product-links">
                    <a href="{{ url('shop') }}">All Products</a>
                    @foreach ($headerCategories as $drawerCategory)
                        <a
                            href="{{ url('category/' . \Illuminate\Support\Str::slug($drawerCategory->category_name)) }}">{{ $drawerCategory->category_name }}</a>
                    @endforeach
                    <a href="{{ url('combos') }}">Signature Box</a>
                </div>
            </details>
            <a href="{{ url('bulk-order') }}">Bulk Orders</a>
            <a href="{{ url('blog') }}">Blog</a>
            <a href="{{ url('contact') }}">Contact</a>
        </nav>
        <div class="knp-reference-drawer-footer">
            @auth
                <a href="{{ url('account') }}">My Account</a>
            @else
                <a href="{{ url('login') }}">Log in</a>
            @endauth
        </div>
    </aside>
    <script>
        (function() {
            const drawer = document.getElementById('knpReferenceDrawer');
            const toggle = document.querySelector('.knp-reference-menu-toggle');
            const backdrop = document.querySelector('.knp-reference-drawer-backdrop');
            if (!drawer || !toggle || !backdrop) return;

            function setOpen(open) {
                drawer.classList.toggle('is-open', open);
                drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                backdrop.hidden = !open;
                document.body.classList.toggle('knp-drawer-open', open);
                if (open) drawer.querySelector('[data-knp-drawer-close]')?.focus();
                else toggle.focus();
            }

            toggle.addEventListener('click', function() {
                setOpen(true);
            });
            document.querySelectorAll('[data-knp-drawer-close]').forEach(function(button) {
                button.addEventListener('click', function() {
                    setOpen(false);
                });
            });
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && drawer.classList.contains('is-open')) setOpen(false);
            });
        }());
    </script>
    <script>
        (function() {
            const searchPopover = document.getElementById('knpReferenceSearch');
            const searchToggles = document.querySelectorAll('.knp-reference-search-toggle');
            if (!searchPopover || searchToggles.length === 0) return;
            const closeButton = searchPopover.querySelector('.knp-search-close');

            function closeSearch(returnFocus = false) {
                searchPopover.classList.remove('is-open');
                document.body.classList.remove('knp-search-open');
                searchToggles.forEach(t => t.setAttribute('aria-expanded', 'false'));
                if (returnFocus && searchToggles[0]) searchToggles[0].focus();
            }

            function openSearch(toggle) {
                searchPopover.classList.add('is-open');
                document.body.classList.add('knp-search-open');
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
                searchPopover.querySelector('input')?.focus();
            }

            searchToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const isOpen = searchPopover.classList.contains('is-open');
                    if (isOpen) {
                        closeSearch();
                    } else {
                        openSearch(toggle);
                    }
                });
            });

            closeButton?.addEventListener('click', function() {
                closeSearch(true);
            });
            searchPopover.addEventListener('click', function(event) {
                if (event.target === searchPopover) closeSearch(true);
            });
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && searchPopover.classList.contains('is-open')) closeSearch(true);
            });
        }());
    </script>
</header>
<script>
    /* Sticky header: becomes solid white once user scrolls past the hero on the home page */
    (function() {
        var nav = document.getElementById('sticky-header');
        if (!nav || !nav.classList.contains('is-home')) return;
        var threshold = window.innerHeight * 0.7;
        function onScroll() {
            if (window.scrollY > threshold) {
                nav.classList.add('sticky');
            } else {
                nav.classList.remove('sticky');
            }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
    }());

    /* Search toggle for the new simplified icon button */
    (function() {
        var wrap = document.querySelector('.knp-reference-search-wrap');
        var toggle = document.querySelector('.knp-reference-search-toggle');
        /* fallback: the search toggle is now an <a>, not inside .knp-reference-search-wrap */
        if (!toggle) return;

        var overlay = document.getElementById('knpReferenceSearch');
        var closeBtn = overlay ? overlay.querySelector('.knp-search-close') : null;

        function openSearch() {
            document.body.classList.add('knp-search-open');
            if (overlay) {
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
                overlay.style.transform = 'none';
                overlay.style.visibility = 'visible';
                var input = overlay.querySelector('input');
                if (input) input.focus();
            }
            toggle.setAttribute('aria-expanded', 'true');
        }

        function closeSearch() {
            document.body.classList.remove('knp-search-open');
            if (overlay) {
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
                overlay.style.transform = 'translateY(-18px)';
                overlay.style.visibility = 'hidden';
            }
            toggle.setAttribute('aria-expanded', 'false');
        }

        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var isOpen = document.body.classList.contains('knp-search-open');
            if (isOpen) { closeSearch(); } else { openSearch(); }
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function() { closeSearch(); });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.body.classList.contains('knp-search-open')) closeSearch();
        });

        document.addEventListener('click', function(e) {
            if (overlay && !overlay.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)) {
                closeSearch();
            }
        });
    }());
</script>
