@extends('layouts.app')

@section('content')

<!-- External Google Fonts & GSAP Animation Suite -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&family=Great+Vibes&family=Montserrat:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap');

    /* ==========================================================================
       HOUSE OF KNP — LIQUID GLASS LUXURY DESIGN SYSTEM
       Aesthetic: Off-White (#FAFAF9), Deep Crimson Red (#B40016), Obsidian Black (#080809)
       ========================================================================== */
    :root {
        /* Core Palette */
        --knp-red: #B40016;
        --knp-red-hover: #8B0011;
        --knp-red-vibrant: #D90429;
        --knp-red-glow: rgba(180, 0, 22, 0.38);
        --knp-red-subtle: rgba(180, 0, 22, 0.08);

        --knp-black: #080809;
        --knp-obsidian: #0E0E11;
        --knp-coal: #16161A;
        --knp-surface: #1E1E24;
        
        --knp-white: #FFFFFF;
        --knp-off-white: #FAFAF9;
        --knp-cream: #F5F4F0;
        --knp-gray-light: #F0EFEA;
        --knp-muted: #52525B;
        --knp-gray-dark: #3F3F46;

        --knp-gold: #C9A227;
        --knp-gold-pale: #E8D5A3;
        --knp-gold-glow: rgba(201, 162, 39, 0.25);

        /* Liquid Glass Tokens */
        --glass-blur-sm: 8px;
        --glass-blur-md: 16px;
        --glass-blur-lg: 24px;
        --glass-blur-xl: 36px;

        --glass-light-bg: rgba(255, 255, 255, 0.85);
        --glass-light-border: rgba(255, 255, 255, 0.95);
        --glass-light-hover: rgba(255, 255, 255, 0.98);

        --glass-dark-bg: rgba(14, 14, 17, 0.82);
        --glass-dark-border: rgba(255, 255, 255, 0.12);
        --glass-dark-hover: rgba(22, 22, 26, 0.92);

        --glass-shadow-soft: 0 10px 30px -10px rgba(0, 0, 0, 0.08), 0 0 1px 1px rgba(255, 255, 255, 0.8);
        --glass-shadow-elevated: 0 20px 45px -12px rgba(0, 0, 0, 0.14), 0 0 2px 1px rgba(255, 255, 255, 0.9);
        --glass-shadow-dark: 0 20px 50px -10px rgba(0, 0, 0, 0.65), inset 0 1px 1px rgba(255, 255, 255, 0.15);

        --knp-ease: cubic-bezier(0.16, 1, 0.3, 1);
        --knp-ease-bounce: cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Global Base Typography & Smooth Rendering */
    .home-wrapper {
        font-family: 'Montserrat', 'Manrope', -apple-system, sans-serif;
        color: var(--knp-black);
        background-color: var(--knp-white);
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }

    .knp-serif,
    .home-wrapper h1, 
    .home-wrapper h2, 
    .home-wrapper h3, 
    .home-wrapper h4, 
    .home-wrapper h5, 
    .home-wrapper h6 {
        font-family: 'Cormorant Garamond', Georgia, serif !important;
        letter-spacing: 0.02em;
    }

    .knp-red-text,
    .section-head h2 span,
    .section-head h2 .knp-red-text,
    .home-wrapper h1 span,
    .home-wrapper h2 span,
    .home-wrapper h3 span,
    .home-wrapper h4 span,
    .home-wrapper h5 span,
    .home-wrapper h6 span,
    .knp-serif span {
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        letter-spacing: inherit !important;
        line-height: inherit !important;
        text-transform: inherit !important;
        color: var(--knp-red) !important;
    }

    /* Accessibility Focus Outline for Keyboard Nav */
    .home-wrapper a:focus-visible,
    .home-wrapper button:focus-visible,
    .home-wrapper div[tabindex="0"]:focus-visible,
    .home-wrapper span[tabindex="0"]:focus-visible {
        outline: 2px solid var(--knp-red) !important;
        outline-offset: 3px !important;
    }

    /* Reduced Motion System Preference */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
        .hero-slide {
            transition: opacity 0.3s ease !important;
            transform: none !important;
        }
    }

    /* ==========================================================================
       1. HERO SECTION — LIQUID GLASS EDITORIAL THEATER
       ========================================================================== */
    .hero-section {
        position: relative;
        height: 92vh;
        min-height: 700px;
        max-height: 980px;
        background: var(--knp-black);
        color: var(--knp-white);
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .hero-bg-container {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        overflow: hidden;
    }

    .hero-slide {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1.2s var(--knp-ease);
        will-change: opacity;
    }

    .hero-slide.active-slide {
        opacity: 1;
    }

    .hero-slide video,
    .hero-slide .hero-static-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center center;
        image-rendering: -webkit-optimize-contrast;
    }

    /* Ambient Clean Specular Overlay - Keeps video vibrant, bright & sharp */
    .hero-glass-overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
        background:
            linear-gradient(to right, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0.15) 50%, rgba(0, 0, 0, 0.05) 100%),
            linear-gradient(to top, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0) 25%);
    }

    .hero-container {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1440px;
        margin: 0 auto;
        padding: clamp(120px, 16vw, 200px) clamp(20px, 5vw, 60px) clamp(60px, 8vw, 100px);
        display: flex;
        justify-content: flex-start;
    }

    .hero-glass-card {
        max-width: 780px;
        position: relative;
    }

    .hero-content-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.8s ease, visibility 0.8s ease;
    }

    .hero-content-slide.active-content {
        position: relative;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .hero-kicker-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        /* Padding and glass effect removed as requested */
    }

    .hero-kicker-dot {
        width: 10px;
        height: 10px;
        background: var(--knp-red);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--knp-red);
        animation: pulseDot 2.2s infinite ease-in-out;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }

    .hero-kicker-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 24px; /* Increased text size significantly */
        font-weight: 900; /* Made bolder as requested */
        letter-spacing: 3px;
        color: var(--knp-red); /* Colorful text matching the brand dot */
        text-transform: uppercase;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4); /* Shadow to ensure it pops against the background */
    }

    .hero-title-main {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(52px, 7.5vw, 108px);
        font-weight: 700;
        line-height: 0.92;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        margin: 0 0 22px;
        color: #FFFFFF;
        text-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
    }
    
    @supports (-webkit-background-clip: text) {
        .hero-title-main {
            background: linear-gradient(105deg, #C9A227 0%, #E8D5A3 25%, #FFFFFF 50%, #E8D5A3 75%, #C9A227 100%);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: heroSheen 4s linear infinite;
            text-shadow: none;
        }
    }
    @keyframes heroSheen {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .hero-divider-bar {
        display: flex;
        width: 140px;
        height: 2px;
        margin-bottom: 35px;
        border-radius: 2px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.15);
    }

    .hero-divider-crimson {
        width: 40px;
        height: 100%;
        background: var(--knp-red);
        box-shadow: 0 0 10px var(--knp-red);
    }

    .hero-desc-text {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(14px, 1.8vw, 16px);
        line-height: 1.85;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
        max-width: 580px;
        margin-bottom: 45px;
        letter-spacing: 0.02em;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    .hero-collection-btn {
        display: inline-flex;
        align-items: center;
        gap: 14px;
        background: #FFFFFF;
        color: var(--knp-black);
        padding: 16px 36px;
        border-radius: 100px;
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.4s var(--knp-ease);
        box-shadow: 0 8px 30px rgba(255, 255, 255, 0.15);
        position: relative;
        overflow: hidden;
    }

    .hero-collection-btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.25) 50%, transparent 100%);
        transition: left 0.7s var(--knp-ease);
    }

    .hero-collection-btn:hover::before {
        left: 100%;
    }

    .hero-collection-btn:hover {
        background: #FFFFFF !important;
        color: var(--knp-black) !important;
        border-color: #FFFFFF !important;
        box-shadow: 0 16px 40px -4px rgba(255, 255, 255, 0.3), inset 0 1px 2px rgba(255, 255, 255, 0.8) !important;
        transform: translateY(-2px);
    }

    .hero-collection-btn svg {
        transition: transform 0.3s var(--knp-ease);
    }

    .hero-collection-btn:hover svg {
        transform: translateX(5px);
    }

    /* Hero Vertical Slider Nav Indicator */
    .hero-nav-container {
        display: none !important;
        position: absolute;
        right: clamp(20px, 4vw, 55px);
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 16px 10px;
        background: rgba(14, 14, 17, 0.45);
        backdrop-filter: blur(var(--glass-blur-md));
        -webkit-backdrop-filter: blur(var(--glass-blur-md));
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.4);
    }

    .hero-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.3s var(--knp-ease);
    }

    .nav-num {
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.4);
        letter-spacing: 1.5px;
        transition: all 0.3s var(--knp-ease);
    }

    .nav-line {
        width: 1px;
        height: 14px;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.4s var(--knp-ease);
    }

    .hero-nav-item.active .nav-num {
        color: #FFFFFF;
        font-weight: 700;
        font-size: 12.5px;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .hero-nav-item.active .nav-line {
        height: 28px;
        width: 2px;
        background: var(--knp-red);
        box-shadow: 0 0 10px var(--knp-red-glow);
    }

    /* Sound/Mute Controller Glass Button */
    .hero-mute-btn {
        position: absolute;
        bottom: 35px;
        right: clamp(20px, 4vw, 55px);
        z-index: 10;
        min-width: 44px;
        height: 44px;
        padding: 0 14px;
        border-radius: 22px;
        background: rgba(14, 14, 17, 0.72);
        backdrop-filter: blur(var(--glass-blur-md));
        -webkit-backdrop-filter: blur(var(--glass-blur-md));
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #FFFFFF;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.5);
        transition: all 0.3s var(--knp-ease);
    }

    .hero-mute-btn:hover {
        background: var(--knp-red);
        border-color: var(--knp-red);
        transform: scale(1.05);
        box-shadow: 0 10px 28px -4px var(--knp-red-glow);
    }

    .hero-mute-btn .hero-mute-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: #FFFFFF;
        line-height: 1;
    }

    /* ==========================================================================
       2. FEATURES STRIP — LIQUID GLASS DARK BADGES
       ========================================================================== */
    .features-strip {
        background: var(--knp-white);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 36px 0;
        position: relative;
        overflow: hidden;
    }

    .features-strip::before {
        content: '';
        position: absolute;
        top: 0; left: 15%; width: 70%; height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(180, 0, 22, 0.3) 50%, transparent 100%);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        max-width: 1360px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .stats-features-grid {
        grid-template-columns: repeat(5, 1fr);
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 16px 20px;
        background: #FFFFFF;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 8px;
        transition: all 0.4s var(--knp-ease);
    }

    .feature-item:hover {
        background: #FFFFFF;
        border-color: rgba(180, 0, 22, 0.2);
        transform: translateY(-4px);
        box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.08), 0 0 15px rgba(180, 0, 22, 0.05);
    }

    .feature-icon-orb {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: rgba(180, 0, 22, 0.05);
        border: 1px solid rgba(180, 0, 22, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--knp-red);
        box-shadow: none;
        transition: all 0.4s var(--knp-ease);
    }

    .feature-item:hover .feature-icon-orb {
        color: #FFFFFF;
        background: linear-gradient(135deg, var(--knp-red) 0%, #8B0011 100%);
        border-color: rgba(255, 255, 255, 0.4);
        box-shadow: 0 0 20px var(--knp-red-glow), inset 0 1px 2px rgba(255, 255, 255, 0.5);
        transform: scale(1.08) rotate(3deg);
    }

    .feature-text strong {
        display: block;
        color: var(--knp-coal);
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 3px;
    }

    .feature-text span {
        font-family: 'Montserrat', sans-serif;
        font-size: 10.5px;
        font-weight: 500;
        letter-spacing: 1px;
        color: var(--knp-red);
        text-transform: uppercase;
    }

    /* ==========================================================================
       3. EDITORIAL SECTION HEADERS & GENERAL WRAPPER
       ========================================================================== */
    .section-padding {
        padding: clamp(60px, 8vw, 100px) clamp(20px, 4vw, 40px);
        max-width: 1360px;
        margin: 0 auto;
    }

    .section-head {
        text-align: center;
        margin-bottom: clamp(35px, 5vw, 60px);
        position: relative;
    }

    .section-kicker-tag {
        display: inline-block;
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--knp-red);
        margin-bottom: 10px;
    }

    .section-head h2 {
        font-family: 'Cormorant Garamond', Georgia, serif !important;
        font-size: clamp(34px, 4.5vw, 52px) !important;
        font-weight: 600 !important;
        margin-bottom: 14px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--knp-black);
    }

    .section-head h2 span,
    .section-head h2 .knp-red-text {
        font-family: 'Cormorant Garamond', Georgia, serif !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        letter-spacing: inherit !important;
        line-height: inherit !important;
        text-transform: inherit !important;
        color: var(--knp-red) !important;
    }

    .section-head-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin: 0 auto 16px;
    }

    .section-head-divider .divider-line {
        width: 45px;
        height: 1.5px;
        background: rgba(180, 0, 22, 0.4);
    }

    .section-head-divider .divider-gem {
        width: 6px;
        height: 6px;
        background: var(--knp-red);
        transform: rotate(45deg);
    }

    .section-head p {
        font-family: 'Montserrat', sans-serif;
        color: var(--knp-muted);
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.5px;
        max-width: 550px;
        margin: 0 auto;
    }

    /* ==========================================================================
       4. DISCOVER YOUR STYLE — LIQUID GLASS CATEGORY SHOWCASE
       ========================================================================== */
    .discover-section-bg {
        background: linear-gradient(180deg, #FFFFFF 0%, var(--knp-off-white) 100%);
    }

    .discover-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 28px;
    }

    .discover-card {
        text-decoration: none;
        color: var(--knp-black);
        display: flex;
        flex-direction: column;
        background: var(--glass-light-bg);
        backdrop-filter: blur(var(--glass-blur-md));
        -webkit-backdrop-filter: blur(var(--glass-blur-md));
        border: 1px solid var(--glass-light-border);
        border-radius: 12px;
        padding: 12px;
        box-shadow: var(--glass-shadow-soft);
        position: relative;
        overflow: hidden;
        transition: transform 0.4s var(--knp-ease), box-shadow 0.4s var(--knp-ease), border-color 0.4s var(--knp-ease);
    }

    .discover-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(180, 0, 22, 0.04) 50%, rgba(255, 255, 255, 0) 100%);
        opacity: 0;
        transition: opacity 0.4s var(--knp-ease);
        pointer-events: none;
    }

    .discover-card:hover {
        transform: translateY(-8px);
        background: var(--glass-light-hover);
        border-color: rgba(180, 0, 22, 0.35);
        box-shadow: var(--glass-shadow-elevated), 0 0 20px rgba(180, 0, 22, 0.1);
    }

    .discover-card:hover::before {
        opacity: 1;
    }

    .discover-img-box {
        width: 100%;
        aspect-ratio: 2 / 3;
        height: auto;
        padding-bottom: 0;
        overflow: hidden;
        border-radius: 8px;
        background: #F5F5F7;
        position: relative;
    }

    .discover-card img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        transform-origin: center center;
        will-change: transform;
    }

    .discover-card-footer {
        padding: 18px 8px 8px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .discover-card h3 {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 21px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
        color: var(--knp-black);
        transition: color 0.3s var(--knp-ease);
    }

    .discover-card:hover h3 {
        color: var(--knp-red);
    }

    .discover-card-link {
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: var(--knp-black);
        text-transform: uppercase;
        letter-spacing: 2px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s var(--knp-ease);
    }

    .discover-card:hover .discover-card-link {
        color: var(--knp-red);
        gap: 12px;
    }

    /* ==========================================================================
       5. BEST SELLERS — LIQUID GLASS PRODUCT CARDS
       ========================================================================== */
    .bestsellers-section {
        background: #FFFFFF;
    }

    .bestsellers-header-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 45px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .view-all-link-pill {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: var(--knp-black) !important;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        text-decoration: none;
        padding: 14px 28px;
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 100px;
        transition: all 0.4s var(--knp-ease);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .view-all-link-pill::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--knp-red);
        z-index: -1;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.4s var(--knp-ease);
    }

    .view-all-link-pill svg {
        transition: transform 0.4s var(--knp-ease);
    }

    .view-all-link-pill:hover {
        color: #FFFFFF !important;
        border-color: var(--knp-red) !important;
        box-shadow: 0 10px 30px -5px rgba(180, 0, 22, 0.4);
    }

    .view-all-link-pill:hover::before {
        transform: scaleX(1);
        transform-origin: left;
    }

    .view-all-link-pill:hover svg {
        transform: translateX(4px);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 24px;
    }

    .product-card {
        min-width: 0;
        background: var(--knp-white);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 10px;
        padding: 12px;
        text-align: center;
        position: relative;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 18px -4px rgba(0, 0, 0, 0.04);
        transition: transform 0.4s var(--knp-ease), box-shadow 0.4s var(--knp-ease), border-color 0.4s var(--knp-ease);
    }

    .product-card:hover {
        transform: translateY(-6px);
        border-color: rgba(180, 0, 22, 0.25);
        box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.1), 0 0 1px 1px rgba(180, 0, 22, 0.15);
    }

    .product-badge {
        position: absolute;
        top: 18px;
        left: 18px;
        background: transparent;
        color: var(--knp-red);
        border: 1px solid rgba(180, 0, 22, 0.5);
        font-family: 'Montserrat', sans-serif;
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 0px 10px;
        z-index: 2;
        border-radius: 2px;
        backdrop-filter: blur(4px);
    }

    .product-badge.dark-badge {
        color: var(--knp-coal);
        border: 1px solid rgba(28, 25, 23, 0.3);
        background: transparent;
        box-shadow: none;
    }

    .product-img-wrapper {
        width: 100%;
        aspect-ratio: 2 / 3;
        border-radius: 6px;
        overflow: hidden;
        background: #F8F8F7;
        margin-bottom: 14px;
        position: relative;
    }

    .product-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        transform-origin: center center;
        will-change: transform;
    }

    .product-info-box {
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: space-between;
    }

    .product-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--knp-black) !important;
        margin-bottom: 4px;
        line-height: 1.35;
        letter-spacing: 0.3px;
        min-height: 36px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s var(--knp-ease);
    }

    .product-card:hover .product-title {
        color: var(--knp-red) !important;
    }

    .product-sub {
        font-family: 'Montserrat', sans-serif;
        font-size: 10.5px;
        font-weight: 600;
        color: var(--knp-muted) !important;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
    }

    .product-price-row {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        margin-top: auto;
    }

    .product-price {
        font-family: 'Montserrat', sans-serif;
        font-size: 16px;
        font-weight: 800;
        color: var(--knp-red) !important;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .product-mrp {
        font-family: 'Montserrat', sans-serif;
        font-size: 11.5px;
        font-weight: 500;
        color: #71717A !important;
        text-decoration: line-through;
        white-space: nowrap;
    }

    /* ==========================================================================
       6. SIGNATURE BOX — LIQUID GLASS EDITORIAL FRAME
       ========================================================================== */
    .sig-box-section {
        width: 100% !important;
        max-width: 100% !important;
        margin: clamp(40px, 6vw, 70px) 0 !important;
        padding: 0 !important;
        position: relative;
        overflow: hidden;
        background: #FFFFFF;
    }

    .sig-box-glass-frame {
        width: 100% !important;
        border-radius: 0 !important;
        overflow: hidden;
        background: #FFFFFF;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        border-left: none !important;
        border-right: none !important;
        box-shadow: none !important;
        position: relative;
    }

    .combo-banner-slider {
        width: 100%;
        overflow: hidden !important;
        position: relative;
    }

    .combo-banner-track {
        display: flex;
        width: 100%;
        margin: 0;
        padding: 0;
        transition: transform 700ms cubic-bezier(0.25, 1, 0.5, 1);
        will-change: transform;
    }

    .combo-banner-slide {
        flex: 0 0 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        width: 100% !important;
        display: block;
        text-decoration: none;
        position: relative;
        background: #FFFFFF;
        line-height: 0;
        overflow: hidden !important;
    }

    .combo-banner-slide img {
        display: block;
        width: 100%;
        height: auto;
        max-height: none;
        object-fit: cover;
        object-position: center;
        transform: none !important;
    }

    .combo-carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(0, 0, 0, 0.12);
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        z-index: 10;
        color: var(--knp-black);
        transition: all 0.3s var(--knp-ease);
    }

    .combo-carousel-btn:hover {
        background: var(--knp-red);
        color: #FFFFFF;
        border-color: var(--knp-red);
        transform: translateY(-50%) scale(1.08);
        box-shadow: 0 8px 25px var(--knp-red-glow);
    }

    .combo-carousel-btn.left {
        left: 25px;
    }

    .combo-carousel-btn.right {
        right: 25px;
    }

    .combo-carousel-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 10;
    }

    .combo-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.25);
        border: 1.5px solid rgba(255, 255, 255, 0.9);
        cursor: pointer;
        transition: all 0.3s var(--knp-ease);
    }

    .combo-dot.active {
        width: 30px;
        border-radius: 10px;
        background: var(--knp-red);
        border-color: var(--knp-red);
        box-shadow: 0 0 10px var(--knp-red-glow);
    }

    /* ==========================================================================
       7. PHILOSOPHY SECTION — HAUTE ATELIER & GENTLEMEN HERITAGE
       ========================================================================== */
    .philosophy-section .reveal {
        opacity: 0;
        transform: translateY(35px);
        transition: opacity 0.9s ease-out, transform 0.9s ease-out;
        will-change: opacity, transform;
    }
    .philosophy-section .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
    .philosophy-section {
        background: var(--knp-dark, #0C0A09);
        padding: 120px 20px;
        position: relative;
    }
    .phil-container {
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 1200px;
        margin: 0 auto;
        gap: 70px;
        position: relative;
    }
    .phil-image-col {
        flex: 0 0 340px;
        position: relative;
        z-index: 1;
        margin: 0;
    }
    .phil-image-col::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: -20px;
        bottom: -20px;
        border: 1.5px solid rgb(73, 60, 19);
        pointer-events: none;
        z-index: 0;
    }
    .phil-border-frame {
        position: relative;
        z-index: 1;
        overflow: hidden;
        contain: paint;
        max-width: 100%;
        padding: 2px;
        background: rgba(73, 60, 19, 0.4);
        box-shadow: 24px 24px 50px -20px rgba(0,0,0,0.7);
    }
    .phil-border-spinner {
        position: absolute;
        inset: -150%;
        background: conic-gradient(
            from 0deg,
            transparent 0deg,
            transparent 70deg,
            rgba(232, 213, 163, 0.25) 110deg,
            #C9A227 145deg,
            #FFFFFF 180deg,
            #C9A227 215deg,
            rgba(232, 213, 163, 0.25) 250deg,
            transparent 290deg,
            transparent 360deg
        );
        animation: philBorderRotate 4s linear infinite;
        pointer-events: none;
        z-index: 1;
    }
    @keyframes philBorderRotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .phil-image-holder {
        position: relative;
        z-index: 2;
        background: #0C0A09;
        width: 100%;
        height: 100%;
        aspect-ratio: 1/1;
        display: block;
    }
    .phil-image-holder img {
        position: relative;
        z-index: 2;
        width: 100%;
        height: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        object-position: center top;
        display: block;
    }
    .phil-content-col {
        flex: 0 1 500px;
        background: transparent;
        padding: 0;
        position: relative;
        z-index: auto;
        margin-left: 0;
        box-shadow: none;
        border: none;
        text-align: center;
    }
    .phil-video-col {
        flex: 0 0 340px;
        position: relative;
        z-index: 1;
        margin-left: 0;
        margin-top: 0;
    }
    .phil-video-box {
        position: relative;
        z-index: 2;
        width: 100%;
        height: 100%;
        aspect-ratio: 1/1;
        background: #000;
        cursor: pointer;
        overflow: hidden;
        display: block;
    }
    .phil-video-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.6;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.6s ease;
    }
    .phil-video-box:hover img {
        transform: scale(1.05);
        opacity: 0.8;
    }
    .phil-play-overlay {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #FFF;
        pointer-events: none;
        z-index: 3;
    }
    .phil-play-btn {
        position: relative;
        width: 62px;
        height: 62px;
        background: rgba(12, 10, 9, 0.78);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1.5px solid var(--knp-gold-light, #E8D5A3);
        color: var(--knp-gold-light, #E8D5A3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        transition: all 0.35s var(--knp-ease, ease-out);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
    }
    .phil-play-btn svg {
        margin-left: 3px;
        fill: var(--knp-gold-light, #E8D5A3);
        transition: fill 0.3s ease, transform 0.3s ease;
    }
    .phil-play-btn::before,
    .phil-play-btn::after {
        content: '';
        position: absolute;
        inset: -8px;
        border: 1.5px solid rgba(201, 162, 39, 0.65);
        border-radius: 50%;
        animation: philPulseWave 2.8s cubic-bezier(0.16, 1, 0.3, 1) infinite;
        pointer-events: none;
    }
    .phil-play-btn::after {
        inset: -18px;
        border-color: rgba(201, 162, 39, 0.35);
        animation-delay: 0.9s;
    }
    @keyframes philPulseWave {
        0% { transform: scale(0.85); opacity: 1; }
        100% { transform: scale(1.45); opacity: 0; }
    }
    .phil-video-box:hover .phil-play-btn {
        background: var(--knp-gold-light, #E8D5A3);
        color: #0C0A09;
        transform: scale(1.1);
        box-shadow: 0 0 30px rgba(201, 162, 39, 0.75);
    }
    .phil-video-box:hover .phil-play-btn svg {
        fill: #0C0A09;
        transform: scale(1.1);
    }
    .phil-play-overlay span {
        font-family: 'Montserrat', sans-serif;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #FFFFFF;
        display: block;
        text-shadow: 0 2px 8px rgba(0,0,0,0.8);
    }
    .phil-kicker {
        color: var(--knp-gold-light, #E8D5A3);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 20px;
        display: block;
        text-align: center;
    }
    .phil-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(34px, 5vw, 48px);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 22px;
        letter-spacing: 0.02em;
        color: #FFFFFF;
        text-transform: uppercase;
        text-align: center;
        text-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
    }
    .phil-title .phil-line {
        display: block;
        overflow: hidden;
    }
    .phil-title .word {
        font-family: 'Cormorant Garamond', Georgia, serif !important;
        display: inline-block;
        margin-right: 0.22em;
        opacity: 0;
        transform: translateY(110%);
        transition: transform 0.85s var(--knp-ease, ease-out), opacity 0.85s ease;
        transition-delay: calc(var(--w-i, 1) * 0.08s);
        will-change: transform, opacity;
    }
    .philosophy-section .reveal.active .phil-title .word,
    .phil-content-col.active .phil-title .word {
        opacity: 1;
        transform: translateY(0%);
    }
    @supports (-webkit-background-clip: text) {
        .phil-title .word {
            background: linear-gradient(105deg, #FFFFFF 25%, #E8D5A3 42%, #C9A227 50%, #E8D5A3 58%, #FFFFFF 75%);
            background-size: 260% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: heroSheen 7s ease-in-out infinite;
        }
    }
    .phil-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        line-height: 1.85;
        color: rgba(255,255,255,0.7);
        font-weight: 400;
        margin-bottom: 30px;
        text-align: center;
    }
    .phil-signature {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-style: italic;
        font-size: 42px;
        line-height: 1.2;
        padding: 5px 0 10px 0;
        color: var(--knp-gold-pale, #E8D5A3);
        font-weight: 400;
        display: block;
        margin-bottom: 16px;
        text-shadow: 0 0 15px rgba(201, 162, 39, 0.4);
        text-align: center;
    }
    @supports (-webkit-background-clip: text) {
        .phil-signature {
            background: linear-gradient(105deg, #E8D5A3 20%, #FFFFFF 40%, #C9A227 60%, #E8D5A3 80%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: signShimmer 6s linear infinite;
        }
    }
    @keyframes signShimmer {
        to { background-position: 200% center; }
    }
    .phil-founder {
        font-family: 'Montserrat', sans-serif;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        color: rgba(255,255,255,0.45);
        text-align: center;
    }

    /* ==========================================================================
       8. STATS STRIP — LIQUID GLASS METRICS
       ========================================================================== */
    .stats-strip-wrapper {
        background: var(--knp-white);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 32px 0;
    }

    /* ==========================================================================
       9. TESTIMONIALS — VIDEO REVIEWS CAROUSEL
       ========================================================================== */
    .testimonials-section {
        background: var(--knp-off-white);
        position: relative;
    }

    .testi-carousel-wrapper {
        position: relative;
        max-width: 1360px;
        margin: 0 auto;
        padding: 0 50px;
    }

    .testi-carousel-btn {
        position: absolute;
        top: 40%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(var(--glass-blur-sm));
        -webkit-backdrop-filter: blur(var(--glass-blur-sm));
        border: 1px solid rgba(0, 0, 0, 0.08);
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        z-index: 2;
        color: var(--knp-black);
        transition: all 0.3s var(--knp-ease);
    }

    .testi-carousel-btn:hover {
        background: var(--knp-red);
        color: #FFFFFF;
        border-color: var(--knp-red);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 8px 24px var(--knp-red-glow);
    }

    .testi-carousel-btn.left { left: 0; }
    .testi-carousel-btn.right { right: 0; }

    .testi-grid {
        display: flex;
        gap: 24px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 15px 5px;
        scroll-behavior: smooth;
    }

    .testi-grid::-webkit-scrollbar {
        display: none;
    }

    .testi-item-wrapper {
        flex: 0 0 calc(20% - 19.2px);
        min-width: 210px;
        scroll-snap-align: start;
        display: flex;
        flex-direction: column;
    }

    .testi-card {
        position: relative;
        background: var(--knp-black);
        color: #FFF;
        aspect-ratio: 16/9;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 14px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        transition: transform 0.4s var(--knp-ease), box-shadow 0.4s var(--knp-ease), border-color 0.4s var(--knp-ease);
    }

    .testi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px -6px rgba(0, 0, 0, 0.2);
        border-color: rgba(180, 0, 22, 0.3);
    }

    .testi-card video,
    .testi-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.75;
        transition: opacity 0.4s var(--knp-ease);
    }

    .testi-card:hover video,
    .testi-card:hover img {
        opacity: 0.95;
    }

    .testi-play {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 44px;
        height: 44px;
        background: rgba(180, 0, 22, 0.8);
        backdrop-filter: blur(var(--glass-blur-sm));
        -webkit-backdrop-filter: blur(var(--glass-blur-sm));
        border: 1.5px solid rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFF;
        cursor: pointer;
        z-index: 2;
        box-shadow: 0 0 15px var(--knp-red-glow);
        transition: all 0.3s var(--knp-ease);
    }

    .testi-card:hover .testi-play {
        background: #FFFFFF;
        color: var(--knp-red);
        border-color: #FFFFFF;
        transform: translate(-50%, -50%) scale(1.15);
    }

    .testi-duration-left,
    .testi-duration-right {
        position: absolute;
        bottom: 8px;
        font-family: 'Montserrat', sans-serif;
        font-size: 10px;
        font-weight: 600;
        color: #FFF;
        background: rgba(0, 0, 0, 0.65);
        padding: 2px 6px;
        border-radius: 4px;
        backdrop-filter: blur(4px);
    }

    .testi-duration-left { left: 8px; }
    .testi-duration-right { right: 8px; }

    .testi-info {
        text-align: left;
    }

    .testi-info strong {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--knp-black);
        display: block;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .testi-stars {
        color: var(--knp-red);
        font-size: 13px;
        letter-spacing: 2px;
    }

    /* ==========================================================================
       10. ATELIER SOCIAL CURATION (INSTAGRAM SHOWCASE)
       ========================================================================== */
    .social-section {
        background: var(--knp-off-white);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .social-carousel-wrapper {
        position: relative;
        max-width: 1360px;
        margin: 0 auto;
        padding: 0 50px;
    }

    .social-carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(var(--glass-blur-sm));
        -webkit-backdrop-filter: blur(var(--glass-blur-sm));
        border: 1px solid rgba(0, 0, 0, 0.08);
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        z-index: 10;
        color: var(--knp-black);
        transition: all 0.3s var(--knp-ease);
    }

    .social-carousel-btn:hover {
        background: var(--knp-red);
        color: #FFFFFF;
        border-color: var(--knp-red);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 8px 24px var(--knp-red-glow);
    }

    .social-carousel-btn.left { left: 0; }
    .social-carousel-btn.right { right: 0; }

    .social-slider {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 15px 5px;
        scroll-behavior: smooth;
    }

    .social-slider::-webkit-scrollbar {
        display: none;
    }

    .social-item-wrapper {
        flex: 0 0 calc(25% - 15px);
        min-width: 240px;
        scroll-snap-align: start;
    }

    .social-card {
        position: relative;
        aspect-ratio: 1/1;
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        display: block;
        box-shadow: var(--glass-shadow-soft);
        border: 1px solid rgba(255, 255, 255, 0.8);
        background: var(--knp-obsidian);
        transition: transform 0.4s var(--knp-ease), box-shadow 0.4s var(--knp-ease);
    }

    .social-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform-origin: center center;
        transition: transform 0.6s var(--knp-ease);
        will-change: transform;
    }

    .social-card:hover img {
        transform: scale(1.08);
    }

    .social-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(8, 8, 9, 0.88) 0%, rgba(180, 0, 22, 0.4) 100%);
        opacity: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: opacity 0.4s var(--knp-ease);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    .social-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--glass-shadow-elevated), 0 0 20px rgba(180, 0, 22, 0.15);
    }

    .social-card:hover .social-overlay,
    .social-card:focus-visible .social-overlay {
        opacity: 1;
    }

    .social-icon-orb {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1;
        font-size: 22px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        color: var(--knp-red);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transform: scale(0.8);
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .social-card:hover .social-icon-orb,
    .social-card:focus-visible .social-icon-orb {
        transform: scale(1);
    }

    .social-handle-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: #FFFFFF;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    /* ==========================================================================
       11. JOURNAL — HIGH FASHION EDITORIAL MAGAZINE CARDS
       ========================================================================== */
    .journal-section {
        background: #FFFFFF;
    }

    .journal-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        max-width: 1360px;
        margin: 0 auto;
    }

    .journal-card {
        display: flex;
        gap: 20px;
        align-items: center;
        text-decoration: none;
        color: var(--knp-black);
        background: var(--glass-light-bg);
        backdrop-filter: blur(var(--glass-blur-sm));
        -webkit-backdrop-filter: blur(var(--glass-blur-sm));
        padding: 18px;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: var(--glass-shadow-soft);
        transition: transform 0.4s var(--knp-ease), box-shadow 0.4s var(--knp-ease), border-color 0.4s var(--knp-ease), background-color 0.4s var(--knp-ease);
        position: relative;
    }

    .journal-card:hover {
        transform: translateY(-6px);
        background: #FFFFFF;
        border-color: rgba(180, 0, 22, 0.3);
        box-shadow: var(--glass-shadow-elevated), 0 0 15px rgba(180, 0, 22, 0.08);
    }

    .journal-img-box {
        width: 130px;
        height: 195px;
        aspect-ratio: 2/3;
        border-radius: 8px;
        overflow: hidden;
        background: var(--knp-black);
        flex-shrink: 0;
    }

    .journal-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform-origin: center center;
        will-change: transform;
    }

    .journal-content {
        flex: 1;
        min-width: 0;
    }

    .journal-tag-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
    }

    .journal-tag {
        font-family: 'Montserrat', sans-serif;
        font-size: 9px;
        font-weight: 700;
        color: var(--knp-red);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .journal-read-pill {
        font-family: 'Montserrat', sans-serif;
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        white-space: nowrap;
        color: var(--knp-black);
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.08);
        padding: 2px 7px;
        border-radius: 100px;
    }

    .journal-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 19px;
        font-weight: 600;
        line-height: 1.35;
        margin-bottom: 10px;
        color: var(--knp-black);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s var(--knp-ease);
    }

    .journal-card:hover .journal-title {
        color: var(--knp-red) !important;
    }

    .journal-date {
        font-family: 'Montserrat', sans-serif;
        font-size: 10.5px;
        color: var(--knp-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .journal-read-link {
        font-size: 10px;
        font-weight: 700;
        color: var(--knp-red);
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.3s var(--knp-ease);
    }

    .journal-card:hover .journal-read-link {
        gap: 8px;
    }

    /* ==========================================================================
       12. VIDEO MODAL DIALOG
       ========================================================================== */
    .video-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background: rgba(8, 8, 9, 0.88);
        backdrop-filter: blur(var(--glass-blur-lg));
        -webkit-backdrop-filter: blur(var(--glass-blur-lg));
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s var(--knp-ease);
    }

    .video-modal-backdrop.modal-active {
        display: flex;
        opacity: 1;
    }

    .video-modal-box {
        position: relative;
        width: 90%;
        max-width: 850px;
        background: #000000;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.8), 0 0 30px var(--knp-red-glow);
        transform: scale(0.95);
        transition: transform 0.4s var(--knp-ease);
    }

    .video-modal-backdrop.modal-active .video-modal-box {
        transform: scale(1);
    }

    .video-modal-close {
        position: absolute;
        top: 14px;
        right: 18px;
        color: #FFF;
        font-size: 28px;
        cursor: pointer;
        z-index: 10;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        transition: all 0.3s var(--knp-ease);
    }

    .video-modal-close:hover {
        background: var(--knp-red);
        transform: rotate(90deg);
    }

    /* ==========================================================================
       13. RESPONSIVE BREAKPOINTS (TABLET & MOBILE OPTIMIZATIONS)
       ========================================================================== */
    @media (max-width: 1024px) {
        .products-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }
        .features-grid,
        .stats-features-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .discover-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }
        .social-item-wrapper {
            flex: 0 0 calc(33.333% - 14px);
            min-width: 220px;
        }
        .journal-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .phil-container {
            flex-direction: column;
            text-align: center;
            gap: 40px;
        }
        .phil-image-col,
        .phil-video-col {
            flex: unset;
            width: 100%;
            max-width: 380px;
        }
        .testi-item-wrapper {
            flex: 0 0 calc(33.333% - 16px);
            min-width: 200px;
        }
    }

    @media (max-width: 767px) {
        /* 1. Hero Section */
        .hero-section {
            min-height: clamp(480px, 68vh, 580px) !important;
            height: 70vh !important;
            max-height: 600px !important;
            position: relative;
            overflow: hidden;
        }
        .hero-slide video,
        .hero-slide .hero-static-bg {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center center !important;
            background-size: cover !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
        }
        .hero-container {
            padding: 115px 16px 50px !important;
        }
        .hero-kicker-badge {
            margin-bottom: 12px !important;
            gap: 8px !important;
        }
        .hero-kicker-dot {
            width: 7px !important;
            height: 7px !important;
        }
        .hero-kicker-text {
            font-size: clamp(12px, 3.4vw, 15px) !important;
            font-weight: 800 !important;
            letter-spacing: 2px !important;
            line-height: 1.2 !important;
        }
        .hero-title-main {
            font-size: clamp(32px, 8.5vw, 44px) !important;
            line-height: 1.02 !important;
            margin-bottom: 12px !important;
        }
        .hero-divider-bar {
            width: 90px !important;
            height: 2px !important;
            margin-bottom: 16px !important;
        }
        .hero-desc-text {
            font-size: 13px !important;
            line-height: 1.65 !important;
            margin-bottom: 24px !important;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8), 0 0 24px rgba(0, 0, 0, 0.6) !important;
            max-width: 100% !important;
        }
        .hero-collection-btn {
            padding: 12px 24px !important;
            font-size: 11px !important;
            letter-spacing: 2px !important;
        }
        .hero-nav-container {
            display: none !important;
        }
        .hero-mute-btn {
            bottom: 18px !important;
            right: 14px !important;
            min-width: 36px !important;
            height: 36px !important;
            padding: 0 10px !important;
            border-radius: 18px !important;
        }
        .hero-mute-btn .hero-mute-text {
            font-size: 10px !important;
        }

        /* 2. Global Section Padding & Headings */
        .section-padding {
            padding: 40px 15px !important;
        }
        .section-head {
            margin-bottom: 28px !important;
        }
        .section-head h2 {
            font-size: clamp(26px, 7vw, 36px) !important;
            line-height: 1.15 !important;
        }
        .section-head p {
            font-size: 13px !important;
            line-height: 1.55 !important;
            padding: 0 8px !important;
        }

        /* 3. Features & Stats Strips - Compact Mobile Layout */
        .features-strip {
            padding: clamp(12px, 3vw, 18px) 0 !important;
        }
        .features-grid,
        .stats-features-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: clamp(6px, 1.8vw, 10px) !important;
            padding: 0 clamp(10px, 3vw, 14px) !important;
        }
        .features-grid .feature-item,
        .stats-features-grid .feature-item {
            display: flex !important;
            align-items: center !important;
            padding: clamp(6px, 1.8vw, 9px) clamp(7px, 2vw, 10px) !important;
            gap: clamp(6px, 1.8vw, 9px) !important;
            border-radius: 8px !important;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03) !important;
            min-height: unset !important;
        }
        .features-grid .feature-icon-orb,
        .stats-features-grid .feature-icon-orb {
            width: clamp(26px, 7vw, 32px) !important;
            height: clamp(26px, 7vw, 32px) !important;
            min-width: clamp(26px, 7vw, 32px) !important;
        }
        .features-grid .feature-icon-orb svg,
        .stats-features-grid .feature-icon-orb svg {
            width: clamp(12px, 3.4vw, 15px) !important;
            height: clamp(12px, 3.4vw, 15px) !important;
        }
        .features-grid .feature-text,
        .stats-features-grid .feature-text {
            min-width: 0 !important;
            flex: 1 !important;
        }
        .features-grid .feature-text strong,
        .stats-features-grid .feature-text strong {
            font-size: clamp(8.5px, 2.4vw, 10.5px) !important;
            letter-spacing: 0.25px !important;
            line-height: 1.15 !important;
            margin-bottom: 1px !important;
            white-space: normal !important;
        }
        .features-grid .feature-text span,
        .stats-features-grid .feature-text span {
            font-size: clamp(7.5px, 2vw, 9px) !important;
            letter-spacing: 0.15px !important;
            line-height: 1.15 !important;
            white-space: normal !important;
        }
        /* Balance 5th item in stats grid to span full width and center icon + text together */
        .stats-features-grid .feature-item:last-child:nth-child(odd) {
            grid-column: span 2 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: clamp(8px, 2.5vw, 12px) !important;
        }
        .stats-features-grid .feature-item:last-child:nth-child(odd) .feature-text {
            flex: 0 1 auto !important;
            text-align: left !important;
        }

        /* 4. Curated Collections (Discover Your Style) */
        .discover-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
        }
        .discover-card {
            padding: 10px 8px !important;
            border-radius: 10px !important;
        }
        .discover-card-footer h3 {
            font-size: clamp(13px, 3.4vw, 16px) !important;
            margin-bottom: 4px !important;
            letter-spacing: 0.8px !important;
        }
        .discover-card-link {
            font-size: 9.5px !important;
            letter-spacing: 1px !important;
            gap: 4px !important;
            white-space: nowrap !important;
        }

        /* 5. Best Sellers Grid */
        .products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 10px !important;
        }
        .product-card {
            padding: 9px !important;
            border-radius: 8px !important;
        }
        .product-badge {
            top: 8px !important;
            left: 8px !important;
            padding: 3px 7px !important;
            font-size: 8.5px !important;
            letter-spacing: 1px !important;
        }
        .product-title {
            font-size: 12px !important;
            min-height: 32px !important;
            line-height: 1.3 !important;
            margin-bottom: 2px !important;
        }
        .product-sub {
            font-size: 9.5px !important;
            margin-bottom: 6px !important;
        }
        .product-price {
            font-size: 14.5px !important;
        }
        .product-mrp {
            font-size: 10.5px !important;
        }
        .bestsellers-header-row {
            margin-bottom: 24px !important;
            justify-content: center !important;
        }
        .view-all-link-pill {
            padding: 10px 22px !important;
            font-size: 10px !important;
            letter-spacing: 1.5px !important;
        }

        /* 6. Signature Combo Box Banner */
        .sig-box-section {
            padding: 0 !important;
            margin: 25px 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow: hidden !important;
        }
        .combo-carousel-btn {
            width: 32px !important;
            height: 32px !important;
            opacity: 0.7 !important;
        }
        .combo-carousel-btn.left { left: 6px !important; }
        .combo-carousel-btn.right { right: 6px !important; }
        .combo-banner-dots {
            bottom: 8px !important;
        }

        /* 7. Philosophy Section Mobile Polish */
        .philosophy-section {
            overflow: hidden !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 45px 16px !important;
        }
        /* 6. Philosophy Section */
        .philosophy-section {
            padding: clamp(34px, 7.5vw, 48px) 16px !important;
            overflow: hidden !important;
        }
        .philosophy-section .reveal {
            opacity: 1 !important;
            transform: none !important;
        }
        .philosophy-section .phil-title .word {
            opacity: 1 !important;
            transform: none !important;
        }
        .phil-container {
            flex-direction: column !important;
            gap: clamp(18px, 4vw, 24px) !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow: hidden !important;
        }
        .phil-image-col {
            flex: unset !important;
            width: 100% !important;
            max-width: clamp(210px, 60vw, 250px) !important;
            margin: 0 auto !important;
            position: relative;
        }
        .phil-image-col::before {
            top: -5px !important;
            left: -5px !important;
            right: -5px !important;
            bottom: -5px !important;
            border: 1px solid rgba(201, 162, 39, 0.45) !important;
        }
        .phil-border-spinner {
            inset: -50% !important;
            width: 200% !important;
            height: 200% !important;
            max-width: none !important;
        }
        .phil-video-col {
            flex: unset !important;
            width: 100% !important;
            max-width: clamp(210px, 60vw, 250px) !important;
            margin: 0 auto !important;
            position: relative;
        }
        .phil-video-col::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 1px solid rgba(201, 162, 39, 0.45);
            pointer-events: none;
            z-index: 0;
        }
        .phil-content-col {
            flex: unset !important;
            width: 100% !important;
            max-width: 360px !important;
            margin: 0 auto !important;
            padding: 0 8px !important;
            text-align: center !important;
        }
        .phil-quote-mark {
            font-size: 22px !important;
            margin-bottom: 4px !important;
        }
        .phil-kicker {
            font-size: 9.5px !important;
            letter-spacing: 2.2px !important;
            margin-bottom: 6px !important;
        }
        .phil-title {
            font-size: clamp(17px, 4.8vw, 21px) !important;
            line-height: 1.25 !important;
            margin-bottom: 10px !important;
            letter-spacing: 0.5px !important;
        }
        .phil-divider {
            margin: 0 auto 12px !important;
            width: 34px !important;
        }
        .phil-text {
            font-size: 12px !important;
            line-height: 1.62 !important;
            margin-bottom: 12px !important;
            padding: 0 4px !important;
            color: rgba(255, 255, 255, 0.72) !important;
        }
        .phil-signature {
            font-size: clamp(20px, 5.5vw, 24px) !important;
            margin-bottom: 2px !important;
        }
        .phil-founder {
            font-size: 8.5px !important;
            letter-spacing: 1.8px !important;
        }
        .phil-play-btn {
            width: 44px !important;
            height: 44px !important;
            margin-bottom: 6px !important;
        }
        .phil-play-btn svg {
            width: 16px !important;
            height: 16px !important;
        }
        .phil-play-overlay span {
            font-size: 8.5px !important;
            letter-spacing: 1.8px !important;
        }

        /* 8. Customer Video Testimonials */
        .testimonials-section {
            overflow: hidden !important;
            padding: 40px 0 !important;
        }
        .testi-carousel-wrapper {
            padding: 0 14px !important;
            overflow: hidden !important;
        }
        .testi-carousel-btn {
            display: none !important;
        }
        .testi-grid {
            gap: 14px !important;
            padding: 10px 4px !important;
        }
        .testi-item-wrapper {
            flex: 0 0 76% !important;
            min-width: 240px !important;
            max-width: 280px !important;
        }
        .testi-card {
            border-radius: 10px !important;
            margin-bottom: 10px !important;
        }
        .testi-play {
            width: 42px !important;
            height: 42px !important;
        }
        .testi-info strong {
            font-size: 13px !important;
        }

        /* 9. Social Atelier */
        .social-section {
            overflow: hidden !important;
            padding: 40px 0 !important;
        }
        .social-carousel-wrapper {
            padding: 0 14px !important;
            overflow: hidden !important;
        }
        .social-carousel-btn {
            display: none !important;
        }
        .social-slider {
            gap: 14px !important;
            padding: 10px 4px !important;
        }
        .social-item-wrapper {
            flex: 0 0 72% !important;
            min-width: 220px !important;
            max-width: 270px !important;
        }
        .social-card {
            border-radius: 12px !important;
            overflow: hidden !important;
        }
        .social-card img {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
        }

        /* 10. Journal / Editorial */
        .journal-section {
            padding: 26px 14px !important;
        }
        .journal-grid {
            gap: 12px !important;
        }
        .journal-card {
            display: flex !important;
            flex-direction: row !important;
            text-align: left !important;
            padding: 10px !important;
            gap: 12px !important;
            align-items: center !important;
            border-radius: 10px !important;
        }
        .journal-img-box {
            width: 95px !important;
            height: auto !important;
            min-width: 95px !important;
            aspect-ratio: 2 / 3 !important;
            border-radius: 8px !important;
            background: #fafafa !important;
            flex-shrink: 0 !important;
        }
        .journal-img-box img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center center !important;
        }
        .journal-content {
            padding: 0 !important;
            flex: 1 !important;
            min-width: 0 !important;
        }
        .journal-tag-row {
            margin-bottom: 4px !important;
            gap: 6px !important;
        }
        .journal-tag {
            font-size: 8.5px !important;
        }
        .journal-read-pill {
            font-size: 7.5px !important;
            padding: 2px 6px !important;
        }
        .journal-title {
            font-size: 14.5px !important;
            line-height: 1.28 !important;
            margin-bottom: 6px !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }
        .journal-date {
            font-size: 10px !important;
        }
        .journal-read-link {
            font-size: 9.5px !important;
        }

        /* 11. Floating Back-to-Top Control */
        #scrollUp {
            bottom: 20px !important;
            right: 14px !important;
            width: 38px !important;
            height: 38px !important;
            line-height: 38px !important;
            font-size: 16px !important;
            border-radius: 50% !important;
            opacity: 0.75 !important;
        }
        #scrollUp:hover,
        #scrollUp:active {
            opacity: 1 !important;
        }
    }

    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 8px !important;
        }
        .product-card {
            padding: 7px !important;
        }
        .product-title {
            font-size: 11.5px !important;
            min-height: 30px !important;
        }
        .product-price {
            font-size: 13.5px !important;
        }
        .product-mrp {
            font-size: 9.5px !important;
        }
        .discover-grid {
            gap: 8px !important;
        }
        .discover-card {
            padding: 8px 6px !important;
        }
        .discover-card-link {
            font-size: 9px !important;
            letter-spacing: 0.8px !important;
        }
    }
    /* Match the catalog card typography in the homepage best sellers. */
    .bestsellers-section .product-info-box {
        min-width: 0;
        text-align: left;
        justify-content: flex-start;
    }
    .bestsellers-section .product-info-box > div,
    .bestsellers-section .product-info-box a {
        min-width: 0;
    }
    .bestsellers-section .product-title {
        display: block;
        min-height: 0 !important;
        margin: 0 0 5px !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #111 !important;
        font-family: Georgia, 'Times New Roman', serif !important;
        font-size: 18px !important;
        font-weight: 600 !important;
        letter-spacing: .02em;
        line-height: 1.3 !important;
        text-transform: uppercase;
    }
    .bestsellers-section .product-sub {
        margin: 0 0 12px !important;
        color: #8a8a8a !important;
        font-family: 'Manrope', Arial, sans-serif !important;
        font-size: 12px !important;
        font-weight: 400;
        letter-spacing: 0;
        line-height: 1.35;
    }
    .bestsellers-section .product-curation {
        margin: 0 0 14px;
        color: #777;
        font-family: 'Manrope', Arial, sans-serif;
        font-size: 11px;
        line-height: 1.3;
    }
    .bestsellers-section .product-price-row {
        flex-direction: row;
        align-items: baseline;
        justify-content: flex-start;
        flex-wrap: wrap;
        column-gap: 14px;
        row-gap: 3px;
        min-width: 0;
        width: 100%;
        margin-top: auto;
    }
    .bestsellers-section .product-price {
        width: auto !important;
        flex: 0 0 auto;
        color: #111 !important;
        font-family: 'Manrope', Arial, sans-serif !important;
        font-size: 16px !important;
        font-weight: 800 !important;
        letter-spacing: 0;
    }
    .bestsellers-section .product-mrp {
        width: auto !important;
        flex: 0 0 auto;
        color: #929292 !important;
        font-family: 'Manrope', Arial, sans-serif !important;
        font-size: 11px !important;
        font-weight: 500;
    }
    @media (max-width: 575px) {
        .bestsellers-section .product-title { font-size: 15px !important; }
        .bestsellers-section .product-sub { font-size: 11px !important; }
        .bestsellers-section .product-curation { font-size: 10px; margin-bottom: 10px; }
        .bestsellers-section .product-price { font-size: 14px !important; }
        .bestsellers-section .product-mrp { font-size: 10px !important; }
        .bestsellers-section .product-price-row { column-gap: 7px; }
    }
