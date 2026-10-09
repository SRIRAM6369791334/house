<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @php
        $storedMeta = null;
        try {
            $path = '/' . ltrim(request()->path(), '/');
            $fullUri = '/' . ltrim(request()->getRequestUri(), '/');

            $query = \App\Models\PageMetaSetting::query()
                ->where('page_url', $fullUri)
                ->orWhere('page_url', $path);

            if (request()->has('slug')) {
                $query->orWhere('page_url', $path . '?slug=' . request()->query('slug'))
                      ->orWhere('page_url', $path . '?slug=' . urlencode(request()->query('slug')));
            }
            if (request()->has('product')) {
                $query->orWhere('page_url', $path . '?slug=' . request()->query('product'))
                      ->orWhere('page_url', $path . '?product=' . request()->query('product'));
            }
            if (request()->has('id')) {
                $query->orWhere('page_url', $path . '?id=' . request()->query('id'));
            }

            $storedMeta = $query->first();
        } catch (\Throwable $exception) {
            // Defaults are used until the dashboard migration has run.
        }

        $rawTitle = trim((string) ($storedMeta->meta_title ?? ''));
        $pageTitle = $rawTitle !== '' ? $rawTitle : trim($__env->yieldContent('meta_title', 'House of KNP'));

        $rawDesc = trim((string) ($storedMeta->meta_description ?? ''));
        $pageDescription = $rawDesc !== '' ? $rawDesc : trim($__env->yieldContent(
            'meta_description',
            'Discover premium shirts, watches, perfumes and accessories from House of KNP.'
        ));

        $rawKeywords = trim((string) ($storedMeta->meta_keywords ?? ''));
        $pageKeywords = $rawKeywords !== '' ? $rawKeywords : trim($__env->yieldContent('meta_keywords', 'House of KNP, mens fashion, shirts, watches, perfumes'));

        $rawCanonical = trim((string) ($storedMeta->canonical_url ?? ''));
        if ($rawCanonical === '') {
            $rawCanonical = trim($__env->yieldContent('canonical_url', ''));
        }
        if ($rawCanonical !== '' && filter_var($rawCanonical, FILTER_VALIDATE_URL)) {
            $pageCanonical = $rawCanonical;
        } elseif ($rawCanonical !== '' && str_starts_with($rawCanonical, '/')) {
            $pageCanonical = url($rawCanonical);
        } else {
            $pageCanonical = url()->current();
        }

        $storedOgImg = null;
        if (!empty($storedMeta->og_image)) {
            $rawOg = trim($storedMeta->og_image);
            if (filter_var($rawOg, FILTER_VALIDATE_URL)) {
                $storedOgImg = $rawOg;
            } else {
                $cleanedOg = ltrim($rawOg, '/');
                $mainUrl = rtrim((string) env('MAIN_URL'), '/');
                $storedOgImg = $mainUrl !== '' ? ($mainUrl . '/' . $cleanedOg) : url($cleanedOg);
            }
        }
        $pageImage = trim($storedOgImg ?? $__env->yieldContent('meta_image', asset('images/logo/logoo.png')));
        $pageType = trim($__env->yieldContent('meta_type', 'website'));
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    @if ($pageKeywords !== '')
        <meta name="keywords" content="{{ $pageKeywords }}">
    @endif
    <link rel="canonical" href="{{ $pageCanonical }}">
    <meta property="og:type" content="{{ $pageType }}">
    <meta property="og:site_name" content="House of KNP">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $pageCanonical }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:secure_url" content="{{ $pageImage }}">
    @php
        $imgExt = strtolower(pathinfo(parse_url($pageImage, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
        $imgMime = match($imgExt) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            default => 'image/jpeg'
        };
    @endphp
    <meta property="og:image:type" content="{{ $imgMime }}">
    <meta property="og:image:alt" content="{{ $pageTitle }}">
    <meta property="og:locale" content="en_IN">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logoo.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- All css files are included here. -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shortcode/shortcodes.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/color/skin-default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/luxury-ui-overrides.css') }}">

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            color: #333;
            margin: 0;
            overflow-x: hidden;
            width: 100%;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        img, video, iframe {
            max-width: 100%;
        }
        h1, h2, h3, h4, h5, h6, .knp-serif {
            font-family: 'Playfair Display', serif;
        }
        .text-brand {
            color: #CC0000 !important;
        }
        .bg-brand {
            background-color: #CC0000 !important;
        }
        ::selection {
            background: #CC0000;
            color: #fff;
        }
    </style>

    <!-- Modernizr JS -->
    <script src="{{ asset('js/vendor/modernizr-3.11.2.min.js') }}"></script>
    <style>
        /* House of KNP typography: editorial headings with clean supporting copy. */
        html,
        body,
        body *:not(.fa):not([class*="fa-"]):not(.zmdi):not([class*="zmdi-"]):not([class*="icon-"]) {
            font-family: "Manrope", "Helvetica Neue", Arial, sans-serif;
        }

        body h1,
        body h2,
        body h3,
        body h4,
        body h5,
        body h6,
        body .knp-serif,
        body .knp-modern-heading,
        body .hero-presence-kicker,
        body .hero-presence-title {
            font-family: "Cormorant Garamond", Georgia, "Times New Roman", serif !important;
        }
    </style>
</head>

<body>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Body main wrapper start -->
    <div class="wrapper home-one">

        <!-- Start of header area -->
          @include('layouts.header')

    @yield('content')

    @include('layouts.footer')
        <!-- End of header area -->





        <!-- QUICKVIEW PRODUCT -->
        <div id="quickview-wrapper">
            <!-- Modal -->
            <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-bs-dismiss="modal"
                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-product">
                                <div class="product-images">
                                    <!--modal tab start-->
                                    <div class="portfolio-thumbnil-area-2">
                                        <div class="tab-content active-portfolio-area-2">
                                            <div role="tabpanel" class="tab-pane active" id="view1">
                                                <div class="product-img">
                                                    <a href="#"><img src="{{ asset('images/product/01.jpg') }}"
                                                            alt="Single portfolio" /></a>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="view2">
                                                <div class="product-img">
                                                    <a href="#"><img src="{{ asset('images/product/02.jpg') }}"
                                                            alt="Single portfolio" /></a>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="view3">
                                                <div class="product-img">
                                                    <a href="#"><img src="{{ asset('images/product/03.jpg') }}"
                                                            alt="Single portfolio" /></a>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="view4">
                                                <div class="product-img">
                                                    <a href="#"><img src="{{ asset('images/product/04.jpg') }}"
                                                            alt="Single portfolio" /></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-more-views-2">
                                            <ul class="thumbnail-carousel-modal-2 nav" data-tabs="tabs">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="view1" data-bs-toggle="tab"
                                                        href="#view1" role="tab" aria-controls="view1"
                                                        aria-selected="true">
                                                        <img src="{{ asset('images/product/01.jpg') }}" alt="" />
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="view2" data-bs-toggle="tab"
                                                        href="#view2" role="tab" aria-controls="view2"
                                                        aria-selected="true">
                                                        <img src="{{ asset('images/product/02.jpg') }}" alt="" />
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="view3" data-bs-toggle="tab"
                                                        href="#view3" role="tab" aria-controls="view3"
                                                        aria-selected="true">
                                                        <img src="{{ asset('images/product/03.jpg') }}" alt="" />
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="view4" data-bs-toggle="tab"
                                                        href="#view4" role="tab" aria-controls="view4"
                                                        aria-selected="true">
                                                        <img src="{{ asset('images/product/04.jpg') }}" alt="" />
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--modal tab end-->
                                <!-- .product-images -->
                                <div class="product-info">
                                    <h1>Aenean eu tristique</h1>
                                    <div class="price-box-3">
                                        <div class="s-price-box"> <span class="new-price">$160.00</span> <span
                                                class="old-price">$190.00</span> </div>
                                    </div> <a href="shop" class="see-all">See all features</a>
                                    <div class="quick-add-to-cart">
                                        <form method="post" class="cart">
                                            <div class="numbers-row">
                                                <input type="number" id="french-hens" value="3"
                                                    min="1">
                                            </div>
                                            <button class="single_add_to_cart_button" type="submit">Add to
                                                cart</button>
                                        </form>
                                    </div>
                                    <div class="quick-desc"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Nam
                                        fringilla augue nec est tristique auctor. Donec non est at libero.Lorem ipsum
                                        dolor
                                        sit amet, consectetur adipiscing elit. Nam fringilla augue nec est tristique
                                        auctor.
                                        Donec non est at libero.Nam fringilla tristique auctor. </div>
                                    <div class="social-sharing-modal">
                                        <div class="widget widget_socialsharing_widget">
                                            <h3 class="widget-title-modal">Share this product</h3>
                                            <ul class="social-icons-modal">
                                                <li><a title="Facebook" href="#"
                                                        class="facebook m-single-icon"><i
                                                            class="fa fa-facebook"></i></a>
                                                </li>
                                                <li><a title="Twitter" href="#"
                                                        class="twitter m-single-icon"><i
                                                            class="fa fa-twitter"></i></a></li>
                                                <li><a title="Pinterest" href="#"
                                                        class="pinterest m-single-icon"><i
                                                            class="fa fa-pinterest"></i></a>
                                                </li>
                                                <li><a title="Google +" href="#"
                                                        class="gplus m-single-icon"><i
                                                            class="fa fa-google-plus"></i></a>
                                                </li>
                                                <li><a title="LinkedIn" href="#"
                                                        class="linkedin m-single-icon"><i
                                                            class="fa fa-linkedin"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- .product-info -->
                            </div>
                            <!-- .modal-product -->
                        </div>
                        <!-- .modal-body -->
                    </div>
                    <!-- .modal-content -->
                </div>
                <!-- .modal-dialog -->
            </div>
            <!-- END Modal -->
        </div>
        <!-- END QUICKVIEW PRODUCT -->
    </div>
    <!-- Body main wrapper end -->

    <style id="house-of-knp-brand-palette">
        :root {
            --knp-white: #FFFFFF;
            --knp-red: #B40016;
            --knp-black: #111111;
            --knp-muted: #666666;
            --knp-border: #E5E5E5;
            --bs-primary: #B40016;
            --bs-primary-rgb: 180, 0, 22;
            --bs-secondary: #111111;
            --bs-secondary-rgb: 17, 17, 17;
            --bs-success: #B40016;
            --bs-success-rgb: 180, 0, 22;
            --bs-info: #111111;
            --bs-info-rgb: 17, 17, 17;
            --bs-link-color: #111111;
            --bs-link-hover-color: #B40016;
        }

        html,
        body,
        .wrapper {
            background-color: var(--knp-white);
            color: var(--knp-black);
        }

        a {
            color: var(--knp-black);
        }

        a:hover,
        a:focus,
        a:active,
        .text-brand,
        .text-primary,
        .text-success,
        .text-info,
        .new-price,
        .product-price,
        .collection-product-price,
        .price-box .new-price,
        .s-price-box .new-price {
            color: var(--knp-red) !important;
        }

        .btn-primary,
        .btn-success,
        .btn-info,
        .bg-brand,
        .bg-primary,
        .bg-success,
        .bg-info,
        .badge-primary,
        .badge-success,
        .badge-info,
        .label-primary,
        .label-success,
        .label-info,
        .collection-offer-percent,
        .single_add_to_cart_button,
        button[type="submit"]:not(.close):not(.navbar-toggler),
        input[type="submit"],
        .pagination .active > a,
        .pagination .active > span,
        .page-item.active .page-link,
        .progress-bar {
            background-color: var(--knp-red) !important;
            border-color: var(--knp-red) !important;
            color: var(--knp-white) !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-success:hover,
        .btn-success:focus,
        .btn-info:hover,
        .btn-info:focus,
        .single_add_to_cart_button:hover,
        button[type="submit"]:not(.close):not(.navbar-toggler):hover,
        input[type="submit"]:hover {
            background-color: var(--knp-black) !important;
            border-color: var(--knp-black) !important;
            color: var(--knp-white) !important;
        }

        .btn-secondary,
        .btn-dark,
        .bg-secondary,
        .bg-dark,
        .badge-secondary,
        .badge-dark {
            background-color: var(--knp-black) !important;
            border-color: var(--knp-black) !important;
            color: var(--knp-white) !important;
        }

        .btn-outline-primary,
        .btn-outline-success,
        .btn-outline-info,
        .page-link {
            background-color: var(--knp-white) !important;
            border-color: var(--knp-red) !important;
            color: var(--knp-red) !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-info:hover,
        .page-link:hover {
            background-color: var(--knp-red) !important;
            color: var(--knp-white) !important;
        }

        .alert-success,
        .alert-info {
            background-color: #FFFFFF !important;
            border-color: var(--knp-red) !important;
            color: var(--knp-black) !important;
        }

        .border-primary,
        .border-success,
        .border-info {
            border-color: var(--knp-red) !important;
        }

        input:focus,
        select:focus,
        textarea:focus,
        .form-control:focus,
        .form-select:focus {
            border-color: var(--knp-red) !important;
            box-shadow: 0 0 0 2px rgba(180, 0, 22, .12) !important;
            outline-color: var(--knp-red) !important;
        }

        ::selection {
            background: var(--knp-red);
            color: var(--knp-white);
        }
    </style>

    <!-- Placed js at the end of the document so the pages load faster -->

    <!-- jquery latest version -->
    <script src="{{ asset('js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery-migrate-3.3.2.min.js') }}"></script>
    <!-- Bootstrap framework js -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- Slider js -->
    <script src="{{ asset('js/slider/jquery.nivo.slider.pack.js') }}"></script>
    <script src="{{ asset('js/slider/nivo-active.js') }}"></script>
    <!-- counterUp-->
    <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
    <!-- All js plugins included in this file. -->
    <script src="{{ asset('js/plugins.js') }}"></script>
    <!-- Main js file that contents all jQuery plugins activation. -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function updateHeaderCounts(data) {
            if (!data) return;
            if (typeof data.cart_count !== 'undefined') {
                document.querySelectorAll('[data-count-target="cart"]').forEach((el) => {
                    el.textContent = data.cart_count;
                });
            }
            if (typeof data.wishlist_count !== 'undefined') {
                document.querySelectorAll('[data-count-target="wishlist"]').forEach((el) => {
                    el.textContent = data.wishlist_count;
                });
            }
        }

        function markAjaxActionActive(event) {
            const target = event && event.currentTarget ? event.currentTarget : event && event.target ? event.target.closest('a, button, .add-to-bag') : null;
            if (!target) return;
            target.classList.add('active');
            if (target.classList.contains('add-to-bag')) {
                target.style.background = 'var(--knp-red)';
                target.style.color = '#fff';
                target.style.borderColor = 'var(--knp-red)';
            }
        }

        function ajaxAddToCart(url, e) {
            if(e) e.preventDefault();
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.error || 'Failed to add to cart. Out of stock?');
                return data;
            })
            .then((data) => {
                if (data.info) {
                    updateHeaderCounts(data);
                    Swal.fire({ title: 'Already Added', text: data.info, icon: 'info', timer: 1500, showConfirmButton: false });
                    return;
                }
                updateHeaderCounts(data);
                markAjaxActionActive(e);
                Swal.fire({
                    title: 'Added to Cart!',
                    text: 'The product has been added to your cart.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Cannot Add Item',
                    text: error.message,
                    icon: 'error',
                    confirmButtonColor: '#cc0000'
                });
            });
        }
        
        function ajaxAddToWishlist(url, e) {
            if(e) e.preventDefault();
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.error || 'Failed to add to wishlist.');
                return data;
            })
            .then((data) => {
                if (data.info) {
                    updateHeaderCounts(data);
                    Swal.fire({ title: 'Already Added', text: data.info, icon: 'info', timer: 1500, showConfirmButton: false });
                    return;
                }
                updateHeaderCounts(data);
                markAjaxActionActive(e);
                Swal.fire({
                    title: 'Added to Wishlist!',
                    text: 'The product has been saved to your wishlist.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Oops!',
                    text: error.message,
                    icon: 'error',
                    confirmButtonColor: '#cc0000'
                });
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#cc0000'
                });
            @endif
        });
    </script>

    @yield('scripts')

</body>

</html>
