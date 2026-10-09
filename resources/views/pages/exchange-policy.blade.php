@extends('layouts.app')

@section('content')
<style>
    .exchange-page { background: #fff; padding: 64px 0 80px; }
    .exchange-shell { border-left: 1px solid #e5e5e5; max-width: 1040px; padding-left: 60px; }
    .exchange-header { border-bottom: 1px solid #eee8df; margin-bottom: 38px; padding-bottom: 34px; }
    .exchange-kicker { color: #cc0000; font-size: 12px; font-weight: 800; letter-spacing: 5px; margin-bottom: 20px; text-transform: uppercase; }
    .exchange-title { color: #111; font-size: 52px; line-height: 1.15; margin: 0 0 14px; }
    .exchange-date { color: #6f747b; font-size: 15px; font-weight: 700; margin: 0; }
    .exchange-intro { border-left: 4px solid #cc0000; color: #4c4c4c; font-size: 15px; line-height: 1.9; margin: 0 0 18px; padding-left: 20px; }
    .exchange-section { border-top: 1px solid #eee8df; margin-top: 28px; padding-top: 28px; }
    .exchange-section h2 { color: #111; font-size: 23px; margin-bottom: 14px; }
    .exchange-section p, .exchange-section li { color: #4c4c4c; font-size: 15px; line-height: 1.9; }
    .exchange-section p { margin-bottom: 12px; }
    .exchange-section ul { margin: 0; padding-left: 21px; }
    .exchange-section li { margin-bottom: 8px; }
    .exchange-alert { background: #fff5f5; border: 1px solid rgba(204,0,0,.2); border-left: 4px solid #cc0000; margin-top: 16px; padding: 18px 20px; }
    .exchange-alert p:last-child { margin-bottom: 0; }
    .exchange-link { color: #cc0000; font-weight: 800; text-decoration: none; }
    .exchange-link:hover { color: #111; }

    @media (max-width: 575px) {
        .exchange-page { padding: 42px 0 60px; }
        .exchange-page > .container { max-width: none; padding-left: 18px; padding-right: 18px; width: 100%; }
        .exchange-shell { border-left: 0; padding-left: 0; }
        .exchange-header { margin-bottom: 30px; padding-bottom: 26px; }
        .exchange-kicker { font-size: 10px; letter-spacing: 3px; }
        .exchange-title { font-size: 36px; }
        .exchange-section h2 { font-size: 21px; }
    }
    @media (max-width: 767px) {
        /* Hide floating scroll-up button on mobile to prevent overlapping */
        #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
            display: none !important;
        }
    }
</style>

<section class="exchange-page">
    <div class="container">
        <article class="exchange-shell">
            <header class="exchange-header">
                <div class="exchange-kicker">HOUSE OF KNP</div>
                <h1 class="exchange-title">Exchange Policy</h1>
                <p class="exchange-date">Last Updated: 29 June 2026</p>
            </header>

            <p class="exchange-intro">
                At HOUSE OF KNP, we maintain a strict <strong>No Return Policy</strong>. Cash on Delivery (COD) is not available.
            </p>
            <p class="exchange-intro">
                Customers may request an exchange within <strong>3 calendar days</strong> of receiving their order, subject to the conditions below.
            </p>

            <section class="exchange-section">
                <h2>Eligible Exchanges</h2>
                <p>Products may be exchanged only if:</p>
                <ul>
                    <li>The wrong item was delivered.</li>
                    <li>There is a manufacturing defect.</li>
                    <li>The requested size is available for exchange. If the requested size is unavailable, we will proceed with a refund.</li>
                </ul>
            </section>

            <section class="exchange-section">
                <h2>Exchange Conditions</h2>
                <ul>
                    <li>Exchange requests must be raised within <strong>3 calendar days</strong> from the date of delivery.</li>
                    <li>The product must be unused, unwashed, and in its original condition with all tags, packaging, invoices, and accessories intact.</li>
                    <li>Items showing signs of use, customer-caused damage, or missing original packaging will not be eligible for exchange.</li>
                </ul>
            </section>

            <section class="exchange-section">
                <h2>Non-Exchangeable Products</h2>
                <p>The following items cannot be exchanged:</p>
                <ul>
                    <li>Opened or used perfumes and fragrances.</li>
                    <li>Customized or personalized products.</li>
                    <li>Gift cards or promotional items.</li>
                </ul>
            </section>

            <section class="exchange-section">
                <h2>Damaged, Defective, Wrong, or Missing Products</h2>
                <div class="exchange-alert">
                    <p>Damaged, defective, wrong, or missing products must be reported within <strong>24 hours of delivery</strong>.</p>
                    <p>Please send supporting photographs to <a class="exchange-link" href="mailto:houseofknp@gmail.com">houseofknp@gmail.com</a>.</p>
                </div>
            </section>

            <section class="exchange-section">
                <h2>Pickup and Self-Shipping</h2>
                <ul>
                    <li><strong>Hassle-free pickup:</strong> We will arrange a pickup within 2–3 working days. A ₹100 reverse shipping fee applies.</li>
                    <li><strong>Self-shipping:</strong> If reverse pickup is unavailable for your pincode, you may courier the product directly to our address.</li>
                </ul>
            </section>

            <section class="exchange-section">
                <h2>Automatic Watches</h2>
                <p>Automatic watches are covered by a product-specific exchange policy due to their mechanical nature.</p>
                <p><a class="exchange-link" href="{{ url('automatic-watch-exchange-policy') }}">Read the Automatic Watch Exchange Policy <i class="fa fa-arrow-right"></i></a></p>
            </section>

            <section class="exchange-section">
                <h2>Inspection and Final Decision</h2>
                <p>HOUSE OF KNP reserves the right to inspect the product before approving any exchange request.</p>
                <p>The final decision regarding exchange eligibility shall remain solely with the company.</p>
            </section>
        </article>
    </div>
</section>
@endsection
