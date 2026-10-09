@extends('layouts.app')

@section('content')
<style>
    .shipping-policy-page {
        background: #fff;
        padding: 64px 0 80px;
    }

    .shipping-policy-shell {
        border-left: 1px solid #e5e5e5;
        max-width: 1040px;
        padding-left: 60px;
    }

    .shipping-policy-header {
        /* border-bottom: 1px solid #eee8df; */
        margin-bottom: 38px;
        padding-bottom: 34px;
    }

    .shipping-policy-kicker {
        color: #cc0000;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 5px;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .shipping-policy-title {
        color: #111;
        font-size: 52px;
        line-height: 1.15;
        margin: 0 0 14px;
    }

    .shipping-policy-date {
        color: #6f747b;
        font-size: 15px;
        font-weight: 700;
        margin: 0;
    }

    .shipping-policy-intro {
        border-left: 4px solid #cc0000;
        color: #4c4c4c;
        font-size: 15px;
        line-height: 1.9;
        margin: 0 0 36px;
        padding-left: 20px;
    }

    .shipping-policy-section {
        border-top: 1px solid #eee8df;
        margin-top: 28px;
        padding-top: 28px;
    }

    .shipping-policy-section h2 {
        color: #111;
        font-size: 23px;
        margin-bottom: 14px;
    }

    .shipping-policy-section p,
    .shipping-policy-section li {
        color: #4c4c4c;
        font-size: 15px;
        line-height: 1.9;
    }

    .shipping-policy-section p {
        margin-bottom: 12px;
    }

    .shipping-policy-section ul {
        margin: 0;
        padding-left: 21px;
    }

    .shipping-policy-section li {
        margin-bottom: 8px;
    }

    .shipping-policy-contact {
        background: #f8f8f8;
        border-left: 4px solid #cc0000;
        margin-top: 16px;
        padding: 20px 22px;
    }

    .shipping-policy-contact p:last-child {
        margin-bottom: 0;
    }

    .shipping-policy-contact a {
        color: #cc0000;
        text-decoration: none;
    }

    @media (max-width: 575px) {
        .shipping-policy-page {
            padding: 42px 0 60px;
        }

        .shipping-policy-page > .container {
            max-width: none;
            padding-left: 18px;
            padding-right: 18px;
            width: 100%;
        }

        .shipping-policy-shell {
            border-left: 0;
            padding-left: 0;
        }

        .shipping-policy-header {
            margin-bottom: 30px;
            padding-bottom: 26px;
        }

        .shipping-policy-kicker {
            font-size: 10px;
            letter-spacing: 3px;
        }

        .shipping-policy-title {
            font-size: 36px;
        }

        .shipping-policy-section h2 {
            font-size: 21px;
        }
    }
    @media (max-width: 767px) {
        /* Hide floating scroll-up button on mobile to prevent overlapping */
        #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
            display: none !important;
        }
    }
</style>

