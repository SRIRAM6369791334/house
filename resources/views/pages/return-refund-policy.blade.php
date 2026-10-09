@extends('layouts.app')

@section('content')
<style>
    .policy-page {
        background: #fff;
        padding: 64px 0 80px;
    }

    .policy-shell {
        border-left: 1px solid #e5e5e5;
        max-width: 1040px;
        padding-left: 60px;
    }

    .policy-header {
        /* border-bottom: 1px solid #eee8df; */
        margin-bottom: 38px;
        padding-bottom: 34px;
    }

    .policy-kicker {
        color: #cc0000;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 5px;
        margin-bottom: 24px;
        text-transform: uppercase;
    }

    .policy-title {
        color: #111;
        font-size: 52px;
        line-height: 1.2;
        margin: 0;
    }

    .policy-section {
        border-top: 1px solid #eee8df;
        margin-top: 30px;
        padding-top: 30px;
    }

    .policy-section:first-of-type {
        border-top: 0;
        margin-top: 0;
        padding-top: 0;
    }

    .policy-section h2 {
        color: #111;
        font-size: 24px;
        margin-bottom: 14px;
    }

    .policy-section p,
    .policy-section li {
        color: #4c4c4c;
        font-size: 15px;
        line-height: 1.9;
    }

    .policy-section ul {
        margin: 0;
        padding-left: 20px;
    }

    .policy-section li {
        margin-bottom: 8px;
    }

    .policy-note {
        border-left: 4px solid #cc0000;
        padding-left: 20px;
    }

    @media (max-width: 575px) {
        .policy-page {
            padding: 42px 0 60px;
        }

        .policy-shell {
            border-left: 0;
            padding-left: 0;
        }

        .policy-title {
            font-size: 34px;
        }
    }
</style>

<section class="policy-page">
    <div class="container">
        <div class="policy-shell">
            <div class="policy-header">
                <div class="policy-kicker">HOUSE OF KNP</div>
                <h1 class="policy-title">Return &amp; Refund Policy</h1>
            </div>

            <div class="policy-section">
                <h2>No Return Policy</h2>
                <p class="policy-note">
                    At HOUSE OF KNP, we maintain strict quality standards. Therefore, all purchases are final, and we do not accept returns once an order has been delivered.
                </p>
                <p>
                    Please review the product details, size, color, and quantity carefully before placing your order.
                </p>
            </div>

            <div class="policy-section">
                <h2>Replacement Policy</h2>
                <p>A replacement will be provided only if:</p>
                <ul>
                    <li>The wrong product was delivered.</li>
                    <li>The product is damaged during delivery.</li>
                    <li>The product has a manufacturing defect.</li>
                </ul>
                <p style="margin-top: 18px;">To request a replacement:</p>
                <ul>
                    <li>Contact us within 48 hours of delivery.</li>
                    <li>Provide your Order ID.</li>
                    <li>Share clear photos or an unboxing video showing the issue.</li>
                </ul>
                <p style="margin-top: 18px;">Requests received after 48 hours may not be accepted.</p>
            </div>

            <div class="policy-section">
                <h2>Shipping Policy</h2>
                <ul>
                    <li>Orders are processed within 1-3 business days.</li>
                    <li>Delivery typically takes 3-7 business days depending on the destination.</li>
                    <li>Delivery timelines may vary during holidays, festivals, or unforeseen circumstances.</li>
                    <li>Customers will receive shipment tracking details once the order is dispatched.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Cancellation Policy</h2>
                <ul>
                    <li>Orders can be cancelled only before they are dispatched.</li>
                    <li>Once shipped, cancellation requests cannot be accepted.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection
