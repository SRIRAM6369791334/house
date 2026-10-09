@extends('layouts.app')

@php
    $subtotal = $cartProducts->sum(fn ($product) => house_product_price($product) * ($product->cart_qty ?? 1));
    $shipping = 0;
    $gst = house_cart_included_gst($cartProducts);
    $coupon = $coupon ?? null;
    $discount = house_coupon_discount($coupon, $subtotal);
    $total = max(0, $subtotal + $shipping - $discount);
    $comboProducts = $cartProducts->where('cart_source', 'combo')->values();
    $regularCartProducts = $cartProducts->where('cart_source', '!=', 'combo')->values();
    $checkoutCombo = $comboProducts->isNotEmpty()
        ? \App\Models\GiftCombo::query()->where('id', $comboProducts->first()->cart_combo_id)->first()
        : null;
    $comboImage = $checkoutCombo && !empty($checkoutCombo->combo_image)
        ? rtrim((string) env('MAIN_URL'), '/') . '/storage/' . ltrim($checkoutCombo->combo_image, '/')
        : asset('images/gift.jpeg');
    $comboTotal = $comboProducts->sum(fn ($product) => house_product_price($product) * ($product->cart_qty ?? 1));
    $defaultCheckoutAddress = $defaultCheckoutAddress ?? [];
    $states = [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Delhi', 'Goa', 'Gujarat',
        'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra',
        'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim',
        'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
    ];
@endphp

