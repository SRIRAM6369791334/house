@extends('layouts.app')

@section('content')
<style>
    .watch-policy-page { background: #fff; padding: 64px 0 80px; }
    .watch-policy-shell { border-left: 1px solid #e5e5e5; max-width: 1040px; padding-left: 60px; }
    .watch-policy-header { border-bottom: 1px solid #eee8df; margin-bottom: 38px; padding-bottom: 34px; }
    .watch-policy-kicker { color: #cc0000; font-size: 12px; font-weight: 800; letter-spacing: 5px; margin-bottom: 20px; text-transform: uppercase; }
    .watch-policy-title { color: #111; font-size: 52px; line-height: 1.15; margin: 0 0 14px; }
    .watch-policy-date { color: #6f747b; font-size: 15px; font-weight: 700; margin: 0; }
    .watch-policy-intro { border-left: 4px solid #cc0000; color: #4c4c4c; font-size: 15px; line-height: 1.9; margin: 0; padding-left: 20px; }
    .watch-policy-section { border-top: 1px solid #eee8df; margin-top: 28px; padding-top: 28px; }
    .watch-policy-section h2 { color: #111; font-size: 23px; margin-bottom: 14px; }
    .watch-policy-section p, .watch-policy-section li { color: #4c4c4c; font-size: 15px; line-height: 1.9; }
    .watch-policy-section p { margin-bottom: 12px; }
    .watch-policy-section ul { margin: 0; padding-left: 21px; }
    .watch-policy-section li { margin-bottom: 8px; }
    .watch-policy-notice { background: #f8f8f8; border-left: 4px solid #cc0000; padding: 20px 22px; }
    .watch-policy-back { color: #cc0000; display: inline-block; font-weight: 800; margin-top: 34px; text-decoration: none; }

    @media (max-width: 575px) {
        .watch-policy-page { padding: 42px 0 60px; }
        .watch-policy-page > .container { max-width: none; padding-left: 18px; padding-right: 18px; width: 100%; }
        .watch-policy-shell { border-left: 0; padding-left: 0; }
        .watch-policy-header { margin-bottom: 30px; padding-bottom: 26px; }
        .watch-policy-kicker { font-size: 10px; letter-spacing: 3px; }
        .watch-policy-title { font-size: 34px; }
        .watch-policy-section h2 { font-size: 21px; }
    }
</style>

<section class="watch-policy-page">
    <div class="container">
        <article class="watch-policy-shell">
            <header class="watch-policy-header">
                <div class="watch-policy-kicker">HOUSE OF KNP</div>
                <h1 class="watch-policy-title">Automatic Watch Exchange Policy</h1>
                <p class="watch-policy-date">Last Updated: 26 June 2026</p>
            </header>

            <p class="watch-policy-intro">
                At HOUSE OF KNP, every curated automatic watch undergoes quality inspection before dispatch. Due to the mechanical nature of these products, we follow an <strong>Exchange-Only Policy</strong> and do not offer returns or refunds.
            </p>

            <section class="watch-policy-section">
                <h2>Exchange Window</h2>
                <p>Customers must raise an exchange request within <strong>3 calendar days</strong> from the date of delivery.</p>
                <p>Requests received after this period will not be eligible for exchange.</p>
            </section>

            <section class="watch-policy-section">
                <h2>Eligible Exchange Conditions</h2>
                <p>An exchange may be approved only if:</p>
                <ul>
                    <li>The watch received is damaged during transit.</li>
                    <li>A manufacturing defect is identified upon delivery.</li>
                    <li>An incorrect model or product was shipped.</li>
                    <li>The watch is completely non-functional despite following the provided operating instructions.</li>
                </ul>
            </section>

            <section class="watch-policy-section">
                <h2>Non-Eligible Conditions</h2>
                <p>Exchange requests will not be accepted for:</p>
                <ul>
                    <li>Minor variations in timekeeping that fall within normal mechanical tolerances.</li>
                    <li>Damage caused by impact, water exposure, improper handling, or unauthorized repairs.</li>
                    <li>Scratches, dents, or signs of wear after use.</li>
                    <li>Issues arising from failure to manually wind or regularly wear the watch.</li>
                    <li>Change of mind, personal preference, or dissatisfaction with design, size, or appearance.</li>
                </ul>
            </section>

            <section class="watch-policy-section">
                <h2>Inspection Process</h2>
                <p>All exchange requests must include:</p>
                <ul>
                    <li>An unboxing video recorded from the moment the package is opened.</li>
                    <li>Clear photographs showing the issue.</li>
                    <li>The original packaging, tags, manuals, and accessories.</li>
                </ul>
                <p style="margin-top: 14px;">The company reserves the right to inspect the returned product before approving any exchange.</p>
            </section>

            <section class="watch-policy-section">
                <h2>Important Notice About Automatic Watches</h2>
                <div class="watch-policy-notice">
                    <p>Automatic watches are mechanical timepieces that operate through movement and may require manual winding before first use. Customers are advised to:</p>
                    <ul>
                        <li>Wind the watch 15–20 times before wearing it for the first time.</li>
                        <li>Wear the watch regularly to maintain power reserve.</li>
                        <li>Keep the watch away from strong magnetic fields and excessive moisture.</li>
                    </ul>
                    <p style="margin-top: 14px; margin-bottom: 0;">Mechanical variations in accuracy are normal and do not constitute manufacturing defects.</p>
                </div>
            </section>

            <section class="watch-policy-section">
                <h2>Final Decision</h2>
                <p>HOUSE OF KNP reserves the right to determine the eligibility of all exchange requests.</p>
                <p>The company's decision regarding exchanges shall be final and binding.</p>
            </section>

            <a class="watch-policy-back" href="{{ url('exchange-policy') }}"><i class="fa fa-arrow-left"></i> Back to Exchange Policy</a>
        </article>
    </div>
</section>
@endsection
