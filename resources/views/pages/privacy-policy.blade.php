@extends('layouts.app')

@section('content')
<style>
    .privacy-policy-page {
        background: #fff;
        padding: 64px 0 80px;
    }

    .privacy-policy-shell {
        border-left: 1px solid #e5e5e5;
        max-width: 1040px;
        padding-left: 60px;
    }

    .privacy-policy-header {
        /* border-bottom: 1px solid #eee8df; */
        margin-bottom: 38px;
        padding-bottom: 34px;
    }

    .privacy-policy-kicker {
        color: #cc0000;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 5px;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .privacy-policy-title {
        color: #111;
        font-size: 52px;
        line-height: 1.15;
        margin: 0 0 14px;
    }

    .privacy-policy-date {
        color: #6f747b;
        font-size: 15px;
        font-weight: 700;
        margin: 0;
    }

    .privacy-policy-intro {
        border-left: 4px solid #cc0000;
        color: #4c4c4c;
        font-size: 15px;
        line-height: 1.9;
        margin: 0 0 20px;
        padding-left: 20px;
    }

    .privacy-policy-section {
        border-top: 1px solid #eee8df;
        margin-top: 28px;
        padding-top: 28px;
    }

    .privacy-policy-section h2 {
        color: #111;
        font-size: 23px;
        margin-bottom: 14px;
    }

    .privacy-policy-section h3 {
        color: #222;
        font-family: 'Open Sans', sans-serif;
        font-size: 16px;
        font-weight: 800;
        margin: 20px 0 10px;
    }

    .privacy-policy-section p,
    .privacy-policy-section li {
        color: #4c4c4c;
        font-size: 15px;
        line-height: 1.9;
    }

    .privacy-policy-section p {
        margin-bottom: 12px;
    }

    .privacy-policy-section ul {
        margin: 0;
        padding-left: 21px;
    }

    .privacy-policy-section li {
        margin-bottom: 8px;
    }

    .privacy-policy-contact {
        background: #f8f8f8;
        border-left: 4px solid #cc0000;
        margin-top: 16px;
        padding: 20px 22px;
    }

    .privacy-policy-contact p:last-child {
        margin-bottom: 0;
    }

    .privacy-policy-contact a {
        color: #cc0000;
        text-decoration: none;
    }

    @media (max-width: 575px) {
        .privacy-policy-page {
            padding: 42px 0 60px;
        }

        .privacy-policy-page > .container {
            max-width: none;
            padding-left: 18px;
            padding-right: 18px;
            width: 100%;
        }

        .privacy-policy-shell {
            border-left: 0;
            padding-left: 0;
        }

        .privacy-policy-header {
            margin-bottom: 30px;
            padding-bottom: 26px;
        }

        .privacy-policy-kicker {
            font-size: 10px;
            letter-spacing: 3px;
        }

        .privacy-policy-title {
            font-size: 36px;
        }

        .privacy-policy-section h2 {
            font-size: 21px;
        }
    }
</style>

