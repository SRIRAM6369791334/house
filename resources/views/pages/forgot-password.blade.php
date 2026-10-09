@extends('layouts.app')

@section('content')
    <style>
        /* 1. HERO BANNER & BREADCRUMB */
        .password-page-banner {
            background-image: url(../images/banner/about.png) !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            min-height: 240px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .password-page-banner.overlay::before {
            background: linear-gradient(to bottom, rgba(8, 8, 8, 0.70) 0%, rgba(8, 8, 8, 0.82) 100%) !important;
            opacity: 1 !important;
        }
        .password-page-banner .bread-main {
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
            background: transparent;
        }
        .password-page-banner .bred-hading h5 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(22px, 3.8vw, 32px);
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #ffffff;
            margin: 0 0 10px;
            line-height: 1.25;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
        }
        .password-page-banner .breadcrumb {
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
        .password-page-banner .breadcrumb li {
            color: #d6d0c7;
            display: inline-flex;
            align-items: center;
        }
        .password-page-banner .breadcrumb li a {
            color: #f0ede8 !important;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .password-page-banner .breadcrumb li a:hover {
            color: #CC0000 !important;
        }
        .password-page-banner .breadcrumb li.active {
            color: #ffffff;
            font-weight: 600;
        }
        .password-page-banner .breadcrumb li+li:before {
            content: "›";
            padding: 0 6px;
            color: rgba(255, 255, 255, 0.45);
            font-size: 13px;
            line-height: 1;
        }

        /* 2. ACCOUNT AREA */
        .account-area {
            background: #f8f7f5;
            padding: 60px 0 80px;
        }

        /* 3. PASSWORD ATELIER CARD */
        .password-card {
            border: 1px solid #e7e2db;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 16px 45px rgba(28, 22, 17, 0.06);
            padding: 38px 36px 34px;
            margin: 0 auto;
            max-width: 520px;
        }
        .password-card h3 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(22px, 2.8vw, 28px);
            font-weight: 700;
            color: #151515;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            border-bottom: 2px solid #f0ebe4;
            padding-bottom: 14px;
            margin-bottom: 16px;
            position: relative;
        }
        .password-card h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 44px;
            height: 2px;
            background: #CC0000;
        }
        .password-help {
            font-size: 13.5px;
            line-height: 1.65;
            color: #666;
            margin-bottom: 22px;
        }
        .password-card label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }
        .password-field {
            position: relative;
        }
        .password-field i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #CC0000;
            font-size: 14px;
            z-index: 2;
        }
        .password-field input.info {
            height: 48px;
            padding-left: 44px;
            background: #fafbfc;
            border: 1px solid #e2ded8;
            border-radius: 6px;
            font-size: 14px;
            color: #222;
            width: 100%;
            transition: all 0.2s ease;
        }
        .password-field input.info:focus {
            border-color: #CC0000;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.08);
            outline: none;
        }
        .password-action {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 48px;
            margin: 22px 0 0;
            border: 0;
            border-radius: 6px;
            background: #CC0000;
            color: #fff !important;
            padding: 14px 28px;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .password-action:hover {
            background: #111;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .password-back {
            margin: 20px 0 0;
            text-align: center;
            font-size: 13.5px;
            color: #666;
        }
        .password-back a {
            color: #CC0000;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .password-back a:hover {
            color: #111;
            text-decoration: underline;
        }

        /* 4. MOBILE RESPONSIVE (<= 767px) */
        @media (max-width: 767px) {
            .password-page-banner {
                min-height: 140px;
                padding: 0 16px;
                background-position: right center !important;
            }
            .password-page-banner.overlay::before {
                background: linear-gradient(to bottom, rgba(8, 8, 8, 0.86) 0%, rgba(8, 8, 8, 0.94) 100%) !important;
            }
            .password-page-banner .bread-main {
                position: static;
                transform: none;
                padding: 22px 8px;
                width: 100%;
            }
            .password-page-banner .bred-hading h5 {
                font-size: clamp(17px, 5.2vw, 21px);
                line-height: 1.25;
                margin-bottom: 6px;
                letter-spacing: 0.02em;
            }
            .password-page-banner .breadcrumb {
                font-size: 11px;
                gap: 4px 6px;
            }
            .password-page-banner .breadcrumb li+li:before {
                content: "›";
                padding: 0 5px 0 1px;
                color: rgba(255, 255, 255, 0.45);
            }
            .account-area {
                padding: 28px 0 44px;
            }
            .account-area .container {
                width: 100%;
                padding-left: 16px;
                padding-right: 16px;
            }
            .password-card {
                width: 100%;
                padding: 24px 18px 22px;
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            }
            .password-card h3 {
                font-size: 21px;
                margin-bottom: 14px;
                padding-bottom: 10px;
            }
            .password-help {
                font-size: 13px;
                margin-bottom: 18px;
                line-height: 1.55;
            }
            .password-card label {
                font-size: 10.5px;
                margin-bottom: 6px;
            }
            .password-field input.info {
                height: 46px;
                font-size: 13.5px;
                padding-left: 40px;
            }
            .password-field i {
                left: 14px;
                font-size: 13px;
            }
            .password-action {
                min-height: 46px;
                font-size: 11.5px;
                letter-spacing: 1.5px;
                margin-top: 18px;
            }
            .password-back {
                margin-top: 16px;
                font-size: 13px;
            }
            /* Prevent back to top collision */
            #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
                display: none !important;
            }
        }
    </style>
    <div class="password-page-banner breadcumb-area breadcumb-3 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center"><h5>Forgot Password</h5></div>
            <ol class="breadcrumb"><li class="home"><a href="/">Home</a></li><li class="active">Forgot Password</li></ol>
        </div>
    </div>
    <div class="account-area ptb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <form action="{{ route('password.email') }}" method="POST" class="password-card">
                        @csrf
                        <h3>Reset your password</h3>
                        <p class="password-help">Enter your registered email address. We will send you a secure 6-digit OTP to verify your password change.</p>
                        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                        @error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        <label for="forgot-email">E-Mail</label>
                        <div class="password-field">
                            <i class="fa fa-envelope"></i>
                            <input id="forgot-email" type="email" name="email" value="{{ old('email') }}" class="info" placeholder="E-Mail" autocomplete="email" required autofocus>
                        </div>
                        <button type="submit" class="password-action">Send OTP</button>
                        <p class="password-back"><a href="{{ route('login') }}">Back to Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

