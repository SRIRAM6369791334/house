@extends('layouts.app')

@section('content')
    <style>
        .contact-banner {
            background-image: url("{{ asset('images/banner/contact.png') }}") !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            min-height: 500px;
        }

        .contact-banner.overlay::before {
            background: rgba(0, 0, 0, .38);
        }

        .contact-banner .bread-main {
            background: transparent;
        }

        .contact-page-new {
            background: #fff;
            color: #111827;
            padding: 88px 0 72px;
        }

        .contact-shell {
            display: grid;
            gap: 38px;
            grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.8fr);
            margin: 0 auto;
            max-width: 1180px;
            padding: 0 22px;
        }

        .contact-eyebrow {
            color: #cc0000;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 3px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .contact-title {
            font-family: 'Open Sans', sans-serif;
            font-size: 30px;
            font-weight: 900;
            line-height: 1.15;
            margin: 0 0 12px;
        }

        .contact-copy {
            color: #667085;
            font-size: 16px;
            line-height: 1.7;
            margin: 0 0 28px;
            max-width: 680px;
        }

        .contact-form-panel,
        .contact-info-panel {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 34px;
        }

        .contact-form-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .contact-field.wide {
            grid-column: 1 / -1;
        }

        .contact-field input,
        .contact-field textarea {
            background: #fff;
            border: 1px solid #d7dce5;
            border-radius: 4px;
            color: #111827;
            font-size: 16px;
            min-height: 58px;
            padding: 14px 16px;
            transition: border-color .2s ease, box-shadow .2s ease;
            width: 100%;
        }

        .contact-field textarea {
            min-height: 142px;
            resize: vertical;
        }

        .contact-field input:focus,
        .contact-field textarea:focus {
            border-color: #cc0000;
            box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
            outline: none;
        }

        .contact-field input.is-invalid,
        .contact-field textarea.is-invalid {
            border-color: #ff1f1f;
            box-shadow: 0 0 0 3px rgba(255, 31, 31, 0.12);
        }

        .contact-error {
            color: #dc2626;
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-top: 7px;
        }

        .contact-form-message {
            color: #e00000;
            display: none;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: .2px;
            margin: 12px 0 0;
            white-space: pre-line;
        }

        .contact-form-message.active {
            display: block;
        }

        .contact-submit {
            background: #111;
            border: 0;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 2px;
            min-width: 190px;
            padding: 18px 26px;
            text-transform: uppercase;
            transition: background .2s ease, transform .2s ease;
        }

        .contact-submit:hover {
            background: #cc0000;
            transform: translateY(-1px);
        }

        .contact-info-panel {
            background: #181818;
            border-color: #181818;
            color: #fff;
        }

        .contact-info-title {
            color: #fff;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 1px;
            margin: 0 0 26px;
            text-transform: uppercase;
        }

        .contact-info-item {
            align-items: flex-start;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            gap: 16px;
            padding: 18px 0;
        }

        .contact-info-icon {
            align-items: center;
            background: #cc0000;
            border-radius: 50%;
            color: #fff;
            display: inline-flex;
            flex: 0 0 auto;
            height: 42px;
            justify-content: center;
            width: 42px;
        }

        .contact-info-label {
            color: #000;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .contact-info-text,
        .contact-info-text a {
            color: #000;
            font-size: 15px;
            line-height: 1.6;
            text-decoration: none;
        }

        .contact-map {
            border-top: 3px solid #cc0000;
        }

        .contact-popup {
            align-items: center;
            background: rgba(17, 24, 39, 0.62);
            display: none;
            inset: 0;
            justify-content: center;
            padding: 24px;
            position: fixed;
            z-index: 99999;
        }

        .contact-popup.active {
            display: flex;
        }

        .contact-popup-box {
            background: #fff;
            border-radius: 8px;
            max-width: 430px;
            padding: 30px;
            text-align: center;
            width: 100%;
        }

        .contact-popup-icon {
            align-items: center;
            border-radius: 50%;
            color: #fff;
            display: inline-flex;
            font-size: 28px;
            font-weight: 900;
            height: 58px;
            justify-content: center;
            margin-bottom: 18px;
            width: 58px;
        }

        .contact-popup.success .contact-popup-icon {
            background: #16a34a;
        }

        .contact-popup.error .contact-popup-icon {
            background: #dc2626;
        }

        .contact-popup-title {
            font-size: 24px;
            font-weight: 900;
            margin: 0 0 10px;
        }

        .contact-popup-message {
            color: #667085;
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 22px;
            white-space: pre-line;
        }

        .contact-popup-close {
            background: #111;
            border: 0;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            font-weight: 900;
            min-width: 120px;
            padding: 12px 18px;
        }

        @media (max-width: 991px) {
            .contact-shell {
                grid-template-columns: 1fr;
            }

            .contact-title {
                font-size: 34px;
            }
        }

        @media (max-width: 575px) {
            .contact-banner {
                min-height: 150px;
            }

            .contact-page-new {
                padding: 52px 0;
            }

            .contact-form-grid {
                grid-template-columns: 1fr;
            }

            .contact-form-panel,
            .contact-info-panel {
                padding: 24px;
            }
        }

        /* Contact page — luxury editorial redesign */
        .contact-hero {
            align-items: center;
            background: #0b0b0b;
            display: flex;
            min-height: 460px;
            overflow: hidden;
            padding: 105px 0;
            position: relative
        }

        .contact-hero:before {
            background: linear-gradient(90deg, rgba(4, 4, 4, .95), rgba(4, 4, 4, .74), rgba(4, 4, 4, .35)), url("{{ asset('images/knp/lifestyle_banner.png') }}") center/cover no-repeat;
            content: "";
            inset: 0;
            position: absolute
        }

        .contact-hero:after {
            background: #ce0000;
            bottom: 0;
            content: "";
            height: 4px;
            left: 0;
            position: absolute;
            width: 30%
        }

        .contact-hero-inner {
            margin: 0 auto;
            max-width: 1180px;
            padding: 0 22px;
            position: relative;
            width: 100%;
            z-index: 1
        }

        .contact-hero .contact-eyebrow {
            align-items: center;
            color: #e00000;
            display: flex;
            gap: 12px;
            letter-spacing: 3.5px
        }

        .contact-hero .contact-eyebrow:before {
            background: #e00000;
            /* content: ""; */
            height: 1px;
            width: 38px
        }

        .contact-hero h1 {
            color: #fff;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(50px, 6vw, 78px);
            font-weight: 700;
            letter-spacing: -3px;
            line-height: 1.03;
            margin: 20px 0 22px;
            max-width: 720px
        }

        .contact-hero p {
            border-left: 1px solid rgba(255, 255, 255, .3);
            color: rgba(255, 255, 255, .7);
            font-size: 17px;
            line-height: 1.8;
            margin: 0;
            max-width: 620px;
            padding-left: 22px
        }

        .contact-page-new {
            background: linear-gradient(180deg, #f8f4ef 0%, #fff 100%);
            padding: 110px 0
        }

        .contact-shell {
            align-items: stretch;
            gap: 28px;
            grid-template-columns: minmax(0, 1.28fr) minmax(330px, .72fr);
            max-width: 1240px
        }

        .contact-form-panel {
            background: #fff;
            border: 1px solid #ebe7e2;
            border-radius: 24px;
            box-shadow: 0 28px 75px rgba(27, 20, 15, .11);
            padding: 52px
        }

        .contact-eyebrow {
            align-items: center;
            display: flex;
            gap: 10px
        }

        .contact-eyebrow:before {
            background: #cc0000;
            content: "";
            height: 1px;
            width: 30px
        }

        .contact-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(28px, 3.5vw, 36px);
            font-weight: 700;
            letter-spacing: 0.01em;
            line-height: 1.15;
            margin: 14px 0 12px;
            color: #111827;
        }

        .contact-copy {
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 32px;
            color: #667085;
        }

        .contact-form-grid {
            gap: 18px;
        }

        .contact-field input,
        .contact-field textarea {
            background: #faf9f7;
            border: 1px solid #e6e1db;
            border-radius: 10px;
            font-size: 14px;
            min-height: 54px;
            padding: 14px 18px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
            width: 100%;
        }

        .contact-field textarea {
            min-height: 140px;
            resize: vertical;
        }

        .contact-field input::placeholder,
        .contact-field textarea::placeholder {
            color: #8c8883;
        }

        .contact-field input:hover,
        .contact-field textarea:hover {
            border-color: #c9c1b8;
            background: #fff;
        }

        .contact-field input:focus,
        .contact-field textarea:focus {
            background: #fff;
            border-color: #cc0000;
            box-shadow: 0 0 0 4px rgba(204, 0, 0, .08);
            outline: none;
        }

        .contact-submit {
            align-items: center;
            background: #cf0000;
            border: none;
            border-radius: 100px;
            box-shadow: 0 10px 25px rgba(207, 0, 0, .25);
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            justify-content: center;
            min-width: 210px;
            padding: 16px 32px;
            text-transform: uppercase;
            transition: all .3s ease;
        }

        .contact-submit:hover {
            background: #111;
            box-shadow: 0 14px 30px rgba(0, 0, 0, .2);
            transform: translateY(-2px);
        }

        .contact-info-panel {
            background: linear-gradient(160deg, rgba(15, 15, 15, .97), rgba(25, 25, 25, .94)), url("{{ asset('images/knp/brand_story_new.png') }}") center/cover;
            border: 0;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
            overflow: hidden;
            padding: 44px 34px;
            position: relative;
        }

        .contact-info-panel:before {
            background: radial-gradient(circle, rgba(204, 0, 0, .24), transparent 70%);
            content: "";
            height: 320px;
            position: absolute;
            right: -180px;
            top: -150px;
            width: 320px;
            pointer-events: none;
        }

        .contact-info-panel>* {
            position: relative;
            z-index: 1;
        }

        .contact-info-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 26px;
            font-weight: 600;
            letter-spacing: 0.02em;
            margin-bottom: 24px;
            text-transform: none;
            color: #FFFFFF;
        }

        .contact-info-title:after {
            background: #cc0000;
            content: "";
            display: block;
            height: 3px;
            margin-top: 12px;
            width: 40px;
        }

        .contact-info-grid {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .contact-info-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            gap: 16px;
            margin-bottom: 0;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            transition: all .25s ease;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .contact-info-item:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(204, 0, 0, .4);
            transform: translateY(-2px);
        }

        .contact-info-icon {
            background: rgba(204, 0, 0, .18);
            border: 1px solid rgba(255, 50, 50, .35);
            border-radius: 12px;
            color: #ff4d4d;
            height: 44px;
            width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .contact-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .contact-info-text,
        .contact-info-text a {
            color: #FFFFFF !important;
            font-size: 14px;
            line-height: 1.5;
            text-decoration: none;
            transition: color .2s ease;
        }

        .contact-info-text a:hover {
            color: #ff4d4d !important;
        }

        .contact-map {
            background: #111;
            border: 0;
            padding: 0;
            position: relative;
            border-top: 3px solid #cc0000;
        }

        .contact-map iframe {
            display: block;
            filter: grayscale(1) contrast(1.06);
            height: 450px;
            transition: filter .35s;
        }

        .contact-map:hover iframe {
            filter: grayscale(.2);
        }

        .contact-popup {
            backdrop-filter: blur(7px);
            background: rgba(10, 10, 10, .68);
        }

        .contact-popup-box {
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .25);
            padding: 38px;
        }

        .contact-popup-close {
            border-radius: 9px;
        }

        @media(max-width:991px) {
            .contact-hero {
                min-height: 410px;
                padding: 88px 0;
            }

            .contact-page-new {
                padding: 60px 0;
            }

            .contact-shell {
                grid-template-columns: 1fr;
            }

            .contact-info-panel {
                padding: 36px;
            }

            .contact-map iframe {
                height: 380px;
            }
        }

        @media(max-width:767px) {
            .contact-banner {
                min-height: 130px !important;
            }
            .contact-banner .bred-hading h5 {
                font-size: 20px !important;
                letter-spacing: 2px !important;
            }

            .contact-page-new {
                padding: 28px 0 24px !important;
            }

            .contact-shell {
                grid-template-columns: 1fr !important;
                gap: 18px !important;
                padding: 0 14px !important;
            }

            .contact-eyebrow {
                font-size: 10px !important;
                letter-spacing: 2px !important;
                margin-bottom: 6px !important;
            }

            .contact-title {
                font-size: clamp(22px, 6.2vw, 27px) !important;
                letter-spacing: 0.01em !important;
                line-height: 1.2 !important;
                margin: 8px 0 6px !important;
            }

            .contact-copy {
                font-size: 12.5px !important;
                line-height: 1.55 !important;
                margin-bottom: 18px !important;
            }

            .contact-form-panel {
                border-radius: 14px !important;
                padding: 18px 14px !important;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
            }

            .contact-form-grid {
                grid-template-columns: 1fr !important;
                gap: 10px !important;
            }

            .contact-field input {
                min-height: 46px !important;
                padding: 10px 14px !important;
                font-size: 13.5px !important;
                border-radius: 8px !important;
            }

            .contact-field textarea {
                min-height: 95px !important;
                padding: 10px 14px !important;
                font-size: 13.5px !important;
                border-radius: 8px !important;
            }

            .contact-submit {
                width: 100% !important;
                min-width: auto !important;
                padding: 13px 20px !important;
                font-size: 11px !important;
                letter-spacing: 2px !important;
                border-radius: 100px !important;
            }

            /* Info Panel Mobile 2-Column Grid */
            .contact-info-panel {
                border-radius: 14px !important;
                padding: 18px 14px !important;
            }

            .contact-info-title {
                font-size: 19px !important;
                letter-spacing: 0.01em !important;
                margin-bottom: 14px !important;
            }

            .contact-info-title:after {
                margin-top: 8px !important;
                width: 32px !important;
            }

            .contact-info-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
            }

            .contact-info-item {
                padding: 12px 9px !important;
                gap: 8px !important;
                border-radius: 10px !important;
                flex-direction: column !important;
                align-items: flex-start !important;
            }

            .contact-info-item.contact-info-address {
                grid-column: span 2 !important;
                flex-direction: row !important;
                align-items: flex-start !important;
            }

            .contact-info-icon {
                width: 32px !important;
                height: 32px !important;
                font-size: 14px !important;
                border-radius: 8px !important;
            }

            .contact-info-label {
                font-size: 9px !important;
                letter-spacing: 1px !important;
                margin-bottom: 2px !important;
            }

            .contact-info-text,
            .contact-info-text a {
                font-size: clamp(10px, 2.8vw, 12px) !important;
                line-height: 1.35 !important;
                word-break: break-word !important;
            }

            .contact-map iframe {
                height: 260px !important;
            }

            #scrollUp {
                bottom: 20px !important;
                right: 14px !important;
                width: 38px !important;
                height: 38px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-radius: 50% !important;
                z-index: 999 !important;
            }
        }
    </style>

    <div class="contact-banner breadcumb-area breadcumb-3 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center">
                <h5>Contact</h5>
            </div>
            <ol class="breadcrumb">
                <li class="home"><a title="Go to Home Page" href="/"style="color: #fff;">Home</a></li>
                <li class="active">Contact</li>
            </ol>
        </div>
    </div>
    {{-- <section class="contact-hero">
    <div class="contact-hero-inner">
        <div class="contact-eyebrow">House of KNP</div>
        <h1>Let’s start a conversation.</h1>
        <p>Questions, feedback, or something special in mind? Our team is here to help you find the right experience.</p>
    </div>
</section> --}}

    <section class="contact-page-new">
        <div class="contact-shell">
            <div class="contact-form-panel">
                <div class="contact-eyebrow">Contact Us</div>
                <h1 class="contact-title">Send Us A Message</h1>
                <p class="contact-copy">Share your details and our team will get back to you shortly.</p>

                <form id="contact-form" class="contact-form-grid" action="{{ route('contact.store') }}" method="POST"
                    novalidate>
                    @csrf
                    <div class="contact-field">
                        <input name="name" class="@error('name') is-invalid @enderror" placeholder="Name*" type="text"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <small class="contact-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="contact-field">
                        <input name="email" class="@error('email') is-invalid @enderror" placeholder="Email*"
                            type="email" value="{{ old('email') }}" required>
                        @error('email')
                            <small class="contact-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="contact-field">
                        <input name="phone" class="@error('phone') is-invalid @enderror" placeholder="Phone*"
                            type="text" inputmode="numeric" pattern="[0-9]{10}" minlength="10" maxlength="10"
                            autocomplete="tel" value="{{ old('phone') }}" required>
                        @error('phone')
                            <small class="contact-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="contact-field">
                        <input name="subject" class="@error('subject') is-invalid @enderror" placeholder="Subject*"
                            type="text" value="{{ old('subject') }}" required>
                        @error('subject')
                            <small class="contact-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="contact-field wide">
                        <textarea name="message" class="@error('message') is-invalid @enderror" placeholder="Your Message*" required>{{ old('message') }}</textarea>
                        @error('message')
                            <small class="contact-error">{{ $message }}</small>
                        @enderror
                        @error('contact_form')
                            <small class="contact-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="contact-field wide">
                        <button class="contact-submit" type="submit">Send Message</button>
                        <p id="contactFormMessage" class="contact-form-message"></p>
                    </div>
                </form>
            </div>

            <aside class="contact-info-panel">
                <h2 class="contact-info-title">We are here to help.</h2>
                <div class="contact-info-grid">
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="zmdi zmdi-phone"></i></div>
                        <div>
                            <div class="contact-info-label">Phone</div>
                            <div class="contact-info-text"><a href="tel:+916374390907">+91 6374390907</a></div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="zmdi zmdi-email"></i></div>
                        <div>
                            <div class="contact-info-label">Email</div>
                            <div class="contact-info-text"><a href="mailto:houseofknp@gmail.com">houseofknp@gmail.com</a></div>
                        </div>
                    </div>
                    <div class="contact-info-item contact-info-address">
                        <div class="contact-info-icon"><i class="zmdi zmdi-pin"></i></div>
                        <div>
                            <div class="contact-info-label">Address</div>
                            <div class="contact-info-text">61/1, PALAKKUKARA STREET, KAMUTHI, RAMANATHAPURAM DISTRICT -623603</div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <div class="contact-map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62979.35477891072!2d78.32055639898962!3d9.402989962425046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b0114621f644ddf%3A0x46eaa9beb3d515a1!2sKamuthi%2C%20Tamil%20Nadu!5e0!3m2!1sen!2sin!4v1784791421265!5m2!1sen!2sin"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="strict-origin-when-cross-origin"></iframe>
        {{-- <iframe src="https://www.google.com/maps?q=Coimbatore,Tamil Nadu&output=embed" width="100%" height="430"
            style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe> --}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('contactPopup');
            const popupTitle = document.getElementById('contactPopupTitle');
            const popupMessage = document.getElementById('contactPopupMessage');
            const popupIcon = document.getElementById('contactPopupIcon');
            const popupClose = document.getElementById('contactPopupClose');
            const form = document.getElementById('contact-form');
            const formMessage = document.getElementById('contactFormMessage');
            const phoneField = form.elements.phone;

            phoneField.addEventListener('input', function() {
                phoneField.value = phoneField.value.replace(/\D/g, '').slice(0, 10);
            });

            function showContactPopup(type, title, message) {
                Swal.fire({
                    icon: type,
                    title: title,
                    text: message,
                    confirmButtonColor: type === 'success' ? '#16a34a' : '#dc2626',
                    confirmButtonText: 'OK'
                });
            }

            Array.from(form.elements).forEach(function(field) {
                if (['name', 'email', 'phone', 'subject', 'message'].includes(field.name)) {
                    field.addEventListener('input', function() {
                        field.classList.remove('is-invalid');

                        const errorElement = field.closest('.contact-field').querySelector(
                            '.contact-error');

                        if (errorElement) {
                            errorElement.remove();
                        }
                    });
                }
            });

            form.addEventListener('submit', function(event) {
                const name = form.elements.name.value.trim();
                const email = form.elements.email.value.trim();
                const phone = form.elements.phone.value.trim();
                const subject = form.elements.subject.value.trim();
                const message = form.elements.message.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                function getFieldErrorElement(field) {
                    const fieldWrapper = field.closest('.contact-field');
                    let errorElement = fieldWrapper.querySelector('.contact-error');

                    if (!errorElement) {
                        errorElement = document.createElement('small');
                        errorElement.className = 'contact-error';
                        field.insertAdjacentElement('afterend', errorElement);
                    }

                    return errorElement;
                }

                function clearFieldError(field) {
                    field.classList.remove('is-invalid');

                    const fieldWrapper = field.closest('.contact-field');
                    const errorElement = fieldWrapper.querySelector('.contact-error');

                    if (errorElement && !errorElement.hasAttribute('data-server-error')) {
                        errorElement.remove();
                    }
                }

                function showFieldError(fieldName, text) {
                    const field = form.elements[fieldName];

                    field.classList.add('is-invalid');
                    getFieldErrorElement(field).textContent = text;
                }

                formMessage.textContent = '';
                formMessage.classList.remove('active');
                Array.from(form.elements).forEach(function(field) {
                    if (['name', 'email', 'phone', 'subject', 'message'].includes(field.name)) {
                        clearFieldError(field);
                    }
                });

                const invalidFields = [];

                function addError(fieldName, text) {
                    invalidFields.push(form.elements[fieldName]);
                    showFieldError(fieldName, text);
                }

                if (!name) {
                    addError('name', 'Please enter name.');
                }

                if (!email) {
                    addError('email', 'Please enter email address.');
                } else if (!emailPattern.test(email)) {
                    addError('email', 'Please enter a valid email address.');
                }

                if (!phone) {
                    addError('phone', 'Please enter phone number.');
                } else if (!/^[0-9]{10}$/.test(phone)) {
                    addError('phone', 'Phone number must be exactly 10 digits.');
                }

                if (!subject) {
                    addError('subject', 'Please enter subject.');
                }

                if (!message) {
                    addError('message', 'Please enter message.');
                }

                if (invalidFields.length) {
                    event.preventDefault();
                    invalidFields[0].focus();
                }
            });

            @if (session('contact_success'))
                showContactPopup('success', 'Message Sent', @json(session('contact_success')));
            @endif
        });
    </script>
@endsection
