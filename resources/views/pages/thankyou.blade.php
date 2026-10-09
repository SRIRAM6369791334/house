@extends('layouts.app')

@section('content')
@php
    $order = session('last_order');
@endphp

<style>
    .thankyou-page {
        background: #fff;
        padding: 78px 0 96px;
        text-align: center;
    }
    .thankyou-title {
        color: #21864f;
        font-family: 'Playfair Display', serif;
        font-size: 52px;
        font-weight: 700;
        line-height: 1.15;
        margin: 0 0 14px;
    }
    .thankyou-copy {
        color: #1f2937;
        font-size: 18px;
        margin: 0;
    }
    .thankyou-summary {
        border: 1px solid #d9d9d9;
        margin: 34px auto 0;
        max-width: 650px;
        padding: 28px;
        text-align: left;
    }
    .thankyou-summary p {
        color: #111827;
        font-size: 17px;
        margin: 0 0 18px;
    }
    .thankyou-summary p:last-child {
        margin-bottom: 0;
    }
    .thankyou-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        justify-content: center;
        margin-top: 34px;
    }
    .thankyou-btn {
        align-items: center;
        border: 1px solid #000;
        display: inline-flex;
        font-size: 14px;
        font-weight: 900;
        justify-content: center;
        letter-spacing: 1px;
        min-height: 52px;
        min-width: 190px;
        padding: 14px 22px;
        text-decoration: none;
        text-transform: uppercase;
    }
    .thankyou-btn.primary {
        background: #000;
        color: #fff;
    }
    .thankyou-btn.secondary {
        background: #fff;
        color: #000;
    }
    .thankyou-btn:hover,
    .thankyou-btn:focus {
        background: #cc0000;
        border-color: #cc0000;
        color: #fff;
        text-decoration: none;
    }
    @media (max-width: 767px) {
        .thankyou-page {
            padding: 54px 0 72px;
        }
        .thankyou-title {
            font-size: 38px;
        }
        .thankyou-summary {
            padding: 22px 18px;
        }
        .thankyou-btn {
            width: 100%;
        }
    }
</style>

<section class="thankyou-page">
    <div class="container">
        <h1 class="thankyou-title">Thank you for your order!</h1>
        <p class="thankyou-copy">Your order has been successfully placed.</p>

        @if($order)
            <div class="thankyou-summary">
                <p><strong>Order Number:</strong> {{ $order['number'] }}</p>
                <p><strong>Payment Method:</strong> Online</p>
                @if(!empty($order['coupon_code']))
                    <p><strong>Coupon:</strong> {{ $order['coupon_code'] }}</p>
                @endif
                <p><strong>Total:</strong> {!! house_money($order['total']) !!}</p>
            </div>
        @endif

        <div class="thankyou-actions">
            @auth
                <a href="{{ url('account') }}" class="thankyou-btn primary">My Account</a>
            @else
                <a href="{{ url('login') }}" class="thankyou-btn primary">My Account</a>
            @endauth
            <a href="{{ url('shop') }}" class="thankyou-btn secondary">Continue Shopping</a>
        </div>
    </div>
</section>
@endsection