<section class="shipping-policy-page">
    <div class="container">
        <article class="shipping-policy-shell">
            <header class="shipping-policy-header">
                <div class="shipping-policy-kicker">HOUSE OF KNP</div>
                <h1 class="shipping-policy-title">Shipping Policy</h1>
                <p class="shipping-policy-date">Last Updated: 26 June 2026</p>
            </header>

            <p class="shipping-policy-intro">
                Thank you for shopping with HOUSE OF KNP. We are committed to delivering your orders safely and efficiently across India.
            </p>

            <section class="shipping-policy-section">
                <h2>1. Order Processing</h2>
                <ul>
                    <li>Orders are processed within <strong>1–3 business days</strong> after successful payment confirmation.</li>
                    <li>Orders placed on Sundays or public holidays will be processed on the next working day.</li>
                    <li>During product launches, festive seasons, or promotional periods, processing times may be extended.</li>
                </ul>
            </section>

            <section class="shipping-policy-section">
                <h2>2. Shipping Timeline</h2>
                <p>Estimated delivery timelines are as follows:</p>
                <ul>
                    <li><strong>Tamil Nadu:</strong> 2–5 business days</li>
                    <li><strong>South India:</strong> 3–6 business days</li>
                    <li><strong>Rest of India:</strong> 4–8 business days</li>
                    <li><strong>Remote locations:</strong> 5–10 business days</li>
                </ul>
                <p style="margin-top: 14px;">Delivery timelines are estimates and may vary depending on courier operations and local conditions.</p>
            </section>

            <section class="shipping-policy-section">
                <h2>3. Shipping Charges</h2>
                <ul>
                    <li>Shipping charges, if applicable, will be displayed at checkout before payment.</li>
                    <li>HOUSE OF KNP may offer free shipping on selected products, order values, or promotional campaigns.</li>
                </ul>
            </section>

            <section class="shipping-policy-section">
                <h2>4. Order Tracking</h2>
                <p>Once your order is dispatched, a tracking ID and courier details will be shared via email, SMS, or WhatsApp.</p>
                <p>Customers are responsible for monitoring their shipment using the provided tracking information.</p>
            </section>

            <section class="shipping-policy-section">
                <h2>5. Delivery Delays</h2>
                <p>While we strive to deliver within the estimated timeframe, delays may occur due to:</p>
                <ul>
                    <li>Weather conditions</li>
                    <li>Natural disasters</li>
                    <li>Public holidays</li>
                    <li>Political disruptions</li>
                    <li>Courier partner delays</li>
                    <li>Incorrect or incomplete shipping information provided by the customer</li>
                </ul>
                <p style="margin-top: 14px;">HOUSE OF KNP shall not be held liable for delays caused by third-party logistics providers.</p>
            </section>

            <section class="shipping-policy-section">
                <h2>6. Incorrect Shipping Information</h2>
                <p>Customers must ensure that all shipping details are accurate at the time of placing the order.</p>
                <p>HOUSE OF KNP will not be responsible for:</p>
                <ul>
                    <li>Failed deliveries due to incorrect addresses.</li>
                    <li>Delays caused by incomplete contact information.</li>
                    <li>Additional charges incurred for re-shipment.</li>
                </ul>
            </section>

            <section class="shipping-policy-section">
                <h2>7. Damaged or Tampered Packages</h2>
                <p>If a package appears damaged or tampered with upon delivery, customers should:</p>
                <ul>
                    <li>Record an unboxing video from the moment the package is opened.</li>
                    <li>Take clear photographs of the packaging and product.</li>
                    <li>Contact our customer support within <strong>48 hours</strong> of receiving the order.</li>
                </ul>
                <p style="margin-top: 14px;">Claims submitted without proper evidence may not qualify for exchange approval.</p>
            </section>

            <section class="shipping-policy-section">
                <h2>8. International Shipping</h2>
                <p>Currently, HOUSE OF KNP ships only within India. International shipping services may be introduced in the future.</p>
            </section>

            <section class="shipping-policy-section">
                <h2>9. Exchange-Only Policy</h2>
                <p>HOUSE OF KNP follows a <strong>No Return and No Refund Policy</strong>.</p>
                <p>Eligible products may be exchanged within <strong>3 days of delivery</strong> in accordance with our Exchange Policy.</p>
                <p>Customized and personalized products are not eligible for exchange, return, or refund.</p>
            </section>

            <section class="shipping-policy-section">
                <h2>10. Contact Us</h2>
                <p>For shipping-related inquiries, please contact:</p>
                <div class="shipping-policy-contact">
                    <p><strong>HOUSE OF KNP</strong></p>
                    <p>Email: <a href="mailto:houseofknp@gmail.com">houseofknp@gmail.com</a></p>
                    <p>Phone: <a href="tel:+916374390907">+91 6374390907</a></p>
                </div>
            </section>
        </article>
    </div>
</section>
@endsection
