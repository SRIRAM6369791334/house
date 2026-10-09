@extends('layouts.app')

@section('meta_title', 'The Journal | House of KNP')
@section('meta_description', 'Thoughts on style, confidence, success and the art of living a life that leaves a mark.')
@section('meta_keywords', 'House of KNP blog, mens style, fashion stories, product care, wardrobe guides')
@section('canonical_url', url('/blog'))
@section('meta_type', 'website')

@section('content')

<style>
    :root {
        --knp-red: #B40016;
        --knp-dark: #111111;
        --knp-light: #F9F9F9;
        --knp-gray: #EAEAEA;
    }

    .journal-wrapper {
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
    .journal-hero {
        display: flex;
        align-items: stretch;
        border-bottom: 1px solid var(--knp-gray);
        height: 440px;
        overflow: hidden;
    }
    .hero-content {
        flex: 1;
        padding: 60px 40px 60px 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .hero-title {
        font-size: 58px;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--knp-dark);
    }
    .hero-red-line {
        width: 40px;
        height: 2px;
        background: var(--knp-red);
        margin-bottom: 25px;
    }
    .hero-desc {
        font-size: 15px;
        line-height: 1.8;
        color: #444;
        max-width: 450px;
    }
    .hero-img {
        flex: 1;
        background: #000;
    }
    .hero-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* 2. FILTER BAR */
    .filter-bar {
        padding: 30px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--knp-gray);
        flex-wrap: wrap;
        gap: 20px;
    }
    .filter-links {
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    .filter-link {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #666;
        text-decoration: none;
        padding-bottom: 5px;
        position: relative;
    }
    .filter-link.active {
        color: var(--knp-red);
    }
    .filter-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--knp-red);
    }
    .filter-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .search-input {
        border: 1px solid var(--knp-gray);
        padding: 10px 15px;
        border-radius: 4px;
        font-size: 13px;
        width: 200px;
        outline: none;
    }
    .sort-select {
        border: 1px solid var(--knp-gray);
        padding: 10px 15px;
        border-radius: 4px;
        font-size: 13px;
        outline: none;
        background: #FFF;
    }

    /* 3. FEATURED SECTION */
    .featured-section {
        padding: 60px 0;
        display: flex;
        gap: 40px;
        align-items: stretch;
    }
    .featured-post {
        flex: 1;
        aspect-ratio: 2 / 3;
        align-self: flex-start;
        background: #111;
        border-radius: 6px;
        overflow: hidden;
        position: relative;
        color: #FFF;
        display: block;
        text-decoration: none;
    }
    .featured-post img {
        width: 100%;
        height: 100%;
        position: absolute;
        inset: 0;
        object-fit: cover;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }
    .featured-post:hover img {
        opacity: 0.8;
    }
    .featured-post-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 40px;
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
    }
    .tag-red {
        display: inline-block;
        background: var(--knp-red);
        color: #FFF;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 4px 8px;
        border-radius: 2px;
        margin-bottom: 15px;
    }
    .post-meta {
        font-size: 12px;
        color: #CCC;
        margin-bottom: 10px;
    }
    .featured-post-title {
        font-size: 32px;
        font-family: 'Cormorant Garamond', serif;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 15px;
        color: #FFF;
    }
    .featured-post-desc {
        font-size: 14px;
        color: #EEE;
        line-height: 1.6;
        margin-bottom: 20px;
        max-width: 90%;
    }
    .read-more-link {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #FFF;
        text-decoration: none;
    }

    .featured-product {
        flex: 1;
        background: var(--knp-light);
        border-radius: 6px;
        padding: 40px 30px;
        display: flex;
        flex-direction: column;
    }
    .featured-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--knp-red);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }
    .fp-img {
        width: 100%;
        height: 280px;
        background: #FFF;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .fp-img img {
        max-height: 250px;
        max-width: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }
    .fp-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .fp-desc {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }
    .fp-link {
        font-size: 11px;
        font-weight: 700;
        color: var(--knp-red);
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        margin-top: auto;
    }

    /* 4. POST GRID */
    .post-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        padding-bottom: 60px;
    }
    .post-card {
        display: block;
        text-decoration: none;
        color: var(--knp-dark);
        border: 1px solid var(--knp-gray);
        border-radius: 6px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .post-img-wrapper {
        position: relative;
        aspect-ratio: 2 / 3;
        overflow: hidden;
    }
    .post-img-wrapper img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .post-card:hover .post-img-wrapper img {
        transform: scale(1.05);
    }
    .post-tag-abs {
        position: absolute;
        bottom: 15px;
        left: 15px;
        margin: 0;
    }
    .post-content {
        padding: 25px;
    }
    .pc-meta {
        font-size: 11px;
        color: #888;
        margin-bottom: 10px;
    }
    .pc-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 12px;
        color: var(--knp-dark);
    }
    .pc-desc {
        font-size: 13px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    .pc-link {
        font-size: 10px;
        font-weight: 700;
        color: var(--knp-red);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* 5. NEWSLETTER STRIP */
    .newsletter-strip {
        background: var(--knp-light);
        padding: 50px 0;
        margin-bottom: 60px;
    }
    .nl-container {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 40px;
        padding: 0 20px;
    }
    .nl-icon {
        color: var(--knp-red);
    }
    .nl-text {
        flex: 1;
    }
    .nl-text h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .nl-text p {
        font-size: 13px;
        color: #666;
    }
    .nl-form {
        display: flex;
        gap: 10px;
    }
    .nl-form input {
        padding: 12px 20px;
        border: 1px solid var(--knp-gray);
        border-radius: 4px;
        width: 250px;
        font-size: 13px;
        outline: none;
    }
    .nl-btn {
        background: var(--knp-red);
        color: #FFF;
        border: none;
        padding: 0 25px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        cursor: pointer;
    }

    /* 6. BOTTOM SECTION (POPULAR & QUOTE) */
    .bottom-section {
        display: flex;
        gap: 40px;
        padding-bottom: 80px;
        align-items: stretch;
    }
    .popular-reads {
        flex: 1;
    }
    .pr-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 25px;
        color: #888;
    }
    .pr-item {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-bottom: 20px;
        text-decoration: none;
        color: var(--knp-dark);
    }
    .pr-img {
        width: 80px;
        height: 120px;
        border-radius: 4px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .pr-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .pr-text h5 {
        font-size: 14px;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 5px;
    }
    .pr-text span {
        font-size: 11px;
        color: #888;
    }

    .quote-block {
        flex: 2;
        background: var(--knp-light);
        border-radius: 6px;
        display: flex;
        overflow: hidden;
    }
    .qb-content {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }
    .qb-quote-mark {
        font-size: 60px;
        font-family: Georgia, serif;
        color: var(--knp-red);
        line-height: 1;
        position: absolute;
        top: 20px;
        left: 30px;
    }
    .qb-text {
        font-family: 'Cormorant Garamond', serif;
        font-size: 26px;
        font-weight: 600;
        line-height: 1.4;
        margin-top: 30px;
        margin-bottom: 20px;
    }
    .qb-signature {
        font-family: 'Great Vibes', cursive, serif;
        font-size: 24px;
        margin-bottom: 5px;
    }
    .qb-role {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #888;
    }
    .qb-img {
        flex: 0 0 40%;
        background: #000;
    }
    .qb-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 991px) {
        .journal-hero { flex-direction: column; height: auto; }
        .hero-content { padding: 40px 20px; }
        .hero-img { height: 300px; }

        .featured-section {
            flex-direction: column;
            padding: 40px 0;
            gap: 30px;
        }
        .featured-post {
            width: 100%;
            max-width: 520px;
            height: auto;
            aspect-ratio: 2 / 3;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            border-radius: 8px;
        }
        .featured-post img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .featured-post-content {
            position: relative;
            z-index: 2;
            padding: 32px 28px 24px;
            background: linear-gradient(to top, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.65) 65%, transparent 100%);
        }
        .featured-post-title {
            font-size: 28px;
            line-height: 1.25;
        }
        .featured-product {
            padding: 30px 24px;
            border-radius: 8px;
        }
        .featured-product .fp-img {
            height: 240px !important;
            background: #FFF;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .featured-product .fp-img img {
            max-height: 220px !important;
            width: auto !important;
            object-fit: contain !important;
            display: block;
            margin: 0 auto;
        }

        .post-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }

        .nl-container { flex-direction: column; text-align: center; gap: 20px; }
        .nl-form { width: 100%; justify-content: center; }

        .bottom-section {
            flex-direction: column;
            gap: 30px;
            padding-bottom: 50px;
        }
        .quote-block {
            flex-direction: column;
            border-radius: 8px;
            overflow: hidden;
        }
        .qb-content {
            padding: 32px 28px;
            flex: none !important;
        }
        .qb-img {
            flex: none !important;
            height: 240px !important;
            width: 100%;
            overflow: hidden;
        }
        .qb-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 25%;
        }
    }
    
    .journal-hero-img {
        width: 100%;
        display: block;
        height: auto;
    }
    
    @media (max-width: 767px) {
        /* Section container padding */
        .section-container {
            padding: 0 16px;
        }

        /* 1. Hero banner: 100% visible, zero crop */
        .journal-hero-img {
            width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
            display: block;
        }

        /* 2. Featured section */
        .featured-section {
            padding: 24px 0 28px;
            gap: 20px;
            flex-direction: column;
        }
        .featured-post {
            min-height: 290px;
            height: auto;
            aspect-ratio: 2 / 3;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        .featured-post img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .featured-post-content {
            position: relative;
            z-index: 2;
            padding: 22px 18px 18px;
            background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.65) 65%, transparent 100%);
        }
        .featured-post-title {
            font-size: clamp(20px, 5.5vw, 24px);
            line-height: 1.25;
            margin-bottom: 8px;
        }
        .featured-post-desc {
            font-size: 12px;
            line-height: 1.5;
            color: #EEE;
            margin-bottom: 12px;
            max-width: 100%;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .read-more-link {
            font-size: 10.5px;
            letter-spacing: 1px;
        }

        /* Featured product */
        .featured-product {
            padding: 20px 16px;
            border-radius: 8px;
            background: #F9F9F9;
            border: 1px solid var(--knp-gray);
        }
        .featured-product .fp-img {
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FFF;
            border-radius: 6px;
            margin-bottom: 14px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.04);
        }
        .featured-product .fp-img img {
            max-height: 145px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .featured-product .fp-title {
            font-size: 18px;
            margin-bottom: 6px;
            line-height: 1.25;
        }
        .featured-product .fp-desc {
            font-size: 12px;
            line-height: 1.5;
            color: #666;
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .featured-product .fp-link {
            font-size: 10.5px;
            font-weight: 700;
        }

        /* 3. Post grid: 2-column luxury card layout */
        .post-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
            padding-bottom: 35px !important;
        }
        .post-card {
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #FFF;
            border: 1px solid var(--knp-gray);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .post-img-wrapper {
            aspect-ratio: 2 / 3 !important;
            width: 100%;
            overflow: hidden;
            position: relative;
        }
        .post-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .post-tag-abs {
            position: absolute;
            bottom: 8px;
            left: 8px;
            font-size: 8px !important;
            padding: 3px 6px !important;
            letter-spacing: 0.5px;
            border-radius: 2px;
        }
        .post-content {
            padding: 12px 10px 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .pc-meta {
            font-size: 9px;
            color: #888;
            margin-bottom: 4px;
        }
        .pc-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(14px, 3.8vw, 16px);
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 6px;
            color: var(--knp-dark);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .pc-desc {
            font-size: 11px;
            color: #666;
            line-height: 1.4;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .pc-link {
            font-size: 9.5px;
            font-weight: 700;
            color: var(--knp-red);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: auto;
        }

        /* 4. Bottom Section: Popular Reads & Quote Block */
        .bottom-section {
            gap: 32px;
            padding-bottom: 48px;
        }
        .popular-reads {
            width: 100%;
        }
        .pr-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-bottom: 16px;
            color: #888;
            border-bottom: 1px solid var(--knp-gray);
            padding-bottom: 8px;
        }
        .pr-item {
            margin-bottom: 12px;
            gap: 12px;
            display: flex;
            align-items: center;
        }
        .pr-img {
            width: 72px;
            height: 108px;
            border-radius: 4px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .pr-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .pr-text h5 {
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 3px;
            color: var(--knp-dark);
        }
        .pr-text span {
            font-size: 10px;
            color: #888;
        }

        /* Quote Block */
        .quote-block {
            border-radius: 8px;
            background: #F9F9F9;
            border: 1px solid var(--knp-gray);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .qb-content {
            padding: 24px 18px 20px;
            position: relative;
            text-align: center;
            flex: none !important;
        }
        .qb-quote-mark {
            font-size: 46px;
            color: var(--knp-red);
            opacity: 0.14;
            position: absolute;
            top: 6px;
            left: 14px;
            line-height: 1;
        }
        .qb-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(16.5px, 4.8vw, 20px);
            font-style: italic;
            font-weight: 600;
            line-height: 1.38;
            margin: 8px 0 14px;
            color: var(--knp-dark);
        }
        .qb-signature {
            font-family: 'Great Vibes', cursive, serif;
            font-size: 22px;
            margin-bottom: 3px;
            color: var(--knp-dark);
        }
        .qb-role {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #888;
            text-transform: uppercase;
        }
        .qb-img {
            flex: none !important;
            height: 180px !important;
            width: 100%;
            overflow: hidden;
            background: #111;
        }
        .qb-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 25%;
        }

        .filter-links { gap: 15px; }
        .nl-form { flex-direction: column; }
        .nl-form input { width: 100%; }
        .nl-btn { padding: 15px; }

        #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
            display: none !important;
        }
    }
</style>

<div class="journal-wrapper">

    <!-- 1. HERO SECTION -->
    <section class="journal-hero-banner" style="width: 100%; display: block;">
        <img src="{{ asset('images/banner/journal.png') }}" alt="The Journal" class="journal-hero-img">
    </section>



    <!-- 3. FEATURED SECTION -->
    <div class="section-container">
        <section class="featured-section">
            @if(isset($blogs) && $blogs->count() > 0)
                @php $featured = $blogs->first(); @endphp
                <a href="{{ route('blog.show', $featured->url_name ?: $featured->id) }}" class="featured-post">
                    <img src="{{ house_main_media_url(basename($featured->image), 'uploads/blogs') }}" onerror="this.src='{{ asset('images/knp/brand_story_new.png') }}'" alt="{{ $featured->title }}">
                    <div class="featured-post-content">
                        <span class="tag-red">STYLE</span>
                        <div class="post-meta">{{ \Carbon\Carbon::parse($featured->date)->format('M d, Y') }} &bull; 5 min read</div>
                        <h2 class="featured-post-title">{{ $featured->title }}</h2>
                        <p class="featured-post-desc">{{ \Illuminate\Support\Str::limit(strip_tags($featured->description), 120) }}</p>
                        <span class="read-more-link">READ MORE &rarr;</span>
                    </div>
                </a>
            @else
                <a href="#" class="featured-post">
                    <img src="{{ asset('images/knp/brand_story_new.png') }}" alt="Featured">
                    <div class="featured-post-content">
                        <span class="tag-red">STYLE</span>
                        <div class="post-meta">May 24, 2024 &bull; 5 min read</div>
                        <h2 class="featured-post-title">The Power of First Impressions</h2>
                        <p class="featured-post-desc">Why your style speaks before you do &mdash; and how to make it unforgettable.</p>
                        <span class="read-more-link">READ MORE &rarr;</span>
                    </div>
                </a>
            @endif

            <div class="featured-product">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <span class="featured-label" style="margin-bottom: 0;">FEATURED</span>
                    <a href="{{ url('combos') }}" style="font-size: 11px; font-weight: 700; color: #555; text-transform: uppercase; text-decoration: none; letter-spacing: 1px;">VIEW ALL</a>
                </div>
                @php 
                    // Pick the latest combo that has an image, or latest combo
                    $featuredCombo = \App\Models\GiftCombo::query()
                        ->where(function($q) {
                            $q->whereNotNull('combo_image')->where('combo_image', '!=', '')
                              ->orWhereNotNull('combo_image_2')->where('combo_image_2', '!=', '')
                              ->orWhereNotNull('combo_image_3')->where('combo_image_3', '!=', '');
                        })
                        ->latest('id')
                        ->first();
                    
                    if (!$featuredCombo) {
                        $featuredCombo = \App\Models\GiftCombo::query()->latest('id')->first();
                    }

                    $comboImageFile = $featuredCombo ? ($featuredCombo->combo_image ?: ($featuredCombo->combo_image_2 ?: $featuredCombo->combo_image_3)) : null;
                @endphp
                @if($featuredCombo)
                    <a href="{{ url('combos/' . $featuredCombo->id) }}" class="fp-img" style="display: flex; text-decoration: none;">
                        @if($comboImageFile)
                            <img src="{{ rtrim(env('MAIN_URL'), '/') }}/images/{{ $comboImageFile }}" onerror="this.src='{{ asset('images/knp/knp_gift_box_1773738766506.png') }}'" alt="{{ $featuredCombo->combo_name }}">
                        @else
                            <img src="{{ asset('images/knp/knp_gift_box_1773738766506.png') }}" alt="{{ $featuredCombo->combo_name }}">
                        @endif
                    </a>
                    <a href="{{ url('combos/' . $featuredCombo->id) }}" style="text-decoration: none; color: inherit;">
                        <h3 class="fp-title knp-serif">{{ $featuredCombo->combo_name }}</h3>
                    </a>
                    @if($featuredCombo->offer_price > 0 || $featuredCombo->mrp_price > 0)
                        <div style="font-size: 15px; font-weight: 700; color: var(--knp-dark); margin-bottom: 8px;">
                            ₹{{ number_format($featuredCombo->offer_price > 0 ? $featuredCombo->offer_price : $featuredCombo->mrp_price) }}
                            @if($featuredCombo->offer_price > 0 && $featuredCombo->offer_price < $featuredCombo->mrp_price)
                                <span style="font-size: 13px; color: #999; text-decoration: line-through; font-weight: 500; margin-left: 6px;">
                                    ₹{{ number_format($featuredCombo->mrp_price) }}
                                </span>
                            @endif
                        </div>
                    @endif
                    <p class="fp-desc">{{ \Illuminate\Support\Str::limit($featuredCombo->product_description ?? 'More than a gift. It\'s a statement.', 60) }}</p>
                    <a href="{{ url('combos/' . $featuredCombo->id) }}" class="fp-link">EXPLORE NOW &rarr;</a>
                @else
                    <a href="{{ url('combos') }}" class="fp-img" style="display: flex; text-decoration: none;">
                        <img src="{{ asset('images/knp/knp_gift_box_1773738766506.png') }}" alt="Signature Box">
                    </a>
                    <h3 class="fp-title knp-serif">The Signature Box</h3>
                    <p class="fp-desc">More than a gift. It's a statement.</p>
                    <a href="{{ url('combos') }}" class="fp-link">EXPLORE NOW &rarr;</a>
                @endif
            </div>
        </section>
    </div>

    <!-- 4. POST GRID -->
    <div class="section-container">
        <div class="post-grid">
            @if(isset($blogs) && $blogs->count() > 1)
                @foreach($blogs->skip(1) as $blog)
                <a href="{{ route('blog.show', $blog->url_name ?: $blog->id) }}" class="post-card">
                    <div class="post-img-wrapper">
                        <img src="{{ house_main_media_url(basename($blog->image), 'uploads/blogs') }}" onerror="this.src='{{ asset('images/knp/product_shirt.png') }}'" alt="{{ $blog->title }}">
                        <span class="tag-red post-tag-abs">LIFESTYLE</span>
                    </div>
                    <div class="post-content">
                        <div class="pc-meta">{{ \Carbon\Carbon::parse($blog->date)->format('M d, Y') }} &bull; 4 min read</div>
                        <h3 class="pc-title">{{ $blog->title }}</h3>
                        <p class="pc-desc">{{ \Illuminate\Support\Str::limit(strip_tags($blog->description), 80) }}</p>
                        <span class="pc-link">READ MORE &rarr;</span>
                    </div>
                </a>
                @endforeach
            @elseif(!isset($blogs) || $blogs->count() == 0)
                <a href="#" class="post-card">
                    <div class="post-img-wrapper">
                        <img src="{{ asset('images/knp/product_shirt.png') }}" alt="Post">
                        <span class="tag-red post-tag-abs">LIFESTYLE</span>
                    </div>
                    <div class="post-content">
                        <div class="pc-meta">May 20, 2024 &bull; 4 min read</div>
                        <h3 class="pc-title">Discipline Today, Freedom Tomorrow</h3>
                        <p class="pc-desc">Small daily choices build the life you dream of.</p>
                        <span class="pc-link">READ MORE &rarr;</span>
                    </div>
                </a>
                <a href="#" class="post-card">
                    <div class="post-img-wrapper">
                        <img src="{{ asset('images/knp/product_watch.png') }}" alt="Post">
                        <span class="tag-red post-tag-abs">STYLE</span>
                    </div>
                    <div class="post-content">
                        <div class="pc-meta">May 17, 2024 &bull; 4 min read</div>
                        <h3 class="pc-title">Watches Are More Than Timekeepers</h3>
                        <p class="pc-desc">A watch reflects your personality, presence and purpose.</p>
                        <span class="pc-link">READ MORE &rarr;</span>
                    </div>
                </a>
                <a href="#" class="post-card">
                    <div class="post-img-wrapper">
                        <img src="{{ asset('images/knp/product_perfume.png') }}" alt="Post">
                        <span class="tag-red post-tag-abs">LIFESTYLE</span>
                    </div>
                    <div class="post-content">
                        <div class="pc-meta">May 14, 2024 &bull; 3 min read</div>
                        <h3 class="pc-title">Find a Scent That Defines You</h3>
                        <p class="pc-desc">A signature scent creates a memory that stays.</p>
                        <span class="pc-link">READ MORE &rarr;</span>
                    </div>
                </a>
            @endif
        </div>

        @if (isset($blogs) && $blogs->hasPages())
            <div style="margin-bottom: 60px;">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>



    <!-- 6. BOTTOM SECTION -->
    <div class="section-container">
        <div class="bottom-section">
            <div class="popular-reads">
                <h4 class="pr-title">POPULAR READS</h4>
                @if(isset($blogs) && $blogs->count() > 0)
                    @foreach($blogs->take(3) as $pop)
                    <a href="{{ route('blog.show', $pop->url_name ?: $pop->id) }}" class="pr-item">
                        <div class="pr-img">
                            <img src="{{ house_main_media_url(basename($pop->image), 'uploads/blogs') }}" onerror="this.src='{{ asset('images/knp/product_shirt.png') }}'" alt="Thumb">
                        </div>
                        <div class="pr-text">
                            <h5>{{ \Illuminate\Support\Str::limit($pop->title, 40) }}</h5>
                            <span>{{ \Carbon\Carbon::parse($pop->date)->format('M d, Y') }} &bull; 4 min read</span>
                        </div>
                    </a>
                    @endforeach
                @else
                    <a href="#" class="pr-item">
                        <div class="pr-img"><img src="{{ asset('images/knp/product_shirt.png') }}" alt="Thumb"></div>
                        <div class="pr-text">
                            <h5>5 Habits of a True Gentleman</h5>
                            <span>May 10, 2024 &bull; 4 min read</span>
                        </div>
                    </a>
                    <a href="#" class="pr-item">
                        <div class="pr-img"><img src="{{ asset('images/knp/product_watch.png') }}" alt="Thumb"></div>
                        <div class="pr-text">
                            <h5>Behind the Creation of HOUSE OF KNP</h5>
                            <span>May 08, 2024 &bull; 6 min read</span>
                        </div>
                    </a>
                    <a href="#" class="pr-item">
                        <div class="pr-img"><img src="{{ asset('images/knp/product_perfume.png') }}" alt="Thumb"></div>
                        <div class="pr-text">
                            <h5>Build a Wardrobe That Works for You</h5>
                            <span>May 05, 2024 &bull; 4 min read</span>
                        </div>
                    </a>
                @endif
            </div>

            <div class="quote-block">
                <div class="qb-content">
                    <div class="qb-quote-mark">&ldquo;</div>
                    <h3 class="qb-text">Style is not about clothes.<br>It's about confidence.<br>It's about presence.<br>It's about who you are.<br><span style="color:var(--knp-red);">Ignite your presence.</span></h3>
                    <div class="qb-signature">Kaveri Logesh</div>
                    <span class="qb-role">FOUNDER, HOUSE OF KNP</span>
                </div>
                <div class="qb-img">
                    <img src="{{ asset('images/knp/brand_story_new.png') }}" alt="Presence">
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
