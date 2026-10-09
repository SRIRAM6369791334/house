@extends('layouts.app')

@php
    $rawMetaTitle = trim((string) ($blog->meta_title ?? ''));
    $blogMetaTitle = (!empty($rawMetaTitle) && !in_array(strtolower($rawMetaTitle), ['house of knp', 'house', 'knp'], true))
        ? $rawMetaTitle
        : (($blog->title ?? 'The Journal') . ' | House of KNP');

    $rawMetaDesc = trim((string) ($blog->meta_description ?? ''));
    $blogExcerpt = trim(strip_tags((string) ($blog->description ?? '')));
    $blogMetaDescription = (!empty($rawMetaDesc) && mb_strlen($rawMetaDesc) > 20 && !in_array(strtolower($rawMetaDesc), ['house of knp', 'house', 'knp'], true))
        ? $rawMetaDesc
        : (!empty($blogExcerpt) ? \Illuminate\Support\Str::limit($blogExcerpt, 160) : 'Read the latest journal post from House of KNP luxury menswear.');

    $blogMetaKeywords = trim((string) ($blog->meta_key ?? ''));
    $blogCanonical = route('blog.show', $blog->url_name ?: $blog->id);
    $blogMetaImage = house_main_media_url(basename($blog->image), 'uploads/blogs');
@endphp

