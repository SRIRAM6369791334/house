@extends('layouts.app')

@section('content')
   <!--breadcumb area start -->
        <div class="breadcumb-area overlay pos-rltv">
            <div class="bread-main">
                <div class="bred-hading text-center">
                    <h5>Login Register</h5> </div>
                <ol class="breadcrumb">
                    <li class="home"><a title="Go to Home Page" href="/"style="color: #fff;">Home</a></li>
                    <li class="active">Login</li>
                </ol>
            </div>
        </div>
        <!--breadcumb area end -->

        <style>
            /* 1. HERO SHARED BANNER & BREADCRUMB */
            .breadcumb-area {
                background-image: url(../images/banner/about.png) !important;
                background-position: center center !important;
                background-repeat: no-repeat !important;
                background-size: cover !important;
                min-height: 500px;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .breadcumb-area.overlay::before {
                background: linear-gradient(to bottom, rgba(8, 8, 8, 0.70) 0%, rgba(8, 8, 8, 0.82) 100%) !important;
                opacity: 1 !important;
            }
            .breadcumb-area .bread-main {
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
            .breadcumb-area .bred-hading h5 {
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
            .breadcumb-area .breadcrumb {
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
            .breadcumb-area .breadcrumb li {
                color: #d6d0c7;
                display: inline-flex;
                align-items: center;
            }
            .breadcumb-area .breadcrumb li a {
                color: #f0ede8 !important;
                text-decoration: none;
                transition: color 0.2s ease;
            }
            .breadcumb-area .breadcrumb li a:hover {
                color: #CC0000 !important;
            }
            .breadcumb-area .breadcrumb li.active {
                color: #ffffff;
                font-weight: 600;
            }
            .breadcumb-area .breadcrumb li+li:before {
                content: "›";
                padding: 0 6px;
                color: rgba(255, 255, 255, 0.45);
                font-size: 13px;
                line-height: 1;
            }

            /* 2. ACCOUNT AREA & CONTAINER */
            .account-area {
                background: #f8f7f5;
                padding: 60px 0 80px;
            }

            /* 3. LOGIN CARD */
            .auth-card {
                border: 1px solid #e7e2db;
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 16px 45px rgba(28, 22, 17, 0.06);
                overflow: hidden;
                margin: 0 auto;
                max-width: 540px;
            }
            .login-reg {
                padding: 38px 36px 30px;
            }
            .auth-card h3 {
                font-family: 'Cormorant Garamond', Georgia, serif;
                font-size: clamp(24px, 3vw, 30px);
                font-weight: 700;
                color: #151515;
                letter-spacing: 0.02em;
                text-transform: uppercase;
                border-bottom: 2px solid #f0ebe4;
                padding-bottom: 14px;
                margin-bottom: 24px;
                position: relative;
            }
            .auth-card h3::after {
                content: '';
                position: absolute;
                left: 0;
                bottom: -2px;
                width: 44px;
                height: 2px;
                background: #CC0000;
            }
            .control-label {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 1.2px;
                text-transform: uppercase;
                color: #555;
                margin-bottom: 8px;
                display: block;
            }
            .auth-field {
                position: relative;
            }
            .auth-field .auth-input-icon {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: #CC0000;
                font-size: 14px;
                z-index: 2;
            }
            .auth-field input.info {
                padding-left: 44px;
                height: 48px;
                background: #fafbfc;
                border: 1px solid #e2ded8;
                border-radius: 6px;
                font-size: 14px;
                color: #222;
                width: 100%;
                transition: all 0.2s ease;
            }
            .auth-field input.info:focus {
                border-color: #CC0000;
                background: #fff;
                box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.08);
                outline: none;
            }
            .password-toggle {
                position: absolute;
                right: 14px;
                top: 50%;
                transform: translateY(-50%);
                border: 0;
                background: transparent;
                color: #777;
                cursor: pointer;
                z-index: 3;
                padding: 6px;
                font-size: 14px;
                transition: color 0.2s ease;
            }
            .password-toggle:hover {
                color: #CC0000;
            }
            .auth-field.has-toggle input.info {
                padding-right: 44px;
            }
            .auth-switch-link {
                color: #CC0000;
                font-weight: 700;
                font-size: 13px;
                text-decoration: none;
                transition: color 0.2s ease;
            }
            .auth-switch-link:hover {
                color: #111;
                text-decoration: underline;
            }

            /* 4. CARD FOOTER */
            .auth-footer {
                background: #faf9f7;
                border-top: 1px solid #f0ebe4;
                margin: 0;
                padding: 24px 36px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                flex-wrap: wrap;
                border-radius: 0 0 10px 10px;
            }
            .auth-action-btn {
                background: #CC0000;
                color: #fff !important;
                border: 0;
                border-radius: 6px;
                padding: 14px 38px;
                min-height: 48px;
                text-transform: uppercase;
                font-weight: 700;
                font-size: 12px;
                letter-spacing: 2px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.25s ease;
            }
            .auth-action-btn:hover {
                background: #111;
                color: #fff !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            .auth-footer p {
                font-size: 13.5px;
                color: #666;
                margin: 0;
            }

            /* 5. ALERTS */
            .alert {
                border-radius: 6px;
                font-size: 13.5px;
                margin-bottom: 24px;
                padding: 14px 18px;
            }
            .alert-success {
                background: #edf7ed;
                border: 1px solid #b7dfb9;
                color: #1e4620;
            }
            .alert-danger {
                background: #fdf2f2;
                border: 1px solid #f8b4b4;
                color: #9b1c1c;
            }

            /* 6. MOBILE RESPONSIVE (<= 767px) */
            @media (max-width: 767px) {
                .breadcumb-area {
                    min-height: 140px;
                    padding: 0 16px;
                    background-position: right center !important;
                }
                .breadcumb-area.overlay::before {
                    background: linear-gradient(to bottom, rgba(8, 8, 8, 0.86) 0%, rgba(8, 8, 8, 0.94) 100%) !important;
                }
                .breadcumb-area .bread-main {
                    position: static;
                    transform: none;
                    padding: 22px 8px;
                    width: 100%;
                }
                .breadcumb-area .bred-hading h5 {
                    font-size: clamp(17px, 5.2vw, 21px);
                    line-height: 1.25;
                    margin-bottom: 6px;
                    letter-spacing: 0.02em;
                }
                .breadcumb-area .breadcrumb {
                    font-size: 11px;
                    gap: 4px 6px;
                }
                .breadcumb-area .breadcrumb li+li:before {
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
                .auth-card {
                    width: 100%;
                    border-radius: 10px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                }
                .login-reg {
                    padding: 24px 18px 20px;
                }
                .auth-card h3 {
                    font-size: 22px;
                    margin-bottom: 18px;
                    padding-bottom: 12px;
                }
                .control-label {
                    font-size: 10.5px;
                    margin-bottom: 6px;
                }
                .auth-field input.info {
                    height: 46px;
                    font-size: 13.5px;
                    padding-left: 40px;
                }
                .auth-field.has-toggle input.info {
                    padding-right: 42px;
                }
                .auth-field .auth-input-icon {
                    left: 14px;
                    font-size: 13px;
                }
                .password-toggle {
                    right: 12px;
                }
                .auth-footer {
                    padding: 20px 18px;
                    flex-direction: column;
                    align-items: stretch;
                    gap: 12px;
                    border-radius: 0 0 10px 10px;
                }
                .auth-footer .input-box {
                    width: 100%;
                    margin: 0;
                }
                .auth-action-btn {
                    display: flex;
                    width: 100%;
                    min-height: 46px;
                    font-size: 11.5px;
                    letter-spacing: 1.5px;
                }
                .auth-footer p {
                    width: 100%;
                    margin: 0 !important;
                    text-align: center;
                    font-size: 13px;
                }
                /* Prevent back to top collision */
                #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
                    display: none !important;
                }
            }
        </style>

        <!-- Account Area Start -->
        <div class="account-area ptb-80">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <form action="{{ route('login.store') }}" method="POST" class="login-side auth-card">
                            @csrf
                            @if(request()->filled('redirect_to'))
                                <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                            @endif
                            <div class="login-reg">
                                <h3>Login</h3>
                                <div class="input-box mb-20">
                                    <label class="control-label">E-Mail</label>
                                    <div class="auth-field">
                                        <i class="fa fa-envelope auth-input-icon"></i>
                                        <input type="email" placeholder="E-Mail" value="{{ old('email') }}" name="email" class="info" required>
                                    </div>
                                </div>
                                <div class="input-box">
                                    <label class="control-label">Password</label>
                                    <div class="auth-field has-toggle">
                                        <i class="fa fa-lock auth-input-icon"></i>
                                        <input type="password" placeholder="Password" name="password" class="info js-password-field" required>
                                        <button class="password-toggle" type="button" aria-label="Show password">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-right mt-10 mb-0">
                                    <a href="{{ route('password.request') }}" class="auth-switch-link">Forgot password?</a>
                                </p>
                            </div>
                            <div class="frm-action auth-footer">
                                <div class="input-box tci-box">
                                    <button type="submit" class="auth-action-btn">Login</button>
                                </div>
                                <p class="mb-0">New customer? <a href="{{ url('register') }}" class="auth-switch-link">Register</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Account Area End -->
        <script>
            document.addEventListener('click', function (event) {
                const toggle = event.target.closest('.password-toggle');
                if (!toggle) return;

                const field = toggle.closest('.auth-field').querySelector('.js-password-field');
                const icon = toggle.querySelector('i');
                const shouldShow = field.type === 'password';

                field.type = shouldShow ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !shouldShow);
                icon.classList.toggle('fa-eye-slash', shouldShow);
                toggle.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
            });
        </script>
@endsection

