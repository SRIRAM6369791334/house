<style>
.knp-site-footer {
    background-color: #ffffff;
    color: #111111;
    font-family: 'Manrope', sans-serif;
    padding: 60px 0 20px;
    border-top: none;
}
.knp-site-footer h5 {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #111111 !important;
    margin-bottom: 20px;
}
.knp-site-footer ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.knp-site-footer ul li {
    margin-bottom: 12px;
    display: block !important;
}
.knp-site-footer ul li a {
    color: #111111 !important;
    text-decoration: none;
    font-size: 13px;
    transition: 0.2s ease;
    display: block !important;
}
.knp-site-footer ul li a:hover {
    color: #000000 !important;
}
.footer-socials {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 25px;
}
.footer-socials .footer-social-link {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #fff;
    color: #CC0000;
    font-size: 20px;
    text-decoration: none;
    transition: background-color .2s, color .2s;
}
.instagram-icon {
    width: 22px;
    height: 22px;
    display: block;
}
.footer-socials .footer-social-link:hover,
.footer-socials .footer-social-link:focus-visible {
    background: #CC0000;
    color: #fff !important;
}
.footer-border-left {
    border-left: 1px solid #d9d9d9;
    padding-left: 30px;
}
@media (max-width: 991px) {
    .footer-border-left {
        border-left: none;
        padding-left: 15px;
        margin-top: 30px;
    }
    .knp-site-footer {
        padding: 40px 0 20px;
    }
}
.nl-form-container {
    position: relative;
    max-width: 300px;
}
.nl-form-container input {
    width: 100%;
    border: none;
    background-color: #111111;
    padding: 14px 50px 14px 18px;
    font-size: 13px;
    outline: none;
    border-radius: 2px;
    color: #333;
}
.nl-form-container input:focus {
    border-color: #ccc;
}
.nl-form-container button {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    background: #111111;
    color: #FFF;
    border: none;
    padding: 0 18px;
    border-radius: 0 2px 2px 0;
    cursor: pointer;
    transition: 0.3s;
}
.nl-form-container button:hover {
    background: #000000;
}
.footer-bottom-row {
    margin-top: 50px;
    padding-top: 20px;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    border-top: 1px solid #d9d9d9;
}
.footer-bottom-copyright {
    text-align: center;
}
.footer-bottom-copyright p {
    color: #555555;
    font-size: 12px;
    letter-spacing: 0.5px;
}
.footer-bottom-copyright a {
    color: #CC0000;
    font-size: 12px;
    font-weight: 700;
}
.footer-bottom-row p, .footer-bottom-row a {
    font-size: 12px;
    color: #111111;
    margin: 0;
    text-decoration: none;
}
.footer-bottom-row a:hover {
    color: #111111;
}
@media (max-width: 767.98px) {
    .knp-site-footer {
        text-align: center;
        padding: 36px 0 16px !important;
    }
    .knp-site-footer img {
        margin: 0 auto 10px !important;
        max-height: 52px !important;
        width: auto !important;
    }
    .knp-site-footer h5 {
        margin-top: 16px !important;
        margin-bottom: 12px !important;
        font-size: 11.5px !important;
    }
    .knp-site-footer ul li {
        margin-bottom: 9px !important;
    }
    .knp-site-footer ul li a {
        font-size: 12px !important;
    }
    .footer-socials {
        justify-content: center;
        margin-bottom: 18px !important;
    }
    .footer-border-left {
        padding-left: 0 !important;
        margin-top: 20px !important;
    }
    .footer-contact-list {
        display: grid;
        justify-content: center;
        max-width: 100%;
        gap: 10px !important;
    }
    .footer-contact-item {
        width: 100% !important;
        max-width: 280px !important;
        margin: 0 auto;
        justify-content: flex-start;
        align-items: center;
        text-align: left;
        font-size: 12px !important;
    }
    .footer-bottom-row {
        margin-top: 30px !important;
        justify-content: center;
        padding-top: 16px;
    }
    .footer-bottom-copyright {
        width: 100%;
    }
    .knp-site-footer .container-fluid {
        padding: 0 15px !important;
    }
}
.footer-contact-list {
    display: grid;
    gap: 14px;
}
.footer-contact-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #111111;
    font-size: 13px;
    line-height: 1.55;
}
.footer-contact-item i {
    width: 28px;
    height: 28px;
    flex: 0 0 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #111111;
    color: #ffffff;
}
.footer-contact-item a {
    color: #111111;
    text-decoration: none;
}
.footer-contact-item a:hover {
    text-decoration: underline;
}
.floating-contact-actions {
    position: fixed;
    right: 24px;
    bottom: 120px;
    z-index: 990;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.floating-contact-actions a {
    display: flex;
    width: 48px;
    height: 48px;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    box-shadow: 0 5px 16px rgba(0, 0, 0, .18);
    color: #fff;
    font-size: 23px;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
}
.floating-contact-actions a:hover,
.floating-contact-actions a:focus-visible {
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, .24);
}
.floating-contact-actions .floating-whatsapp {
    background: #25d366;
}
.floating-contact-actions .floating-contact {
    background: #b40016;
}
@media (max-width: 767px) {
    .floating-contact-actions {
        right: 16px;
        bottom: calc(24px + env(safe-area-inset-bottom));
    }
    .floating-contact-actions a {
        width: 44px;
        height: 44px;
        font-size: 21px;
    }
}</style>