@section('meta_title', $blogMetaTitle)
@section('meta_description', $blogMetaDescription)
@section('meta_keywords', $blogMetaKeywords)
@section('canonical_url', $blogCanonical)
@section('meta_image', $blogMetaImage)
@section('meta_type', 'article')
@section('content')
    <style>
        .journal-detail {
            --journal-accent: #c80000;
            --journal-ink: #151515;
            background: #f5f2ed;
            color: var(--journal-ink);
            overflow: hidden;
        }

        /* 1. HERO SHARED BANNER & BREADCRUMB */
        .journal-detail .about-shared-banner {
            /* min-height: 240px; */
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .journal-detail .about-shared-banner.overlay::before {
            background: linear-gradient(to bottom, rgba(8, 8, 8, 0.68) 0%, rgba(8, 8, 8, 0.80) 100%) !important;
            opacity: 1 !important;
        }

        .journal-detail .about-shared-banner .bread-main {
            position: relative;
            z-index: 2;
            padding: 30px 20px;
            text-align: center;
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
            left: auto;
            transform: none;
            bottom: auto;
        }

        .journal-detail .bred-hading h5 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(22px, 3.8vw, 34px);
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #ffffff;
            margin: 0 0 10px;
            line-height: 1.25;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
        }

        .journal-detail .breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 6px;
            margin: 0;
            padding: 0;
            font-size: 12px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .journal-detail .breadcrumb li {
            color: #d6d0c7;
            display: inline-flex;
            align-items: center;
        }

        .journal-detail .breadcrumb li a {
            color: #f0ede8;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .journal-detail .breadcrumb li a:hover {
            color: var(--journal-accent);
        }

        .journal-detail .breadcrumb li.active {
            color: #ffffff;
            font-weight: 600;
        }

        .journal-detail .breadcrumb li+li:before {
            content: "/";
            padding: 0 6px;
            color: rgba(255, 255, 255, 0.45);
        }

        /* 2. MAIN CONTENT LAYOUT */
        .journal-content-wrap {
            align-items: start;
            display: grid;
            gap: 46px;
            grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
            margin: 0 auto;
            max-width: 1280px;
            padding: 70px 30px 90px;
        }

        .journal-content-wrap > div {
            min-width: 0;
        }

        /* 3. COVER IMAGE */
        .journal-cover-wrap {
            border-radius: 8px;
            box-shadow: 0 16px 45px rgba(28, 22, 17, 0.12);
            margin: 0 0 24px;
            overflow: hidden;
            position: relative;
            width: 100%;
            max-width: 600px;
            aspect-ratio: 2 / 3;
            margin-left: auto;
            margin-right: auto;
            background: #111;
        }

        .journal-cover {
            background: #120021;
            display: block;
            height: 100%;
            aspect-ratio: 2 / 3;
            object-fit: cover;
            object-position: center;
            width: 100%;
            border-radius: 8px;
        }

        /* 4. ARTICLE CONTAINER */
        .journal-article {
            background: #fff;
            border: 1px solid #ddd5cb;
            border-radius: 8px;
            box-shadow: 0 26px 65px rgba(35, 27, 20, 0.1);
            min-height: 540px;
            padding: clamp(34px, 5vw, 48px);
            position: relative;
        }

        .journal-article-head {
            align-items: flex-start;
            display: flex;
            gap: 16px;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .journal-article-name {
            font-family: 'Cormorant Garamond', Georgia, serif;
            color: var(--journal-ink);
            font-size: clamp(24px, 3vw, 34px);
            font-weight: 700;
            letter-spacing: 0.01em;
            line-height: 1.25;
            margin: 0;
            text-transform: uppercase;
            flex: 1;
        }

        .journal-article-date {
            align-items: center;
            color: var(--journal-accent);
            display: inline-flex;
            gap: 6px;
            flex: 0 0 auto;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding-top: 6px;
        }

        .journal-article-date::before {
            background: var(--journal-accent);
            border-radius: 50%;
            content: '';
            display: inline-block;
            height: 6px;
            width: 6px;
        }

        .journal-description,
        .journal-article p {
            color: #44403c;
            font-size: 16px;
            line-height: 1.85;
            white-space: normal;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .journal-description h1,
        .journal-description h2,
        .journal-description h3,
        .journal-description h4 {
            color: var(--journal-ink);
            font-family: 'Cormorant Garamond', Georgia, serif;
            line-height: 1.25;
            margin: 28px 0 14px;
        }

        .journal-description p {
            margin-bottom: 16px;
        }

        .journal-description a,
        .journal-content-link {
            color: #b40016;
            text-decoration: underline;
            text-underline-offset: 3px;
            font-weight: 600;
            word-break: break-all;
            transition: color 0.2s ease, text-decoration 0.2s ease;
        }

        .journal-description a:hover,
        .journal-content-link:hover {
            color: #8b0011;
            text-decoration: underline;
            text-decoration-thickness: 2px;
        }

        /* 5. SIDEBAR */
        .journal-sidebar {
            align-self: start;
            background: #111;
            border-radius: 8px;
            box-shadow: 0 26px 65px rgba(20, 20, 20, 0.16);
            padding: 32px 28px 22px;
            position: sticky;
            top: 24px;
            width: 100%;
        }

        .journal-sidebar-head {
            border-bottom: 2px solid rgba(255, 255, 255, 0.22);
            margin-bottom: 4px;
            padding-bottom: 14px;
        }

        .journal-sidebar-head h2 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            color: #fff;
            font-size: 28px;
            letter-spacing: 0.02em;
            margin: 0;
        }

        .journal-recent {
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            color: #fff;
            display: grid;
            gap: 15px;
            grid-template-columns: 80px 1fr;
            padding: 18px 0;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .journal-recent:last-child {
            border-bottom: none;
        }

        .journal-recent-thumb {
            border-radius: 6px;
            height: 120px;
            object-fit: cover;
            width: 80px;
            flex-shrink: 0;
        }

        .journal-recent-copy {
            align-self: center;
            min-width: 0;
        }

        .journal-recent strong {
            color: #ffffff;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.38;
            transition: color 0.2s ease;
        }

        .journal-recent time {
            color: rgba(255, 255, 255, 0.65);
            display: block;
            font-size: 10px;
            letter-spacing: 1.2px;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .journal-recent:hover {
            text-decoration: none;
        }

        .journal-recent:hover strong {
            color: #ff3535;
        }

        .journal-empty {
            color: rgba(255, 255, 255, 0.65);
            font-size: 14px;
            padding: 20px 0;
            text-align: center;
        }

        /* 6. TABLET RESPONSIVE (768px - 991px) */
        @media(max-width: 991px) {
            .journal-content-wrap {
                gap: 36px;
                grid-template-columns: 1fr;
                padding: 48px 24px 64px;
            }

            .journal-sidebar {
                position: static;
            }

            .journal-cover {
                aspect-ratio: 2 / 3;
                max-height: none;
                object-fit: cover;
                object-position: center;
            }
        }

        /* 7. MOBILE RESPONSIVE (<= 767px) */
        @media(max-width: 767px) {
            .journal-detail .about-shared-banner {
                min-height: 140px;
                padding: 0 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-position: right center !important;
            }

            .journal-detail .about-shared-banner.overlay::before {
                background: linear-gradient(to bottom, rgba(8, 8, 8, 0.86) 0%, rgba(8, 8, 8, 0.94) 100%) !important;
                opacity: 1 !important;
            }

            .journal-detail .about-shared-banner .bread-main {
                position: static;
                transform: none;
                padding: 22px 8px;
                width: 100%;
            }

            .journal-detail .bred-hading h5 {
                font-size: clamp(17px, 5.2vw, 21px);
                line-height: 1.25;
                margin-bottom: 6px;
                letter-spacing: 0.02em;
            }

            .journal-detail .breadcrumb {
                font-size: 11px;
                gap: 4px 6px;
                justify-content: center;
            }

            .journal-detail .breadcrumb li {
                display: inline-flex;
                align-items: center;
            }

            .journal-detail .breadcrumb li+li:before {
                content: "›";
                padding: 0 5px 0 1px;
                color: rgba(255, 255, 255, 0.45);
                font-size: 13px;
                line-height: 1;
            }

            .journal-detail .breadcrumb li.active {
                max-width: 210px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .journal-content-wrap {
                gap: 26px;
                padding: 24px 16px 42px;
            }

            .journal-cover-wrap {
                margin-bottom: 18px;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            }

            .journal-cover {
                aspect-ratio: 2 / 3 !important;
                height: 100% !important;
                max-height: none !important;
                object-fit: cover !important;
                object-position: center !important;
                border-radius: 8px;
            }

            .journal-article {
                min-height: 0;
                padding: 24px 18px;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            }

            .journal-article-head {
                align-items: flex-start;
                flex-direction: column;
                justify-content: flex-start !important;
                gap: 6px;
                margin-bottom: 14px;
            }

            .journal-article-name {
                flex: none !important;
                width: 100%;
                font-size: clamp(20px, 5.8vw, 25px);
                line-height: 1.25;
                letter-spacing: 0.01em;
                margin: 0;
            }

            .journal-article-date {
                font-size: 10px;
                letter-spacing: 1.2px;
                padding-top: 0;
            }

            .journal-description,
            .journal-description p,
            .journal-article p {
                font-size: 14.5px;
                line-height: 1.8;
            }

            .journal-sidebar {
                border-radius: 8px;
                padding: 22px 18px 16px;
            }

            .journal-sidebar-head {
                padding-bottom: 10px;
                margin-bottom: 4px;
            }

            .journal-sidebar-head h2 {
                font-size: 21px;
            }

            .journal-recent {
                grid-template-columns: 72px 1fr;
                gap: 12px;
                padding: 14px 0;
            }

            .journal-recent-thumb {
                width: 72px;
                height: 72px;
                border-radius: 5px;
            }

            .journal-recent strong {
                font-size: 13.5px;
                line-height: 1.35;
            }

            .journal-recent time {
                font-size: 9.5px;
                margin-top: 6px;
            }

            /* Prevent scrollUp button overlap */
            #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
                display: none !important;
            }
        }
    </style>

    <main class="journal-detail">
        <div class="about-shared-banner breadcumb-area breadcumb-3 overlay pos-rltv">
            <div class="bread-main">
                <div class="bred-hading text-center">
                    <h5>{{ $blog->title }}</h5>
                </div>
                <ol class="breadcrumb">
                    <li class="home"><a title="Go to Home Page" href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('blog') }}">Blog</a></li>
                    <li class="active">{{ $blog->title }}</li>
                </ol>
            </div>
        </div>


        <div class="journal-content-wrap">
            <div>
                <div class="journal-cover-wrap">
                    <img class="journal-cover" src="{{ house_main_media_url(basename($blog->image), 'uploads/blogs') }}"
                        onerror="this.src='{{ asset('images/knp/brand_story_new.png') }}'" alt="{{ $blog->title }}" decoding="async">
                    {{-- <span class="journal-cover-index">STORY</span> --}}
                </div>
                <article class="journal-article">
                    <header class="journal-article-head">
                        <h2 class="journal-article-name">{{ $blog->title }}</h2>
                        <time class="journal-article-date"
                            datetime="{{ \Carbon\Carbon::parse($blog->date)->format('Y-m-d') }}">
                            {{ \Carbon\Carbon::parse($blog->date)->format('d M Y') }}
                        </time>
                    </header>
                    @php
                        $rawDesc = $blog->description ?? '';
                        $hasHtmlTags = $rawDesc !== strip_tags($rawDesc);
                        if ($hasHtmlTags) {
                            $renderedBlogContent = $rawDesc;
                        } else {
                            $escapedDesc = e($rawDesc);
                            $linkedDesc = preg_replace(
                                '~(?<!["\'])(https?://[^\s<]+)~i',
                                '<a href="$1" target="_blank" rel="noopener noreferrer" class="journal-content-link">$1</a>',
                                $escapedDesc
                            );
                            $renderedBlogContent = nl2br($linkedDesc);
                        }
                    @endphp
                    <div class="journal-description">{!! $renderedBlogContent !!}</div>
                </article>
            </div>

            <aside class="journal-sidebar" aria-label="Recent posts">
                <div class="journal-sidebar-head">
                    {{-- <span>Continue Reading</span> --}}
                    <h2>More Stories</h2>
                </div>
                @forelse($recentBlogs as $recentBlog)
                    <a class="journal-recent" href="{{ route('blog.show', $recentBlog->url_name ?: $recentBlog->id) }}">
                        <img class="journal-recent-thumb"
                            src="{{ house_main_media_url(basename($recentBlog->image), 'uploads/blogs') }}" onerror="this.src='{{ asset('images/knp/brand_story_new.png') }}'" alt=""
                            loading="lazy" decoding="async">
                        <span class="journal-recent-copy">
                            <strong>{{ $recentBlog->title }}</strong>
                            <time
                                datetime="{{ \Carbon\Carbon::parse($recentBlog->date)->format('Y-m-d') }}">{{ \Carbon\Carbon::parse($recentBlog->date)->format('d M Y') }}</time>
                        </span>
                    </a>
                @empty
                    <p class="journal-empty">More journal stories will appear here.</p>
                @endforelse
            </aside>
        </div>
    </main>
@endsection