<section class="privacy-policy-page">
    <div class="container">
        <article class="privacy-policy-shell">
            <header class="privacy-policy-header">
                <div class="privacy-policy-kicker">HOUSE OF KNP</div>
                <h1 class="privacy-policy-title">Privacy Policy</h1>
                <p class="privacy-policy-date">Last Updated: 26 June 2026</p>
            </header>

            <p class="privacy-policy-intro">
                At HOUSE OF KNP, we respect your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, store, and safeguard your data when you use our website and services.
            </p>
            <p class="privacy-policy-intro">
                By accessing or using our website, you agree to the practices described in this Privacy Policy.
            </p>

            <section class="privacy-policy-section">
                <h2>1. Information We Collect</h2>
                <p>We may collect the following information:</p>

                <h3>Personal Information</h3>
                <ul>
                    <li>Full name</li>
                    <li>Email address</li>
                    <li>Mobile number</li>
                    <li>Billing and shipping address</li>
                    <li>Payment details processed through secure third-party payment gateways</li>
                </ul>

                <h3>Order Information</h3>
                <ul>
                    <li>Products purchased</li>
                    <li>Order history</li>
                    <li>Transaction details</li>
                    <li>Customer support communications</li>
                </ul>

                <h3>Technical Information</h3>
                <ul>
                    <li>IP address</li>
                    <li>Device information</li>
                    <li>Browser type</li>
                    <li>Operating system</li>
                    <li>Website usage data</li>
                    <li>Cookies and analytics information</li>
                </ul>
            </section>

            <section class="privacy-policy-section">
                <h2>2. How We Use Your Information</h2>
                <p>Your information may be used to:</p>
                <ul>
                    <li>Process and deliver your orders.</li>
                    <li>Provide customer support.</li>
                    <li>Send order confirmations and shipping updates.</li>
                    <li>Improve our products, services, and website experience.</li>
                    <li>Conduct marketing and promotional activities with your consent.</li>
                    <li>Detect fraudulent or unauthorized activities.</li>
                    <li>Comply with legal and regulatory requirements.</li>
                </ul>
            </section>

            <section class="privacy-policy-section">
                <h2>3. Payment Security</h2>
                <p>HOUSE OF KNP does not store your complete debit card, credit card, UPI, or banking credentials.</p>
                <p>All payments are processed through secure third-party payment service providers that comply with applicable security standards.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>4. Sharing of Information</h2>
                <p>We do not sell, rent, or trade your personal information.</p>
                <p>We may share information only with:</p>
                <ul>
                    <li>Payment gateway providers.</li>
                    <li>Courier and logistics partners.</li>
                    <li>Technology and website service providers.</li>
                    <li>Government or legal authorities when required by law.</li>
                </ul>
                <p style="margin-top: 14px;">All third parties are expected to maintain the confidentiality and security of customer information.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>5. Cookies and Tracking Technologies</h2>
                <p>Our website may use cookies and similar technologies to:</p>
                <ul>
                    <li>Remember customer preferences.</li>
                    <li>Analyze website traffic.</li>
                    <li>Improve user experience.</li>
                    <li>Deliver personalized marketing communications.</li>
                </ul>
                <p style="margin-top: 14px;">Users may disable cookies through their browser settings, although some website features may not function properly.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>6. Data Security</h2>
                <p>We implement reasonable technical, administrative, and organizational measures to protect customer information against:</p>
                <ul>
                    <li>Unauthorized access</li>
                    <li>Misuse</li>
                    <li>Disclosure</li>
                    <li>Alteration</li>
                    <li>Loss or destruction</li>
                </ul>
                <p style="margin-top: 14px;">However, no method of electronic transmission or storage can guarantee absolute security.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>7. Marketing Communications</h2>
                <p>Customers may receive promotional messages, newsletters, product announcements, or special offers if they choose to subscribe.</p>
                <p>Customers can opt out of marketing communications at any time through the unsubscribe option or by contacting our support team.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>8. Customer Rights</h2>
                <p>Customers may request to:</p>
                <ul>
                    <li>Access their personal information.</li>
                    <li>Correct inaccurate information.</li>
                    <li>Update account details.</li>
                    <li>Delete their account where legally permissible.</li>
                    <li>Withdraw consent for marketing communications.</li>
                </ul>
                <p style="margin-top: 14px;">Certain information may be retained as required for taxation, legal compliance, fraud prevention, or business records.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>9. Children's Privacy</h2>
                <p>Our products and services are intended for individuals aged 18 years and above.</p>
                <p>We do not knowingly collect personal information from children under the age of 18.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>10. Third-Party Links</h2>
                <p>Our website may contain links to third-party websites, social media platforms, or external services.</p>
                <p>HOUSE OF KNP is not responsible for the privacy practices or content of such third-party platforms.</p>
                <p>Customers are encouraged to review their respective privacy policies.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>11. Changes to This Policy</h2>
                <p>We reserve the right to modify this Privacy Policy at any time.</p>
                <p>Updated versions will be published on our website, and continued use of our services constitutes acceptance of the revised policy.</p>
            </section>

            <section class="privacy-policy-section">
                <h2>12. Contact Us</h2>
                <p>For questions, concerns, or requests regarding this Privacy Policy, please contact:</p>
                <div class="privacy-policy-contact">
                    <p><strong>HOUSE OF KNP</strong></p>
                    <p>Email: <a href="mailto:houseofknp@gmail.com">houseofknp@gmail.com</a></p>
                    <p>Phone: <a href="tel:+916374390907">+91 6374390907</a></p>
                    <p>Address: 777, Seventh Street, Rampura, Dhaka, Bangladesh</p>
                </div>
            </section>
        </article>
    </div>
</section>
@endsection
