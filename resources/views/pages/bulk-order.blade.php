@extends('layouts.app')

@section('meta_title', 'Corporate Gifting & Bulk Orders | House of KNP')
@section('meta_description', 'Bespoke corporate gifting, bulk orders, and custom merchandise from House of KNP. Luxury watches, premium cotton shirts, French perfumes, and curated gift boxes with volume pricing and custom engraving.')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap');

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

    /* Accessibility */
    .bulk-page-wrapper a:focus-visible,
    .bulk-page-wrapper button:focus-visible,
    .bulk-page-wrapper input:focus-visible,
    .bulk-page-wrapper select:focus-visible,
    .bulk-page-wrapper textarea:focus-visible {
        outline: 2px solid var(--knp-red) !important;
        outline-offset: 3px !important;
    }
    
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* ========================================================================
       BULK ORDER PAGE — UNIFIED LUXURY MONTSERRAT TYPOGRAPHY
       ======================================================================== */
    .bulk-page-wrapper,
    .bulk-page-wrapper *:not(.fa):not([class*="fa-"]):not(.zmdi):not([class*="zmdi-"]):not([class*="icon-"]) {
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    }

    .bulk-page-wrapper {
        color: var(--knp-black);
        background-color: var(--knp-off-white);
        overflow-x: hidden;
        width: 100%;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }

    /* Headings & Serif title classes in Montserrat */
    .bulk-page-wrapper h1,
    .bulk-page-wrapper h2,
    .bulk-page-wrapper h3,
    .bulk-page-wrapper h4,
    .bulk-page-wrapper h5,
    .bulk-page-wrapper h6,
    .bulk-page-wrapper .knp-serif,
    .bulk-page-wrapper .knp-serif-title,
    .bulk-hero-title,
    .section-head h2,
    .bulk-value-title,
    .bulk-cat-title,
    .process-step-title,
    .vip-concierge-title {
        font-family: 'Montserrat', -apple-system, sans-serif !important;
        letter-spacing: -0.01em;
    }

    .bulk-hero-title {
        font-weight: 800 !important;
        letter-spacing: -0.02em !important;
    }

    .section-head h2 {
        font-weight: 700 !important;
    }

    .bulk-value-title,
    .bulk-cat-title,
    .process-step-title,
    .vip-concierge-title {
        font-weight: 700 !important;
    }

    .bulk-hero-stat-num,
    .process-step-num {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 800 !important;
    }

    .bulk-page-wrapper :is(h1, h2, h3, h4, h5, h6, .knp-serif, .knp-serif-title) :is(span, em, strong) {
        font-family: inherit !important;
    }

    /* Buttons, Badges, Labels & Controls */
    .bulk-page-wrapper :is(button, input, select, textarea, .btn, .btn-gold-luxury, .btn-primary-glass, .btn-outline-luxury, .btn-bulk-submit, .btn-select-cat, .bulk-hero-badge, .section-kicker-tag, .bulk-cat-tag, .vip-badge-pill, .bulk-hero-stat-label, .form-label-luxury) {
        font-family: 'Montserrat', sans-serif !important;
    }

    /* Scroll Reveal Animations */
    .bulk-reveal {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 0.5s var(--knp-ease), transform 0.5s var(--knp-ease);
    }

    /* 1. HERO SECTION */
    .bulk-hero {
        position: relative;
        background: var(--knp-black);
        color: var(--knp-white);
        padding: clamp(80px, 10vw, 120px) 0 clamp(70px, 9vw, 110px);
        overflow: hidden;
    }

    .bulk-hero::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -20%;
        width: 80vw;
        height: 80vw;
        background: radial-gradient(circle, var(--knp-red-glow) 0%, transparent 60%);
        pointer-events: none;
        z-index: 1;
        opacity: 0.7;
    }

    .bulk-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 60vw;
        height: 60vw;
        background: radial-gradient(circle, var(--knp-gold-glow) 0%, transparent 60%);
        pointer-events: none;
        z-index: 1;
        opacity: 0.5;
    }

    .bulk-hero-content {
        position: relative;
        z-index: 2;
    }

    .bulk-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--glass-dark-border);
        padding: 8px 18px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--knp-white);
        margin-bottom: 24px;
        backdrop-filter: blur(var(--glass-blur-md));
        box-shadow: var(--glass-shadow-dark);
    }

    .pulsing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--knp-red-vibrant);
        box-shadow: 0 0 10px var(--knp-red-vibrant);
        animation: pulseDot 2s infinite var(--knp-ease);
    }

    @keyframes pulseDot {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 4, 41, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(217, 4, 41, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 4, 41, 0); }
    }

    .bulk-hero-title {
        font-family: 'Montserrat', sans-serif !important;
        font-size: clamp(34px, 4.5vw, 56px);
        font-weight: 800 !important;
        letter-spacing: -0.02em;
        line-height: 1.15;
        margin-bottom: 24px;
        color: var(--knp-white);
    }

    .bulk-hero-title span {
        background: linear-gradient(105deg, #C9A227 0%, #E8D5A3 25%, #FFFFFF 50%, #E8D5A3 75%, #C9A227 100%);
        background-size: 200% 100%;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: heroSheen 4s linear infinite;
        font-style: italic;
    }

    @keyframes heroSheen {
        to { background-position: 200% center; }
    }

    .bulk-hero-desc {
        font-size: clamp(16px, 1.5vw, 18px);
        line-height: 1.75;
        color: rgba(255, 255, 255, 0.85);
        max-width: 640px;
        margin-bottom: 38px;
        font-weight: 400;
    }

    /* Buttons */
    .bulk-hero-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 48px;
    }

    .btn-gold-luxury, .btn-primary-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: var(--knp-white);
        color: var(--knp-black) !important;
        font-family: 'Montserrat', sans-serif !important;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        padding: 18px 40px;
        border-radius: 100px;
        border: none;
        text-decoration: none;
        transition: all 0.4s var(--knp-ease);
        box-shadow: 0 8px 30px rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .btn-gold-luxury::before, .btn-primary-glass::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--knp-red);
        z-index: -1;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.5s var(--knp-ease);
    }

    .btn-gold-luxury:hover, .btn-primary-glass:hover {
        color: var(--knp-white) !important;
        box-shadow: 0 15px 40px var(--knp-red-glow);
        transform: translateY(-2px);
    }
    
    .btn-gold-luxury:hover::before, .btn-primary-glass:hover::before {
        transform: scaleX(1);
        transform-origin: left;
    }

    .btn-gold-luxury i {
        transition: transform 0.4s var(--knp-ease);
    }
    
    .btn-gold-luxury:hover i {
        transform: translateX(4px);
    }

    .btn-outline-luxury {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.03);
        color: var(--knp-white);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 16px 32px;
        border-radius: 100px;
        border: 1px solid var(--glass-dark-border);
        text-decoration: none;
        transition: all 0.4s var(--knp-ease);
        backdrop-filter: blur(var(--glass-blur-sm));
    }

    .btn-outline-luxury:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.3);
        color: var(--knp-white);
        transform: translateY(-2px);
    }

    .bulk-hero-badges-row {
        display: flex;
        align-items: center;
        gap: clamp(16px, 3vw, 36px);
        flex-wrap: wrap;
        padding-top: 30px;
        border-top: 1px solid var(--glass-dark-border);
    }

    .bulk-hero-mini-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
    }

    .bulk-hero-mini-badge i {
        color: var(--knp-gold);
        font-size: 15px;
    }

    .bulk-hero-visual-card {
        background: var(--glass-dark-bg);
        border: 1px solid var(--glass-dark-border);
        border-radius: 16px;
        padding: 32px;
        backdrop-filter: blur(var(--glass-blur-lg));
        box-shadow: var(--glass-shadow-dark);
        position: relative;
        overflow: hidden;
        z-index: 2;
    }

    .bulk-hero-visual-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--knp-red), var(--knp-gold), var(--knp-red));
        background-size: 200% 100%;
        animation: heroSheen 4s linear infinite;
    }

    .bulk-hero-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .bulk-hero-stat-item {
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 24px 16px;
        border-radius: 12px;
        text-align: center;
        transition: transform 0.4s var(--knp-ease), border-color 0.4s var(--knp-ease);
    }

    .bulk-hero-stat-item:hover {
        transform: translateY(-4px);
        border-color: rgba(201, 162, 39, 0.3);
    }

    .bulk-hero-stat-num {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 38px;
        font-weight: 800;
        color: var(--knp-gold-pale);
        line-height: 1;
        margin-bottom: 8px;
    }

    .bulk-hero-stat-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
    }

    /* 2. SECTION HEADERS */
    .section-spacing {
        padding: clamp(80px, 8vw, 120px) 0;
    }

    .section-head {
        text-align: center;
        max-width: 720px;
        margin: 0 auto clamp(40px, 6vw, 64px);
    }

    .section-kicker-tag {
        display: inline-block;
        color: var(--knp-red);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .section-head h2 {
        font-family: 'Montserrat', sans-serif !important;
        font-size: clamp(28px, 3.5vw, 42px);
        font-weight: 700;
        letter-spacing: -0.01em;
        line-height: 1.25;
        color: var(--knp-black);
        margin-bottom: 16px;
    }

    .section-head-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .divider-line {
        width: 45px;
        height: 1.5px;
        background: rgba(180, 0, 22, 0.4);
    }

    .divider-gem {
        width: 6px;
        height: 6px;
        background: var(--knp-red);
        transform: rotate(45deg);
    }

    .section-head p {
        font-size: 16px;
        color: var(--knp-muted);
        line-height: 1.8;
        margin: 0;
    }

    /* 3. VALUE PROPOSITIONS (CARDS) */
    .bulk-value-card {
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        padding: 36px 28px;
        height: 100%;
        transition: all 0.4s var(--knp-ease);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
    }

    .bulk-value-card:hover {
        transform: translateY(-6px);
        border-color: rgba(180, 0, 22, 0.3);
        box-shadow: 0 16px 36px -8px rgba(180, 0, 22, 0.12);
    }
    
    .bulk-value-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(180, 0, 22, 0.08);
        color: var(--knp-red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 20px;
        transition: all 0.35s var(--knp-ease);
    }

    .bulk-value-card:hover .bulk-value-icon {
        background: linear-gradient(135deg, var(--knp-red), var(--knp-red-vibrant));
        color: #FFFFFF;
        box-shadow: 0 8px 20px var(--knp-red-glow);
        transform: scale(1.08);
    }

    .bulk-value-title {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 19px;
        font-weight: 700 !important;
        color: #111827;
        margin-bottom: 10px;
        line-height: 1.35;
        letter-spacing: -0.01em;
    }

    .bulk-value-text {
        font-size: 13.5px;
        color: #4B5563;
        line-height: 1.65;
        margin: 0;
    }

    /* 5. CATEGORIES SHOWCASE */
    .bulk-cat-card {
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.06);
        transition: all 0.4s var(--knp-ease);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .bulk-cat-card:hover {
        transform: translateY(-8px);
        border-color: rgba(180, 0, 22, 0.3);
        box-shadow: 0 20px 40px -10px rgba(180, 0, 22, 0.12), 0 0 0 1px rgba(180, 0, 22, 0.15);
    }

    .bulk-cat-img-wrapper {
        position: relative;
        height: 230px;
        background: #0E0E11;
        overflow: hidden;
    }

    .bulk-cat-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s var(--knp-ease);
        opacity: 0.95;
    }

    .bulk-cat-card:hover .bulk-cat-img-wrapper img {
        transform: scale(1.06);
        opacity: 1;
    }

    .bulk-cat-tag {
        position: absolute;
        top: 14px;
        left: 14px;
        background: rgba(14, 14, 17, 0.75);
        color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 100px;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .bulk-cat-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        background: #FFFFFF;
    }

    .bulk-cat-title {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 20px;
        font-weight: 700 !important;
        color: #111827;
        margin-bottom: 10px;
        line-height: 1.35;
        letter-spacing: -0.01em;
    }

    .bulk-cat-desc {
        font-size: 13.5px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .bulk-cat-features-list {
        list-style: none;
        padding: 0;
        margin: 0 0 20px;
    }

    .bulk-cat-features-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: #4B5563;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .bulk-cat-features-list li i {
        color: var(--knp-red);
        font-size: 12px;
    }

    .btn-select-cat {
        width: 100%;
        margin-top: auto;
        background: #FFFFFF;
        border: 1.5px solid var(--knp-red);
        color: var(--knp-red);
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 12px 18px;
        border-radius: 100px;
        transition: all 0.35s var(--knp-ease);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(180, 0, 22, 0.06);
    }

    .btn-select-cat:hover {
        background: var(--knp-red);
        color: #FFFFFF;
        box-shadow: 0 8px 24px var(--knp-red-glow);
        transform: translateY(-2px);
    }

    /* 6. 4-STEP ORDERING PROCESS */
    .process-section {
        background: #0E0E11;
        color: #FFFFFF;
        position: relative;
    }
    
    .process-section .section-head h2 {
        color: #FFFFFF;
    }
    .process-section .section-head p {
        color: rgba(255, 255, 255, 0.7);
    }

    .process-step-item {
        position: relative;
        padding: 36px 28px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        height: 100%;
        transition: all 0.4s var(--knp-ease);
        z-index: 1;
        overflow: hidden;
    }

    .process-step-item:hover {
        border-color: rgba(201, 162, 39, 0.4);
        background: rgba(255, 255, 255, 0.06);
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
    }

    .process-step-num {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 38px;
        font-weight: 800;
        color: #C9A227;
        line-height: 1;
        margin-bottom: 16px;
    }

    .process-step-title {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 19px;
        font-weight: 700 !important;
        color: #FFFFFF;
        margin-bottom: 10px;
        line-height: 1.35;
        letter-spacing: -0.01em;
    }

    .process-step-text {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.65;
        margin: 0;
    }

    /* 7. INQUIRY FORM & VIP CONCIERGE */
    .inquiry-section {
        background: #F5F4F0;
    }

    .inquiry-form-card {
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        padding: clamp(32px, 4.5vw, 48px);
        box-shadow: 0 12px 35px -5px rgba(0, 0, 0, 0.06);
    }

    .form-group-luxury {
        margin-bottom: 20px;
    }

    .form-label-luxury {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-label-luxury .required {
        color: var(--knp-red);
    }

    .form-control-luxury {
        width: 100%;
        background: #FAFAFA;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        padding: 13px 16px;
        font-size: 14.5px;
        color: #111827;
        transition: all 0.25s ease;
        outline: none;
    }

    .form-control-luxury:focus {
        background: #FFFFFF;
        border-color: var(--knp-red);
        box-shadow: 0 0 0 3.5px rgba(180, 0, 22, 0.1);
    }

    .form-select-luxury {
        width: 100%;
        background: #FAFAFA url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%234b5563' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") no-repeat right 16px center/12px;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        padding: 13px 16px;
        font-size: 14.5px;
        color: #111827;
        outline: none;
        cursor: pointer;
        -webkit-appearance: none;
        appearance: none;
        transition: all 0.25s ease;
    }

    .form-select-luxury:focus {
        background-color: #FFFFFF;
        border-color: var(--knp-red);
        box-shadow: 0 0 0 3.5px rgba(180, 0, 22, 0.1);
    }

    .custom-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    @media (max-width: 767px) {
        .custom-checkbox-grid {
            grid-template-columns: 1fr;
        }
    }

    .custom-check-item {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #FAFAFA;
        border: 1.5px solid #E5E7EB;
        padding: 12px 16px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .custom-check-item:hover {
        border-color: rgba(180, 0, 22, 0.4);
        background: #FFF8F8;
    }

    .custom-check-item input[type="checkbox"] {
        accent-color: var(--knp-red);
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .custom-check-item span {
        font-size: 13px;
        color: #374151;
        margin: 0;
        cursor: pointer;
        user-select: none;
        font-weight: 600;
    }

    .btn-bulk-submit {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: linear-gradient(135deg, #B40016 0%, #8B0011 100%);
        color: #FFFFFF !important;
        font-family: 'Montserrat', sans-serif !important;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        padding: 18px 36px;
        border-radius: 100px;
        border: none;
        cursor: pointer;
        transition: all 0.4s var(--knp-ease);
        box-shadow: 0 10px 25px rgba(180, 0, 22, 0.35);
    }

    .btn-bulk-submit:hover {
        background: linear-gradient(135deg, #D90429 0%, #B40016 100%);
        box-shadow: 0 14px 35px rgba(180, 0, 22, 0.5);
        transform: translateY(-2px);
    }

    .vip-concierge-sidebar {
        position: sticky;
        top: 100px;
    }

    .vip-concierge-card {
        background: #0E0E11;
        color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 36px 28px;
        box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.5);
        position: relative;
        overflow: hidden;
    }

    .vip-concierge-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--knp-red), var(--knp-gold), var(--knp-red));
    }

    .vip-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(180, 0, 22, 0.2);
        color: #FFFFFF;
        border: 1px solid rgba(180, 0, 22, 0.5);
        padding: 5px 14px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    .vip-concierge-title {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 24px;
        font-weight: 700 !important;
        color: #FFFFFF;
        margin-bottom: 10px;
        letter-spacing: -0.01em;
    }

    .vip-concierge-desc {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .vip-contact-channel {
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 12px;
        text-decoration: none;
        color: #FFFFFF;
        transition: all 0.3s var(--knp-ease);
    }

    .vip-contact-channel:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.25);
        transform: translateX(4px);
    }

    .vip-channel-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.06);
    }

    .vip-channel-icon.wa { color: #25D366; }
    .vip-channel-icon.phone { color: #C9A227; }
    .vip-channel-icon.mail { color: #FF4D4D; }

    .vip-channel-info-label {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.6);
        margin: 0 0 2px;
    }

    .vip-channel-info-value {
        font-size: 14.5px;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0;
    }

    .vip-perks-list {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        list-style: none;
        padding-left: 0;
    }

    .vip-perks-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12.5px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 10px;
    }

    .vip-perks-list li i {
        color: #C9A227;
        font-size: 13px;
    }

    .gst-assurance-banner {
        background: rgba(180, 0, 22, 0.12);
        border: 1px dashed rgba(180, 0, 22, 0.4);
        padding: 14px;
        border-radius: 8px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        line-height: 1.5;
    }

    /* 8. FAQ ACCORDION */
    .faq-section {
        background: var(--knp-off-white);
    }

    .faq-accordion-item {
        background: var(--glass-light-bg);
        border: 1px solid var(--glass-light-border);
        border-radius: 10px;
        margin-bottom: 16px;
        overflow: hidden;
        transition: all 0.4s var(--knp-ease);
        box-shadow: var(--glass-shadow-soft);
    }

    .faq-accordion-item.active {
        border-color: var(--knp-red);
        box-shadow: var(--glass-shadow-elevated);
    }

    .faq-accordion-trigger {
        width: 100%;
        background: transparent;
        border: none;
        padding: 24px 32px;
        font-size: 16px;
        font-weight: 700;
        color: var(--knp-black);
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background 0.3s var(--knp-ease);
    }

    .faq-accordion-trigger:hover {
        background: rgba(0,0,0,0.02);
    }

    .faq-accordion-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: var(--knp-black);
        transition: all 0.4s var(--knp-ease);
        flex-shrink: 0;
    }

    .faq-accordion-item.active .faq-accordion-icon {
        transform: rotate(180deg);
        background: var(--knp-red);
        color: var(--knp-white);
    }

    .faq-accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s var(--knp-ease), padding 0.5s var(--knp-ease);
        background: transparent;
        padding: 0 32px;
        font-size: 15px;
        line-height: 1.7;
        color: var(--knp-gray-dark);
    }

    .faq-accordion-item.active .faq-accordion-content {
        padding: 0 32px 28px;
        max-height: 400px;
    }

    /* 9. RESPONSIVE BREAKPOINTS */
    @media (max-width: 991px) {
        .tier-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .vip-concierge-sidebar {
            position: static;
            margin-top: 36px;
        }
        .custom-checkbox-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        /* Layout & Gutter Optimization */
        .bulk-page-wrapper .container-fluid {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }
        .bulk-page-wrapper .row.g-4 {
            --bs-gutter-x: 10px !important;
            --bs-gutter-y: 10px !important;
        }
        .bulk-page-wrapper .row.g-5 {
            --bs-gutter-x: 14px !important;
            --bs-gutter-y: 20px !important;
        }

        /* Section Spacing & Headings */
        .section-spacing {
            padding: 36px 0 !important;
        }
        .section-head {
            margin-bottom: 22px !important;
        }
        .section-kicker-tag {
            font-size: 10px !important;
            letter-spacing: 2px !important;
            margin-bottom: 8px !important;
        }
        .section-head h2 {
            font-size: clamp(21px, 5.8vw, 26px) !important;
            line-height: 1.25 !important;
            margin-bottom: 10px !important;
        }
        .section-head-divider {
            margin-bottom: 12px !important;
        }
        .divider-line {
            width: 28px !important;
        }
        .section-head p {
            font-size: 14px !important;
            line-height: 1.65 !important;
        }

        /* 1. Hero Mobile Optimization */
        .bulk-hero {
            padding: 28px 0 32px !important;
        }
        .bulk-hero-badge {
            padding: 5px 12px !important;
            font-size: 9px !important;
            letter-spacing: 1.2px !important;
            margin-bottom: 14px !important;
        }
        .bulk-hero-title {
            font-size: clamp(25px, 6.8vw, 32px) !important;
            line-height: 1.18 !important;
            margin-bottom: 14px !important;
        }
        .bulk-hero-desc {
            font-size: 14px !important;
            line-height: 1.65 !important;
            margin-bottom: 20px !important;
        }
        .bulk-hero-actions {
            flex-direction: column !important;
            width: 100% !important;
            gap: 10px !important;
            margin-bottom: 22px !important;
        }
        .bulk-hero-actions a,
        .bulk-hero-actions button {
            width: 100% !important;
        }
        .btn-gold-luxury, .btn-primary-glass {
            padding: 13px 20px !important;
            font-size: 10.5px !important;
            letter-spacing: 1.5px !important;
        }
        .btn-outline-luxury {
            padding: 12px 18px !important;
            font-size: 10.5px !important;
            letter-spacing: 1px !important;
        }
        .bulk-hero-badges-row {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px 10px !important;
            padding-top: 16px !important;
        }
        .bulk-hero-mini-badge {
            font-size: 11px !important;
            gap: 6px !important;
        }
        .bulk-hero-mini-badge i {
            font-size: 13px !important;
        }

        /* Hero Visual Card & 2-Column Stats Grid */
        .bulk-hero-visual-card {
            padding: 18px 14px !important;
            border-radius: 14px !important;
            margin-top: 20px !important;
        }
        .bulk-hero-visual-card img {
            height: 44px !important;
        }
        .bulk-hero-visual-card p {
            font-size: 9.5px !important;
            letter-spacing: 1.5px !important;
            margin-top: 6px !important;
            margin-bottom: 14px !important;
        }
        .bulk-hero-stats-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
        }
        .bulk-hero-stat-item {
            padding: 12px 8px !important;
            border-radius: 10px !important;
        }
        .bulk-hero-stat-num {
            font-size: 24px !important;
            margin-bottom: 3px !important;
        }
        .bulk-hero-stat-label {
            font-size: 9px !important;
            letter-spacing: 0.6px !important;
            line-height: 1.25 !important;
        }

        /* 2. Value Propositions 2-Column Grid */
        .bulk-value-card {
            padding: 16px 12px !important;
            border-radius: 12px !important;
            text-align: center !important;
            align-items: center !important;
        }
        .bulk-value-icon {
            width: 38px !important;
            height: 38px !important;
            font-size: 16px !important;
            border-radius: 10px !important;
            margin-bottom: 10px !important;
        }
        .bulk-value-title {
            font-size: 15px !important;
            font-weight: 700 !important;
            margin-bottom: 6px !important;
            line-height: 1.25 !important;
        }
        .bulk-value-text {
            font-size: 13px !important;
            line-height: 1.55 !important;
        }

        /* 3. 4-Step Gifting Workflow 2-Column Grid */
        .process-step-item {
            padding: 16px 12px !important;
            border-radius: 12px !important;
        }
        .process-step-num {
            font-size: 26px !important;
            margin-bottom: 6px !important;
        }
        .process-step-title {
            font-size: 15px !important;
            font-weight: 700 !important;
            margin-bottom: 6px !important;
            line-height: 1.25 !important;
        }
        .process-step-text {
            font-size: 14px !important;
            line-height: 1.65 !important;
        }

        /* 4. Inquiry Form & VIP Concierge */
        .inquiry-form-card {
            padding: 20px 14px !important;
            border-radius: 14px !important;
        }
        .inquiry-form-card .section-head {
            margin-bottom: 18px !important;
        }
        .form-group-luxury {
            margin-bottom: 12px !important;
        }
        .form-label-luxury {
            font-size: 10px !important;
            margin-bottom: 5px !important;
        }
        .form-control-luxury, .form-select-luxury {
            padding: 10px 12px !important;
            font-size: 16px !important;
            border-radius: 8px !important;
        }
        .btn-bulk-submit {
            padding: 13px 20px !important;
            font-size: 11px !important;
            letter-spacing: 1.5px !important;
            width: 100% !important;
        }
        .vip-concierge-sidebar {
            margin-top: 20px !important;
        }
        .vip-concierge-card {
            padding: 20px 14px !important;
            border-radius: 14px !important;
        }
        .vip-badge-pill {
            font-size: 9px !important;
            padding: 4px 10px !important;
            margin-bottom: 12px !important;
        }
        .vip-concierge-title {
            font-size: 18px !important;
            font-weight: 700 !important;
            margin-bottom: 6px !important;
        }
        .vip-concierge-desc {
            font-size: 14px !important;
            line-height: 1.65 !important;
            margin-bottom: 16px !important;
        }
        .vip-contact-channel {
            padding: 10px 12px !important;
            margin-bottom: 8px !important;
            gap: 10px !important;
        }
        .vip-channel-icon {
            width: 34px !important;
            height: 34px !important;
            font-size: 15px !important;
        }
        .vip-channel-info-label {
            font-size: 9px !important;
        }
        .vip-channel-info-value {
            font-size: 13px !important;
        }
        .vip-perks-list {
            margin-top: 14px !important;
            padding-top: 12px !important;
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 6px !important;
        }
        .vip-perks-list li {
            font-size: 11px !important;
            gap: 6px !important;
            margin-bottom: 0 !important;
        }
        .gst-assurance-banner {
            margin-top: 12px !important;
            padding: 10px 12px !important;
            font-size: 10.5px !important;
        }

        /* 5. FAQ Accordion Mobile Refinement */
        .faq-accordion-item {
            margin-bottom: 10px !important;
            border-radius: 8px !important;
        }
        .faq-accordion-trigger {
            padding: 14px 14px !important;
            font-size: 13px !important;
            gap: 10px !important;
            line-height: 1.35 !important;
        }
        .faq-accordion-icon {
            width: 26px !important;
            height: 26px !important;
            font-size: 10px !important;
        }
        .faq-accordion-content {
            font-size: 12px !important;
            line-height: 1.55 !important;
            padding: 0 14px !important;
        }
        .faq-accordion-item.active .faq-accordion-content {
            padding: 0 14px 14px !important;
        }
    }

    /* Alert / Flash feedback */
    .alert-bulk-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #065F46;
        padding: 20px 24px;
        border-radius: 12px;
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 15px;
        font-weight: 500;
    }
