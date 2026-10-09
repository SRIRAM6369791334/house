@extends('layouts.app')

@php
    $subtotal = $cartProducts->sum(function ($product) {
        return house_product_price($product) * ($product->cart_qty ?? 1);
    });
@endphp

@section('content')
        <style>
            .cart-swal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.55);
                z-index: 99999;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .cart-swal-overlay.active {
                display: flex;
            }

            .cart-swal-box {
                width: min(420px, 100%);
                background: #fff;
                text-align: center;
                padding: 36px 30px 30px;
                box-shadow: 0 24px 70px rgba(0,0,0,0.24);
                border-radius: 4px;
            }

            .cart-swal-icon {
                width: 74px;
                height: 74px;
                border-radius: 50%;
                border: 4px solid #CC0000;
                color: #CC0000;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 34px;
                margin-bottom: 20px;
            }

            .cart-swal-box h3 {
                font-size: 28px;
                margin: 0 0 12px;
                color: #1A1A1A;
            }

            .cart-swal-box p {
                color: #666;
                margin-bottom: 26px;
                line-height: 1.65;
            }

            .cart-swal-actions {
                display: flex;
                justify-content: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .cart-swal-cancel,
            .cart-swal-confirm {
                border: 0;
                padding: 12px 24px;
                color: #fff;
                font-weight: 800;
                letter-spacing: 1px;
                text-transform: uppercase;
                cursor: pointer;
            }

            .cart-swal-cancel {
                background: #777;
            }

            .cart-swal-confirm {
                background: #CC0000;
            }

            .cart-responsive-table {
                overflow-x: auto;
                overflow-y: hidden;
                width: 100%;
                -webkit-overflow-scrolling: touch;
            }

            @media (max-width: 991.98px) {
                /* Banner Scrim & Header */
                .about-shared-banner {
                    background-image: linear-gradient(to bottom, rgba(8, 8, 8, 0.82) 0%, rgba(8, 8, 8, 0.94) 100%), url('{{ asset('images/banner/about.png') }}') !important;
                    background-size: cover !important;
                    background-position: right center !important;
                }
                .breadcumb-area .bred-hading h5 {
                    font-family: 'Cormorant Garamond', 'Playfair Display', serif !important;
                    font-size: clamp(22px, 6.2vw, 28px) !important;
                    letter-spacing: 2px !important;
                    text-transform: uppercase !important;
                }

                .cart-checkout-area {
                    padding-top: 24px !important;
                    background: #fbfaf8 !important;
                }
                .cart-checkout-area .container {
                    padding-left: 14px !important;
                    padding-right: 14px !important;
                }

                /* Clear All Action */
                .cart-clear-action {
                    display: flex !important;
                    justify-content: flex-end !important;
                    margin-bottom: 16px !important;
                    margin-top: 4px !important;
                }
                .cart-clear-action .btn-def {
                    background: transparent !important;
                    border: 1px solid #d4c5b9 !important;
                    color: #555 !important;
                    border-radius: 6px !important;
                    font-size: 11px !important;
                    font-weight: 700 !important;
                    letter-spacing: 1px !important;
                    padding: 8px 14px !important;
                    text-transform: uppercase !important;
                    transition: all 0.25s ease !important;
                    box-shadow: none !important;
                }
                .cart-clear-action .btn-def:hover {
                    background: #cc0000 !important;
                    color: #fff !important;
                    border-color: #cc0000 !important;
                }

                /* Unroll Rigid HTML Table to Luxury Atelier Cards */
                .cart-responsive-table {
                    border: none !important;
                    overflow: visible !important;
                    width: 100% !important;
                    margin-bottom: 16px !important;
                }
                .responsive-cart-table {
                    display: block !important;
                    width: 100% !important;
                    min-width: 0 !important;
                    border: none !important;
                }
                .responsive-cart-table thead {
                    display: none !important;
                }
                .responsive-cart-table tbody {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 16px !important;
                    width: 100% !important;
                }

                /* Individual Product Card */
                .responsive-cart-table tr.cart_item {
                    display: grid !important;
                    grid-template-columns: 85px minmax(0, 1fr) !important;
                    grid-template-areas: 
                        "img title"
                        "img price"
                        "qty total" !important;
                    gap: 8px 14px !important;
                    background: #ffffff !important;
                    border: 1px solid #e7e2db !important;
                    border-radius: 12px !important;
                    padding: 16px !important;
                    position: relative !important;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.06) !important;
                    align-items: center !important;
                }

                /* Card Thumbnail */
                .responsive-cart-table tr.cart_item td.item-img {
                    grid-area: img !important;
                    width: 85px !important;
                    height: 104px !important;
                    padding: 0 !important;
                    border: none !important;
                }
                .responsive-cart-table tr.cart_item td.item-img a {
                    display: block !important;
                    width: 100% !important;
                    height: 100% !important;
                }
                .responsive-cart-table tr.cart_item td.item-img img {
                    width: 85px !important;
                    height: 104px !important;
                    object-fit: cover !important;
                    border-radius: 8px !important;
                    display: block !important;
                    background: #f4f3f0 !important;
                }

                /* Card Title */
                .responsive-cart-table tr.cart_item td.item-title {
                    grid-area: title !important;
                    padding: 0 36px 0 0 !important;
                    border: none !important;
                    text-align: left !important;
                }
                .responsive-cart-table tr.cart_item td.item-title a {
                    font-family: 'Cormorant Garamond', 'Playfair Display', serif !important;
                    font-size: clamp(15px, 4.2vw, 17px) !important;
                    font-weight: 600 !important;
                    color: #1a1a1a !important;
                    line-height: 1.3 !important;
                    display: -webkit-box !important;
                    -webkit-line-clamp: 2 !important;
                    -webkit-box-orient: vertical !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: normal !important;
                    text-decoration: none !important;
                }
                .responsive-cart-table tr.cart_item td.item-title small {
                    display: block !important;
                    margin-top: 3px !important;
                    color: #888 !important;
                    font-size: 11px !important;
                }

                /* Unit Price */
                .responsive-cart-table tr.cart_item td.item-price {
                    grid-area: price !important;
                    padding: 0 !important;
                    border: none !important;
                    font-size: 13px !important;
                    color: #777 !important;
                    text-align: left !important;
                }

                /* Quantity Stepper */
                .responsive-cart-table tr.cart_item td.item-qty {
                    grid-area: qty !important;
                    padding: 0 !important;
                    border: none !important;
                    width: 85px !important;
                }
                .responsive-cart-table tr.cart_item td.item-qty .cart-quantity,
                .responsive-cart-table tr.cart_item td.item-qty .product-qty {
                    width: 85px !important;
                    margin: 0 !important;
                }
                .responsive-cart-table tr.cart_item td.item-qty .cart-plus-minus {
                    display: flex !important;
                    align-items: center !important;
                    width: 85px !important;
                    height: 32px !important;
                    border: 1px solid #e2ded9 !important;
                    border-radius: 6px !important;
                    overflow: hidden !important;
                    background: #ffffff !important;
                }
                .responsive-cart-table tr.cart_item td.item-qty .cart-plus-minus a.qtybutton,
                .responsive-cart-table tr.cart_item td.item-qty .cart-plus-minus span.qtybutton {
                    width: 26px !important;
                    height: 30px !important;
                    line-height: 30px !important;
                    font-size: 14px !important;
                    font-weight: 700 !important;
                    text-align: center !important;
                    background: #f7f5f2 !important;
                    color: #333 !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-decoration: none !important;
                    cursor: pointer !important;
                    user-select: none !important;
                }
                .responsive-cart-table tr.cart_item td.item-qty .cart-plus-minus input.cart-plus-minus-box {
                    width: 33px !important;
                    height: 30px !important;
                    line-height: 30px !important;
                    border: none !important;
                    border-left: 1px solid #e2ded9 !important;
                    border-right: 1px solid #e2ded9 !important;
                    font-size: 13px !important;
                    font-weight: 700 !important;
                    text-align: center !important;
                    padding: 0 !important;
                    background: #fff !important;
                    color: #111 !important;
                }

                /* Line Total */
                .responsive-cart-table tr.cart_item td.total-price {
                    grid-area: total !important;
                    padding: 0 !important;
                    border: none !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: flex-end !important;
                    text-align: right !important;
                }
                .responsive-cart-table tr.cart_item td.total-price strong {
                    font-size: 16.5px !important;
                    color: #cc0000 !important;
                    font-weight: 800 !important;
                }

                /* Floating Delete Button */
                .responsive-cart-table tr.cart_item td.remove-item {
                    position: absolute !important;
                    top: 12px !important;
                    right: 12px !important;
                    padding: 0 !important;
                    border: none !important;
                    width: auto !important;
                }
                .responsive-cart-table tr.cart_item td.remove-item a {
                    width: 32px !important;
                    height: 32px !important;
                    border-radius: 50% !important;
                    border: 1px solid #ede8e3 !important;
                    background: #faf9f8 !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    color: #888 !important;
                    font-size: 14px !important;
                    text-decoration: none !important;
                    transition: all 0.25s ease !important;
                }
                .responsive-cart-table tr.cart_item td.remove-item a:hover {
                    background: #cc0000 !important;
                    border-color: #cc0000 !important;
                    color: #ffffff !important;
                }

                /* Empty State */
                .responsive-cart-table tbody tr td.text-center {
                    padding: 45px 20px !important;
                    background: #ffffff !important;
                    border: 1px dashed #dcd5cc !important;
                    border-radius: 12px !important;
                    color: #888 !important;
                    font-style: italic !important;
                    font-size: 15px !important;
                    text-align: center !important;
                    display: block !important;
                    width: 100% !important;
                }

                /* Cart Bottom & Order Summary Area */
                .cart-bottom-area {
                    margin-top: 10px !important;
                }
                .cart-total-area {
                    background: #ffffff !important;
                    border: 1px solid #e7e2db !important;
                    border-radius: 14px !important;
                    padding: 24px 20px !important;
                    box-shadow: 0 12px 35px rgba(0,0,0,0.06) !important;
                    margin-top: 16px !important;
                }
                .cart-total-area .catagory-title {
                    text-align: left !important;
                    margin-bottom: 16px !important;
                    padding-bottom: 12px !important;
                    border-bottom: 1px solid #f0eae1 !important;
                }
                .cart-total-area .catagory-title h3 {
                    font-family: 'Cormorant Garamond', 'Playfair Display', serif !important;
                    font-size: 20px !important;
                    font-weight: 700 !important;
                    color: #1a1a1a !important;
                    text-transform: uppercase !important;
                    letter-spacing: 1px !important;
                    margin: 0 !important;
                }
                .sub-shipping p {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    font-size: 13.5px !important;
                    color: #555 !important;
                    margin-bottom: 10px !important;
                    font-weight: 500 !important;
                }
                .sub-shipping p span {
                    font-weight: 700 !important;
                    color: #1a1a1a !important;
                }
                .process-cart-total {
                    border-top: 1px dashed #e2ded9 !important;
                    padding-top: 14px !important;
                    margin-top: 14px !important;
                }
                .process-cart-total p {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    font-size: 16px !important;
                    font-weight: 800 !important;
                    color: #111111 !important;
                    margin: 0 0 20px 0 !important;
                }
                .process-cart-total p span {
                    color: #cc0000 !important;
                    font-size: 20px !important;
                    font-weight: 800 !important;
                }
                .process-checkout-btn {
                    text-align: center !important;
                    width: 100% !important;
                }
                .process-checkout-btn .btn-def {
                    display: block !important;
                    width: 100% !important;
                    text-align: center !important;
                    background: #cc0000 !important;
                    color: #ffffff !important;
                    font-size: 13px !important;
                    font-weight: 800 !important;
                    letter-spacing: 1.5px !important;
                    text-transform: uppercase !important;
                    padding: 14px 20px !important;
                    border-radius: 8px !important;
                    box-shadow: 0 6px 18px rgba(204, 0, 0, 0.28) !important;
                    border: none !important;
                    transition: all 0.3s ease !important;
                    text-decoration: none !important;
                }
                .process-checkout-btn .btn-def:hover {
                    background: #111111 !important;
                    transform: translateY(-1px) !important;
                    box-shadow: 0 8px 24px rgba(0,0,0,0.2) !important;
                }

                /* Hide floating scrollUp on mobile */
                #scrollUp {
                    display: none !important;
                }
            }

            /* SweetAlert Mobile Clamping */
            .swal2-popup {
                border-radius: 12px !important;
                padding: 24px 18px !important;
                max-width: 90vw !important;
            }
            .swal2-title {
                font-family: 'Cormorant Garamond', 'Playfair Display', serif !important;
                font-size: clamp(20px, 5.5vw, 24px) !important;
                font-weight: 700 !important;
                color: #1a1a1a !important;
            }
            .swal2-html-container {
                font-size: 13.5px !important;
                color: #666 !important;
            }
            .swal2-actions button {
                border-radius: 6px !important;
                font-weight: 700 !important;
                font-size: 12px !important;
                letter-spacing: 1px !important;
                text-transform: uppercase !important;
                padding: 10px 20px !important;
            }        </style>

        <!--breadcumb area start -->
        <div class="about-shared-banner breadcumb-area overlay pos-rltv">
            <div class="bread-main">
                <div class="bred-hading text-center">
                    <h5>Cart Details</h5>
                </div>
                <ol class="breadcrumb">
                    <li class="home"><a title="Go to Home Page" href="/" style="color: #fff;">Home</a></li>
                    <li class="active">Cart</li>
                </ol>
            </div>
        </div>
        <!--breadcumb area end -->

        <!--cart-checkout-area start -->
        <div class="cart-checkout-area pt-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-area">
                            @if($cartProducts->isNotEmpty())
                                <div class="cart-clear-action text-end mb-20">
                                    <a class="btn-def btn2 cart-confirm-remove" href="{{ route('cart.clear') }}" data-title="Remove all cart?" data-text="All products will be removed from your cart.">Remove All Cart</a>
                                </div>
                            @endif
                            <div class="clearfix"></div>
                            <div class="content-tab-product-category pb-70">
                                        <div class="cart-page-area">
                                            <div class="cart-responsive-table table-responsive mb-20">
                                                <table class="responsive-cart-table shop_table-2 cart table">
                                                    <thead>
                                                        <tr>
                                                            <th class="product-thumbnail">Image</th>
                                                            <th class="product-name">Product Name</th>
                                                            <th class="product-price">Unit Price</th>
                                                            <th class="product-quantity">Quantity</th>
                                                            <th class="product-subtotal">Total</th>
                                                            <th class="product-remove">Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($cartProducts as $product)
                                                            @php
                                                                $images = house_product_images($product);
                                                                $price = house_product_price($product);
                                                                $lineTotal = $price * ($product->cart_qty ?? 1);
                                                            @endphp
                                                            <tr class="cart_item" data-cart-row>
                                                                <td class="item-img">
                                                                    <a href="{{ url('single-product') . '?' . http_build_query(['product' => ($product->slug ?? $product->id), 'variant_id' => $product->cart_variant_id ?? '']) }}">
                                                                        <img src="{{ ($product->cart_variant_image ?? null) ?: $images[0] }}" alt="{{ $product->product_name }}{{ ($product->cart_variant_label ?? null) ? ' - ' . $product->cart_variant_label : '' }}">
                                                                    </a>
                                                                </td>
                                                                <td class="item-title">
                                                                    <a href="{{ url('single-product') . '?' . http_build_query(['product' => ($product->slug ?? $product->id), 'variant_id' => $product->cart_variant_id ?? '']) }}">{{ $product->product_name }}</a>
                                                                    @if($product->cart_variant_label ?? null)
                                                                        <small style="display:block; margin-top:4px; color:#777;">{{ $product->cart_variant_label }}</small>
                                                                    @endif
                                                                    @if(($product->cart_source ?? null) === 'combo')
                                                                        <small style="display:block; margin-top:4px; color:#b40016;">Part of your Signature Box</small>
                                                                    @endif
                                                                </td>
                                                                <td class="item-price">{!! house_money($price) !!}</td>
                                                                <td class="item-qty">
                                                                    <div class="cart-quantity">
                                                                        <div class="product-qty">
                                                                            <div class="cart-quantity">
                                                                                <div class="cart-plus-minus">
                                                                                    <a href="{{ route('cart.qty', [$product->cart_key ?? 0, 'dec']) }}" class="dec qtybutton cart-qty-action" style="text-decoration: none;">-</a>
                                                                                    <input value="{{ $product->cart_qty ?? 1 }}" name="qtybutton" class="cart-plus-minus-box" type="text" readonly>
                                                                                    @if(($product->cart_qty ?? 1) < ($product->cart_stock ?? 0))
                                                                                        <a href="{{ route('cart.qty', [$product->cart_key ?? 0, 'inc']) }}" class="inc qtybutton cart-qty-action" style="text-decoration: none;">+</a>
                                                                                    @else
                                                                                        <span class="inc qtybutton" aria-disabled="true" style="opacity:.45; cursor:not-allowed;">+</span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="total-price"><strong data-cart-line-total>{!! house_money($lineTotal) !!}</strong></td>
                                                                <td class="remove-item"><a class="cart-confirm-remove" href="{{ route('cart.remove', $product->cart_key ?? 0) }}" data-title="{{ ($product->cart_source ?? null) === 'combo' ? 'Remove this box?' : 'Remove this item?' }}" data-text="{{ ($product->cart_source ?? null) === 'combo' ? 'All three products in this box will be removed from your cart.' : $product->product_name.' will be removed from your cart.' }}"><i class="fa fa-trash-o"></i></a></td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center">Cart is empty.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="cart-bottom-area">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-7">
                                                        {{-- <div class="update-coupne-area">
                                                            <div class="update-continue-btn text-end pb-20">
                                                                <a href="{{ url('shop') }}" class="btn-def btn2">Continue Shopping</a>
                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                    <div class="col-lg-4 col-md-5">
                                                        <div class="cart-total-area">
                                                            <div class="catagory-title cat-tit-5 mb-20 text-end">
                                                                <h3>Cart Totals</h3>
                                                            </div>
                                                            <div class="sub-shipping">
                                                                <p>Subtotal <span data-cart-subtotal>{!! house_money($subtotal) !!}</span></p>
                                                                <p>Shipping <span>{!! house_money(0) !!}</span></p>
                                                            </div>
                                                            <div class="process-cart-total">
                                                                <p>Total <span data-cart-total>{!! house_money($subtotal) !!}</span></p>
                                                            </div>
                                                            <div class="process-checkout-btn text-end">
                                                                <a class="btn-def btn2" href="{{ url('checkout') }}">Process To Checkout</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--cart-checkout-area end-->

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const updateCartPage = (data, row) => {
                    if (data.reload) { window.location.reload(); return; }
                    if (typeof updateHeaderCounts === 'function') {
                        updateHeaderCounts(data);
                    }
                    if (data.subtotal) {
                        document.querySelectorAll('[data-cart-subtotal]').forEach((el) => el.innerHTML = data.subtotal);
                    }
                    if (data.total) {
                        document.querySelectorAll('[data-cart-total]').forEach((el) => el.innerHTML = data.total);
                    }
                    if (row && data.removed) {
                        row.remove();
                    } else if (row) {
                        const input = row.querySelector('.cart-plus-minus-box');
                        const lineTotal = row.querySelector('[data-cart-line-total]');
                        if (input && typeof data.quantity !== 'undefined') input.value = data.quantity;
                        if (lineTotal && data.line_total) lineTotal.innerHTML = data.line_total;
                    }
                    if (!document.querySelector('[data-cart-row]')) {
                        const tbody = document.querySelector('.responsive-cart-table tbody');
                        if (tbody) {
                            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Cart is empty.</td></tr>';
                        }
                        document.querySelector('.cart-clear-action')?.remove();
                    }
                };

                const requestCartAction = (url, row) => {
                    return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                        .then(async (response) => {
                            const data = await response.json().catch(() => ({}));
                            if (!response.ok) throw new Error(data.error || 'Unable to update cart.');
                            updateCartPage(data, row);
                            return data;
                        });
                };

                document.querySelectorAll('.cart-qty-action').forEach((link) => {
                    link.addEventListener('click', function (event) {
                        event.preventDefault();
                        requestCartAction(this.href, this.closest('[data-cart-row]')).catch((error) => {
                            Swal.fire({ title: 'Cannot Update Cart', text: error.message, icon: 'error', confirmButtonColor: '#cc0000' });
                        });
                    });
                });

                document.querySelectorAll('.cart-confirm-remove').forEach((link) => {
                    link.addEventListener('click', function (event) {
                        event.preventDefault();
                        const targetUrl = this.href;
                        const row = this.closest('[data-cart-row]');
                        const title = this.dataset.title || 'Remove item?';
                        const text = this.dataset.text || 'This item will be removed from your cart.';

                        Swal.fire({
                            title: title,
                            text: text,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, Remove'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                requestCartAction(targetUrl, row).catch((error) => {
                                    Swal.fire({ title: 'Cannot Update Cart', text: error.message, icon: 'error', confirmButtonColor: '#cc0000' });
                                });
                            }
                        });
                    });
                });
            });
        </script>
@endsection