</style>

<div class="home-wrapper">

    <!-- ==========================================================================
         1. HERO SECTION — LIQUID GLASS THEATER
         ========================================================================== -->
    <section class="hero-section" aria-label="Hero Showcase">

        <!-- Dynamic Hero Background Slides -->
        <div id="hero-bg-container" class="hero-bg-container">
            @if(isset($bannerVideos) && count($bannerVideos) > 0)
                @foreach($bannerVideos as $index => $banner)
                    <div class="hero-slide {{ $index === 0 ? 'active-slide' : '' }}" data-slide-index="{{ $index }}">
                        @if(Str::endsWith(strtolower($banner->video), ['.mp4', '.mov', '.webm']))
                            <video autoplay muted loop playsinline preload="auto" class="hero-media-player">
                                <source src="{{ house_main_media_url($banner->video, 'images') }}" type="video/{{ Str::endsWith(strtolower($banner->video), '.webm') ? 'webm' : 'mp4' }}">
                            </video>
                        @else
                            <div class="hero-static-bg" style="background: url('{{ house_main_media_url($banner->video, 'images') }}') no-repeat center center / cover;"></div>
                        @endif
                    </div>
                @endforeach
            @else
                <!-- High-Resolution Luxury Fallback BG -->
                <div class="hero-slide active-slide" data-slide-index="0">
                    <div class="hero-static-bg" style="background: url('{{ asset('images/banner/add.jpg') }}') no-repeat center center / cover;"></div>
                </div>
            @endif
        </div>

        <!-- Cinema-Grade Gradient Overlays -->
        <div class="hero-gradient-overlay" aria-hidden="true"></div>
        <div class="hero-glass-overlay"></div>

        <!-- Editorial Content Container -->
        <div class="hero-container">
            <div class="hero-glass-card">
                @if(isset($bannerVideos) && count($bannerVideos) > 0)
                    @foreach($bannerVideos as $index => $banner)
                        <div class="hero-content-slide {{ $index === 0 ? 'active-content' : '' }}" data-content-index="{{ $index }}">
                            <!-- Kicker Badge with Pulse Dot -->
                            <div class="hero-kicker-badge">
                                <div class="hero-kicker-dot"></div>
                                <span class="hero-kicker-text">{{ $banner->title ?: 'Haute Horlogerie & Atelier Parfum' }}</span>
                            </div>

                            <!-- Main Editorial Title -->
                            <h1 class="hero-title-main">
                                {{ $banner->subtitle ?: 'PRESENCE' }}
                            </h1>

                            <!-- Crimson & Specular Glass Divider -->
                            <div class="hero-divider-bar">
                                <div class="hero-divider-crimson"></div>
                                <div class="hero-divider-glass"></div>
                            </div>

                            <!-- Editorial Description -->
                            <p class="hero-desc-text">
                                {!! nl2br(e($banner->content ?: "Luxury essentials for men who believe style is remembered before words. Crafted with precision, worn with distinction.")) !!}
                            </p>

                            <!-- Magnetic CTA Button -->
                            <a class="hero-collection-btn magnetic-target" href="{{ url('shop') }}" aria-label="Explore Collection">
                                <span>EXPLORE COLLECTION</span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="hero-content-slide active-content" data-content-index="0">
                        <div class="hero-kicker-badge">
                            <div class="hero-kicker-dot"></div>
                            <span class="hero-kicker-text">Haute Horlogerie & Atelier Parfum</span>
                        </div>

                        <h1 class="hero-title-main">
                            PRESENCE
                        </h1>

                        <div class="hero-divider-bar">
                            <div class="hero-divider-crimson"></div>
                            <div class="hero-divider-glass"></div>
                        </div>

                        <p class="hero-desc-text">
                            Luxury essentials for men who believe style is remembered before words. Crafted with precision, worn with distinction.
                        </p>

                        <a class="hero-collection-btn magnetic-target" href="{{ url('shop') }}" aria-label="Explore Collection">
                            <span>EXPLORE COLLECTION</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right-side Minimalist Nav -->
        <div class="hero-nav-container">
            @if(isset($bannerVideos) && count($bannerVideos) > 0)
                @foreach($bannerVideos as $index => $banner)
                    <div class="hero-nav-item {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})" data-nav-index="{{ $index }}">
                        <span class="nav-num">{{ sprintf('%02d', $index + 1) }}</span>
                        <div class="nav-line"></div>
                    </div>
                @endforeach
            @else
                <div class="hero-nav-item active">
                    <span class="nav-num">01</span>
                    <div class="nav-line"></div>
                </div>
            @endif
        </div>

        <!-- Mute/Unmute Control -->
        <div class="hero-controls">
            <button id="hero-mute-btn" class="hero-mute-btn magnetic-target" type="button" aria-label="Toggle sound" title="Sound Muted - Click to play audio">
                <svg class="icon-muted" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <line x1="23" y1="9" x2="17" y2="15"></line>
                    <line x1="17" y1="9" x2="23" y2="15"></line>
                </svg>
                <svg class="icon-unmuted" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                </svg>
                <span class="hero-mute-text">SOUND</span>
            </button>
        </div>
    </section>

    <!-- ==========================================================================
         2. FEATURES STRIP — 5 TRUST PILLARS
         ========================================================================== -->
    @include('partials.trust-strip')

    <!-- ==========================================================================
         3. DISCOVER YOUR STYLE — CATEGORY SHOWCASE
         ========================================================================== -->
    <section class="discover-section-bg section-padding" aria-label="Curated Categories">
        <div class="section-head">
            <span class="section-kicker-tag">{{ $homeContent['collections_kicker'] ?? 'Curated Collections' }}</span>
            <h2>{!! $homeContent['collections_heading_html'] ?? 'DISCOVER YOUR <span class="knp-red-text">STYLE</span>' !!}</h2>
            <div class="section-head-divider">
                <div class="divider-line"></div>
                <div class="divider-gem"></div>
                <div class="divider-line"></div>
            </div>
            <p>{{ $homeContent['collections_desc'] ?? 'Explore our bespoke artisanal watches, bespoke apparel & signature fragrances' }}</p>
        </div>

        <div class="discover-grid">
            @if(isset($homeCategories) && count($homeCategories) > 0)
                @foreach($homeCategories as $category)
                    @if($loop->index < 4)
                    <a href="{{ url('shop?category='.$category->id) }}" class="discover-card" aria-label="{{ $category->category_name }}">
                        <div class="discover-img-box">
                            <img src="{{ house_main_media_url($category->category_image, 'images') }}" onerror="this.src='{{ asset('images/product/01.jpg') }}'" alt="{{ $category->category_name }}" loading="lazy">
                        </div>
                        <div class="discover-card-footer">
                            <h3>{{ $category->category_name }}</h3>
                            <span class="discover-card-link">
                                View Collection &rarr;
                            </span>
                        </div>
                    </a>
                    @endif
                @endforeach
            @else
                <a href="{{ url('shop?category=shirts') }}" class="discover-card" aria-label="Bespoke Shirts">
                    <div class="discover-img-box">
                        <img src="{{ asset('images/product/01.jpg') }}" alt="Shirts" loading="lazy">
                    </div>
                    <div class="discover-card-footer">
                        <h3>Bespoke Shirts</h3>
                        <span class="discover-card-link">View Collection &rarr;</span>
                    </div>
                </a>
                <a href="{{ url('shop?category=watches') }}" class="discover-card" aria-label="Haute Horlogerie">
                    <div class="discover-img-box">
                        <img src="{{ asset('images/product/02.jpg') }}" alt="Watches" loading="lazy">
                    </div>
                    <div class="discover-card-footer">
                        <h3>Haute Horlogerie</h3>
                        <span class="discover-card-link">View Collection &rarr;</span>
                    </div>
                </a>
                <a href="{{ url('shop?category=fragrance') }}" class="discover-card" aria-label="Atelier Fragrance">
                    <div class="discover-img-box">
                        <img src="{{ asset('images/product/03.jpg') }}" alt="Fragrance" loading="lazy">
                    </div>
                    <div class="discover-card-footer">
                        <h3>Atelier Fragrance</h3>
                        <span class="discover-card-link">View Collection &rarr;</span>
                    </div>
                </a>
                <a href="{{ url('combos') }}" class="discover-card" aria-label="Signature Box">
                    <div class="discover-img-box">
                        <img src="{{ asset('images/product/04.jpg') }}" alt="Signature Box" loading="lazy">
                    </div>
                    <div class="discover-card-footer">
                        <h3>Signature Box</h3>
                        <span class="discover-card-link">View Collection &rarr;</span>
                    </div>
                </a>
            @endif
        </div>
    </section>

    <!-- ==========================================================================
         4. BEST SELLERS — TRENDING & ICONIC PIECES
         ========================================================================== -->
    <section class="bestsellers-section section-padding" aria-label="Best Sellers">
        <div class="section-head">
            <span class="section-kicker-tag">{{ $homeContent['bestsellers_kicker'] ?? 'House Icons' }}</span>
            <h2>{!! $homeContent['bestsellers_heading_html'] ?? 'BEST <span class="knp-red-text">SELLERS</span>' !!}</h2>
            <div class="section-head-divider">
                <div class="divider-line"></div>
                <div class="divider-gem"></div>
                <div class="divider-line"></div>
            </div>
            <a href="{{ url('shop') }}" class="view-all-link-pill magnetic-target" style="margin-top: 10px;" aria-label="View all pieces">
                <span>VIEW ALL PIECES</span>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <div class="products-grid">
            @php
                $displayProducts = collect();
                if (isset($trendingCollectionProducts) && count($trendingCollectionProducts) > 0) {
                    $displayProducts = collect($trendingCollectionProducts)->take(5);
                } elseif (isset($latestCollectionProducts) && count($latestCollectionProducts) > 0) {
                    $displayProducts = collect($latestCollectionProducts)->take(5);
                } elseif (isset($homeCategoryProducts) && count($homeCategoryProducts) > 0) {
                    $displayProducts = collect($homeCategoryProducts)->take(5);
                }
            @endphp

            @forelse($displayProducts as $product)
                @php
                    $prodPrice = $product->trending_price ?? $product->latest_price ?? $product->home_price ?? house_product_price($product);
                    $prodMrp = $product->trending_mrp ?? $product->latest_mrp ?? $product->home_mrp ?? ($product->product_mrp_price ?? $prodPrice);
                    $prodImg = $product->trending_image ?? $product->latest_image ?? $product->home_image ?? house_main_product_image_url($product);
                    $prodSlug = $product->slug ?? $product->id;
                    $prodName = $product->product_name ?? 'Bespoke Creation';
                    $prodCate = $product->cate_name ?? $product->home_category_name ?? 'Haute Collection';
                @endphp
                <div class="product-card">
                    @if($prodMrp > $prodPrice)
                        <span class="product-badge">New </span>
                    @else
                        <span class="product-badge dark-badge">Iconic</span>
                    @endif

                    <div class="product-img-wrapper">
                        <a href="{{ url('single-product?product='.$prodSlug) }}" aria-label="{{ $prodName }}">
                            <img src="{{ $prodImg }}" onerror="this.src='{{ asset('images/product/01.jpg') }}'" alt="{{ $prodName }}" loading="lazy">
                        </a>
                    </div>

                    <div class="product-info-box">
                        <div>
                            <a href="{{ url('single-product?product='.$prodSlug) }}" style="text-decoration: none;">
                                <div class="product-title">{{ $prodName }}</div>
                            </a>
                            <div class="product-sub">{{ $prodCate }}</div>
                            <div class="product-curation">★ Atelier Curated</div>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">{{ house_money($prodPrice) }}</span>
                            @if($prodMrp > $prodPrice)
                                <span class="product-mrp">{{ house_money($prodMrp) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="product-card">
                    <span class="product-badge">New Release</span>
                    <div class="product-img-wrapper">
                        <a href="{{ url('shop') }}" aria-label="Royal Oxford Handcrafted Shirt">
                            <img src="{{ asset('images/product/01.jpg') }}" alt="Artisanal Shirt" loading="lazy">
                        </a>
                    </div>
                    <div class="product-info-box">
                        <div>
                            <div class="product-sub">Bespoke Apparel</div>
                            <a href="{{ url('shop') }}" style="text-decoration: none;">
                                <div class="product-title">Royal Oxford Handcrafted Shirt</div>
                            </a>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">₹2,499</span>
                            <span class="product-mrp">MRP ₹3,999</span>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <span class="product-badge dark-badge">Iconic</span>
                    <div class="product-img-wrapper">
                        <a href="{{ url('shop') }}" aria-label="Chronograph Obsidian Automatic">
                            <img src="{{ asset('images/product/02.jpg') }}" alt="Haute Horlogerie" loading="lazy">
                        </a>
                    </div>
                    <div class="product-info-box">
                        <div>
                            <div class="product-sub">Haute Horlogerie</div>
                            <a href="{{ url('shop') }}" style="text-decoration: none;">
                                <div class="product-title">Chronograph Obsidian Automatic</div>
                            </a>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">₹14,999</span>
                            <span class="product-mrp">MRP ₹19,999</span>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <span class="product-badge">New Release</span>
                    <div class="product-img-wrapper">
                        <a href="{{ url('shop') }}" aria-label="Sovereign Oud Pure Extrait">
                            <img src="{{ asset('images/product/03.jpg') }}" alt="Atelier Fragrance" loading="lazy">
                        </a>
                    </div>
                    <div class="product-info-box">
                        <div>
                            <div class="product-sub">Atelier Parfum</div>
                            <a href="{{ url('shop') }}" style="text-decoration: none;">
                                <div class="product-title">Sovereign Oud Pure Extrait</div>
                            </a>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">₹4,299</span>
                            <span class="product-mrp">MRP ₹5,499</span>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <span class="product-badge dark-badge">Iconic</span>
                    <div class="product-img-wrapper">
                        <a href="{{ url('combos') }}" aria-label="The Executive Suite Collection">
                            <img src="{{ asset('images/product/04.jpg') }}" alt="Signature Box" loading="lazy">
                        </a>
                    </div>
                    <div class="product-info-box">
                        <div>
                            <div class="product-sub">Signature Combo</div>
                            <a href="{{ url('combos') }}" style="text-decoration: none;">
                                <div class="product-title">The Executive Suite Collection</div>
                            </a>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">₹8,999</span>
                            <span class="product-mrp">MRP ₹12,499</span>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <span class="product-badge">New Release</span>
                    <div class="product-img-wrapper">
                        <a href="{{ url('shop') }}" aria-label="Elysium Celestial Moonphase">
                            <img src="{{ asset('images/product/01.jpg') }}" alt="Tourbillon" loading="lazy">
                        </a>
                    </div>
                    <div class="product-info-box">
                        <div>
                            <div class="product-sub">Grand Complication</div>
                            <a href="{{ url('shop') }}" style="text-decoration: none;">
                                <div class="product-title">Elysium Celestial Moonphase</div>
                            </a>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">₹18,499</span>
                            <span class="product-mrp">MRP ₹24,999</span>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- ==========================================================================
         5. SIGNATURE BOX — COMBOS EDITORIAL BANNER
         ========================================================================== -->
    <section class="sig-box-section" aria-label="Signature Gift Box">
        <div class="sig-box-glass-frame">
            <div class="combo-banner-slider" id="combo-banner-slider">
                {{-- Previous Button --}}
                <button class="combo-carousel-btn left" onclick="comboSlidePrev()" aria-label="Previous Banner">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                @php
                    $allComboBanners = collect();
                    if (isset($giftBanners) && count($giftBanners) > 0) {
                        foreach ($giftBanners as $b) {
                            $allComboBanners->push([
                                'url' => url('combos'),
                                'img' => house_main_media_url($b->category_image, 'images'),
                                'alt' => 'Combo Box Banner'
                            ]);
                        }
                    }
                    if ($allComboBanners->isEmpty()) {
                        $allComboBanners = collect([
                            ['url' => url('combos'), 'img' => asset('images/knp/knp_gift_box_1773738766506.png'), 'alt' => 'Signature Combo Box 1'],
                            ['url' => url('combos'), 'img' => asset('images/knp/combo_box.png'), 'alt' => 'Signature Combo Box 2'],
                            ['url' => url('combos'), 'img' => asset('images/knp/knp_combo_products_1773738840245.png'), 'alt' => 'Signature Combo Box 3'],
                        ]);
                    }
                @endphp

                <div class="combo-banner-track">
                    @foreach($allComboBanners as $banner)
                        <a class="combo-banner-slide" href="{{ $banner['url'] }}" aria-label="{{ $banner['alt'] }}">
                            <img src="{{ $banner['img'] }}" onerror="this.src='{{ asset('images/knp/knp_gift_box_1773738766506.png') }}'" alt="{{ $banner['alt'] }}" loading="lazy">
                        </a>
                    @endforeach
                </div>

                {{-- Next Button --}}
                <button class="combo-carousel-btn right" onclick="comboSlideNext()" aria-label="Next Banner">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>

                {{-- Pagination Dots --}}
                @if($allComboBanners->count() > 1)
                <div class="combo-carousel-dots">
                    @foreach($allComboBanners as $idx => $b)
                        <span class="combo-dot {{ $idx === 0 ? 'active' : '' }}" onclick="goToComboSlide({{ $idx }})" role="button" aria-label="Slide {{ $idx + 1 }}"></span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         6. PHILOSOPHY SECTION — ATELIER & GENTLEMEN HERITAGE
         ========================================================================== -->
    @php
        $homeSectionMediaUrl = function ($path) {
            $path = trim((string) $path, " \t\n\r\x00\v\"'");
            $mainUrl = rtrim((string) env('MAIN_URL'), '/');

            if ($path === '') {
                return '';
            }

            if (preg_match('/^https?:\/\//i', $path)) {
                return $path;
            }

            $path = ltrim($path, '/');

            if ($mainUrl !== '' && str_starts_with($path, 'home_sections/')) {
                return $mainUrl.'/images/'.$path;
            }

            if ($mainUrl !== '' && str_starts_with($path, 'storage/')) {
                return $mainUrl.'/'.$path;
            }

            if (str_starts_with($path, 'storage/')) {
                return asset($path);
            }

            if (str_starts_with($path, 'home_sections/')) {
                return asset($path);
            }

            return asset($path);
        };

        $philosophyKicker = trim((string) ($homeContent['philosophy_kicker'] ?? ''));
        $philosophyTitle1 = trim((string) ($homeContent['philosophy_title_1'] ?? ''));
        $philosophyTitle2 = trim((string) ($homeContent['philosophy_title_2'] ?? ''));
        $philosophyTitle3 = trim((string) ($homeContent['philosophy_title_3'] ?? ''));
        $philosophyDesc = trim((string) ($homeContent['philosophy_desc'] ?? ''));
        $philosophySignature = trim((string) ($homeContent['philosophy_signature'] ?? ''));
        $philosophyDesignation = trim((string) ($homeContent['philosophy_designation'] ?? ''));
        $philosophyImageUrl = $homeSectionMediaUrl($homeContent['philosophy_image'] ?? null);
        $philosophyThumbnailUrl = $homeSectionMediaUrl($homeContent['philosophy_thumbnail'] ?? null);
        $philosophyVideoUrl = $homeSectionMediaUrl($homeContent['philosophy_video'] ?? null);
        $philosophyTitle1Words = preg_split('/\s+/', $philosophyTitle1, -1, PREG_SPLIT_NO_EMPTY);
        $philosophyTitle2Words = preg_split('/\s+/', $philosophyTitle2, -1, PREG_SPLIT_NO_EMPTY);
        $philosophyTitle3Words = preg_split('/\s+/', $philosophyTitle3, -1, PREG_SPLIT_NO_EMPTY);
    @endphp
    <section class="philosophy-section" aria-label="Atelier Philosophy">
        <div class="phil-container">
            <div class="phil-col phil-image-col reveal">
                <div class="phil-border-frame" style="aspect-ratio: 1/1;">
                    <div class="phil-border-spinner"></div>
                    <div class="phil-image-holder">
                        @if($philosophyImageUrl !== '')
                            <img src="{{ $philosophyImageUrl }}" onerror="this.remove()" alt="Our Philosophy Portrait" loading="lazy">
                        @endif
                    </div>
                </div>
            </div>

            <div class="phil-col phil-content-col reveal" style="transition-delay: 0.2s;">
                <div class="phil-quote-mark" style="color: var(--knp-gold-light, #E8D5A3); font-size: 32px; line-height: 1; margin-bottom: 8px; font-family: 'Cormorant Garamond', Georgia, serif; text-align: center;">&#10077;</div>
                @if($philosophyKicker !== '')
                    <span class="phil-kicker">{{ $philosophyKicker }}</span>
                @endif
                <h2 class="phil-title">
                    <span class="phil-line">
                        @foreach($philosophyTitle1Words as $i => $word)
                            <span class="word" style="--w-i: {{ $i + 1 }}">{{ $word }}</span>
                        @endforeach
                    </span>
                    <span class="phil-line">
                        @foreach($philosophyTitle2Words as $i => $word)
                            <span class="word" style="--w-i: {{ $i + 4 }}">{{ $word }}</span>
                        @endforeach
                    </span>
                    <span class="phil-line">
                        @foreach($philosophyTitle3Words as $i => $word)
                            <span class="word" style="--w-i: {{ $i + 7 }}">{{ $word }}</span>
                        @endforeach
                    </span>
                </h2>
                <div class="phil-divider" style="width: 45px; height: 1px; background: var(--knp-gold-light, #E8D5A3); margin: 0 auto 22px;"></div>
                @if($philosophyDesc !== '')
                    <p class="phil-text">{!! nl2br(e($philosophyDesc)) !!}</p>
                @endif
                @if($philosophySignature !== '')
                    <div class="phil-signature">{{ $philosophySignature }}</div>
                @endif
                @if($philosophyDesignation !== '')
                    <div class="phil-founder">{{ $philosophyDesignation }}</div>
                @endif
            </div>

            <div class="phil-col phil-video-col reveal" style="transition-delay: 0.4s;">
                <div class="phil-border-frame" style="aspect-ratio: 1/1; cursor: pointer;" @if($philosophyVideoUrl !== '') onclick="openVideoModal('{{ $philosophyVideoUrl }}')" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ $philosophyVideoUrl }}');}" tabindex="0" role="button" @endif aria-label="Watch Our Story Film">
                    <div class="phil-border-spinner"></div>
                    <div class="phil-video-box">
                        @if($philosophyThumbnailUrl !== '')
                            <img src="{{ $philosophyThumbnailUrl }}" onerror="this.remove()" alt="Our Story Film" loading="lazy">
                        @endif
                        <div class="phil-play-overlay">
                            <div class="phil-play-btn">
                                <svg viewBox="0 0 24 24" width="22" height="22"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            <span>OUR STORY FILM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function setReviewVideoDuration(video) {
            if (!video || !Number.isFinite(video.duration)) return;
            const minutes = Math.floor(video.duration / 60);
            const seconds = String(Math.floor(video.duration % 60)).padStart(2, '0');
            const duration = minutes + ':' + seconds;
            const card = video.closest('.testi-card');
            if (card) {
                const durationElements = card.querySelectorAll('.testi-duration-left, .testi-duration-right');
                durationElements.forEach(function(element) {
                    element.textContent = duration;
                });
            }
        }
    </script>

    <!-- ==========================================================================
         8. TESTIMONIALS — VIDEO REVIEWS
         ========================================================================== -->
    <section class="testimonials-section section-padding" aria-label="Customer Video Reviews">
        <div class="section-head">
            <span class="section-kicker-tag">{{ $homeContent['testimonials_kicker'] ?? 'Voices of Distinction' }}</span>
            <h2>{!! $homeContent['testimonials_heading_html'] ?? 'WHAT <span class="knp-red-text">GENTLEMEN</span> SAY' !!}</h2>
            <div class="section-head-divider">
                <div class="divider-line"></div>
                <div class="divider-gem"></div>
                <div class="divider-line"></div>
            </div>
            <p>{{ $homeContent['testimonials_desc'] ?? 'Real stories from collectors, leaders, and modern gentlemen' }}</p>
        </div>

        <div class="testi-carousel-wrapper">
            <button class="testi-carousel-btn left" onclick="testiSlidePrev()" aria-label="Previous Testimonial">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <div class="testi-grid" id="testi-slider">
                @if(isset($reviewVideos) && count($reviewVideos) > 0)
                    @foreach($reviewVideos as $video)
                    <div class="testi-item-wrapper">
                        <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by {{ $video->name ?? 'House of KNP Collector' }}" onclick="openVideoModal('{{ house_main_media_url($video->video, 'images') }}')" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ house_main_media_url($video->video, 'images') }}');}">
                            <video src="{{ house_main_media_url($video->video, 'images') }}" preload="metadata" muted playsinline onloadedmetadata="setReviewVideoDuration(this)"></video>
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
                                @for($i=1; $i<=5; $i++)
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
                        <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by Arun Prakash" onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ asset('videos/our_story.mp4') }}');}">
                            <img src="{{ asset('images/knp/testi1.jpg') }}" onerror="this.src='{{ asset('images/product/01.jpg') }}'" alt="Review 1" loading="lazy">
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
                        <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by Vikram Malhotra" onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ asset('videos/our_story.mp4') }}');}">
                            <img src="{{ asset('images/knp/phil_man.jpg') }}" onerror="this.src='{{ asset('images/product/02.jpg') }}'" alt="Review 2" loading="lazy">
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
                        <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by Rohan Singhania" onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ asset('videos/our_story.mp4') }}');}">
                            <img src="{{ asset('images/knp/story_bg.jpg') }}" onerror="this.src='{{ asset('images/product/03.jpg') }}'" alt="Review 3" loading="lazy">
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
                        <div class="testi-card" role="button" tabindex="0" aria-label="Watch review by Siddharth Sen" onclick="openVideoModal('{{ asset('videos/our_story.mp4') }}')" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openVideoModal('{{ asset('videos/our_story.mp4') }}');}">
                            <img src="{{ asset('images/knp/product_perfume.png') }}" onerror="this.src='{{ asset('images/product/04.jpg') }}'" alt="Review 4" loading="lazy">
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
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </section>

    <!-- ==========================================================================
         9. ATELIER SOCIAL SHOWCASE — THE KNP WORLD
         ========================================================================== -->
    <section class="social-section section-padding" aria-label="The Social Atelier">
        <div class="section-head">
            <span class="section-kicker-tag">{{ $homeContent['social_kicker'] ?? 'The Social Atelier' }}</span>
            <h2>{!! $homeContent['social_heading_html'] ?? 'FOLLOW <span class="knp-red-text">@HOUSEOFKNP</span>' !!}</h2>
            <div class="section-head-divider">
                <div class="divider-line"></div>
                <div class="divider-gem"></div>
                <div class="divider-line"></div>
            </div>
            <p>{{ $homeContent['social_desc'] ?? 'Step inside our world of haute horlogerie, bespoke tailoring, and sensory luxury' }}</p>
        </div>

        <div class="social-carousel-wrapper">
            <button class="social-carousel-btn left" onclick="socialSlidePrev()" aria-label="Previous Social Post">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <div class="social-slider" id="social-slider">
                @php
                    $socialPlatforms = [
                        'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'fa-whatsapp'],
                        'instagram' => ['label' => 'Instagram', 'icon' => 'fa-instagram'],
                        'facebook' => ['label' => 'Facebook', 'icon' => 'fa-facebook'],
                        'youtube' => ['label' => 'YouTube', 'icon' => 'fa-youtube-play'],
                    ];
                    $socialItems = collect();
                    if (isset($instagramImages) && count($instagramImages) > 0) {
                        foreach ($instagramImages as $item) {
                            $socialItems->push([
                                'url' => $item->link_url ?: '#',
                                'platform' => strtolower(trim((string) $item->platform)),
                                'img' => house_main_media_url($item->bg_image, 'images')
                            ]);
                        }
                    }
                    if ($socialItems->isEmpty()) {
                        $socialItems = collect([
                            ['platform' => 'instagram', 'url' => 'https://www.instagram.com', 'img' => asset('images/knp/phil_man.jpg')],
                            ['platform' => 'instagram', 'url' => 'https://www.instagram.com', 'img' => asset('images/knp/product_watch.png')],
                            ['platform' => 'instagram', 'url' => 'https://www.instagram.com', 'img' => asset('images/knp/product_perfume.png')],
                            ['platform' => 'instagram', 'url' => 'https://www.instagram.com', 'img' => asset('images/knp/knp_gift_box_1773738766506.png')],
                            ['platform' => 'instagram', 'url' => 'https://www.instagram.com', 'img' => asset('images/knp/product_shirt.png')],
                            ['platform' => 'instagram', 'url' => 'https://www.instagram.com', 'img' => asset('images/knp/knp_brand_story_1773738876981.png')],
                            ['platform' => 'instagram', 'url' => 'https://www.instagram.com', 'img' => asset('images/knp/knp_instagram_gallery_1773738894114.png')],
                            ['platform' => 'instagram', 'url' => 'https://www.instagram.com', 'img' => asset('images/knp/lifestyle_banner.png')],
                        ]);
                    }
                @endphp

                @foreach($socialItems as $social)
                @php
                    $platform = $socialPlatforms[$social['platform']] ?? ['label' => 'Social Media', 'icon' => 'fa-external-link'];
                @endphp
                <div class="social-item-wrapper">
                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" class="social-card" aria-label="Visit House of KNP on {{ $platform['label'] }}">
                        <img src="{{ $social['img'] }}" onerror="this.src='{{ asset('images/product/01.jpg') }}'" alt="House of KNP on {{ $platform['label'] }}" loading="lazy">
                        <div class="social-icon-orb" aria-hidden="true">
                            <i class="fa {{ $platform['icon'] }}"></i>
                        </div>
                        <div class="social-overlay">
                            <span class="social-handle-text">{{ $platform['label'] }}</span>
                            <span class="social-handle-text">@HOUSEOFKNP</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <button class="social-carousel-btn right" onclick="socialSlideNext()" aria-label="Next Social Post">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </section>

    <!-- ==========================================================================
         10. JOURNAL — HIGH FASHION EDITORIAL STORIES
         ========================================================================== -->
    <section class="journal-section section-padding" aria-label="Editorial Journal">
        <div class="section-head">
            <span class="section-kicker-tag">{{ $homeContent['journal_kicker'] ?? 'The Horology & Scent Chronicles' }}</span>
            <h2>{!! $homeContent['journal_heading_html'] ?? 'FROM THE <span class="knp-red-text">JOURNAL</span>' !!}</h2>
            <div class="section-head-divider">
                <div class="divider-line"></div>
                <div class="divider-gem"></div>
                <div class="divider-line"></div>
            </div>
            <a href="{{ url('blog') }}" class="view-all-link-pill magnetic-target" style="margin-top: 10px;" aria-label="View all journal articles">
                <span>VIEW ALL ARTICLES</span>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <div class="journal-grid">
            @if(isset($latestBlogs) && count($latestBlogs) > 0)
                @php
                    $fallbacks = [
                        asset('images/knp/product_shirt.png'),
                        asset('images/knp/product_watch.png'),
                        asset('images/knp/product_perfume.png')
                    ];
                    $journalTags = ['Bespoke Style', 'Haute Horlogerie', 'The Art of Scent'];
                @endphp
                @foreach(collect($latestBlogs)->take(3) as $index => $blog)
                <a href="{{ url('blog/'.$blog->id) }}" class="journal-card" aria-label="{{ $blog->title }}">
                    <div class="journal-img-box">
                        <img src="{{ house_main_media_url($blog->image, 'uploads/blogs') }}" onerror="this.src='{{ $fallbacks[$index % 3] }}'" alt="{{ $blog->title }}" loading="lazy">
                    </div>
                    <div class="journal-content">
                        <div class="journal-tag-row">
                            <span class="journal-tag">{{ $journalTags[$index % 3] }}</span>
                            <span class="journal-read-pill">3 Min Read</span>
                        </div>
                        <div class="journal-title">{{ $blog->title }}</div>
                        <div class="journal-date">
                            <span>{{ \Carbon\Carbon::parse($blog->date ?? $blog->created_at ?? now())->format('M d, Y') }}</span>
                            <span class="journal-read-link">Read Story &rarr;</span>
                        </div>
                    </div>
                </a>
                @endforeach
            @else
                <a href="{{ url('blog') }}" class="journal-card" aria-label="5 Sartorial Habits Every Modern Leader Must Master">
                    <div class="journal-img-box">
                        <img src="{{ asset('images/knp/product_shirt.png') }}" onerror="this.src='{{ asset('images/product/01.jpg') }}'" alt="Blog 1" loading="lazy">
                    </div>
                    <div class="journal-content">
                        <div class="journal-tag-row">
                            <span class="journal-tag">Bespoke Style</span>
                            <span class="journal-read-pill">3 Min Read</span>
                        </div>
                        <div class="journal-title">5 Sartorial Habits Every Modern Leader Must Master</div>
                        <div class="journal-date">
                            <span>JULY 20, 2025</span>
                            <span class="journal-read-link">Read Story &rarr;</span>
                        </div>
                    </div>
                </a>
                <a href="{{ url('blog') }}" class="journal-card" aria-label="Why An Automatic Movement Is The Ultimate Statement of Precision">
                    <div class="journal-img-box">
                        <img src="{{ asset('images/knp/product_watch.png') }}" onerror="this.src='{{ asset('images/product/02.jpg') }}'" alt="Blog 2" loading="lazy">
                    </div>
                    <div class="journal-content">
                        <div class="journal-tag-row">
                            <span class="journal-tag">Haute Horlogerie</span>
                            <span class="journal-read-pill">4 Min Read</span>
                        </div>
                        <div class="journal-title">Why An Automatic Movement Is The Ultimate Statement of Precision</div>
                        <div class="journal-date">
                            <span>JULY 16, 2025</span>
                            <span class="journal-read-link">Read Story &rarr;</span>
                        </div>
                    </div>
                </a>
                <a href="{{ url('blog') }}" class="journal-card" aria-label="The Psychology of A Signature Olfactory Impression">
                    <div class="journal-img-box">
                        <img src="{{ asset('images/knp/product_perfume.png') }}" onerror="this.src='{{ asset('images/product/03.jpg') }}'" alt="Blog 3" loading="lazy">
                    </div>
                    <div class="journal-content">
                        <div class="journal-tag-row">
                            <span class="journal-tag">The Art of Scent</span>
                            <span class="journal-read-pill">3 Min Read</span>
                        </div>
                        <div class="journal-title">The Psychology of A Signature Olfactory Impression</div>
                        <div class="journal-date">
                            <span>JULY 15, 2025</span>
                            <span class="journal-read-link">Read Story &rarr;</span>
                        </div>
                    </div>
                </a>
            @endif
        </div>
    </section>

