@extends('layouts.app')

@section('content')
<style>
    .terms-page {
        background: #fff;
        padding: 64px 0 80px;
    }

    .terms-shell {
        background: #fff;
        border-left: 1px solid #e5e5e5;
        margin: 0;
        max-width: 1040px;
        padding: 0 0 0 60px;
    }

    .terms-kicker {
        color: #cc0000;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 5px;
        margin-bottom: 24px;
        text-transform: uppercase;
    }

    .terms-title {
        color: #111;
        font-size: 56px;
        line-height: 1.2;
        margin-bottom: 14px;
    }

    .terms-date {
        color: #6f747b;
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 38px;
    }

    .terms-intro,
    .terms-section p,
    .terms-section li {
        color: #4c4c4c;
        font-size: 15px;
        line-height: 1.9;
    }

    .terms-intro {
        border-left: 4px solid #cc0000;
        margin-bottom: 36px;
        padding-left: 20px;
    }

    .terms-section {
        border-top: 1px solid #eee8df;
        padding-top: 28px;
        margin-top: 28px;
    }

    .terms-section h2 {
        color: #111;
        font-size: 22px;
        margin-bottom: 12px;
    }

    .terms-section ul {
        margin: 0;
        padding-left: 20px;
    }

    .terms-section li {
        margin-bottom: 8px;
    }

    @media (max-width: 575px) {
        .terms-page {
            padding: 42px 0 60px;
        }

        .terms-shell {
            border-left: 0;
            padding: 0;
        }

        .terms-title {
            font-size: 36px;
        }
    }
</style>

<div class="rts-navigation-area-breadcrumb" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="/">Home</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <a class="current" href="{{ url('terms-condition') }}">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-seperator" style="display: none;">
    <div class="container">
        <hr class="section-seperator">
    </div>
</div>

<section class="terms-page">
    <div class="container">
        <div class="terms-shell">
            <div class="terms-kicker">HOUSE OF KNP</div>
            <h1 class="terms-title">Terms &amp; Conditions</h1>
            <div class="terms-date">Effective Date: [Update Date]</div>

            <p class="terms-intro">
                Welcome to HOUSE OF KNP. By accessing or purchasing from our website, you agree to the following Terms &amp; Conditions. Please read them carefully before using our website.
            </p>

            <div class="terms-section">
                <h2>1. General</h2>
                <p>
                    HOUSE OF KNP is a premium men's lifestyle brand offering formal shirts, perfumes, watches, and curated gift collections. By using this website, you confirm that you are at least 18 years of age or are using the website under the supervision of a parent or legal guardian.
                </p>
            </div>

            <div class="terms-section">
                <h2>2. Product Information</h2>
                <p>
                    We strive to ensure that all product descriptions, images, specifications, and pricing are accurate. However, slight variations in color, texture, fragrance, or appearance may occur due to lighting, screen settings, or manufacturing processes.
                </p>
            </div>

            <div class="terms-section">
                <h2>3. Pricing</h2>
                <ul>
                    <li>All prices are displayed in Indian Rupees (INR).</li>
                    <li>Prices include applicable taxes unless otherwise stated.</li>
                    <li>Prices may change without prior notice.</li>
                </ul>
            </div>

            <div class="terms-section">
                <h2>4. Orders</h2>
                <ul>
                    <li>Orders are confirmed only after successful payment or order confirmation.</li>
                    <li>HOUSE OF KNP reserves the right to cancel any order due to product unavailability, pricing errors, suspected fraud, or other unforeseen circumstances.</li>
                    <li>Customers will be notified if an order cannot be fulfilled.</li>
                </ul>
            </div>

            <div class="terms-section">
                <h2>5. Payment</h2>
                <p>
                    We accept secure online payment methods available on our website. All payment information is processed through trusted payment gateways.
                </p>
            </div>

            <div class="terms-section">
                <h2>6. Shipping</h2>
                <p>
                    Orders are processed within the estimated timeline mentioned during checkout. Delivery times may vary depending on the customer's location and courier service availability.
                </p>
            </div>

            <div class="terms-section">
                <h2>7. Intellectual Property</h2>
                <p>
                    All website content including logos, product images, text, graphics, and designs are the exclusive property of HOUSE OF KNP and may not be copied, reproduced, or used without written permission.
                </p>
            </div>

            <div class="terms-section">
                <h2>8. Limitation of Liability</h2>
                <p>
                    HOUSE OF KNP shall not be responsible for any indirect, incidental, or consequential damages arising from the use of our products or website.
                </p>
            </div>

            <div class="terms-section">
                <h2>9. Changes to Terms</h2>
                <p>
                    We reserve the right to modify these Terms &amp; Conditions at any time. Updated versions will be published on this page.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
