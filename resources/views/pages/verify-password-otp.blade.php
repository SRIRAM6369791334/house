@extends('layouts.app')

@section('content')
    <style>
        .password-page-banner { background-image: url(../images/banner/about.png) !important; background-position: center !important; background-size: cover !important; min-height: 245px; }
        .password-page-banner.overlay::before { background: rgba(0, 0, 0, .38); }
        .password-page-banner .bread-main { background: transparent; }
        .password-card { border: 1px solid #ddd; background: #fff; padding: 34px; box-shadow: 0 20px 50px rgba(0, 0, 0, .05); }
        .password-card h3 { border-bottom: 1px solid #ccc; padding-bottom: 14px; margin-bottom: 14px; }
        .password-help { line-height: 1.7; margin-bottom: 22px; }
        .otp-input { height: 64px !important; text-align: center; font-size: 28px !important; font-weight: 700; letter-spacing: 12px; background: #f6f8fc !important; border: 1px solid #e0e0e0 !important; }
        .password-action { display: block; width: min(100%, 260px); min-width: 0; margin: 20px auto 0; border: 0; background: #CC0000; color: #fff; padding: 17px 20px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        .password-back { margin: 18px 0 0; text-align: center; }
        .password-back button { border: 0; padding: 0; background: transparent; color: #CC0000; font-weight: 700; cursor: pointer; }
        @media (max-width: 767px) { .password-page-banner { min-height: 150px; } .account-area { padding: 40px 0; } .password-card { width: 100%; padding: 24px 20px; } }
    </style>
    <div class="password-page-banner breadcumb-area breadcumb-3 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center"><h5>Verify OTP</h5></div>
            <ol class="breadcrumb"><li class="home"><a href="/">Home</a></li><li class="active">Verify OTP</li></ol>
        </div>
    </div>
    <div class="account-area ptb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <form action="{{ route('password.otp.verify') }}" method="POST" class="password-card">
                        @csrf
                        <h3>Enter verification code</h3>
                        <p class="password-help">We sent a 6-digit OTP to <strong>{{ $email }}</strong>. The code expires in 10 minutes.</p>
                        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                        @error('otp')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        <label for="password-otp">OTP</label>
                        <input id="password-otp" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" name="otp" class="info otp-input" autocomplete="one-time-code" required autofocus>
                        <button type="submit" class="password-action">Verify OTP</button>
                    </form>
                    <form action="{{ route('password.email') }}" method="POST" class="password-back">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit">Resend OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
