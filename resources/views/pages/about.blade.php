@extends('layouts.app')

@section('content')

    <style>
        /* About Page Specific UI */
        :root {
            --knp-red: #B40016;
            --knp-dark: #111111;
            --knp-light: #F9F9F9;
            --knp-gray: #666666;
            --knp-border: #EAEAEA;
        }

        .about-wrapper {
            font-family: 'Manrope', sans-serif;
            color: var(--knp-dark);
            background: #FFFFFF;
            width: 100%;
            overflow-x: hidden;
        }

        .knp-serif {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }

        .section-padding {
            padding: 50px 20px;
            max-width: 1300px;
            margin: 0 auto;
        }

        /* 1. HERO SECTION */
        .about-hero-wrapper {
            position: relative;
            width: 100%;
            background: #000;
            height: 440px;
            min-height: 440px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .about-hero-bg {
            position: absolute;
            top: 0;
            right: 0;
            width: 64%;
            height: 100%;
            background: #111;
            z-index: 1;
        }

        .about-hero-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
        }

        .about-hero-content-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: none;
            margin: 0;
            display: flex;
        }

        .about-hero-content {
            background: #FFF;
            padding: 28px clamp(92px, 11vw, 180px) 28px clamp(32px, 6vw, 100px);
            width: 54%;
            height: 440px;
            min-height: 440px;
            clip-path: polygon(0 0, 100% 0, 82% 100%, 0 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .about-kicker {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--knp-red);
            margin-bottom: 15px;
            display: block;
        }

        .about-title {
            font-size: clamp(36px, 3.5vw, 46px);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 15px;
            color: var(--knp-dark);
        }

        .about-red-line {
            width: 40px;
            height: 2px;
            background: var(--knp-red);
            margin-bottom: 18px;
        }

        .about-text {
            font-size: 15px;
            line-height: 1.8;
            color: #444;
            margin-bottom: 20px;
            max-width: 470px;
        }

        .about-btn {
            display: inline-flex;
            align-items: center;
            background: var(--knp-red);
            color: #FFF;
            padding: 12px 25px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 2px;
            width: max-content;
        }

        /* 2. FOUNDER SECTION */
        .founder-section {
            display: flex;
            align-items: center;
            padding: 28px 20px 24px;
            gap: 56px;
            border-bottom: 1px solid var(--knp-border);
            max-width: 1120px;
            margin: 0 auto;
        }

        .founder-img {
            flex: 0 0 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: transparent;
            padding: 0;
        }

        .polaroid {
            background: #FFF;
            padding: 0;
            box-shadow: none;
            transform: none;
            max-width: 400px;
        }

        .polaroid img {
            width: 100%;
            display: block;
        }

        .founder-content {
            flex: 1;
            padding: 20px 0;
        }

        .founder-features {
            flex: 1;
            padding: 20px 0;
            border-left: 1px solid var(--knp-border);
            padding-left: 40px;
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .f-feature {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .f-icon {
            color: var(--knp-red);
        }

        .f-text h4 {
            font-family: 'Manrope', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .f-text p {
            font-size: 13px;
            line-height: 1.6;
            color: #666;
            margin: 0;
        }

        /* 3. OUR VALUES */
        .values-section {
            padding: 80px 20px;
            text-align: center;
            border-bottom: 1px solid var(--knp-border);
            max-width: 1300px;
            margin: 0 auto;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .value-card {
            text-align: center;
        }

        .value-card i,
        .value-card svg {
            color: var(--knp-red);
            margin-bottom: 15px;
            display: block;
        }

        .value-card h4 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .value-card p {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        /* 4. OUR JOURNEY */
        .journey-section {
            padding: 80px 20px;
            text-align: center;
            max-width: 1300px;
            margin: 0 auto;
        }

        .journey-timeline {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-top: 40px;
        }

        .journey-step {
            flex: 1;
            position: relative;
            text-align: center;
            padding: 0 15px;
        }

        .step-icon {
            width: 60px;
            height: 60px;
            background: var(--knp-red);
            color: #FFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 20px;
            position: relative;
            z-index: 2;
        }

        .step-arrow {
            position: absolute;
            top: 30px;
            right: -10px;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 18px;
        }

        .journey-step:last-child .step-arrow {
            display: none;
        }

        .journey-step h4 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .journey-step p {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        .journey-video-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
            margin-top: 42px;
        }

        .journey-video-card {
            background: #fff;
            border: 1px solid #eee;
            cursor: pointer;
            overflow: hidden;
            position: relative;
            aspect-ratio: 16 / 10;
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.08);
        }

        .journey-video-card video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .journey-video-card .journey-play {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.18);
            color: #fff;
            font-size: 22px;
        }

        .journey-video-name {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.4px;
            margin-top: 10px;
            text-transform: uppercase;
        }

        /* 5. OUR PROMISE */
        .promise-wrapper {
            background: var(--knp-light);
            width: 100%;
        }

        .promise-section {
            display: flex;
            align-items: stretch;
            max-width: 1300px;
            margin: 0 auto;
        }

        .promise-content {
            flex: 1;
            padding: 80px 40px 80px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .promise-title {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            color: var(--knp-dark);
        }

        .founder-signature {
            font-family: 'Great Vibes', cursive, serif;
            font-size: 32px;
            color: var(--knp-dark);
            margin-top: 20px;
        }

        .promise-img {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .promise-img img {
            max-width: 100%;
            mix-blend-mode: multiply;
        }

        /* Promise section: compact editorial treatment */
        .promise-wrapper {
            background: #fbfbfb;
            border-bottom: 4px solid var(--knp-red);
        }

        .promise-section {
            box-sizing: border-box;
            max-width: 1200px;
            min-height: 540px;
            align-items: center;
            gap: 72px;
        }

        .promise-content {
            padding: 48px 0;
            animation: promise-copy-enter .7s ease-out both;
        }

        .promise-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(38px, 3.2vw, 48px);
            font-weight: 700;
            letter-spacing: -.02em;
            line-height: 1.15;
            margin-bottom: 24px;
        }

        .promise-content .about-kicker {
            color: var(--knp-red);
            font-family: 'Manrope', sans-serif;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .18em;
        }

        .promise-content .about-text {
            max-width: 570px;
            font-size: 16px;
            line-height: 1.75;
        }

        .promise-content .founder-signature {
            font-family: 'Cormorant Garamond', Georgia, serif !important;
            font-size: 34px;
            font-weight: 500;
        }

        .promise-img {
            flex: 0 0 438px;
            padding: 0;
            animation: promise-image-enter .8s ease-out .12s both;
        }

        .promise-img img {
            width: 438px;
            height: 405px;
            max-width: 100%;
            object-fit: cover;
            object-position: center;
            mix-blend-mode: normal;
            display: block;
            transition: transform .45s ease, box-shadow .45s ease;
        }

        .promise-img:hover img {
            transform: scale(1.025);
            box-shadow: 0 16px 34px rgba(0, 0, 0, .16);
        }

        @keyframes promise-copy-enter {
            from {
                opacity: 0;
                transform: translateX(-26px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes promise-image-enter {
            from {
                opacity: 0;
                transform: translateX(26px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .promise-content,
            .promise-img {
                animation: none;
            }

            .promise-img img {
                transition: none;
            }
        }

        /* 6. STATS STRIP */
        .stats-strip-red {
            background: var(--knp-red);
            padding: 40px 0;
            color: #FFF;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-icon svg,
        .stat-icon i {
            color: #FFF;
            font-size: 28px;
        }

        .stat-text strong {
            display: block;
            font-size: 18px;
            font-weight: 700;
        }

        .stat-text span {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        /* 7. TESTIMONIALS (FROM GENTLEMEN) */
        .testi-carousel-wrapper {
            position: relative;
            margin-top: 40px;
        }

        .testi-grid {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 10px 5px;
            scroll-behavior: smooth;
        }

        .testi-grid::-webkit-scrollbar {
            display: none;
        }

        .testi-item-wrapper {
            flex: 0 0 calc(20% - 16px);
            min-width: 180px;
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
        }

        .testi-card {
            position: relative;
            border-radius: 6px;
            overflow: hidden;
            aspect-ratio: 16/9;
            background: #111;
            margin-bottom: 15px;
        }

        .testi-card video,
        .testi-card img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .testi-play {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.6);
            color: #FFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            pointer-events: none;
        }

        .testi-duration-left,
        .testi-duration-right {
            position: absolute;
            bottom: 8px;
            font-size: 11px;
            color: #FFF;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .testi-duration-left {
            left: 8px;
        }

        .testi-duration-right {
            right: 8px;
        }

        .testi-info {
            text-align: left;
        }

        .testi-info strong {
            display: block;
            font-size: 15px;
            font-weight: 800;
            color: #111;
            margin-bottom: 4px;
        }

        .testi-info p {
            font-size: 11px;
            color: #666;
            line-height: 1.4;
            margin-top: 5px;
        }

        .testi-carousel-btn {
            position: absolute;
            top: 40%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: #FFF;
            border: 1px solid #EAEAEA;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: #111;
        }

        .testi-carousel-btn:hover {
            background: var(--knp-red);
            color: #FFF;
            border-color: var(--knp-red);
        }

        .testi-carousel-btn.left {
            left: 0;
        }

        .testi-carousel-btn.right {
            right: 0;
        }

        .founder-content {
            flex: 1 1 420px;
            padding: 0;
        }

        .founder-features {
            flex: 0 0 225px;
            padding: 0 0 0 28px;
            gap: 22px;
        }

        .f-feature {
            gap: 15px;
        }

        .values-section {
            padding: 24px 20px 28px;
            max-width: 1120px;
        }

        .values-grid {
            gap: 0;
            margin-top: 20px;
        }

        .value-card {
            padding: 0 20px;
            border-right: 1px solid var(--knp-border);
        }

        .value-card:last-child {
            border-right: 0;
        }

        /* Customer-story cards: reference-scale thumbnails, overlays and controls. */
        .section-padding:has(.testi-carousel-wrapper) {
            max-width: 1400px;
            padding-top: 40px !important;
            padding-bottom: 48px !important;
        }

        .section-padding:has(.testi-carousel-wrapper) .section-head {
            margin-bottom: 24px !important;
        }

        .testi-carousel-wrapper {
            margin-top: 0;
            padding: 0 50px;
            max-width: 1300px;
            margin-left: auto;
            margin-right: auto;
        }

        .testi-grid {
            gap: 26px;
            padding: 0;
        }

        .testi-item-wrapper {
            flex-basis: calc(20% - 21px);
        }

        .testi-card {
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .testi-card img {
            object-fit: cover;
        }

        .testi-play {
            width: 44px;
            height: 44px;
            background: rgba(0, 0, 0, .45);
            border: 2px solid rgba(255, 255, 255, .92);
            font-size: 15px;
        }

        .testi-duration-left,
        .testi-duration-right {
            bottom: 7px;
            font-size: 10px;
            font-weight: 700;
        }

        .testi-info strong {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .testi-info p {
            font-size: 11px;
            line-height: 1.45;
            margin-top: 5px;
        }

        .testi-carousel-btn {
            width: 36px;
            height: 36px;
            top: 35%;
        }

        /* Journey row: compact, bold and clear like the reference. */
        .journey-section {
            padding: 38px 20px 46px;
            max-width: 1400px;
        }

        .journey-section>h2 {
            font-size: 24px !important;
        }

        .journey-timeline {
            margin-top: 24px;
        }

        .step-icon {
            width: 48px;
            height: 48px;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .journey-step {
            padding: 0 20px;
        }

        .journey-step h4 {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .75px;
            margin-bottom: 8px;
        }

        .journey-step p {
            font-size: 12px;
            line-height: 1.45;
            color: #555;
            max-width: 180px;
            margin: 0 auto;
        }

        .step-arrow {
            top: 24px;
            right: -5px;
            font-size: 18px;
            color: #bbb;
        }

        /* Values row: bold typography and the same icon set as the reference. */
        .values-section>h2 {
            font-size: 24px !important;
            letter-spacing: .2px;
        }

        .value-card svg {
            width: 29px;
            height: 29px;
            stroke-width: 1.9;
            margin-bottom: 11px !important;
        }

        .value-card h4 {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .75px;
            margin-bottom: 8px;
        }

        .value-card p {
            font-size: 12px;
            line-height: 1.45;
            color: #555;
            max-width: 170px;
            margin: 0 auto;
        }

        /* Founder feature column: clear icons and reference-style rhythm. */
        .founder-features {
            gap: 0;
        }

        .f-feature {
            min-height: 82px;
            padding: 0 0 16px;
            gap: 16px;
        }

        .f-feature+.f-feature {
            border-top: 1px solid var(--knp-border);
            padding-top: 17px;
        }

        .f-icon {
            flex: 0 0 30px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            color: var(--knp-red);
        }

        .f-icon svg {
            width: 27px;
            height: 27px;
            stroke-width: 2.2;
        }

        .f-text h4 {
            font-size: 11px;
            line-height: 1.25;
            margin: 2px 0 6px;
        }

        .f-text p {
            font-size: 12px;
            line-height: 1.5;
        }

        /* Reference alignment: one shared container creates equal left and right space. */
        .founder-section,
        .values-section {
            box-sizing: border-box;
            width: calc(100% - 120px);
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .founder-section {
            display: grid;
            grid-template-columns: 400px minmax(0, 1fr) 225px;
            column-gap: 48px;
            align-items: center;
            padding-left: 0;
            padding-right: 0;
        }

        .founder-img,
        .founder-content,
        .founder-features {
            min-width: 0;
        }

        .founder-img {
            width: 400px;
        }

        .values-section {
            padding-left: 0;
            padding-right: 0;
        }

        .about-hero-img {
            width: 100%;
            display: block;
            height: auto;
        }

        @media (max-width: 991.98px) {

            .founder-section,
            .values-section {
                width: 100%;
                padding-left: 20px;
                padding-right: 20px;
            }

            .promise-section {
                padding-left: 20px;
                padding-right: 20px;
                gap: 40px;
            }

            .founder-img {
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }

            .promise-img {
                flex: 0 0 auto;
                width: 100%;
                max-width: 400px;
                padding: 0;
                margin: 0 auto;
            }

            .promise-img img {
                width: 100%;
                height: auto;
            }

            .promise-content {
                padding: 40px 0 0 0;
            }

            .about-hero-wrapper {
                height: auto;
                min-height: 0;
                flex-direction: column;
            }

            .about-hero-bg {
                width: 100%;
                position: relative;
                height: auto;
                min-height: 350px;
            }

            .about-hero-content-container {
                display: block;
            }

            .about-hero-content {
                width: 100%;
                height: auto;
                min-height: auto;
                clip-path: none;
                padding: 40px 20px;
            }

            .founder-section,
            .promise-section {
                display: flex;
                width: 100%;
                flex-direction: column;
                gap: 40px;
            }

            .founder-features {
                border-left: none;
                border-top: 1px solid var(--knp-border);
                padding-left: 0;
                padding-top: 20px;
            }

            .values-grid,
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .value-card {
                border-right: none;
                border-bottom: 1px solid var(--knp-border);
                padding: 0 0 20px 0;
            }

            .value-card:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .stat-item {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding-bottom: 15px;
            }

            .stat-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .journey-timeline {
                flex-wrap: wrap;
            }

            .journey-step {
                flex: 0 0 50%;
                margin-bottom: 20px;
            }

            .step-arrow {
                display: none;
            }

            .about-hero-content {
                padding: 40px 20px;
            }
        }

        @media (max-width: 767.98px) {

            /* Hide floating scroll-up button on mobile if needed */
            #scrollUp,
            .back-to-top,
            .go-top,
            .scroll-top,
            .scrollToTop,
            .scrollup,
            #back-top {
                display: none !important;
            }

            /* 1. Hero Banner */
            .about-hero-banner {
                width: 100% !important;
                display: block !important;
                background: #000 !important;
            }

            .about-hero-img {
                width: 100% !important;
                height: auto !important;
                object-fit: contain !important;
                display: block !important;
            }

            /* 2. Founder Section */
            .founder-section {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                padding: 24px 16px 26px !important;
                gap: 20px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .founder-img {
                width: 100% !important;
                max-width: clamp(210px, 58vw, 250px) !important;
                margin: 0 auto !important;
                display: flex !important;
                justify-content: center !important;
                flex: unset !important;
            }

            .polaroid {
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 10px !important;
                overflow: hidden !important;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
                border: 1px solid rgba(201, 162, 39, 0.35) !important;
            }

            .polaroid img {
                width: 100% !important;
                height: auto !important;
                display: block !important;
                object-fit: cover !important;
            }

            .founder-content {
                padding: 0 !important;
                text-align: center !important;
                width: 100% !important;
                flex: unset !important;
            }

            .founder-content .about-kicker {
                font-size: 10px !important;
                letter-spacing: 2px !important;
                margin-bottom: 6px !important;
            }

            .founder-content h2 {
                font-size: clamp(22px, 6vw, 26px) !important;
                line-height: 1.22 !important;
                margin-bottom: 12px !important;
            }

            .founder-content p {
                font-size: 13px !important;
                line-height: 1.65 !important;
                margin-bottom: 10px !important;
                color: #555 !important;
            }

            .founder-signature {
                font-size: 24px !important;
                margin-top: 10px !important;
                margin-bottom: 4px !important;
            }

            /* Founder Features (Founded In, Mission, Vision) */
            .founder-features {
                width: 100% !important;
                max-width: 100% !important;
                border-top: 1px solid var(--knp-border) !important;
                border-left: none !important;
                padding: 16px 0 0 0 !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 10px !important;
                flex: unset !important;
            }

            .f-feature {
                min-height: auto !important;
                padding: 10px 14px !important;
                gap: 14px !important;
                align-items: center !important;
                background: #FAFAFA !important;
                border-radius: 8px !important;
                border: 1px solid #EFEFEF !important;
            }

            .f-feature+.f-feature {
                border-top: 1px solid #EFEFEF !important;
                padding-top: 10px !important;
            }

            .f-icon {
                flex: 0 0 24px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .f-icon svg {
                width: 20px !important;
                height: 20px !important;
                stroke-width: 2 !important;
            }

            .f-text {
                text-align: left !important;
                flex: 1 !important;
            }

            .f-text h4 {
                font-size: 10px !important;
                font-weight: 800 !important;
                margin: 0 0 2px 0 !important;
                letter-spacing: 0.8px !important;
            }

            .f-text p {
                font-size: 11.5px !important;
                line-height: 1.4 !important;
                color: #666 !important;
                margin: 0 !important;
            }

            /* 3. Values Section (2-Column Grid + 5th centered) */
            .values-section {
                width: 100% !important;
                max-width: 100% !important;
                padding: 26px 14px 22px !important;
                box-sizing: border-box !important;
                text-align: center !important;
            }

            .values-section>h2 {
                font-size: clamp(20px, 5.5vw, 24px) !important;
                margin-bottom: 14px !important;
            }

            .values-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
                margin-top: 14px !important;
            }

            .value-card {
                padding: 14px 10px !important;
                background: #FAFAFA !important;
                border-radius: 8px !important;
                border: 1px solid #EAEAEA !important;
                border-right: 1px solid #EAEAEA !important;
                border-bottom: 1px solid #EAEAEA !important;
                text-align: center !important;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02) !important;
            }

            .value-card:last-child {
                grid-column: span 2 !important;
                max-width: 260px !important;
                margin: 0 auto !important;
                width: 100% !important;
                box-sizing: border-box !important;
                border-bottom: 1px solid #EAEAEA !important;
                padding-bottom: 14px !important;
            }

            .value-card svg {
                width: 22px !important;
                height: 22px !important;
                margin: 0 auto 6px !important;
            }

            .value-card h4 {
                font-size: 10px !important;
                font-weight: 800 !important;
                letter-spacing: 0.8px !important;
                margin-bottom: 4px !important;
            }

            .value-card p {
                font-size: 10.5px !important;
                line-height: 1.38 !important;
                color: #666 !important;
                margin: 0 !important;
            }

            /* 4. Journey Timeline (Connected Vertical Timeline) */
            .journey-section {
                width: 100% !important;
                max-width: 100% !important;
                padding: 26px 16px 28px !important;
                box-sizing: border-box !important;
                text-align: center !important;
            }

            .journey-section>h2 {
                font-size: clamp(20px, 5.5vw, 24px) !important;
                margin-bottom: 16px !important;
            }

            .journey-timeline {
                display: flex !important;
                flex-direction: column !important;
                gap: 0 !important;
                margin-top: 14px !important;
                position: relative !important;
                padding-left: 14px !important;
                text-align: left !important;
            }

            .journey-timeline::before {
                content: '' !important;
                position: absolute !important;
                left: 31px !important;
                top: 18px !important;
                bottom: 22px !important;
                width: 2px !important;
                background: linear-gradient(180deg, var(--knp-red) 0%, rgba(180, 0, 22, 0.2) 100%) !important;
                z-index: 1 !important;
            }

            .journey-step {
                display: flex !important;
                flex-direction: row !important;
                align-items: flex-start !important;
                gap: 14px !important;
                padding: 0 0 16px 0 !important;
                position: relative !important;
                z-index: 2 !important;
                flex: unset !important;
                width: 100% !important;
                text-align: left !important;
                margin-bottom: 0 !important;
            }

            .journey-step:last-child {
                padding-bottom: 0 !important;
            }

            .step-icon {
                width: 34px !important;
                height: 34px !important;
                min-width: 34px !important;
                font-size: 13px !important;
                margin: 0 !important;
                border-radius: 50% !important;
                background: var(--knp-red) !important;
                box-shadow: 0 0 0 3px #FFFFFF, 0 2px 6px rgba(180, 0, 22, 0.3) !important;
                flex-shrink: 0 !important;
            }

            .step-arrow {
                display: none !important;
            }

            .journey-step-text {
                flex: 1 !important;
                padding-top: 2px !important;
            }

            .journey-step h4 {
                font-size: 11px !important;
                font-weight: 800 !important;
                letter-spacing: 0.8px !important;
                margin-bottom: 2px !important;
                color: var(--knp-dark) !important;
            }

            .journey-step p {
                font-size: 11.5px !important;
                line-height: 1.42 !important;
                color: #555 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }

            .journey-video-grid {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
                margin-top: 24px !important;
            }

            .journey-video-card {
                aspect-ratio: 16 / 9 !important;
                border-radius: 8px !important;
            }

            /* 5. Promise Section */
            .promise-wrapper {
                background: #fbfbfb !important;
                padding: 0 !important;
                border-bottom: 3px solid var(--knp-red) !important;
            }

            .promise-section {
                display: flex !important;
                flex-direction: column !important;
                padding: 24px 16px !important;
                gap: 18px !important;
                min-height: auto !important;
            }

            .promise-content {
                padding: 0 !important;
                text-align: center !important;
                width: 100% !important;
                animation: none !important;
            }

            .promise-content .about-kicker {
                font-size: 10px !important;
                letter-spacing: 2px !important;
                margin-bottom: 6px !important;
            }

            .promise-title {
                font-size: clamp(21px, 5.8vw, 25px) !important;
                line-height: 1.22 !important;
                margin-bottom: 10px !important;
            }

            .promise-content .about-text {
                font-size: 13px !important;
                line-height: 1.6 !important;
                margin-bottom: 8px !important;
                max-width: 100% !important;
            }

            .promise-content .founder-signature {
                font-size: 24px !important;
                margin-top: 6px !important;
            }

            .promise-img {
                width: 100% !important;
                max-width: clamp(210px, 58vw, 250px) !important;
                margin: 0 auto !important;
                display: flex !important;
                justify-content: center !important;
                padding: 0 !important;
                flex: unset !important;
                animation: none !important;
            }

            .promise-img img {
                width: 100% !important;
                height: auto !important;
                border-radius: 8px !important;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
                border: 1px solid rgba(201, 162, 39, 0.35) !important;
            }

            /* 6. Red Stats Strip (2-Column Grid + 5th Centered) */
            .stats-strip-red {
                padding: 18px 14px !important;
            }

            .stats-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 12px 10px !important;
                padding: 0 !important;
            }

            .stat-item {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 10px !important;
                padding: 0 !important;
                border-right: none !important;
                border-bottom: none !important;
            }

            .stat-item:last-child {
                grid-column: span 2 !important;
                justify-content: center !important;
                border-bottom: none !important;
                padding-top: 2px !important;
            }

            .stat-item:last-child .stat-text {
                text-align: left !important;
            }

            .stat-icon svg,
            .stat-icon i {
                font-size: 18px !important;
                width: 20px !important;
                height: 20px !important;
                flex-shrink: 0 !important;
            }

            .stat-text strong {
                font-size: 14px !important;
                line-height: 1.15 !important;
            }

            .stat-text span {
                font-size: 8.5px !important;
                letter-spacing: 0.8px !important;
                display: block !important;
            }

            /* 7. Testimonials (Video Reviews Carousel) */
            .section-padding:has(.testi-carousel-wrapper) {
                padding-top: 24px !important;
                padding-bottom: 36px !important;
            }

            .section-padding:has(.testi-carousel-wrapper) .section-head {
                margin-bottom: 16px !important;
            }

            .testi-carousel-wrapper {
                padding: 0 14px !important;
                margin-top: 14px !important;
            }

            .testi-carousel-btn {
                display: none !important;
            }

            .testi-grid {
                gap: 12px !important;
                padding: 4px 2px !important;
            }

            .testi-item-wrapper {
                flex: 0 0 clamp(160px, 48vw, 190px) !important;
                min-width: unset !important;
            }

            .testi-card {
                border-radius: 6px !important;
                margin-bottom: 6px !important;
            }

            .testi-play {
                width: 34px !important;
                height: 34px !important;
                font-size: 12px !important;
            }

            .testi-info strong {
                font-size: 12px !important;
                margin-bottom: 2px !important;
            }

            .testi-info p {
                font-size: 10px !important;
                margin-top: 3px !important;
            }
        }

        /* Client reference desktop alignment overrides */
        @media (min-width: 992px) {
            .about-wrapper {
                background: #fff;
            }

            .about-hero-banner {
                width: 100%;
                line-height: 0;
                background: #fff;
                overflow: hidden;
            }

            .about-hero-img {
                width: 100%;
                height: auto;
                /* min-height: 430px; */
                object-fit: cover;
                object-position: center top;
            }

            .founder-section,
            .values-section,
            .journey-section,
            .promise-section,
            .stats-grid,
            .section-padding:has(.testi-carousel-wrapper) {
                width: calc(100% - 140px);
                max-width: 1320px;
                margin-left: auto;
                margin-right: auto;
                box-sizing: border-box;
            }

            .founder-section {
                display: grid;
                grid-template-columns: 440px minmax(0, 1fr) 270px;
                column-gap: 64px;
                align-items: center;
                padding: 26px 0 28px;
                border-bottom: 1px solid var(--knp-border);
            }

            .founder-img {
                width: 440px;
                flex: none;
            }

            .polaroid,
            .polaroid img {
                width: 100%;
                max-width: 440px;
            }

            .founder-content h2 {
                font-size: 31px !important;
                line-height: 1.12;
                margin-bottom: 18px !important;
            }

            .founder-content p {
                font-size: 13px !important;
                line-height: 1.72 !important;
                max-width: 510px;
            }

            .founder-features {
                flex: none;
                border-left: 1px solid var(--knp-border);
                padding-left: 34px;
            }

            .values-section {
                padding: 22px 0 26px;
                border-bottom: 1px solid var(--knp-border);
            }

            .values-section>h2,
            .journey-section>h2 {
                font-size: 24px !important;
                line-height: 1.1;
                margin: 0;
                letter-spacing: .2px;
            }

            .values-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
                gap: 0;
                margin-top: 20px;
            }

            .value-card {
                min-height: 118px;
                padding: 0 28px;
                border-right: 1px solid var(--knp-border);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
            }

            .value-card:last-child {
                border-right: 0;
            }

            .value-card svg {
                width: 28px !important;
                height: 28px !important;
                margin-bottom: 10px !important;
            }

            .value-card h4,
            .journey-step h4 {
                font-size: 11px;
                font-weight: 800;
                letter-spacing: .65px;
                margin-bottom: 7px;
            }

            .value-card p,
            .journey-step p {
                font-size: 11.5px;
                line-height: 1.43;
                color: #555;
                max-width: 170px;
                margin: 0 auto;
            }

            .journey-section {
                padding: 25px 0 34px;
                border-bottom: 1px solid var(--knp-border);
            }

            .journey-timeline {
                margin-top: 24px;
                display: grid;
                grid-template-columns: repeat(5, minmax(0, 1fr));
                align-items: start;
            }

            .journey-step {
                padding: 0 24px;
                min-height: 135px;
            }

            .step-icon {
                width: 52px;
                height: 52px;
                margin-bottom: 13px;
                font-size: 19px;
            }

            .step-arrow {
                top: 26px;
                right: -7px;
                color: #bdbdbd;
                font-size: 18px;
            }

            .promise-wrapper {
                background: #f7f7f7;
                border-bottom: 0;
            }

            .promise-section {
                min-height: 300px;
                display: grid;
                grid-template-columns: 0.92fr 1.08fr;
                gap: 36px;
                align-items: center;
                padding: 26px 0 0;
            }

            .promise-content {
                padding: 0 0 26px;
            }

            .promise-title {
                font-size: 32px;
                line-height: 1.12;
                margin-bottom: 14px;
            }

            .promise-content .about-text {
                max-width: 520px;
                font-size: 13px;
                line-height: 1.72;
            }

            .promise-content .founder-signature {
                font-size: 26px !important;
                margin-top: 12px;
            }

            .promise-img {
                width: 100%;
                height: 300px;
                padding: 0;
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
            }

            .promise-img img {
                width: 100%;
                max-width: 690px;
                height: 300px;
                object-fit: contain;
                object-position: right bottom;
                box-shadow: none !important;
            }

            .stats-strip-red {
                padding: 22px 0;
                background: linear-gradient(90deg, #9f0012 0%, var(--knp-red) 52%, #9f0012 100%);
            }

            .stats-grid {
                padding: 0;
                grid-template-columns: repeat(5, minmax(0, 1fr));
                gap: 0;
            }

            .stat-item {
                justify-content: center;
                padding: 0 26px;
                min-height: 55px;
            }

            .stat-icon svg,
            .stat-icon i {
                width: 34px;
                height: 34px;
                font-size: 31px;
            }

            .stat-text strong {
                font-size: 21px;
                line-height: 1.1;
            }

            .stat-text span {
                font-size: 10px;
                text-transform: none;
                letter-spacing: 0;
            }

            .section-padding:has(.testi-carousel-wrapper) {
                padding: 28px 0 42px !important;
            }

            .section-padding:has(.testi-carousel-wrapper) .section-head {
                margin-bottom: 20px !important;
            }

            .section-padding:has(.testi-carousel-wrapper) h2 {
                font-size: 25px !important;
                line-height: 1.1;
            }

            .testi-carousel-wrapper {
                padding: 0 44px;
                max-width: 100%;
            }

            .testi-grid {
                gap: 28px;
            }

            .testi-item-wrapper {
                flex: 0 0 calc(20% - 23px);
                min-width: 175px;
            }

            .testi-card {
                border-radius: 5px;
                aspect-ratio: 16 / 9;
                margin-bottom: 9px;
            }
        }
    </style>

    <div class="about-wrapper">

        <!-- 1. HERO SECTION -->
        <section class="about-hero-banner" style="width: 100%; display: block;">
            <img src="{{ asset('images/banner/about.png') }}" alt="About House of KNP" class="about-hero-img">
        </section>



        <!-- 2. THE FOUNDER SECTION -->
        @php
            $founderImagePath = trim((string) ($aboutContent['about_founder_image'] ?? ''));
            $founderImageUrl = $founderImagePath !== ''
                ? house_main_media_url($founderImagePath)
                : asset('images/knp/brand_story_new.png');
        @endphp
        <section class="founder-section">
            <div class="founder-img">
                <div class="polaroid">
                    <img src="{{ $founderImageUrl }}" alt="House of KNP founder story">
                </div>
            </div>
            <div class="founder-content">
                <span class="about-kicker">{{ $aboutContent['about_founder_kicker'] ?? 'THE FOUNDER' }}</span>
                <h2 class="knp-serif" style="font-size:32px; font-weight:700; margin-bottom:20px;">{{ $aboutContent['about_founder_title'] ?? 'From a Dream to a Brand' }}
                </h2>
                <p style="font-size:14px; line-height:1.8; color:#444; margin-bottom:15px;">{{ $aboutContent['about_founder_intro_desc'] ?? "I'm Kaveri Logesh, the founder of HOUSE OF KNP. It started as a simple thought — why can't men's essentials be more than just ordinary?" }}</p>
                <p style="font-size:14px; line-height:1.8; color:#444; margin-bottom:15px;">{{ $aboutContent['about_founder_story_desc'] ?? 'Driven by passion, countless late nights and a vision to build something unforgettable, HOUSE OF KNP was born.' }}</p>
                <p style="font-size:14px; line-height:1.8; color:#444; margin-bottom:20px;">{{ $aboutContent['about_founder_purpose_desc'] ?? "This is not just my brand, it's my purpose." }}</p>
                <div class="founder-signature"
                    style="font-family:'Cormorant Garamond', serif; font-style:italic; font-size:24px; font-weight:600; margin-bottom:5px;">
                    {{ $aboutContent['about_founder_name'] ?? 'Kaveri Logesh' }}</div>
                <span
                    style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#888;">{{ $aboutContent['about_founder_role'] ?? 'FOUNDER, HOUSE OF KNP' }}</span>
            </div>
            <div class="founder-features">
                <div class="f-feature">
                    <div class="f-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg></div>
                    <div class="f-text">
                        <h4>{{ $aboutContent['about_founder_year_heading'] ?? 'FOUNDED IN' }}</h4>
                        <p>{{ $aboutContent['about_founder_year'] ?? '2024' }}</p>
                    </div>
                </div>
                <div class="f-feature">
                    <div class="f-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="6"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                        </svg></div>
                    <div class="f-text">
                        <h4>{{ $aboutContent['about_founder_mission_heading'] ?? 'OUR MISSION' }}</h4>
                        <p>{{ $aboutContent['about_founder_mission_desc'] ?? "Redefine men's luxury essentials with timeless design and unmatched quality." }}</p>
                    </div>
                </div>
                <div class="f-feature">
                    <div class="f-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg></div>
                    <div class="f-text">
                        <h4>{{ $aboutContent['about_founder_vision_heading'] ?? 'OUR VISION' }}</h4>
                        <p>{{ $aboutContent['about_founder_vision_desc'] ?? "To be India's most trusted lifestyle brand for the modern gentleman." }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. OUR VALUES -->
        <section class="values-section">
            <h2 class="knp-serif" style="font-size:32px; font-weight:700;"><span style="color:var(--knp-red);">OUR</span>
                VALUES</h2>
            <div class="values-grid">
                <div class="value-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 15px;">
                        <path d="M6 3h12l4 6-10 13L2 9Z"></path>
                    </svg>
                    <h4>QUALITY FIRST</h4>
                    <p>We source the finest materials and ensure exceptional quality in every detail.</p>
                </div>
                <div class="value-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 15px;">
                        <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7z"></path>
                        <path d="M5 20h14"></path>
                    </svg>
                    <h4>TIMELESS STYLE</h4>
                    <p>Our designs are created to stay timeless, not trendy.</p>
                </div>
                <div class="value-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 15px;">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <h4>BUILT ON TRUST</h4>
                    <p>Transparency, honesty and trust are at the core of everything we do.</p>
                </div>
                <div class="value-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 15px;">
                        <rect x="3" y="8" width="18" height="13" rx="2"></rect>
                        <path d="M12 8v13M3 12h18"></path>
                        <path d="M12 8H7.5a2.5 2.5 0 1 1 2.5-2.5V8zm0 0h4.5A2.5 2.5 0 1 0 14 5.5V8z"></path>
                    </svg>
                    <h4>MEANINGFUL</h4>
                    <p>We believe in products that add meaning to moments and memories.</p>
                </div>
                <div class="value-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 15px;">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <h4>FOR GENTLEMEN</h4>
                    <p>Everything we create is for men who lead, inspire and leave a lasting impression.</p>
                </div>
            </div>
        </section>

        <!-- 4. OUR JOURNEY -->
        <section class="journey-section">
            <h2 class="knp-serif" style="font-size:32px; font-weight:700;"><span style="color:var(--knp-red);">OUR</span>
                JOURNEY</h2>
            <div class="journey-timeline">
                <div class="journey-step">
                    <div class="step-icon"><i class="fa fa-lightbulb-o"></i></div>
                    <div class="step-arrow"><i class="fa fa-angle-right"></i></div>
                    <div class="journey-step-text">
                        <h4>THE IDEA</h4>
                        <p>It began with a simple question &mdash; why not create essentials that reflect true class and
                            confidence?</p>
                    </div>
                </div>
                <div class="journey-step">
                    <div class="step-icon"><i class="fa fa-pencil"></i></div>
                    <div class="step-arrow"><i class="fa fa-angle-right"></i></div>
                    <div class="journey-step-text">
                        <h4>THE FIRST STEP</h4>
                        <p>Countless sketches, ideas and research turned into a clear vision.</p>
                    </div>
                </div>
                <div class="journey-step">
                    <div class="step-icon"><i class="fa fa-cube"></i></div>
                    <div class="step-arrow"><i class="fa fa-angle-right"></i></div>
                    <div class="journey-step-text">
                        <h4>THE CREATION</h4>
                        <p>Carefully crafted products, tested for quality and designed with purpose.</p>
                    </div>
                </div>
                <div class="journey-step">
                    <div class="step-icon"><i class="fa fa-rocket"></i></div>
                    <div class="step-arrow"><i class="fa fa-angle-right"></i></div>
                    <div class="journey-step-text">
                        <h4>THE LAUNCH</h4>
                        <p>HOUSE OF KNP was launched to the world.</p>
                    </div>
                </div>
                <div class="journey-step">
                    <div class="step-icon"><i class="fa fa-users"></i></div>
                    <div class="journey-step-text">
                        <h4>THE FUTURE</h4>
                        <p>This is just the beginning. We're building a legacy that inspires generations of gentlemen.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. PROMISE SECTION -->
        <div class="promise-wrapper">
            <section class="promise-section">
                <div class="promise-content">
                    <span class="about-kicker">OUR PROMISE</span>
                    <h2 class="promise-title knp-serif">More Than Products,<br>We Deliver Presence.</h2>
                    <p class="about-text">Every product from HOUSE OF KNP is a promise of quality, elegance and confidence.
                        Because when you look good, you feel unstoppable.</p>
                    <div class="founder-signature" style="font-family:'Cormorant Garamond', serif; font-style:italic;">
                        Kaveri Logesh</div>
                </div>
                <div class="promise-img">
                    <img src="{{ asset('images/knp/knp_gift_box_1773738766506.png') }}"
                        onerror="this.src='{{ asset('images/knp/knp_combo_products_1773738840245.png') }}'"
                        alt="Our Promise">
                </div>
            </section>
        </div>

        <!-- 6. STATS STRIP -->
        <div class="stats-strip-red">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg></div>
                    <div class="stat-text"><strong>10,000+</strong><span>Happy Customers</span></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                            </path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg></div>
                    <div class="stat-text"><strong>50,000+</strong><span>Products Delivered</span></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg></div>
                    <div class="stat-text"><strong>4.9/5</strong><span>Average Rating</span></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path
                                d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                            </path>
                        </svg></div>
                    <div class="stat-text"><strong>All India</strong><span>Shipping</span></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg></div>
                    <div class="stat-text"><strong>100%</strong><span>Authentic Products</span></div>
                </div>
            </div>
        </div>

        <script>
            function setReviewVideoDuration(video) {
                if (!video || !Number.isFinite(video.duration)) return;
                const minutes = Math.floor(video.duration / 60);
                const seconds = String(Math.floor(video.duration % 60)).padStart(2, '0');
                const duration = minutes + ':' + seconds;
                const card = video.closest('.testi-card');
                if (card) {
                    const durationElements = card.querySelectorAll('.testi-duration-left, .testi-duration-right');
                    durationElements.forEach(function (el) {
                        el.textContent = duration;
                    });
                }
            }
        </script>

        <!-- 7. TESTIMONIALS (FROM GENTLEMEN) -->
        <section class="section-padding" style="padding-bottom: 80px;">
            <div class="section-head text-center" style="margin-bottom: 40px;">
                <h2 class="knp-serif text-uppercase" style="font-size: 28px; font-weight: 700; letter-spacing: 1px;">FROM
                    <span style="color:var(--knp-red);">GENTLEMEN</span></h2>
                <p style="font-size: 13px; color: #666; margin-top: 5px;">Real stories from real gentlemen.</p>
            </div>
            <div class="testi-carousel-wrapper">
                <button class="testi-carousel-btn left" onclick="testiSlidePrev()" aria-label="Previous Testimonial">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                <div class="testi-grid" id="testi-slider">
                    @if(isset($reviewVideos) && count($reviewVideos) > 0)
                        @foreach($reviewVideos as $video)
                            <div class="testi-item-wrapper">
                                <div class="testi-card" role="button" tabindex="0"
                                    aria-label="Watch review by {{ $video->name ?? 'House of KNP Collector' }}"
                                    onclick="openVideoModal('{{ house_main_media_url($video->video, 'images') }}')"
                                    onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ house_main_media_url($video->video, 'images') }}');}">
                                    <video src="{{ house_main_media_url($video->video, 'images') }}" preload="metadata" muted
                                        playsinline onloadedmetadata="setReviewVideoDuration(this)"></video>
                                    <div class="testi-play">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg>
                                    </div>
                                    <span class="testi-duration-left">--:--</span>
                                    <span class="testi-duration-right">--:--</span>
                                </div>
                                <div class="testi-info">
                                    <strong>{{ $video->name ?? 'House of KNP Collector' }}</strong>
                                    <div class="testi-stars">
                                        @php $rating = $video->rating ?? 5; @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating)
                                                &#9733;
                                            @else
                                                &#9734;
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="testi-item-wrapper">
                            <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by Arun Prakash"
                                onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ asset('videos/our_story.mp4') }}');}">
                                <img src="{{ asset('images/knp/testi1.jpg') }}"
                                    onerror="this.src='{{ asset('images/product/01.jpg') }}'" alt="Review 1" loading="lazy">
                                <div class="testi-play">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                                <span class="testi-duration-left">01:15</span>
                                <span class="testi-duration-right">01:15</span>
                            </div>
                            <div class="testi-info">
                                <strong>Arun Prakash</strong>
                                <div class="testi-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                            </div>
                        </div>
                        <div class="testi-item-wrapper">
                            <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by Vikram Malhotra"
                                onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ asset('videos/our_story.mp4') }}');}">
                                <img src="{{ asset('images/knp/phil_man.jpg') }}"
                                    onerror="this.src='{{ asset('images/product/02.jpg') }}'" alt="Review 2" loading="lazy">
                                <div class="testi-play">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                                <span class="testi-duration-left">00:48</span>
                                <span class="testi-duration-right">00:48</span>
                            </div>
                            <div class="testi-info">
                                <strong>Vikram Malhotra</strong>
                                <div class="testi-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                            </div>
                        </div>
                        <div class="testi-item-wrapper">
                            <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by Rohan Singhania"
                                onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ asset('videos/our_story.mp4') }}');}">
                                <img src="{{ asset('images/knp/story_bg.jpg') }}"
                                    onerror="this.src='{{ asset('images/product/03.jpg') }}'" alt="Review 3" loading="lazy">
                                <div class="testi-play">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                                <span class="testi-duration-left">01:30</span>
                                <span class="testi-duration-right">01:30</span>
                            </div>
                            <div class="testi-info">
                                <strong>Rohan Singhania</strong>
                                <div class="testi-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                            </div>
                        </div>
                        <div class="testi-item-wrapper">
                            <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by Siddharth Sen"
                                onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ asset('videos/our_story.mp4') }}');}">
                                <img src="{{ asset('images/knp/product_perfume.png') }}"
                                    onerror="this.src='{{ asset('images/product/04.jpg') }}'" alt="Review 4" loading="lazy">
                                <div class="testi-play">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                                <span class="testi-duration-left">01:05</span>
                                <span class="testi-duration-right">01:05</span>
                            </div>
                            <div class="testi-info">
                                <strong>Siddharth Sen</strong>
                                <div class="testi-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                            </div>
                        </div>
                    @endif
                </div>

                <button class="testi-carousel-btn right" onclick="testiSlideNext()" aria-label="Next Testimonial">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </section>

    </div>

    <!-- Video Modal UI -->
    <div id="reviewVideoModal"
        style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.85); align-items: center; justify-content: center;">
        <div
            style="position: relative; width: 90%; max-width: 800px; background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
            <span onclick="closeVideoModal()"
                style="position: absolute; top: 10px; right: 15px; color: #FFF; font-size: 35px; cursor: pointer; z-index: 10; text-shadow: 0 2px 4px rgba(0,0,0,0.8); line-height: 1;">&times;</span>
            <video id="modalVideoPlayer" controls autoplay
                style="width: 100%; height: auto; display: block; max-height: 80vh;"></video>
        </div>
    </div>

    <script>
        function openVideoModal(videoSrc) {
            const modal = document.getElementById('reviewVideoModal');
            const player = document.getElementById('modalVideoPlayer');
            if (modal && player) {
                player.src = videoSrc;
                modal.style.display = 'flex';
                player.play().catch(() => { });
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

        document.addEventListener("DOMContentLoaded", function () {
            const slider = document.getElementById('testi-slider');
            let autoSlideInterval = setInterval(slideNext, 3000);

            function slideNext() {
                if (!slider) return;
                const itemWidth = slider.querySelector('.testi-item-wrapper')?.offsetWidth || 300;
                if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    slider.scrollBy({ left: itemWidth + 20, behavior: 'smooth' });
                }
            }

            function slidePrev() {
                if (!slider) return;
                const itemWidth = slider.querySelector('.testi-item-wrapper')?.offsetWidth || 300;
                if (slider.scrollLeft <= 0) {
                    slider.scrollTo({ left: slider.scrollWidth, behavior: 'smooth' });
                } else {
                    slider.scrollBy({ left: -(itemWidth + 20), behavior: 'smooth' });
                }
            }

            window.testiSlideNext = function () {
                clearInterval(autoSlideInterval);
                slideNext();
            };

            window.testiSlidePrev = function () {
                clearInterval(autoSlideInterval);
                slidePrev();
            };

            if (slider) {
                slider.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
                slider.addEventListener('mouseleave', () => autoSlideInterval = setInterval(slideNext, 3000));
            }

            // Close modal if clicking outside video
            const videoModal = document.getElementById('reviewVideoModal');
            if (videoModal) {
                videoModal.addEventListener('click', function (e) {
                    if (e.target === this) closeVideoModal();
                });
            }

            // Escape key close
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeVideoModal();
            });
        });
    </script>

@endsection