@if(!request()->is('/'))
    @include('partials.trust-strip')
@endif

<footer class="knp-site-footer">
    <div class="container-fluid" style="max-width: 1400px; padding: 0 40px;">
        <div class="row">
            <!-- Brand Column -->
            <div class="col-lg-2 col-md-12 mb-4 mb-lg-0">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/logo/logoo.png') }}" alt="House of KNP"
                        style="height: 100px; object-fit: contain; margin-bottom: 12px; display: block;">
                </a>
                <div style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #111111; margin-bottom: 18px;">
                    IGNITE YOUR PRESENCE
                </div>
                <div class="footer-socials">
                    <a class="footer-social-link" href="https://www.instagram.com/houseofknp" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram"><svg class="instagram-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm0 2h10c1.66 0 3 1.34 3 3v10c0 1.66-1.34 3-3 3H7c-1.66 0-3-1.34-3-3V7c0-1.66 1.34-3 3-3zm5 3.5A4.5 4.5 0 1 0 12 16.5 4.5 4.5 0 0 0 12 7.5zm0 2A2.5 2.5 0 1 1 12 14.5 2.5 2.5 0 0 1 12 9.5zM17.25 6.5a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5z" fill="currentColor"/></svg></a>
                    <a class="footer-social-link" href="https://www.facebook.com/search/pages/?q=House%20of%20KNP" target="_blank" rel="noopener noreferrer" aria-label="Find House of KNP on Facebook" title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a class="footer-social-link" href="https://wa.me/916374390907" target="_blank" rel="noopener noreferrer" aria-label="Chat with House of KNP on WhatsApp" title="WhatsApp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                    <a class="footer-social-link" href="https://www.youtube.com/results?search_query=House+of+KNP" target="_blank" rel="noopener noreferrer" aria-label="Find House of KNP on YouTube" title="YouTube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                </div>
            </div>

            <!-- Collections -->
            <div class="col-lg-2 col-md-3 col-sm-6 mb-4 mb-lg-0 footer-border-left">
                <h5>Collections</h5>
                <ul>
                    @php
                        $footerCategories = \App\Models\Category::query()
                            ->whereNotNull('category_name')
                            ->where('category_name', '!=', '')
                            ->orderBy('id')
                            ->get(['id', 'category_name']);
                    @endphp
                    @foreach ($footerCategories as $fCat)
                        @php
                            $fSlug = \Illuminate\Support\Str::slug($fCat->category_name);
                            $fLink = ($fSlug === 'signature-box' || str_contains($fSlug, 'combo'))
                                ? url('combos')
                                : url('category/' . $fSlug);
                        @endphp
                        <li><a href="{{ $fLink }}">{{ $fCat->category_name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Customer Care -->
            <div class="col-lg-3 col-md-3 col-sm-6 mb-4 mb-lg-0 footer-border-left">
                <h5>Customer Care</h5>
                <ul>
                    <li><a href="{{ url('shipping-policy') }}">Shipping & Delivery</a></li>
                    <li><a href="{{ url('exchange-policy') }}">Returns & Exchange</a></li>
                    <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('terms-condition') }}">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div class="col-lg-2 col-md-3 col-sm-6 mb-4 mb-lg-0 footer-border-left">
                <h5>Company</h5>
                <ul>
                    <li><a href="{{ url('about') }}">About Us</a></li>
                    <li><a href="{{ url('bulk-order') }}">Corporate &amp; Bulk Orders</a></li>
                    <li><a href="{{ url('blog') }}">Journal</a></li>
                    <li><a href="{{ url('contact') }}">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact Address -->
            <div class="col-lg-3 col-md-3 col-sm-6 mb-4 mb-lg-0 footer-border-left">
                <h5>Our Addresses</h5>
                <div class="footer-contact-list">
                    <div class="footer-contact-item">
                        <i class="fa fa-phone"></i>
                        <a href="tel:+916374390907">+91 6374390907</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:houseofknp@gmail.com">houseofknp@gmail.com</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa fa-map-marker"></i>
                        <span>61/1, Palakkukara Street, Kamuthi, Ramanathapuram District - 623603</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="footer-bottom-row">
            <div class="footer-bottom-copyright copyright">
                <p>
                    Copyright &copy; HOUSE OF KNP All rights reserved {{ date('Y') }}. Developed by
                    <a href="https://saitechnosolutions.com/" target="_blank" rel="noopener">Sai Techno Solutions</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<nav class="floating-contact-actions" aria-label="Quick contact">
    <a class="floating-whatsapp" href="https://wa.me/916374390907" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp" title="Chat on WhatsApp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
    <a class="floating-contact" href="tel:+916374390907" aria-label="Call House of KNP" title="Call House of KNP"><i class="fa fa-phone" aria-hidden="true"></i></a>
</nav>
