@extends('layouts.app')

@section('content')
    <style>
        .password-page-banner { background-image: url(../images/banner/about.png) !important; background-position: center !important; background-size: cover !important; min-height: 245px; }
        .password-page-banner.overlay::before { background: rgba(0, 0, 0, .38); }
        .password-page-banner .bread-main { background: transparent; }
        .password-card { border: 1px solid #ddd; background: #fff; padding: 34px; box-shadow: 0 20px 50px rgba(0, 0, 0, .05); }
        .password-card h3 { border-bottom: 1px solid #ccc; padding-bottom: 14px; margin-bottom: 24px; }
        .password-input { position: relative; margin-bottom: 18px; }
        .password-input > i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #CC0000; z-index: 2; }
        .password-input input.info { height: 56px; padding-left: 46px; padding-right: 48px; background: #f6f8fc; border: 1px solid #e0e0e0; }
        .password-toggle { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); border: 0; background: transparent; color: #555; cursor: pointer; z-index: 3; }
        .password-action { display: block; width: min(100%, 260px); min-width: 0; margin: 0 auto; border: 0; background: #CC0000; color: #fff; padding: 17px 20px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        @media (max-width: 767px) {
            .password-page-banner { min-height: 150px; }
            .account-area { padding: 40px 0; }
            .account-area .container { width: 100%; padding-left: 15px; padding-right: 15px; }
            .password-card { width: 100%; padding: 24px 20px; }
        }
    </style>
    <div class="password-page-banner breadcumb-area breadcumb-3 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center"><h5>Reset Password</h5></div>
            <ol class="breadcrumb"><li class="home"><a href="/">Home</a></li><li class="active">Reset Password</li></ol>
        </div>
    </div>
    <div class="account-area ptb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <form action="{{ route('password.update') }}" method="POST" class="password-card">
                        @csrf

                        <h3>Create new password</h3>
                        @if ($errors->any())
                            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                        @endif
                        <div class="alert alert-success">OTP verified for <strong>{{ $email }}</strong>. You can now create a new password.</div>
                        <label for="reset-password">New Password</label>
                        <div class="password-input">
                            <i class="fa fa-lock"></i>
                            <input id="reset-password" type="password" name="password" class="info js-password-field" placeholder="New Password" autocomplete="new-password" required>
                            <button class="password-toggle" type="button" aria-label="Show password"><i class="fa fa-eye"></i></button>
                        </div>
                        <label for="reset-password-confirmation">Confirm Password</label>
                        <div class="password-input">
                            <i class="fa fa-lock"></i>
                            <input id="reset-password-confirmation" type="password" name="password_confirmation" class="info js-password-field" placeholder="Confirm Password" autocomplete="new-password" required>
                            <button class="password-toggle" type="button" aria-label="Show password"><i class="fa fa-eye"></i></button>
                        </div>
                        <button type="submit" class="password-action">Reset password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('click', function (event) {
            const toggle = event.target.closest('.password-toggle');
            if (!toggle) return;
            const field = toggle.closest('.password-input').querySelector('.js-password-field');
            const icon = toggle.querySelector('i');
            const shouldShow = field.type === 'password';
            field.type = shouldShow ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !shouldShow);
            icon.classList.toggle('fa-eye-slash', shouldShow);
            toggle.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
        });
    </script>
@endsection