@section('content')
    <style>
        .checkout-area {
            background: #fff;
            color: #030712;
        }
        .checkout-shell {
            display: grid;
            gap: 38px;
            grid-template-columns: minmax(0, 1.4fr) minmax(360px, 0.9fr);
        }
        .checkout-heading {
            font-family: 'Open Sans', sans-serif;
            font-size: 22px;
            font-weight: 900;
            line-height: 1.2;
            margin: 0 0 20px;
        }
        .checkout-card {
            background: #fff;
            border: 1px solid #d7e0ef;
            border-radius: 14px;
            margin-bottom: 28px;
            padding: 34px 36px;
        }
        .checkout-card.compact {
            padding: 30px 36px;
        }
        .checkout-card h3,
        .checkout-card h4 {
            font-family: 'Open Sans', sans-serif;
            font-weight: 900;
            margin: 0;
        }
        .checkout-login-text {
            color: #60708a;
            font-size: 18px;
            line-height: 1.6;
            margin: 10px 0 14px;
        }
        .checkout-login-toggle {
            background: #f5e9ff;
            border: 0;
            border-radius: 999px;
            color: #170035;
            cursor: pointer;
            font-weight: 900;
            padding: 8px 17px;
        }
        .checkout-login-form {
            display: none;
            margin-top: 22px;
        }
        .checkout-login-form.active {
            display: block;
        }
        .checkout-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .checkout-grid .wide {
            grid-column: 1 / -1;
        }
        .checkout-field {
            margin-bottom: 0;
            min-width: 0;
        }
        .checkout-field label {
            display: block;
            font-weight: 900;
            margin-bottom: 12px;
        }
        .checkout-required {
            color: #e11d48;
        }
        .checkout-field input,
        .checkout-field select,
        .checkout-field textarea,
        .coupon-form input {
            background: #fff;
            border: 1px solid #cbd7e8;
            border-radius: 12px;
            color: #030712;
            font-size: 16px;
            min-height: 54px;
            padding: 14px 18px;
            width: 100%;
        }
        .checkout-field textarea {
            min-height: 110px;
            resize: vertical;
        }
        .checkout-field input:focus,
        .checkout-field select:focus,
        .checkout-field textarea:focus,
        .coupon-form input:focus {
            border-color: #b423a9;
            box-shadow: 0 0 0 3px rgba(180, 35, 169, 0.12);
            outline: none;
        }
        .password-control {
            position: relative;
        }
        .password-control input {
            padding-right: 58px;
        }
        .password-toggle {
            align-items: center;
            background: transparent;
            border: 0;
            color: #2b005a;
            cursor: pointer;
            display: flex;
            font-size: 20px;
            height: 100%;
            justify-content: center;
            min-height: 64px;
            padding: 0;
            position: absolute;
            right: 14px;
            top: 0;
            width: 34px;
        }
        .password-toggle:focus {
            outline: none;
        }
        .field-error {
            color: #dc2626;
            display: block;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.4;
            margin-top: 8px;
        }
        .checkout-divider {
            border-top: 1px solid #e5e7eb;
            margin: 40px 0 24px;
        }
        .checkout-subtitle {
            font-size: 21px;
            margin-bottom: 18px;
        }
        .checkout-checkbox,
        .payment-option {
            align-items: center;
            display: flex;
            gap: 13px;
            font-size: 17px;
            font-weight: 900;
            margin: 0 0 18px;
        }
        .checkout-checkbox input,
        .payment-option input {
            accent-color: #c026a9;
            height: 22px;
            margin: 0;
            width: 22px;
        }
        .billing-fields {
            display: none;
            margin-top: 22px;
        }
        .billing-fields.active {
            display: block;
        }
        .checkout-btn {
            background: #000;
            border: 0;
            border-radius: 0;
            color: #fff !important;
            cursor: pointer;
            display: inline-flex;
            font-weight: 900;
            justify-content: center;
            min-height: 52px;
            padding: 14px 22px;
            text-align: center;
        }
        .checkout-btn.pill {
            background: #f5e9ff;
            border-radius: 999px;
            color: #170035 !important;
            min-height: auto;
            padding: 8px 17px;
        }
        .checkout-btn.full {
            border-radius: 10px;
            font-size: 20px;
            width: 100%;
        }
        .checkout-summary-wrap {
            position: sticky;
            top: 92px;
        }
        .checkout-summary {
            border: 1px solid #d7e0ef;
            border-radius: 34px;
            padding: 34px 36px;
        }
        .summary-item {
            align-items: flex-start;
            border-bottom: 1px solid #e5e7eb;
            display: grid;
            gap: 18px;
            grid-template-columns: 76px minmax(0, 1fr) auto;
            padding-bottom: 24px;
        }
        .summary-item img {
            border-radius: 8px;
            height: 76px;
            object-fit: cover;
            width: 76px;
        }
        .summary-item-title {
            font-size: 18px;
            font-weight: 900;
            line-height: 1.45;
            margin: 2px 0 6px;
            text-transform: uppercase;
        }
        .summary-item-meta {
            color: #c026a9;
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 8px;
        }
        .summary-item-price {
            font-size: 20px;
            font-weight: 900;
            white-space: nowrap;
        }
        .checkout-combo-card { border: 1px solid #e3d8e7; border-radius: 18px; margin-bottom: 24px; overflow: hidden; }
        .checkout-combo-head { align-items: center; background: #faf6fb; display: grid; gap: 16px; grid-template-columns: 86px minmax(0, 1fr) auto; padding: 18px; }
        .checkout-combo-head img { border-radius: 12px; height: 86px; object-fit: cover; width: 86px; }
        .checkout-combo-label { color: #c026a9; font-size: 11px; font-weight: 900; letter-spacing: 1.2px; margin-bottom: 5px; text-transform: uppercase; }
        .checkout-combo-title { font-size: 19px; font-weight: 900; line-height: 1.3; text-transform: uppercase; }
        .checkout-combo-total { font-size: 20px; font-weight: 900; white-space: nowrap; }
        .checkout-combo-products { padding: 4px 18px; }
        .checkout-combo-product { align-items: center; border-bottom: 1px solid #ece7ee; display: grid; gap: 14px; grid-template-columns: 58px minmax(0, 1fr); padding: 14px 0; }
        .checkout-combo-product:last-child { border-bottom: 0; }
        .checkout-combo-product img { border-radius: 8px; height: 58px; object-fit: cover; width: 58px; }
        .checkout-combo-product-name { font-size: 14px; font-weight: 900; line-height: 1.35; text-transform: uppercase; }
        .checkout-combo-product-size { color: #c026a9; font-size: 12px; font-weight: 800; margin-top: 5px; }
        @media (max-width: 575px) {
            .checkout-combo-head { grid-template-columns: 70px minmax(0, 1fr); }
            .checkout-combo-head img { height: 70px; width: 70px; }
            .checkout-combo-total { grid-column: 2; }
        }
        .summary-row {
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            font-size: 20px;
            justify-content: space-between;
            padding: 18px 0;
        }
        .summary-row.discount span:last-child {
            color: #ef4444;
        }
        .shipping-quote-message {
            color: #16a34a;
            display: none;
            font-size: 14px;
            font-weight: 800;
            margin-top: 8px;
        }
        .shipping-quote-message.error {
            color: #dc2626;
        }
        .summary-total {
            border-bottom: 0;
            font-size: 20px;
            padding-top: 26px;
        }
        .summary-total strong:last-child {
            font-size: 30px;
        }
        .coupon-title,
        .payment-title {
            font-size: 20px;
            font-weight: 900;
            margin: 22px 0 14px;
        }
        .coupon-form {
            display: flex;
            overflow: hidden;
        }
        .coupon-form input {
            border-bottom-right-radius: 0;
            border-right: 0;
            border-top-right-radius: 0;
        }
        .coupon-form button {
            border-bottom-right-radius: 10px;
            border-top-right-radius: 10px;
            min-width: 132px;
        }
        .coupon-feedback {
            border-radius: 10px;
            display: block;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.4;
            margin-top: 10px;
            padding: 10px 12px;
        }
        .coupon-feedback.success {
            background: #edf8ee;
            border: 1px solid #bfe3c4;
            color: #166534;
        }
        .coupon-feedback.error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
        }
        .checkout-alert {
            border: 1px solid transparent;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 12px 15px;
        }
        .checkout-alert.success {
            background: #edf8ee;
            border-color: #bfe3c4;
            color: #226b2d;
        }
        .checkout-alert.error {
            background: #fff0f0;
            border-color: #f0c0c0;
            color: #9b0000;
        }
        .checkout-empty {
            border: 1px solid #d7e0ef;
            border-radius: 14px;
            padding: 45px 30px;
            text-align: center;
        }
        @media (max-width: 1199px) {
            .checkout-shell {
                grid-template-columns: 1fr;
            }
            .checkout-summary-wrap {
                position: static;
            }
        }
        @media (max-width: 767px) {
            .checkout-area {
                padding-top: 45px;
                padding-bottom: 45px;
            }
            .checkout-card,
            .checkout-card.compact,
            .checkout-summary {
                border-radius: 14px;
                padding: 24px 18px;
            }
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            .summary-item {
                grid-template-columns: 64px minmax(0, 1fr);
            }
            .summary-item-price {
                grid-column: 2;
            }
            .coupon-form {
                display: block;
            }
            .coupon-form input,
            .coupon-form button {
                border-radius: 10px;
                width: 100%;
            }
            .coupon-form button {
                margin-top: 10px;
            }
        }
    </style>

    <div class="about-shared-banner breadcumb-area breadcumb-3 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center">
                <h5>Checkout</h5>
            </div>
            <ol class="breadcrumb">
                <li class="home"><a title="Go to Home Page" href="/"style="color: #fff;">Home</a></li>
                <li class="home"><a title="Go to Cart Page" href="{{ url('cart') }}">Cart</a></li>
                <li class="active">Checkout</li>
            </ol>
        </div>
    </div>

    <div class="checkout-area ptb-80">
        <div class="container">
            @if (session('success'))
                <div class="checkout-alert success">{{ session('success') }}</div>
            @endif
            @if($cartProducts->isEmpty())
                <div class="checkout-empty">
                    <h3>Your cart is empty.</h3>
                    <p>Add products to your cart before continuing to checkout.</p>
                    <a class="btn-def btn2" href="{{ url('shop') }}">Continue Shopping</a>
                </div>
            @else
                <form id="checkoutPlaceForm" action="{{ route('checkout.place') }}" method="POST" novalidate>
                    @csrf
                    <div class="checkout-shell">
                        <div>
                            <h2 class="checkout-heading">Your Information &amp; Shipping Address</h2>
                            @guest
                                <p style="color:#60708a; font-size:12px; margin:-10px 0 18px;">Your guest checkout details are remembered on this device for next time.</p>
                            @endguest
                            @auth
                                @if(($savedCheckoutAddresses ?? collect())->isNotEmpty())
                                    <div class="checkout-card compact" style="margin-bottom: 20px;">
                                        <div class="checkout-field wide">
                                            <label for="saved_checkout_address">Select Saved Address</label>
                                            <select id="saved_checkout_address">
                                                @foreach($savedCheckoutAddresses as $savedAddress)
                                                    <option value="{{ $savedAddress['id'] }}" {{ $savedAddress['is_default'] ? 'selected' : '' }}>
                                                        {{ $savedAddress['is_default'] ? 'Default - ' : '' }}{{ $savedAddress['label'] }} â€” {{ $savedAddress['door_no'] }}, {{ $savedAddress['city'] }} - {{ $savedAddress['pincode'] }}
                                                    </option>
                                                @endforeach
                                                <option value="custom">Use a different address</option>
                                            </select>
                                            <small style="display:block;margin-top:8px;color:#777;">Choose an address or edit the fields below for this order.</small>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                            <div class="checkout-card">
                                <div class="checkout-grid">
                                    <div class="checkout-field wide">
                                        <label for="customer_name">Full Name <span class="checkout-required">*</span></label>
                                        <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', old('billing_first_name', $defaultCheckoutAddress['name'] ?? auth()->user()->name ?? '')) }}" required>
                                        @error('billing_first_name') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="checkout-field">
                                        <label for="customer_email">Email Address <span class="checkout-required">*</span></label>
                                        <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', old('billing_email', auth()->user()->email ?? '')) }}" required>
                                        @error('billing_email') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="checkout-field">
                                        <label for="customer_phone">Phone Number <span class="checkout-required">*</span></label>
                                        <input id="customer_phone" name="customer_phone" type="tel" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" value="{{ old('customer_phone', old('billing_phone', $defaultCheckoutAddress['phone'] ?? auth()->user()->phone ?? auth()->user()->phone_number ?? '')) }}" required>
                                        @error('billing_phone') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="checkout-field">
                                        <label for="shipping_country">Country <span class="checkout-required">*</span></label>
                                        <select id="shipping_country" name="shipping_country" required>
                                            <option value="India" selected>India</option>
                                        </select>
                                    </div>
                                    <div class="checkout-field">
                                        <label for="shipping_door_no">Door No/Flat No <span class="checkout-required">*</span></label>
                                        <input id="shipping_door_no" name="shipping_door_no" type="text" value="{{ old('shipping_door_no', $defaultCheckoutAddress['door_no'] ?? '') }}" placeholder="e.g. 42A" required>
                                        @error('billing_address') <span class="field-error">{{ $message }}</span> @enderror
                                        @error('shipping_address') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="checkout-field">
                                        <label for="shipping_street">Street Name <span class="checkout-required">*</span></label>
                                        <input id="shipping_street" name="shipping_street" type="text" value="{{ old('shipping_street', $defaultCheckoutAddress['street'] ?? '') }}" placeholder="e.g. MG Road" required>
                                    </div>
                                    <div class="checkout-field">
                                        <label for="shipping_area">Area/Landmark <span class="checkout-required">*</span></label>
                                        <input id="shipping_area" name="shipping_area" type="text" value="{{ old('shipping_area', $defaultCheckoutAddress['area'] ?? '') }}" placeholder="Near Central Park" required>
                                    </div>
                                    <div class="checkout-field">
                                        <label for="shipping_state">State <span class="checkout-required">*</span></label>
                                        <select id="shipping_state" name="shipping_state" required>
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state }}" {{ strcasecmp(old('shipping_state', $defaultCheckoutAddress['state'] ?? ''), $state) === 0 ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                        @error('billing_state') <span class="field-error">{{ $message }}</span> @enderror
                                        @error('shipping_state') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="checkout-field">
                                        <label for="shipping_city">City <span class="checkout-required">*</span></label>
                                        <input id="shipping_city" name="shipping_city" type="text" value="{{ old('shipping_city', $defaultCheckoutAddress['city'] ?? '') }}" placeholder="Enter City" required>
                                        @error('billing_city') <span class="field-error">{{ $message }}</span> @enderror
                                        @error('shipping_city') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="checkout-field">
                                        <label for="shipping_postcode">Pincode <span class="checkout-required">*</span></label>
                                        <input id="shipping_postcode" name="shipping_postcode" type="text" inputmode="numeric" pattern="[0-9]{6}" value="{{ old('shipping_postcode', $defaultCheckoutAddress['pincode'] ?? '') }}" placeholder="Enter 6-digit Pincode" maxlength="6" required>
                                        @error('billing_postcode') <span class="field-error">{{ $message }}</span> @enderror
                                        @error('shipping_postcode') <span class="field-error">{{ $message }}</span> @enderror
                                        <span id="shippingQuoteMessage" class="shipping-quote-message"></span>
                                    </div>
                                </div>

                            </div>

                            <h2 class="checkout-heading">Billing Address</h2>
                            <div class="checkout-card compact">
                                <label class="checkout-checkbox">
                                    <input type="radio" name="billing_mode" value="same" {{ old('billing_mode', 'same') !== 'different' ? 'checked' : '' }}>
                                    Same as Shipping Address
                                </label>
                                <label class="checkout-checkbox">
                                    <input type="radio" name="billing_mode" value="different" {{ old('billing_mode') === 'different' ? 'checked' : '' }}>
                                    Different Billing Address
                                </label>

                                <div id="billingFields" class="billing-fields {{ old('billing_mode') === 'different' ? 'active' : '' }}">
                                    <div class="checkout-grid">
                                        <div class="checkout-field wide">
                                            <label for="billing_name">Billing Name <span class="checkout-required">*</span></label>
                                            <input id="billing_name" name="billing_name" type="text" value="{{ old('billing_name') }}">
                                        </div>
                                        <div class="checkout-field">
                                            <label for="billing_email">Billing Email <span class="checkout-required">*</span></label>
                                            <input id="billing_email" name="billing_email" type="email" value="{{ old('billing_email') }}">
                                        </div>
                                        <div class="checkout-field">
                                            <label for="billing_phone">Billing Phone <span class="checkout-required">*</span></label>
                                            <input id="billing_phone" name="billing_phone" type="tel" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" value="{{ old('billing_phone') }}">
                                            @error('billing_phone') <span class="field-error">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="checkout-field">
                                            <label for="billing_door_no">Door No/Flat No <span class="checkout-required">*</span></label>
                                            <input id="billing_door_no" name="billing_door_no" type="text" value="{{ old('billing_door_no') }}">
                                        </div>
                                        <div class="checkout-field">
                                            <label for="billing_street">Street Name <span class="checkout-required">*</span></label>
                                            <input id="billing_street" name="billing_street" type="text" value="{{ old('billing_street') }}">
                                        </div>
                                        <div class="checkout-field">
                                            <label for="billing_area">Area/Landmark <span class="checkout-required">*</span></label>
                                            <input id="billing_area" name="billing_area" type="text" value="{{ old('billing_area') }}">
                                        </div>
                                        <div class="checkout-field">
                                            <label for="billing_state">State <span class="checkout-required">*</span></label>
                                            <select id="billing_state" name="billing_state">
                                                <option value="">Select State</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state }}" {{ old('billing_state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="checkout-field">
                                            <label for="billing_city">City <span class="checkout-required">*</span></label>
                                            <input id="billing_city" name="billing_city" type="text" value="{{ old('billing_city') }}">
                                        </div>
                                        <div class="checkout-field">
                                            <label for="billing_postcode">Pincode <span class="checkout-required">*</span></label>
                                            <input id="billing_postcode" name="billing_postcode" type="text" inputmode="numeric" pattern="[0-9]{6}" value="{{ old('billing_postcode') }}" maxlength="6">
                                            @error('billing_postcode') <span class="field-error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="checkout-heading">Your Order</h2>
                            <div class="checkout-summary-wrap">
                                <div class="checkout-summary">
                                    @if($comboProducts->isNotEmpty())
                                        <div class="checkout-combo-card">
                                            <div class="checkout-combo-head">
                                                <img src="{{ $comboImage }}" alt="{{ $checkoutCombo->combo_name ?? 'Gift Combo Box' }}">
                                                <div>
                                                    <div class="checkout-combo-label">Gift Combo Box</div>
                                                    <div class="checkout-combo-title">{{ $checkoutCombo->combo_name ?? 'House of KNP Combo' }}</div>
                                                </div>
                                                <div class="checkout-combo-total">{!! house_money($comboTotal) !!}</div>
                                            </div>
                                            <div class="checkout-combo-products">
                                                @foreach($comboProducts as $product)
                                                    @php $comboProductImages = house_product_images($product); @endphp
                                                    <div class="checkout-combo-product">
                                                        <img src="{{ $comboProductImages[0] }}" alt="{{ $product->product_name }}">
                                                        <div>
                                                            <div class="checkout-combo-product-name">{{ $product->product_name }}</div>
                                                            @if($product->cart_variant_size ?? null)
                                                                @php
                                                                    $comboCategory = strtolower((string) ($product->category_name ?? $product->cate_name ?? ''));
                                                                    $variantType = str_contains($comboCategory, 'watch') ? 'Model' : (str_contains($comboCategory, 'perfume') ? 'Volume' : 'Size');
                                                                @endphp
                                                                <div class="checkout-combo-product-size">{{ $variantType }}: {{ $product->cart_variant_size }}</div>
                                                            @endif
                                                            @if($product->cart_variant_color ?? null)
                                                                <div class="checkout-combo-product-size">Color: {{ $product->cart_variant_color }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @foreach($regularCartProducts as $product)
                                        @php
                                            $images = house_product_images($product);
                                            $price = house_product_price($product);
                                            $lineTotal = $price * ($product->cart_qty ?? 1);
                                        @endphp
                                        <div class="summary-item">
                                            <img src="{{ $images[0] }}" alt="{{ $product->product_name }}">
                                            <div>
                                                <div class="summary-item-title">{{ $product->product_name }}</div>
                                                <div class="summary-item-meta">{{ $product->subcate_name ?? $product->category_name ?? '' }}</div>
                                                @if($product->cart_variant_label ?? null)
                                                    <div class="summary-item-meta">{{ $product->cart_variant_label }}</div>
                                                @endif
                                                <div>x {{ ($product->cart_qty ?? 1) }}</div>
                                            </div>
                                            <div class="summary-item-price">{!! house_money($lineTotal) !!}</div>
                                        </div>
                                    @endforeach

                                    <div class="summary-row">
                                        <span>Subtotal</span>
                                        <span>{!! house_money($subtotal) !!}</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Included GST</span>
                                        <span>{!! house_money($gst) !!}</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Shipping</span>
                                        <span id="checkoutShippingAmount">{!! house_money($shipping) !!}</span>
                                    </div>
                                    <div class="summary-row discount">
                                        <span>Coupon Discount</span>
                                        <span>-{!! house_money($discount) !!}</span>
                                    </div>
                                    <div class="summary-row summary-total">
                                        <strong>Total</strong>
                                        <strong id="checkoutTotalAmount">{!! house_money($total) !!}</strong>
                                    </div>

                                    <div class="coupon-title">Have a Coupon?</div>
                                    @if(session('coupon_success'))
                                        <span class="coupon-feedback success">{{ session('coupon_success') }}</span>
                                    @endif
                                    @error('coupon_code') <span class="coupon-feedback error">{{ $message }}</span> @enderror
                                    <div class="coupon-form">
                                        <input form="couponApplyForm" name="coupon_code" type="text" value="{{ old('coupon_code') }}" placeholder="Enter coupon code">
                                        <button form="couponApplyForm" type="submit" class="checkout-btn">Apply</button>
                                    </div>
                                    <div class="payment-title">Payment Method</div>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="card" {{ old('payment_method', 'card') === 'card' ? 'checked' : '' }}>
                                        Online Payment
                                    </label>
                                    <button id="checkoutPayButton" type="submit" class="checkout-btn full">Pay &amp; Place Order</button>
                                    @error('payment') <span class="field-error">{{ $message }}</span> @enderror
                                    @error('shipping') <span class="field-error">{{ $message }}</span> @enderror
                                    <span id="checkoutPaymentError" class="field-error" style="display: none;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form id="couponApplyForm" action="{{ route('checkout.coupon.apply') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <form id="couponRemoveForm" action="{{ route('checkout.coupon.remove') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
        </div>
    </div>

    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkoutForm = document.getElementById('checkoutPlaceForm');
            const checkoutPayButton = document.getElementById('checkoutPayButton');
            const checkoutPaymentError = document.getElementById('checkoutPaymentError');
            const billingFields = document.getElementById('billingFields');
            const billingModeInputs = document.querySelectorAll('input[name="billing_mode"]');
            const savedAddressSelect = document.getElementById('saved_checkout_address');
            const savedAddresses = @json(($savedCheckoutAddresses ?? collect())->values());
            const shippingPostcodeInput = document.getElementById('shipping_postcode');
            const shippingQuoteMessage = document.getElementById('shippingQuoteMessage');
            const checkoutShippingAmount = document.getElementById('checkoutShippingAmount');
            const checkoutTotalAmount = document.getElementById('checkoutTotalAmount');
            const rememberGuestCheckout = @json(auth()->guest());
            const restoreGuestCheckout = @json(!session()->hasOldInput('customer_name') && !session()->hasOldInput('shipping_door_no'));
            const guestCheckoutStorageKey = 'houseofknp_guest_checkout_v1';
            const guestCheckoutFields = [
                'customer_name', 'customer_email', 'customer_phone',
                'shipping_door_no', 'shipping_street', 'shipping_area',
                'shipping_state', 'shipping_city', 'shipping_postcode',
                'billing_name', 'billing_email', 'billing_phone',
                'billing_door_no', 'billing_street', 'billing_area',
                'billing_state', 'billing_city', 'billing_postcode',
            ];
            let shippingQuoteTimer = null;

            function saveGuestCheckoutDetails() {
                if (!rememberGuestCheckout || !checkoutForm) return;
                const details = {};
                guestCheckoutFields.forEach((name) => {
                    const field = checkoutForm.elements.namedItem(name);
                    if (field) details[name] = field.value.trim();
                });
                details.billing_mode = checkoutForm.querySelector('input[name="billing_mode"]:checked')?.value || 'same';
                try {
                    localStorage.setItem(guestCheckoutStorageKey, JSON.stringify(details));
                } catch (error) {
                    // Checkout still works when browser storage is unavailable.
                }
            }

            function loadGuestCheckoutDetails() {
                if (!rememberGuestCheckout || !restoreGuestCheckout || !checkoutForm) return;
                let details;
                try {
                    details = JSON.parse(localStorage.getItem(guestCheckoutStorageKey) || 'null');
                } catch (error) {
                    return;
                }
                if (!details || typeof details !== 'object' || Array.isArray(details)) return;
                guestCheckoutFields.forEach((name) => {
                    const field = checkoutForm.elements.namedItem(name);
                    const value = details[name];
                    if (!field || typeof value !== 'string') return;
                    if (field.tagName === 'SELECT' && !Array.from(field.options).some((option) => option.value === value)) return;
                    field.value = value;
                });
                if (details.billing_mode === 'different') {
                    const differentBilling = checkoutForm.querySelector('input[name="billing_mode"][value="different"]');
                    if (differentBilling) differentBilling.checked = true;
                }
            }

            function money(amount) {
                return new Intl.NumberFormat('en-IN', {
                    style: 'currency',
                    currency: 'INR',
                    maximumFractionDigits: 2,
                }).format(Number(amount || 0));
            }

            function setShippingQuoteMessage(message, isError = false) {
                if (!shippingQuoteMessage) {
                    return;
                }

                shippingQuoteMessage.textContent = message;
                shippingQuoteMessage.classList.toggle('error', isError);
                shippingQuoteMessage.style.display = message ? 'block' : 'none';
            }

            async function refreshShippingQuote() {
                if (!shippingPostcodeInput || !checkoutShippingAmount || !checkoutTotalAmount) {
                    return;
                }

                const pincode = shippingPostcodeInput.value.replace(/\D+/g, '').slice(0, 6);
                shippingPostcodeInput.value = pincode;

                if (pincode.length !== 6) {
                    setShippingQuoteMessage('');
                    return;
                }

                setShippingQuoteMessage('Checking delivery charge...');

                try {
                    const paymentMethod = checkoutForm?.querySelector('input[name="payment_method"]:checked')?.value || 'card';
                    const response = await fetch(@json(route('checkout.shipping.quote')), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': checkoutForm.querySelector('input[name="_token"]').value,
                        },
                        body: JSON.stringify({
                            shipping_postcode: pincode,
                            payment_method: paymentMethod,
                        }),
                    });
                    const data = await response.json().catch(() => ({}));

                    if (!response.ok || !data.serviceable) {
                        throw new Error(data.message || 'Delivery is not available for this pincode.');
                    }

                    checkoutShippingAmount.textContent = money(data.shipping);
                    checkoutTotalAmount.textContent = money(data.total);
                    setShippingQuoteMessage(data.courier ? `Delivery available via ${data.courier}` : 'Delivery available.');
                } catch (error) {
                    checkoutShippingAmount.textContent = money(0);
                    setShippingQuoteMessage(error.message || 'Unable to check delivery for this pincode.', true);
                }
            }

            function fillShippingAddress(address) {
                const fieldValues = {
                    customer_name: address?.name || '',
                    customer_phone: address?.phone || '',
                    shipping_door_no: address?.door_no || '',
                    shipping_street: address?.street || '',
                    shipping_area: address?.area || '',
                    shipping_city: address?.city || '',
                    shipping_postcode: address?.pincode || '',
                };

                Object.entries(fieldValues).forEach(([id, value]) => {
                    const field = document.getElementById(id);
                    if (field) field.value = value;
                });

                const stateSelect = document.getElementById('shipping_state');
                if (stateSelect) {
                    const state = (address?.state || '').trim().toLowerCase();
                    const matchingOption = Array.from(stateSelect.options).find((option) => option.value.trim().toLowerCase() === state);
                    stateSelect.value = matchingOption ? matchingOption.value : '';
                }

                if (shippingPostcodeInput) {
                    shippingPostcodeInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }

            if (savedAddressSelect) {
                savedAddressSelect.addEventListener('change', function () {
                    if (this.value === 'custom') {
                        fillShippingAddress(null);
                        document.getElementById('shipping_door_no')?.focus();
                        return;
                    }

                    const address = savedAddresses.find((item) => String(item.id) === this.value);
                    if (address) fillShippingAddress(address);
                });
            }

            if (shippingPostcodeInput) {
                shippingPostcodeInput.addEventListener('input', function () {
                    clearTimeout(shippingQuoteTimer);
                    shippingQuoteTimer = setTimeout(refreshShippingQuote, 500);
                });

                if (shippingPostcodeInput.value.replace(/\D+/g, '').length === 6) {
                    refreshShippingQuote();
                }
            }

            function clearClientErrors(form) {
                form.querySelectorAll('.client-error').forEach((error) => error.remove());
            }

            function addClientError(input, message) {
                const field = input.closest('.checkout-field') || input.closest('.checkout-summary');

                if (!field) {
                    return;
                }

                const error = document.createElement('span');
                error.className = 'field-error client-error';
                error.textContent = message;
                field.appendChild(error);
            }

            function labelText(input) {
                const label = document.querySelector(`label[for="${input.id}"]`);
                return label ? label.textContent.replace('*', '').trim().toLowerCase() : 'this field';
            }

            if (checkoutForm) {
                if (rememberGuestCheckout) {
                    loadGuestCheckoutDetails();
                    let guestSaveTimer;
                    checkoutForm.addEventListener('input', () => {
                        clearTimeout(guestSaveTimer);
                        guestSaveTimer = setTimeout(saveGuestCheckoutDetails, 300);
                    });
                    checkoutForm.addEventListener('change', saveGuestCheckoutDetails);
                }
                let pendingConfirmationOrder = null;
                checkoutForm.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    saveGuestCheckoutDetails();

                    clearClientErrors(this);
                    if (checkoutPaymentError) {
                        checkoutPaymentError.style.display = 'none';
                        checkoutPaymentError.textContent = '';
                    }

                    const invalidFields = Array.from(this.querySelectorAll('input, select, textarea')).filter((input) => {
                        if (input.disabled || input.offsetParent === null) {
                            return false;
                        }

                        return !input.checkValidity();
                    });

                    if (pendingConfirmationOrder || !invalidFields.length) {
                        if (!pendingConfirmationOrder && typeof Cashfree === 'undefined') {
                            if (checkoutPaymentError) {
                                checkoutPaymentError.textContent = 'Cashfree checkout could not be loaded. Please refresh and try again.';
                                checkoutPaymentError.style.display = 'block';
                            }
                            return;
                        }

                        if (checkoutPayButton) {
                            checkoutPayButton.disabled = true;
                            checkoutPayButton.textContent = pendingConfirmationOrder ? 'Verifying Payment...' : 'Opening Payment...';
                        }

                        try {
                            let data = pendingConfirmationOrder ? { order_number: pendingConfirmationOrder } : null;
                            if (!data) {
                                const response = await fetch(this.action, {
                                    method: 'POST',
                                    body: new FormData(this),
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                });

                                data = await response.json().catch(() => ({}));

                                if (!response.ok) {
                                    if (response.status === 422 && data.errors) {
                                        Object.entries(data.errors).forEach(([field, messages]) => {
                                            const input = this.querySelector(`[name="${field}"]`);
                                            if (input) {
                                                addClientError(input, messages[0]);
                                            } else if (checkoutPaymentError) {
                                                checkoutPaymentError.textContent = messages[0];
                                                checkoutPaymentError.style.display = 'block';
                                            }
                                        });
                                        return;
                                    }

                                    throw new Error(data.message || 'Unable to start payment.');
                                }

                                if (!data.payment_session_id) {
                                    throw new Error('Cashfree payment session was not created.');
                                }

                                const cashfree = Cashfree({ mode: data.cashfree_mode || 'sandbox' });

                                const paymentResult = await cashfree.checkout({
                                    paymentSessionId: data.payment_session_id,
                                    redirectTarget: '_modal',
                                });

                                if (paymentResult && paymentResult.error) {
                                    throw new Error(paymentResult.error.message || 'Cashfree payment was not completed.');
                                }
                                pendingConfirmationOrder = data.order_number;
                            }

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Verifying Payment',
                                    text: 'Please wait while we confirm your payment.',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    },
                                });
                            }

                            let verifyResponse;
                            let verifyData;
                            for (let attempt = 0; attempt < 6; attempt++) {
                                if (attempt > 0) {
                                    await new Promise(resolve => setTimeout(resolve, 2000));
                                }
                                verifyResponse = await fetch(@json(route('checkout.cashfree.verify')), {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                                    },
                                    body: JSON.stringify({
                                        order_number: data.order_number,
                                    }),
                                });

                                verifyData = await verifyResponse.json().catch(() => ({}));
                                if (!verifyData.pending && verifyResponse.status !== 503) {
                                    break;
                                }
                            }

                            if (!verifyResponse.ok || !verifyData.success) {
                                throw new Error(verifyData.message || 'Payment could not be verified.');
                            }

                            if (checkoutPayButton) {
                                checkoutPayButton.style.display = 'none';
                            }

                            const successMessage = verifyData.shipping_error
                                ? `${verifyData.message}<br><small>${verifyData.shipping_error}</small>`
                                : verifyData.message;

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Payment Successful',
                                    html: successMessage,
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#000000',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                }).then(() => {
                                    window.location.href = verifyData.thankyou_url || @json(url('thankyou'));
                                });
                            } else {
                                alert('Payment Successful');
                                window.location.href = verifyData.thankyou_url || @json(url('thankyou'));
                            }
                        } catch (error) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Payment Not Completed',
                                    text: error.message || 'Unable to start Cashfree payment.',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#000000',
                                });
                            }

                            if (checkoutPaymentError) {
                                checkoutPaymentError.textContent = error.message || 'Unable to start Cashfree payment.';
                                checkoutPaymentError.style.display = 'block';
                            }
                        } finally {
                            if (checkoutPayButton && checkoutPayButton.style.display !== 'none') {
                                checkoutPayButton.disabled = false;
                                checkoutPayButton.textContent = pendingConfirmationOrder ? 'Retry Payment Confirmation' : 'Pay & Place Order';
                            }
                        }

                        return;
                    }

                    invalidFields.forEach((input) => {
                        if (input.validity.valueMissing) {
                            addClientError(input, `Please enter ${labelText(input)}.`);
                        } else if (input.validity.tooShort || input.validity.tooLong || input.validity.patternMismatch) {
                            addClientError(input, `Please enter a valid ${labelText(input)}.`);
                        } else if (input.validity.typeMismatch) {
                            addClientError(input, `Please enter a valid ${labelText(input)}.`);
                        } else {
                            addClientError(input, input.validationMessage);
                        }
                    });

                    invalidFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    invalidFields[0].focus({ preventScroll: true });
                });
            }

            document.querySelectorAll('[data-password-toggle]').forEach((button) => {
                button.addEventListener('click', function () {
                    const input = document.getElementById(this.dataset.passwordToggle);
                    const icon = this.querySelector('i');

                    if (!input) {
                        return;
                    }

                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    this.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');

                    if (icon) {
                        icon.classList.toggle('fa-eye', !isHidden);
                        icon.classList.toggle('fa-eye-slash', isHidden);
                    }
                });
            });


            function toggleBillingFields() {
                if (!billingFields) {
                    return;
                }

                const different = document.querySelector('input[name="billing_mode"]:checked')?.value === 'different';
                billingFields.classList.toggle('active', different);
                billingFields.querySelectorAll('input, select').forEach((input) => {
                    input.required = different;
                });
            }

            billingModeInputs.forEach((input) => {
                input.addEventListener('change', toggleBillingFields);
            });

            toggleBillingFields();
            if (rememberGuestCheckout && restoreGuestCheckout && shippingPostcodeInput?.value.replace(/\D+/g, '').length === 6) {
                refreshShippingQuote();
            }
        });
    </script>
@endsection