</style>

<div class="bulk-page-wrapper">

    <!-- ========================================================================
         1. HERO SECTION
         ======================================================================== -->
    <section class="bulk-hero">
        <div class="container-fluid" style="max-width: 1320px; padding: 0 clamp(20px, 4vw, 40px);">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="bulk-hero-content bulk-reveal">
                        <div class="bulk-hero-badge">
                            <div class="pulsing-dot"></div>
                            <span>Bespoke Corporate Gifting &amp; Bulk Concierge</span>
                        </div>
                        <h1 class="bulk-hero-title knp-serif-title">
                            Elevate Your Brand With<br><span>Timeless Distinction.</span>
                        </h1>
                        <p class="bulk-hero-desc">
                            Tailored bulk procurement solutions for corporate milestones, executive recognition, festive gifting, and wedding honors. Enjoy direct volume privileges, custom laser engraving, and white-glove doorstep delivery.
                        </p>
                        <div class="bulk-hero-actions">
                            <a href="#bulk-inquiry-form" class="btn-primary-glass">
                                <i class="fa fa-envelope-open-o"></i>
                                <span>Request Bulk Quotation</span>
                            </a>
                            <a href="https://wa.me/916374390907?text=Hello%20House%20of%20KNP%2C%20I%20am%20interested%20in%20corporate%20gifting%20and%20bulk%20orders." target="_blank" rel="noopener" class="btn-outline-luxury">
                                <i class="fa fa-whatsapp text-success" style="font-size: 16px;"></i>
                                <span>Chat With VIP Concierge</span>
                            </a>
                        </div>
                        <div class="bulk-hero-badges-row">
                            <div class="bulk-hero-mini-badge">
                                <i class="fa fa-check-circle"></i>
                                <span>Volume Tier Privileges</span>
                            </div>
                            <div class="bulk-hero-mini-badge">
                                <i class="fa fa-check-circle"></i>
                                <span>Custom Logo Engraving</span>
                            </div>
                            <div class="bulk-hero-mini-badge">
                                <i class="fa fa-check-circle"></i>
                                <span>Pan-India Multi-Drop Delivery</span>
                            </div>
                            <div class="bulk-hero-mini-badge">
                                <i class="fa fa-check-circle"></i>
                                <span>100% GST Tax Invoicing</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bulk-hero-visual-card bulk-reveal">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo/logoo.png') }}" alt="House of KNP" style="height: 68px; object-fit: contain; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.5));">
                            <p style="color: rgba(255,255,255,0.7); font-size: 11.5px; letter-spacing: 2.5px; text-transform: uppercase; margin-top: 12px; font-weight: 600;">
                                The Gold Standard in Corporate Gifting
                            </p>
                        </div>
                        <div class="bulk-hero-stats-grid">
                            <div class="bulk-hero-stat-item">
                                <div class="bulk-hero-stat-num">100%</div>
                                <p class="bulk-hero-stat-label">Handcrafted Quality</p>
                            </div>
                            <div class="bulk-hero-stat-item">
                                <div class="bulk-hero-stat-num">25+</div>
                                <p class="bulk-hero-stat-label">Flexible MOQ</p>
                            </div>
                            <div class="bulk-hero-stat-item">
                                <div class="bulk-hero-stat-num">2 hrs</div>
                                <p class="bulk-hero-stat-label">Fast Quote Response</p>
                            </div>
                            <div class="bulk-hero-stat-item">
                                <div class="bulk-hero-stat-num">All-India</div>
                                <p class="bulk-hero-stat-label">Insured Express Shipping</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================
         2. VALUE PROPOSITIONS (4 CARDS)
         ======================================================================== -->
    <section class="section-spacing">
        <div class="container-fluid" style="max-width: 1320px; padding: 0 clamp(20px, 4vw, 40px);">
            <div class="section-head bulk-reveal">
                <span class="section-kicker-tag">The House of KNP Advantage</span>
                <h2>Why Leading Brands Choose Us</h2>
                <div class="section-head-divider">
                    <span class="divider-line"></span>
                    <span class="divider-gem"></span>
                    <span class="divider-line"></span>
                </div>
                <p>We combine bespoke Indian craftsmanship with dependable institutional fulfillment.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6 col-6 bulk-reveal">
                    <div class="bulk-value-card">
                        <div class="bulk-value-icon">
                            <i class="fa fa-paint-brush"></i>
                        </div>
                        <h3 class="bulk-value-title">Bespoke Personalization</h3>
                        <p class="bulk-value-text">
                            Custom company logo engraving on watch casebacks, monogrammed shirt cuffs, bespoke packaging sleeves, and custom wax-sealed greeting cards.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 bulk-reveal" style="transition-delay: 100ms;">
                    <div class="bulk-value-card">
                        <div class="bulk-value-icon">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <h3 class="bulk-value-title">Volume Tier Privileges</h3>
                        <p class="bulk-value-text">
                            Transparent tiered wholesale pricing structures directly from the atelier, ensuring maximum value and premium ROI for institutional budgets.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 bulk-reveal" style="transition-delay: 200ms;">
                    <div class="bulk-value-card">
                        <div class="bulk-value-icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <h3 class="bulk-value-title">Dedicated Concierge</h3>
                        <p class="bulk-value-text">
                            Your personal corporate account manager coordinates sample prototyping, custom colorways, packaging proofs, and synchronized dispatches.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 bulk-reveal" style="transition-delay: 300ms;">
                    <div class="bulk-value-card">
                        <div class="bulk-value-icon">
                            <i class="fa fa-truck"></i>
                        </div>
                        <h3 class="bulk-value-title">Pan-India Multi-Drop</h3>
                        <p class="bulk-value-text">
                            Seamless white-glove fulfillment. We deliver single bulk pallets to headquarters or ship individual gift parcels directly to 500+ remote employee doorsteps.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================
         4. GIFTING COLLECTIONS SHOWCASE
         ======================================================================== -->
    {{--<section class="section-spacing">
        <div class="container-fluid" style="max-width: 1320px; padding: 0 clamp(20px, 4vw, 40px);">
            <div class="section-head bulk-reveal">
                <span class="section-kicker-tag">Curated Luxury Offerings</span>
                <h2>Corporate Gifting Collections</h2>
                <div class="section-head-divider">
                    <span class="divider-line"></span>
                    <span class="divider-gem"></span>
                    <span class="divider-line"></span>
                </div>
                <p>Select an exclusive category to auto-fill your customized quotation request below.</p>
            </div>

            <div class="row g-4">
                <!-- 1. Watches -->
                <div class="col-lg-3 col-md-6 bulk-reveal">
                    <div class="bulk-cat-card">
                        <div class="bulk-cat-img-wrapper">
                            <img src="{{ asset('images/knp/knp_watch_category_1773738803722.png') }}" alt="Haute Horlogerie Timepieces" onerror="this.src='{{ asset('images/knp/product_watch.png') }}'">
                            <span class="bulk-cat-tag">Timepieces</span>
                        </div>
                        <div class="bulk-cat-body">
                            <h3 class="bulk-cat-title">Haute Horlogerie</h3>
                            <p class="bulk-cat-desc">Automatic, Chronograph, and Dress Watches with precision Japanese movements and sapphire glass.</p>
                            <ul class="bulk-cat-features-list">
                                <li><i class="fa fa-check"></i> Laser Caseback Engraving</li>
                                <li><i class="fa fa-check"></i> Genuine Leather / Steel Straps</li>
                                <li><i class="fa fa-check"></i> 1-Year Atelier Warranty</li>
                            </ul>
                            <button type="button" class="btn-select-cat" onclick="prefillGifting('Watches', '')">
                                Select For Quotation
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Shirts -->
                <div class="col-lg-3 col-md-6 bulk-reveal" style="transition-delay: 100ms;">
                    <div class="bulk-cat-card">
                        <div class="bulk-cat-img-wrapper">
                            <img src="{{ asset('images/knp/knp_shirts_category_1773738786041.png') }}" alt="Bespoke Cotton & Linen Shirts" onerror="this.src='{{ asset('images/knp/product_shirt.png') }}'">
                            <span class="bulk-cat-tag">Apparel</span>
                        </div>
                        <div class="bulk-cat-body">
                            <h3 class="bulk-cat-title">Bespoke Shirts</h3>
                            <p class="bulk-cat-desc">100% Egyptian Giza Cotton &amp; Pure European Linen tailored shirts designed for executive elegance.</p>
                            <ul class="bulk-cat-features-list">
                                <li><i class="fa fa-check"></i> Monogrammed Cuff / Pocket</li>
                                <li><i class="fa fa-check"></i> Slim &amp; Classic Tailored Fits</li>
                                <li><i class="fa fa-check"></i> Mother of Pearl Buttons</li>
                            </ul>
                            <button type="button" class="btn-select-cat" onclick="prefillGifting('Shirts', '')">
                                Select For Quotation
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 3. Fragrance -->
                <div class="col-lg-3 col-md-6 bulk-reveal" style="transition-delay: 200ms;">
                    <div class="bulk-cat-card">
                        <div class="bulk-cat-img-wrapper">
                            <img src="{{ asset('images/knp/knp_perfume_category_1773738820081.png') }}" alt="Artisan French Fragrance" onerror="this.src='{{ asset('images/knp/product_perfume.png') }}'">
                            <span class="bulk-cat-tag">Fragrances</span>
                        </div>
                        <div class="bulk-cat-body">
                            <h3 class="bulk-cat-title">Artisan Fragrance</h3>
                            <p class="bulk-cat-desc">Long-lasting Eau de Parfum and Extrait de Parfum hand-crafted in Grasse, France with notes of Oud &amp; Amber.</p>
                            <ul class="bulk-cat-features-list">
                                <li><i class="fa fa-check"></i> Custom Metallic Logo Printing</li>
                                <li><i class="fa fa-check"></i> High-Concentration Oils (12h+)</li>
                                <li><i class="fa fa-check"></i> Premium Gift Sleeve</li>
                            </ul>
                            <button type="button" class="btn-select-cat" onclick="prefillGifting('Perfume', '')">
                                Select For Quotation
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 4. Signature Combo Box -->
                <div class="col-lg-3 col-md-6 bulk-reveal" style="transition-delay: 300ms;">
                    <div class="bulk-cat-card">
                        <div class="bulk-cat-img-wrapper">
                            <img src="{{ asset('images/knp/combo_box.png') }}" alt="The Executive Suite Combo Box" onerror="this.src='{{ asset('images/knp/knp_gift_box_1773738766506.png') }}'">
                            <span class="bulk-cat-tag">Signature Suite</span>
                        </div>
                        <div class="bulk-cat-body">
                            <h3 class="bulk-cat-title">The Executive Suite</h3>
                            <p class="bulk-cat-desc">Curated 3-in-1 combo sets featuring a premium timepiece, bespoke shirt, and signature perfume in a wooden presentation chest.</p>
                            <ul class="bulk-cat-features-list">
                                <li><i class="fa fa-check"></i> Rigid Velvet Presentation Chest</li>
                                <li><i class="fa fa-check"></i> Full Corporate Customization</li>
                                <li><i class="fa fa-check"></i> Highest Perceived Value</li>
                            </ul>
                            <button type="button" class="btn-select-cat" onclick="prefillGifting('SIGNATURE BOX', '')">
                                Select For Quotation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- ========================================================================
         5. 4-STEP ORDERING PROCESS
         ======================================================================== -->
    <section class="section-spacing process-section">
        <div class="container-fluid" style="max-width: 1320px; padding: 0 clamp(20px, 4vw, 40px);">
            <div class="section-head bulk-reveal">
                <span class="section-kicker-tag" style="color: var(--knp-gold);">Effortless Fulfillment</span>
                <h2>The 4-Step Gifting Workflow</h2>
                <div class="section-head-divider">
                    <span class="divider-line" style="background: rgba(201, 162, 39, 0.4);"></span>
                    <span class="divider-gem" style="background: var(--knp-gold);"></span>
                    <span class="divider-line" style="background: rgba(201, 162, 39, 0.4);"></span>
                </div>
                <p>From initial brief to synchronized multi-destination delivery.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6 col-6 bulk-reveal">
                    <div class="process-step-item">
                        <div class="process-step-num">01</div>
                        <h3 class="process-step-title">Inquiry &amp; Consultation</h3>
                        <p class="process-step-text">Share your expected quantity, budget parameters, and timeline through our form or direct VIP concierge.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 bulk-reveal" style="transition-delay: 100ms;">
                    <div class="process-step-item">
                        <div class="process-step-num">02</div>
                        <h3 class="process-step-title">3D Mockups &amp; Sample</h3>
                        <p class="process-step-text">We generate digital renders with your company logo and dispatch physical evaluation prototypes within 48 hours.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 bulk-reveal" style="transition-delay: 200ms;">
                    <div class="process-step-item">
                        <div class="process-step-num">03</div>
                        <h3 class="process-step-title">Precision Crafting</h3>
                        <p class="process-step-text">Our artisans manufacture your order, perform multi-point quality control, and package each unit in bespoke gift presentation.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 bulk-reveal" style="transition-delay: 300ms;">
                    <div class="process-step-item">
                        <div class="process-step-num">04</div>
                        <h3 class="process-step-title">Insured Doorstep Dispatch</h3>
                        <p class="process-step-text">Consolidated shipment to your headquarters or individual white-glove dispatches with real-time SMS/Email tracking.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================
         6. INQUIRY FORM & VIP CONCIERGE SIDEBAR
         ======================================================================== -->
    <section class="section-spacing inquiry-section" id="bulk-inquiry-form">
        <div class="container-fluid" style="max-width: 1320px; padding: 0 clamp(20px, 4vw, 40px);">
            <div class="row g-5">
                <!-- Left: Form -->
                <div class="col-lg-8 bulk-reveal">
                    <div class="inquiry-form-card">
                        <div class="section-head" style="text-align: left; margin-left: 0; margin-bottom: 40px;">
                            <span class="section-kicker-tag">Request Quotation</span>
                            <h2>Corporate Gifting Inquiry</h2>
                            <p>Fill out your requirements below and our corporate concierge will respond with a tailored proposal within 2 hours.</p>
                        </div>

                        @if(session('success'))
                            <div class="alert-bulk-success">
                                <i class="fa fa-check-circle" style="font-size: 24px;"></i>
                                <div>
                                    <strong>Inquiry Received Successfully!</strong>
                                    <div>{{ session('success') }}</div>
                                </div>
                            </div>
                        @endif

                        <form id="bulkOrderForm" method="POST" action="{{ url('bulk-order') }}">
                            @csrf

                            <!-- Honeypot anti-spam -->
                            <div style="display: none;">
                                <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group-luxury">
                                        <label for="name" class="form-label-luxury">Full Name <span class="required">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control-luxury" placeholder="e.g. Rajesh Sharma" required value="{{ old('name') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-luxury">
                                        <label for="company_name" class="form-label-luxury">Company / Organization <span class="required">*</span></label>
                                        <input type="text" id="company_name" name="company_name" class="form-control-luxury" placeholder="e.g. Apex Global Technologies" required value="{{ old('company_name') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-luxury">
                                        <label for="email" class="form-label-luxury">Corporate Email <span class="required">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control-luxury" placeholder="e.g. procurement@company.com" required value="{{ old('email') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-luxury">
                                        <label for="phone" class="form-label-luxury">Phone / WhatsApp <span class="required">*</span></label>
                                        <input type="tel" id="phone" name="phone" class="form-control-luxury" placeholder="e.g. +91 98400 12345" required value="{{ old('phone') }}">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group-luxury">
                                        <label for="product_interest" class="form-label-luxury">Select Product or Combo Box <span class="required">*</span></label>
                                        <select id="product_interest" name="product_interest" class="form-select-luxury" required>
                                            <option value="">-- Select Product / Combo Gift Box --</option>
                                            <option value="Curated Combo Gift Boxes (Custom Curation)" {{ old('product_interest') == 'Curated Combo Gift Boxes (Custom Curation)' ? 'selected' : '' }}>🎁 Curated Combo Gift Boxes (Executive Suite Curation)</option>
                                            @if(isset($comboProducts) && count($comboProducts) > 0)
                                                <optgroup label="✨ Curated Combo Gift Boxes">
                                                    @foreach($comboProducts as $cp)
                                                        <option value="{{ $cp->product_name }} (Combo Box #{{ $cp->id }})" {{ old('product_interest') == $cp->product_name . ' (Combo Box #' . $cp->id . ')' ? 'selected' : '' }}>{{ $cp->product_name }} (Combo Box)</option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                            @if(isset($products) && count($products) > 0)
                                                @if(isset($categories) && count($categories) > 0)
                                                    @foreach($categories as $cat)
                                                        @php
                                                            $catProds = $products->where('category_id', $cat->id);
                                                        @endphp
                                                        @if(count($catProds) > 0)
                                                            <optgroup label="🛍️ {{ $cat->category_name }}">
                                                                @foreach($catProds as $prod)
                                                                    <option value="{{ $prod->product_name }} ({{ $cat->category_name }} #{{ $prod->id }})" {{ old('product_interest') == $prod->product_name . ' (' . $cat->category_name . ' #' . $prod->id . ')' ? 'selected' : '' }}>{{ $prod->product_name }} - {{ $cat->category_name }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <optgroup label="Shop Products">
                                                        @foreach($products as $prod)
                                                            <option value="{{ $prod->product_name }} (#{{ $prod->id }})" {{ old('product_interest') == $prod->product_name . ' (#' . $prod->id . ')' ? 'selected' : '' }}>{{ $prod->product_name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endif
                                            <option value="Mixed Assortment / Custom Curation" {{ old('product_interest') == 'Mixed Assortment / Custom Curation' ? 'selected' : '' }}>Mixed Assortment / Custom Gifting Curation</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group-luxury">
                                        <label for="message" class="form-label-luxury">Project Scope, Quantity &amp; Custom Requirements</label>
                                        <textarea id="message" name="message" class="form-control-luxury" rows="4" placeholder="Tell us about your estimated quantity (e.g. 50 units), preferred delivery timeline, branding/customization requests, or any specific notes...">{{ old('message') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" id="submitBulkBtn" class="btn-bulk-submit">
                                        <i class="fa fa-paper-plane"></i>
                                        <span>Submit Bulk Quotation Request</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right: VIP Concierge Desk -->
                <div class="col-lg-4 bulk-reveal" style="transition-delay: 200ms;">
                    <div class="vip-concierge-sidebar">
                        <div class="vip-concierge-card">
                            <div class="vip-badge-pill">
                                <i class="fa fa-star"></i>
                                <span>Priority Concierge</span>
                            </div>
                            <h3 class="vip-concierge-title knp-serif-title">Instant VIP Assistance</h3>
                            <p class="vip-concierge-desc">
                                Prefer an immediate conversation? Our institutional gifting leads are available for instant consultation.
                            </p>

                            <!-- WhatsApp -->
                            <a href="https://wa.me/916374390907?text=Hello%20House%20of%20KNP%2C%20I%20am%20interested%20in%20corporate%20bulk%20orders." target="_blank" rel="noopener" class="vip-contact-channel">
                                <div class="vip-channel-icon wa">
                                    <i class="fa fa-whatsapp"></i>
                                </div>
                                <div>
                                    <p class="vip-channel-info-label">WhatsApp VIP Desk</p>
                                    <p class="vip-channel-info-value">+91 6374390907</p>
                                </div>
                            </a>

                            <!-- Phone -->
                            <a href="tel:+916374390907" class="vip-contact-channel">
                                <div class="vip-channel-icon phone">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div>
                                    <p class="vip-channel-info-label">Corporate Hotline</p>
                                    <p class="vip-channel-info-value">+91 6374390907</p>
                                </div>
                            </a>

                            <!-- Email -->
                            <a href="mailto:houseofknp@gmail.com?subject=Corporate%20Bulk%20Order%20Inquiry" class="vip-contact-channel">
                                <div class="vip-channel-icon mail">
                                    <i class="fa fa-envelope-o"></i>
                                </div>
                                <div>
                                    <p class="vip-channel-info-label">Email Concierge</p>
                                    <p class="vip-channel-info-value">houseofknp@gmail.com</p>
                                </div>
                            </a>

                            <!-- VIP Perks List -->
                            <ul class="vip-perks-list">
                                <li><i class="fa fa-check-circle"></i> Direct Dedicated Account Manager</li>
                                <li><i class="fa fa-check-circle"></i> Free 3D Digital Prototyping</li>
                                <li><i class="fa fa-check-circle"></i> Custom Packaging Proofs in 48h</li>
                                <li><i class="fa fa-check-circle"></i> Pan-India Insured Delivery</li>
                            </ul>

                            <div class="gst-assurance-banner">
                                <i class="fa fa-file-text-o" style="font-size: 18px; color: var(--knp-gold);"></i>
                                <span>Official GST Tax Invoice issued with input tax credit eligibility.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================
         7. FREQUENTLY ASKED QUESTIONS (ACCORDION)
         ======================================================================== -->
    <section class="section-spacing faq-section">
        <div class="container-fluid" style="max-width: 960px; padding: 0 clamp(20px, 4vw, 40px);">
            <div class="section-head bulk-reveal">
                <span class="section-kicker-tag">Got Questions?</span>
                <h2>Frequently Asked Questions</h2>
                <div class="section-head-divider">
                    <span class="divider-line"></span>
                    <span class="divider-gem"></span>
                    <span class="divider-line"></span>
                </div>
                <p>Clear answers to institutional procurement and gifting queries.</p>
            </div>

            <div class="faq-accordion-wrapper bulk-reveal">
                <!-- FAQ 1 -->
                <div class="faq-accordion-item active">
                    <button class="faq-accordion-trigger" onclick="toggleFaq(this)" type="button">
                        <span>What is the Minimum Order Quantity (MOQ) for corporate gifting?</span>
                        <div class="faq-accordion-icon"><i class="fa fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-accordion-content">
                        Our standard MOQ for corporate volume pricing starts at 25 units per category. For bespoke custom colorways or custom fragrance formulations, our MOQ is typically 50 units.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-accordion-item">
                    <button class="faq-accordion-trigger" onclick="toggleFaq(this)" type="button">
                        <span>Can we request physical samples before confirming our final order?</span>
                        <div class="faq-accordion-icon"><i class="fa fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-accordion-content">
                        Yes. After your initial consultation, we can dispatch physical product and packaging evaluation samples directly to your office. Sample costs are fully credited toward your confirmed bulk purchase.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="faq-accordion-item">
                    <button class="faq-accordion-trigger" onclick="toggleFaq(this)" type="button">
                        <span>What customization options are available for company branding?</span>
                        <div class="faq-accordion-icon"><i class="fa fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-accordion-content">
                        We offer high-precision laser engraving on watch casebacks and clasps, custom embroidered monograms on shirt cuffs, custom screen-printed rigid gift chests, branded satin ribbons, and bespoke message cards.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-accordion-item">
                    <button class="faq-accordion-trigger" onclick="toggleFaq(this)" type="button">
                        <span>Can you handle direct deliveries to remote employee homes across India?</span>
                        <div class="faq-accordion-icon"><i class="fa fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-accordion-content">
                        Absolutely. Simply provide us with a secure recipient dispatch list (names, addresses, phone numbers) and our logistics team will manage insured doorstep delivery across 27,000+ pincodes in India with automated live tracking.
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="faq-accordion-item">
                    <button class="faq-accordion-trigger" onclick="toggleFaq(this)" type="button">
                        <span>Do you provide GST compliant business invoices?</span>
                        <div class="faq-accordion-icon"><i class="fa fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-accordion-content">
                        Yes, 100% of our corporate orders are accompanied by official GST invoices featuring your company's registered GSTIN, enabling full input tax credit (ITC) claims.
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="faq-accordion-item">
                    <button class="faq-accordion-trigger" onclick="toggleFaq(this)" type="button">
                        <span>What are your production and dispatch lead times?</span>
                        <div class="faq-accordion-icon"><i class="fa fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-accordion-content">
                        Ready-to-ship catalog items with basic packaging dispatch within 3-5 business days. Customized orders with laser engraving or custom boxes typically require 7-12 business days depending on volume.
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('scripts')
<script>
    // 1. Pre-fill Product or Category in Dropdown and Scroll to Form
    window.prefillGifting = function(categoryName, productName) {
        const prodSelect = document.getElementById('product_interest');
        
        if (prodSelect) {
            let matched = false;
            if (productName) {
                const pLower = productName.toLowerCase();
                for (let j = 0; j < prodSelect.options.length; j++) {
                    if (prodSelect.options[j].value.toLowerCase().includes(pLower) || prodSelect.options[j].text.toLowerCase().includes(pLower)) {
                        prodSelect.selectedIndex = j;
                        matched = true;
                        break;
                    }
                }
            }
            if (!matched && categoryName) {
                const cLower = categoryName.toLowerCase();
                for (let j = 0; j < prodSelect.options.length; j++) {
                    const optVal = prodSelect.options[j].value.toLowerCase();
                    const optText = prodSelect.options[j].text.toLowerCase();
                    if (optVal.includes(cLower) || optText.includes(cLower) || (cLower.includes('box') && (optVal.includes('box') || optVal.includes('combo')))) {
                        prodSelect.selectedIndex = j;
                        matched = true;
                        break;
                    }
                }
            }
        }

        const formSec = document.getElementById('bulk-inquiry-form');
        if (formSec) {
            formSec.scrollIntoView({ behavior: 'smooth' });
            if (prodSelect) {
                setTimeout(() => prodSelect.focus(), 300);
            }
        }
    };
    window.prefillCategory = window.prefillGifting;

    // 3. FAQ Accordion Toggle
    window.toggleFaq = function(button) {
        const item = button.closest('.faq-accordion-item');
        const isActive = item.classList.contains('active');
        document.querySelectorAll('.faq-accordion-item').forEach(el => el.classList.remove('active'));
        if (!isActive) {
            item.classList.add('active');
        }
    };

    // 4. Form Submission with AJAX & Feedback
    const bulkForm = document.getElementById('bulkOrderForm');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBulkBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Submitting Request...';
            }
        });
    }

    // 5. Scroll Reveal Intersection Observer
    document.addEventListener("DOMContentLoaded", function() {
        const reveals = document.querySelectorAll(".bulk-reveal");
        const revealOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };
        
        const revealOnScroll = new IntersectionObserver(function(entries, observer) {
            entries.forEach(entry => {
                if (!entry.isIntersecting) {
                    return;
                } else {
                    entry.target.classList.add("revealed");
                    observer.unobserve(entry.target);
                }
            });
        }, revealOptions);
        
        reveals.forEach(reveal => {
            revealOnScroll.observe(reveal);
        });
    });
</script>
@endsection