</div>

<!-- ==========================================================================
     11. VIDEO MODAL DIALOG
     ========================================================================== -->
<div id="reviewVideoModal" class="video-modal-backdrop" role="dialog" aria-modal="true" aria-label="Video Player">
    <div class="video-modal-box">
        <button onclick="closeVideoModal()" class="video-modal-close" aria-label="Close Video Dialog">&times;</button>
        <video id="modalVideoPlayer" controls autoplay playsinline style="width: 100%; height: auto; display: block; max-height: 80vh;"></video>
    </div>
</div>

<!-- ==========================================================================
     12. GSAP SCRIPTS & INTERACTION ENGINE
     ========================================================================== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

<script>
    /* --------------------------------------------------------------------------
       MODAL CONTROLLER (ACCESSIBLE & ESCAPE KEY ENABLED)
       -------------------------------------------------------------------------- */
    function openVideoModal(videoSrc) {
        const modal = document.getElementById('reviewVideoModal');
        const player = document.getElementById('modalVideoPlayer');
        if (modal && player) {
            player.src = videoSrc;
            modal.classList.add('modal-active');
            player.play().catch(() => {});
        }
    }

    function closeVideoModal() {
        const modal = document.getElementById('reviewVideoModal');
        const player = document.getElementById('modalVideoPlayer');
        if (modal && player) {
            player.pause();
            player.src = '';
            modal.classList.remove('modal-active');
        }
    }

    function setReviewVideoDuration(video) {
        if (!Number.isFinite(video.duration)) return;
        const minutes = Math.floor(video.duration / 60);
        const seconds = String(Math.floor(video.duration % 60)).padStart(2, '0');
        const duration = minutes + ':' + seconds;
        const card = video.closest('.testi-card');
        if (card) {
            const durationElements = card.querySelectorAll('.testi-duration-left, .testi-duration-right');
            durationElements.forEach(function(element) {
                element.textContent = duration;
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Modal backdrop click & escape key close
        const videoModal = document.getElementById('reviewVideoModal');
        if (videoModal) {
            videoModal.addEventListener('click', function(e) {
                if (e.target === this) closeVideoModal();
            });
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                closeVideoModal();
            }
        });

        /* --------------------------------------------------------------------------
           HERO SLIDER CONTROLLER
           -------------------------------------------------------------------------- */
        const bgSlides = document.querySelectorAll('.hero-slide');
        const contentSlides = document.querySelectorAll('.hero-content-slide');
        const navItems = document.querySelectorAll('.hero-nav-item');
        let currentSlide = 0;
        let slideInterval;

        function updateSlide(index) {
            if (bgSlides.length <= 1 && contentSlides.length <= 1) return;

            // Pause previous video if present
            const prevVideo = bgSlides[currentSlide] ? bgSlides[currentSlide].querySelector('video') : null;
            if (prevVideo) prevVideo.pause();

            // Fade out current slide
            bgSlides.forEach((slide) => slide.classList.remove('active-slide'));
            contentSlides.forEach((slide) => slide.classList.remove('active-content'));
            navItems.forEach((item) => item.classList.remove('active'));

            // Fade in target slide
            currentSlide = index;
            if (bgSlides[currentSlide]) {
                bgSlides[currentSlide].classList.add('active-slide');
                const nextVideo = bgSlides[currentSlide].querySelector('video');
                if (nextVideo) {
                    nextVideo.currentTime = 0;
                    nextVideo.muted = isMuted;
                    if (!isMuted) nextVideo.volume = 1.0;
                    nextVideo.play().catch(() => {});
                }
            }
            if (contentSlides[currentSlide]) contentSlides[currentSlide].classList.add('active-content');
            if (navItems[currentSlide]) navItems[currentSlide].classList.add('active');

            // Trigger GSAP entrance on the newly revealed slide (if motion allowed)
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (typeof gsap !== 'undefined' && contentSlides[currentSlide] && !prefersReducedMotion) {
                const elements = contentSlides[currentSlide].querySelectorAll('.hero-kicker-badge, .hero-title-main, .hero-divider-bar, .hero-desc-text, .hero-collection-btn');
                gsap.killTweensOf(elements);
                gsap.fromTo(elements,
                    { opacity: 0, y: 25 },
                    { opacity: 1, y: 0, duration: 0.7, stagger: 0.08, ease: 'power3.out' }
                );
            }
        }

        window.goToSlide = function(index) {
            if (index === currentSlide) return;
            updateSlide(index);
            clearInterval(slideInterval);
            if (bgSlides.length > 1) {
                slideInterval = setInterval(nextSlide, 6500);
            }
        };

        function nextSlide() {
            const nextIdx = (currentSlide + 1) % Math.max(bgSlides.length, 1);
            updateSlide(nextIdx);
        }

        if (bgSlides.length > 1) {
            slideInterval = setInterval(nextSlide, 6500);
        }

        /* --------------------------------------------------------------------------
           AUDIO CONTROLLER
           -------------------------------------------------------------------------- */
        let isMuted = true;
        const muteBtn = document.getElementById('hero-mute-btn');
        const iconUnmuted = muteBtn ? muteBtn.querySelector('.icon-unmuted') : null;
        const iconMuted = muteBtn ? muteBtn.querySelector('.icon-muted') : null;
        const muteText = muteBtn ? muteBtn.querySelector('.hero-mute-text') : null;
        const heroVideos = document.querySelectorAll('.hero-slide video');

        if (heroVideos.length === 0 && muteBtn) {
            muteBtn.style.display = 'none';
        }

        window.toggleMuteUnmute = function() {
            isMuted = !isMuted;
            if (muteBtn) {
                muteBtn.setAttribute('aria-pressed', isMuted ? 'false' : 'true');
                muteBtn.setAttribute('title', isMuted ? 'Sound Muted - Click to play audio' : 'Sound Playing - Click to mute');
            }
            if (iconUnmuted && iconMuted) {
                if (isMuted) {
                    iconUnmuted.style.display = 'none';
                    iconMuted.style.display = 'block';
                    if (muteText) muteText.textContent = 'SOUND';
                } else {
                    iconUnmuted.style.display = 'block';
                    iconMuted.style.display = 'none';
                    if (muteText) muteText.textContent = 'MUTE';
                }
            }
            heroVideos.forEach(function(video) {
                video.muted = isMuted;
                if (!isMuted) {
                    video.volume = 1.0;
                    if (video.paused) {
                        video.play().catch(() => {});
                    }
                }
            });
        };

        if (muteBtn) {
            muteBtn.addEventListener('click', window.toggleMuteUnmute);
        }

        /* --------------------------------------------------------------------------
           COMBOS / SIGNATURE BOX CAROUSEL CONTROLLER
           -------------------------------------------------------------------------- */
        const comboBannerSlider = document.getElementById('combo-banner-slider');
        const comboBannerTrack = comboBannerSlider ? comboBannerSlider.querySelector('.combo-banner-track') : null;
        const comboBannerSlides = comboBannerTrack ? comboBannerTrack.querySelectorAll('.combo-banner-slide') : [];
        const comboBannerDots = document.querySelectorAll('.combo-dot');
        let comboBannerIndex = 0;
        let comboBannerInterval = null;

        function updateComboBannerUI() {
            if (!comboBannerTrack || comboBannerSlides.length === 0) return;
            comboBannerTrack.style.transform = 'translateX(-' + (comboBannerIndex * 100) + '%)';
            comboBannerDots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === comboBannerIndex);
            });
        }

        function showNextComboBanner() {
            if (comboBannerSlides.length < 2) return;
            comboBannerIndex = (comboBannerIndex + 1) % comboBannerSlides.length;
            updateComboBannerUI();
        }

        function showPrevComboBanner() {
            if (comboBannerSlides.length < 2) return;
            comboBannerIndex = (comboBannerIndex - 1 + comboBannerSlides.length) % comboBannerSlides.length;
            updateComboBannerUI();
        }

        function startComboBannerSlider() {
            if (comboBannerSlides.length < 2 || comboBannerInterval) return;
            comboBannerInterval = setInterval(showNextComboBanner, 4500);
        }

        function stopComboBannerSlider() {
            clearInterval(comboBannerInterval);
            comboBannerInterval = null;
        }

        window.comboSlideNext = function() {
            stopComboBannerSlider();
            showNextComboBanner();
            startComboBannerSlider();
        };

        window.comboSlidePrev = function() {
            stopComboBannerSlider();
            showPrevComboBanner();
            startComboBannerSlider();
        };

        window.goToComboSlide = function(index) {
            stopComboBannerSlider();
            comboBannerIndex = index;
            updateComboBannerUI();
            startComboBannerSlider();
        };

        if (comboBannerSlider) {
            comboBannerSlider.addEventListener('mouseenter', stopComboBannerSlider);
            comboBannerSlider.addEventListener('mouseleave', startComboBannerSlider);
            comboBannerSlider.addEventListener('touchstart', stopComboBannerSlider, { passive: true });
            comboBannerSlider.addEventListener('touchend', startComboBannerSlider, { passive: true });
            startComboBannerSlider();
        }

        /* --------------------------------------------------------------------------
           TESTIMONIALS SLIDER CONTROLLER
           -------------------------------------------------------------------------- */
        const testiSlider = document.getElementById('testi-slider');
        let testiAutoInterval = null;

        function getTestiSlideWidth() {
            const item = testiSlider ? testiSlider.querySelector('.testi-item-wrapper') : null;
            if (!item) return 0;
            return item.getBoundingClientRect().width + parseFloat(getComputedStyle(testiSlider).gap || 0);
        }

        function startTestiAutoSlide() {
            if (!testiSlider || testiSlider.children.length < 2 || testiAutoInterval) return;
            testiAutoInterval = setInterval(slideTestiNext, 3500);
        }

        function stopTestiAutoSlide() {
            clearInterval(testiAutoInterval);
            testiAutoInterval = null;
        }

        function slideTestiNext() {
            if (!testiSlider) return;
            const itemWidth = getTestiSlideWidth();
            if (testiSlider.scrollLeft >= testiSlider.scrollWidth - testiSlider.clientWidth - itemWidth / 2) {
                testiSlider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                testiSlider.scrollBy({ left: itemWidth, behavior: 'smooth' });
            }
        }

        function slideTestiPrev() {
            if (!testiSlider) return;
            const itemWidth = getTestiSlideWidth();
            if (testiSlider.scrollLeft <= 0) {
                testiSlider.scrollTo({ left: testiSlider.scrollWidth - testiSlider.clientWidth, behavior: 'smooth' });
            } else {
                testiSlider.scrollBy({ left: -itemWidth, behavior: 'smooth' });
            }
        }

        window.testiSlideNext = function() {
            stopTestiAutoSlide();
            slideTestiNext();
            startTestiAutoSlide();
        };

        window.testiSlidePrev = function() {
            stopTestiAutoSlide();
            slideTestiPrev();
            startTestiAutoSlide();
        };

        if (testiSlider) {
            testiSlider.addEventListener('mouseenter', stopTestiAutoSlide);
            testiSlider.addEventListener('mouseleave', startTestiAutoSlide);
            testiSlider.addEventListener('touchstart', stopTestiAutoSlide, { passive: true });
            testiSlider.addEventListener('touchend', startTestiAutoSlide, { passive: true });
            startTestiAutoSlide();
        }

        /* --------------------------------------------------------------------------
           INSTAGRAM SOCIAL SLIDER CONTROLLER (INFINITE LOOP & PREV/NEXT)
           -------------------------------------------------------------------------- */
        const socialSlider = document.getElementById('social-slider');
        let socialAutoInterval = null;

        function getSocialSlideWidth() {
            const item = socialSlider ? socialSlider.querySelector('.social-item-wrapper') : null;
            if (!item) return 0;
            return item.getBoundingClientRect().width + parseFloat(getComputedStyle(socialSlider).gap || 0);
        }

        function startSocialAutoSlide() {
            if (!socialSlider || socialSlider.children.length < 2 || socialAutoInterval) return;
            socialAutoInterval = setInterval(slideSocialNext, 3500);
        }

        function stopSocialAutoSlide() {
            clearInterval(socialAutoInterval);
            socialAutoInterval = null;
        }

        function slideSocialNext() {
            if (!socialSlider) return;
            const itemWidth = getSocialSlideWidth();
            if (socialSlider.scrollLeft >= socialSlider.scrollWidth - socialSlider.clientWidth - itemWidth / 2) {
                socialSlider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                socialSlider.scrollBy({ left: itemWidth, behavior: 'smooth' });
            }
        }

        function slideSocialPrev() {
            if (!socialSlider) return;
            const itemWidth = getSocialSlideWidth();
            if (socialSlider.scrollLeft <= 0) {
                socialSlider.scrollTo({ left: socialSlider.scrollWidth - socialSlider.clientWidth, behavior: 'smooth' });
            } else {
                socialSlider.scrollBy({ left: -itemWidth, behavior: 'smooth' });
            }
        }

        window.socialSlideNext = function() {
            stopSocialAutoSlide();
            slideSocialNext();
            startSocialAutoSlide();
        };

        window.socialSlidePrev = function() {
            stopSocialAutoSlide();
            slideSocialPrev();
            startSocialAutoSlide();
        };

        if (socialSlider) {
            socialSlider.addEventListener('mouseenter', stopSocialAutoSlide);
            socialSlider.addEventListener('mouseleave', startSocialAutoSlide);
            socialSlider.addEventListener('touchstart', stopSocialAutoSlide, { passive: true });
            socialSlider.addEventListener('touchend', startSocialAutoSlide, { passive: true });
            startSocialAutoSlide();
        }

        /* --------------------------------------------------------------------------
           GSAP LUXURY ANIMATION SUITE (R2 IMPLEMENTATION)
           -------------------------------------------------------------------------- */
        if (typeof gsap !== 'undefined') {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
            }

            if (!prefersReducedMotion) {
                // SETUP 1: Staggered Hero Load Reveal Timeline
                const heroTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } });
                heroTimeline
                    .from('.active-content .hero-kicker-badge', { opacity: 0, y: -25, duration: 0.9, delay: 0.2 })
                    .from('.active-content .hero-title-main', { opacity: 0, y: 45, duration: 1.1, skewY: 1.5 }, '-=0.6')
                    .from('.active-content .hero-divider-bar', { scaleX: 0, transformOrigin: 'left center', duration: 0.8 }, '-=0.7')
                    .from('.active-content .hero-desc-text', { opacity: 0, y: 25, duration: 0.8 }, '-=0.6')
                    .from('.active-content .hero-collection-btn', { opacity: 0, y: 25, scale: 0.92, duration: 0.7 }, '-=0.5')
                    .from('.hero-nav-container', { opacity: 0, x: 30, duration: 0.8 }, '-=0.5');

                // SETUP 2: ScrollTrigger Parallax & Scroll-Triggered Stagger Reveals
                if (typeof ScrollTrigger !== 'undefined') {
                    // Parallax on hero background (contained within 120% height buffer)
                    gsap.to('.hero-slide video, .hero-slide .hero-static-bg', {
                        yPercent: 10,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: '.hero-section',
                            start: 'top top',
                            end: 'bottom top',
                            scrub: 0.5
                        }
                    });

                                        // Parallax depth on philosophy portrait image
                    gsap.to('.phil-image-holder img, .phil-video-box img', {
                        yPercent: -8,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: '.philosophy-section',
                            start: 'top bottom',
                            end: 'bottom top',
                            scrub: 0.5
                        }
                    });

                    // Trigger reveal class for philosophy staggered text
                    ScrollTrigger.create({
                        trigger: '.philosophy-section',
                        start: 'top 75%',
                        onEnter: () => {
                            document.querySelectorAll('.philosophy-section .reveal').forEach(el => el.classList.add('active'));
                        }
                    });


                    // Category Cards Reveal removed to fix CSS hover conflict

                    // Best Sellers Product Cards Reveal removed to fix CSS hover conflict

                    // Testimonials Video Cards Reveal
                    gsap.from('.testi-item-wrapper', {
                        scrollTrigger: {
                            trigger: '.testi-grid',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        },
                        opacity: 0,
                        y: 35,
                        stagger: 0.1,
                        duration: 0.8,
                        ease: 'power2.out'
                    });

                    // Social Atelier Cards Reveal removed to fix CSS hover conflict

                    // Journal Editorial Cards Reveal removed to fix staggered layout

                    window.addEventListener('load', () => ScrollTrigger.refresh());
                    if (document.fonts && document.fonts.ready) {
                        document.fonts.ready.then(() => ScrollTrigger.refresh());
                    }
                }

                // SETUP 3: Interactive Micro-Interactions (Magnetic CTA & Card Zoom)
                if (window.matchMedia('(pointer: fine)').matches) {
                    const magneticElements = document.querySelectorAll('.magnetic-target');
                    magneticElements.forEach((el) => {
                        const xTo = gsap.quickTo(el, 'x', { duration: 0.35, ease: 'power2.out' });
                        const yTo = gsap.quickTo(el, 'y', { duration: 0.35, ease: 'power2.out' });

                        el.addEventListener('mousemove', (e) => {
                            const rect = el.getBoundingClientRect();
                            const x = (e.clientX - rect.left - rect.width / 2) * 0.25;
                            const y = (e.clientY - rect.top - rect.height / 2) * 0.25;
                            xTo(x);
                            yTo(y);
                        });

                        el.addEventListener('mouseleave', () => {
                            xTo(0);
                            yTo(0);
                        });
                    });
                }

                // Smooth scale interactions on product and interactive cards
                const interactiveCards = document.querySelectorAll('.product-card, .discover-card, .journal-card, .social-card');
                interactiveCards.forEach((card) => {
                    const img = card.querySelector('img');
                    if (img) {
                        card.addEventListener('mouseenter', () => {
                            gsap.to(img, { scale: 1.06, duration: 0.5, ease: 'power2.out' });
                        });
                        card.addEventListener('mouseleave', () => {
                            gsap.to(img, { scale: 1.0, duration: 0.5, ease: 'power2.out' });
                        });
                    }
                });
            }
        }
    });
</script>

@endsection
