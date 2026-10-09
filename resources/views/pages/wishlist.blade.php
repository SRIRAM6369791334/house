@extends('layouts.app')

@section('content')
        <style>
            .wishlist-swal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.55);
                z-index: 99999;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .wishlist-swal-overlay.active {
                display: flex;
            }

            .wishlist-swal-box {
                width: min(420px, 100%);
                background: #fff;
                text-align: center;
                padding: 36px 30px 30px;
                box-shadow: 0 24px 70px rgba(0,0,0,0.24);
                border-radius: 4px;
            }

            .wishlist-swal-icon {
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

            .wishlist-swal-box h3 {
                font-size: 28px;
                margin: 0 0 12px;
                color: #1A1A1A;
            }

            .wishlist-swal-box p {
                color: #666;
                margin-bottom: 26px;
                line-height: 1.65;
            }

            .wishlist-swal-actions {
                display: flex;
                justify-content: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .wishlist-swal-cancel,
            .wishlist-swal-confirm {
                border: 0;
                padding: 12px 24px;
                color: #fff;
                font-weight: 800;
                letter-spacing: 1px;
                text-transform: uppercase;
                cursor: pointer;
            }

            .wishlist-swal-cancel {
                background: #777;
            }

            .wishlist-swal-confirm {
                background: #CC0000;
            }

            @media (min-width: 992px) {
                .wishlist-table {
                    table-layout: fixed;
                    width: 100%;
                }

                .wishlist-table .product-thumbnail { width: 10%; }
                .wishlist-table .product-name { width: 29%; }
                .wishlist-table .product-price { width: 10%; }
                .wishlist-table .product-subtotal { width: 12%; }
                .wishlist-table .product-quantity { width: 18%; }
                .wishlist-table .product-quantity + .product-quantity { width: 14%; }
                .wishlist-table .product-remove { width: 7%; }

                .wishlist-table th,
                .wishlist-table td {
                    white-space: normal !important;
                }

                .wishlist-table .total-price .btn-def {
                    padding-left: 14px;
                    padding-right: 14px;
                    white-space: nowrap;
                }
            }

            /* ==========================================================================
               AUTHORITATIVE MOBILE & TABLET RESPONSIVENESS OVERHAUL (HOUSE OF KNP ATELIER)
               ========================================================================== */
            @media (max-width: 991.98px) {
                /* Scrim overlay for breadcrumb banner */
                .about-shared-banner {
                    min-height: 180px !important;
                    background-position: right center !important;
                }
                .about-shared-banner.overlay::before {
                    background: linear-gradient(to bottom, rgba(8, 8, 8, 0.82), rgba(8, 8, 8, 0.94)) !important;
                }
                .about-shared-banner .bread-main {
                    padding-top: 32px !important;
                }
                .about-shared-banner .bred-hading h5 {
                    font-family: 'Playfair Display', serif !important;
                    font-size: 22px !important;
                    letter-spacing: 1px !important;
                    margin-bottom: 6px !important;
                    color: #FFFFFF !important;
                }
                .about-shared-banner .breadcrumb {
                    font-size: 12px !important;
                    letter-spacing: 0.8px !important;
                }

                /* Section & Heading */
                .wishlist-area {
                    padding: 24px 0 60px !important;
                    background: #FAFAF9 !important;
                }
                .wishlist-area .container {
                    padding-left: 16px !important;
                    padding-right: 16px !important;
                }
                .wishlist-heading {
                    display: flex !important;
                    flex-direction: row !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    gap: 12px !important;
                    margin-bottom: 20px !important;
                    padding-bottom: 14px !important;
                    border-bottom: 1px solid #e7e2db !important;
                }
                .wishlist-heading h4 {
                    font-family: 'Playfair Display', serif !important;
                    font-size: clamp(17px, 4.8vw, 21px) !important;
                    font-weight: 600 !important;
                    color: #1C1917 !important;
                    margin: 0 !important;
                }
                .wishlist-heading .btn-def {
                    border: 1px solid #D6D3D1 !important;
                    background: #ffffff !important;
                    color: #64748B !important;
                    padding: 8px 14px !important;
                    border-radius: 6px !important;
                    font-size: 10px !important;
                    font-weight: 800 !important;
                    letter-spacing: 1.2px !important;
                    text-transform: uppercase !important;
                    white-space: nowrap !important;
                    transition: all 0.25s ease !important;
                }
                .wishlist-heading .btn-def:hover {
                    color: #DC2626 !important;
                    border-color: #DC2626 !important;
                    background: #FEF2F2 !important;
                }

                /* Table Container */
                .wishlist-area .table-responsive {
                    border: none !important;
                    overflow: visible !important;
                    width: 100% !important;
                    margin: 0 !important;
                }
                .wishlist-table {
                    display: block !important;
                    min-width: 0 !important;
                    width: 100% !important;
                    border: none !important;
                }
                .wishlist-table thead {
                    display: none !important;
                }
                .wishlist-table tbody {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 16px !important;
                    width: 100% !important;
                }
                .wishlist-table tr.cart_item {
                    display: grid !important;
                    grid-template-columns: 85px minmax(0, 1fr) !important;
                    grid-template-rows: auto auto auto !important;
                    gap: 8px 14px !important;
                    align-items: center !important;
                    background: #FFFFFF !important;
                    border: 1px solid #e7e2db !important;
                    border-radius: 10px !important;
                    padding: 16px 14px !important;
                    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03) !important;
                    position: relative !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                }

                /* Product Image */
                .wishlist-table td.item-img {
                    grid-column: 1 !important;
                    grid-row: 1 / 3 !important;
                    width: 85px !important;
                    height: 104px !important;
                    padding: 0 !important;
                    border: 1px solid #f0ebe4 !important;
                    border-radius: 6px !important;
                    overflow: hidden !important;
                    background: #faf9f7 !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                }
                .wishlist-table .item-img a {
                    display: block !important;
                    width: 100% !important;
                    height: 100% !important;
                }
                .wishlist-table .item-img img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                    display: block !important;
                }

                /* Title */
                .wishlist-table td.item-title {
                    grid-column: 2 !important;
                    grid-row: 1 !important;
                    padding: 0 36px 0 0 !important;
                    border: none !important;
                    text-align: left !important;
                    align-self: start !important;
                }
                .wishlist-table .item-title > a {
                    font-family: 'Playfair Display', serif !important;
                    font-size: 15px !important;
                    font-weight: 600 !important;
                    color: #1C1917 !important;
                    line-height: 1.35 !important;
                    display: -webkit-box !important;
                    -webkit-line-clamp: 2 !important;
                    -webkit-box-orient: vertical !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: normal !important;
                    padding: 0 !important;
                    text-decoration: none !important;
                }
                .wishlist-table .item-title > a:hover {
                    color: #CC0000 !important;
                }

                /* Price */
                .wishlist-table td.item-price {
                    grid-column: 2 !important;
                    grid-row: 2 !important;
                    padding: 0 !important;
                    border: none !important;
                    text-align: left !important;
                    font-size: 16px !important;
                    font-weight: 700 !important;
                    font-family: 'Playfair Display', serif !important;
                    color: #CC0000 !important;
                    align-self: start !important;
                    margin-top: 2px !important;
                }

                /* Remove Trash Button */
                .wishlist-table td.remove-item {
                    position: absolute !important;
                    top: 14px !important;
                    right: 14px !important;
                    padding: 0 !important;
                    border: none !important;
                    z-index: 2 !important;
                    width: auto !important;
                    height: auto !important;
                }
                .wishlist-table .remove-item a {
                    width: 32px !important;
                    height: 32px !important;
                    border-radius: 50% !important;
                    background: #faf9f7 !important;
                    border: 1px solid #e2ded8 !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    color: #64748B !important;
                    font-size: 13px !important;
                    transition: all 0.2s ease !important;
                    text-decoration: none !important;
                }
                .wishlist-table .remove-item a:hover {
                    color: #DC2626 !important;
                    background: #FEE2E2 !important;
                    border-color: #FCA5A5 !important;
                }

                /* Quantity Stepper */
                .wishlist-table td.item-qty {
                    grid-column: 1 !important;
                    grid-row: 3 !important;
                    padding: 0 !important;
                    border: none !important;
                    text-align: left !important;
                    margin-top: 6px !important;
                }
                .wishlist-table .cart-plus-minus {
                    display: inline-flex !important;
                    align-items: center !important;
                    height: 38px !important;
                    width: 85px !important;
                    border: 1px solid #e2ded8 !important;
                    border-radius: 6px !important;
                    overflow: hidden !important;
                    background: #faf9f7 !important;
                    position: relative !important;
                }
                .wishlist-table .qtybutton {
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    width: 26px !important;
                    height: 38px !important;
                    line-height: 38px !important;
                    font-size: 14px !important;
                    font-weight: 700 !important;
                    color: #44403c !important;
                    background: transparent !important;
                    cursor: pointer !important;
                    user-select: none !important;
                    text-decoration: none !important;
                    border: none !important;
                }
                .wishlist-table .qtybutton:hover {
                    background: #f0ebe4 !important;
                    color: #1C1917 !important;
                }
                .wishlist-table .cart-plus-minus-box {
                    width: 33px !important;
                    height: 38px !important;
                    text-align: center !important;
                    border: 0 !important;
                    border-left: 1px solid #e2ded8 !important;
                    border-right: 1px solid #e2ded8 !important;
                    background: #ffffff !important;
                    font-size: 13px !important;
                    font-weight: 700 !important;
                    color: #1C1917 !important;
                    padding: 0 !important;
                }

                /* Add To Cart Button */
                .wishlist-table td.total-price {
                    grid-column: 2 !important;
                    grid-row: 3 !important;
                    padding: 0 !important;
                    border: none !important;
                    width: 100% !important;
                    margin-top: 6px !important;
                }
                .wishlist-table .total-price .btn-def {
                    width: 100% !important;
                    min-height: 38px !important;
                    border-radius: 6px !important;
                    background: #CC0000 !important;
                    color: #ffffff !important;
                    border: 1px solid #CC0000 !important;
                    font-size: 11px !important;
                    font-weight: 800 !important;
                    letter-spacing: 1.2px !important;
                    text-transform: uppercase !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    transition: all 0.25s ease !important;
                    box-shadow: 0 3px 10px rgba(204, 0, 0, 0.2) !important;
                    padding: 0 14px !important;
                }
                .wishlist-table .total-price .btn-def:hover {
                    background: #A00000 !important;
                    border-color: #A00000 !important;
                    color: #ffffff !important;
                }

                /* Empty State */
                .wishlist-table tr.wishlist-empty-row {
                    display: block !important;
                    width: 100% !important;
                }
                .wishlist-table td.wishlist-empty {
                    display: flex !important;
                    flex-direction: column !important;
                    align-items: center !important;
                    justify-content: center !important;
                    padding: 48px 24px !important;
                    background: #FFFFFF !important;
                    border: 1px solid #e7e2db !important;
                    border-radius: 10px !important;
                    font-family: 'Playfair Display', serif !important;
                    font-size: 18px !important;
                    font-style: italic !important;
                    color: #64748B !important;
                    text-align: center !important;
                }

                /* SweetAlert Mobile Modal */
                .wishlist-swal-box {
                    width: min(340px, 92vw) !important;
                    padding: 24px 18px 20px !important;
                    border-radius: 10px !important;
                }
                .wishlist-swal-icon {
                    width: 56px !important;
                    height: 56px !important;
                    font-size: 24px !important;
                    margin-bottom: 12px !important;
                }
                .wishlist-swal-box h3 {
                    font-size: 19px !important;
                }
                .wishlist-swal-box p {
                    font-size: 13px !important;
                    margin-bottom: 18px !important;
                }
                .wishlist-swal-cancel,
                .wishlist-swal-confirm {
                    padding: 10px 16px !important;
                    font-size: 10px !important;
                    border-radius: 6px !important;
                }

                /* Hide Back-to-Top Button on Mobile to prevent action blocking */
                #scrollUp, .back-to-top, .go-top, .scroll-top, .scrollToTop, .scrollup, #back-top {
                    display: none !important;
                }
            }
        </style>

        <!--breadcumb area start -->
        <div class="about-shared-banner breadcumb-area overlay pos-rltv">
            <div class="bread-main">
                <div class="bred-hading text-center">
                    <h5>Wishlist Details</h5>
                </div>
                <ol class="breadcrumb">
                    <li class="home"><a title="Go to Home Page" href="/"style="color: #fff;">Home</a></li>
                    <li class="active">Wishlist</li>
                </ol>
            </div>
        </div>
        <!--breadcumb area end -->

        <!-- wishlist are start-->
        <div class="wishlist-area ptb-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="wishlist-heading d-flex justify-content-between align-items-center mb-20">
                            <h4 class="mb-0">My Wishlist</h4>
                            @if($wishlistProducts->isNotEmpty())
                                <a class="btn-def btn2 wishlist-confirm-remove" href="{{ route('wishlist.clear') }}" data-title="Remove all wishlist?" data-text="All wishlist products will be removed.">Remove All Wishlist</a>
                            @endif
                        </div>
                        <div class="cart-page-area">
                            <div class="table-responsive">
                                <table class="shop_table-2 cart table wishlist-table">
                                    <thead>
                                        <tr>
                                            <th class="product-thumbnail">Image</th>
                                            <th class="product-name">Product Name</th>
                                            <th class="product-price">Unit Price</th>
                                            {{-- <th class="product-subtotal">Stock</th> --}}
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-quantity">Add Item</th>
                                            <th class="product-remove">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($wishlistProducts as $product)
                                            @php
                                                $images = house_product_images($product);
                                                $price = house_product_price($product);
                                            @endphp
                                            <tr class="cart_item" data-wishlist-row>
                                                <td class="item-img" data-label="Image">
                                                    <a href="{{ url('single-product') . '?' . http_build_query(['product' => ($product->slug ?? $product->id), 'variant_id' => $product->cart_variant_id ?? '']) }}">
                                                        <img src="{{ ($product->cart_variant_image ?? null) ?: $images[0] }}" alt="{{ $product->product_name }}{{ ($product->cart_variant_label ?? null) ? ' - ' . $product->cart_variant_label : '' }}">
                                                    </a>
                                                </td>
                                                <td class="item-title" data-label="Product Name">
                                                    <a href="{{ url('single-product') . '?' . http_build_query(['product' => ($product->slug ?? $product->id), 'variant_id' => $product->cart_variant_id ?? '']) }}">{{ $product->product_name }}</a>
                                                    @if($product->cart_variant_label ?? null)
                                                        <small style="display:block; margin-top:4px; color:#777;">{{ $product->cart_variant_label }}</small>
                                                    @endif
                                                </td>
                                                <td class="item-price" data-label="Unit Price">{!! house_money($price) !!}</td>
                                                {{-- <td class="item-qty" data-label="Stock">{{ $product->cart_stock > 0 ? 'Available: ' . $product->cart_stock : 'Out of Stock' }}</td> --}}
                                                <td class="item-qty" data-label="Quantity">
                                                    <div class="cart-quantity">
                                                        <div class="product-qty">
                                                            <div class="cart-quantity">
                                                                <div class="cart-plus-minus">
                                                                    <a href="{{ route('wishlist.qty', [$product->cart_key ?? 0, 'dec']) }}" class="dec qtybutton wishlist-qty-action" style="text-decoration: none;">-</a>
                                                                    <input value="{{ $product->cart_qty ?? 1 }}" name="qtybutton" class="cart-plus-minus-box" type="text" readonly>
                                                                    @if(($product->cart_qty ?? 1) < ($product->cart_stock ?? 0))
                                                                        <a href="{{ route('wishlist.qty', [$product->cart_key ?? 0, 'inc']) }}" class="inc qtybutton wishlist-qty-action" style="text-decoration: none;">+</a>
                                                                    @else
                                                                        <span class="inc qtybutton" aria-disabled="true" style="opacity:.45; cursor:not-allowed;">+</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="total-price" data-label="Add Item">@if(($product->cart_stock ?? 0) > 0)
                                                    <a class="btn-def btn2 wishlist-move-cart" href="{{ route('wishlist.cart', $product->cart_key ?? 0) }}">Add To Cart</a>
                                                @else
                                                    <span class="btn-def btn2" style="background:#ccc; cursor:not-allowed;">Out of Stock</span>
                                                @endif</td>
                                                <td class="remove-item" data-label="Remove"><a class="wishlist-confirm-remove" href="{{ route('wishlist.remove', $product->cart_key ?? 0) }}" data-title="Remove this item?" data-text="{{ $product->product_name }} will be removed from your wishlist."><i class="fa fa-trash-o"></i></a></td>
                                            </tr>
                                        @empty
                                            <tr class="wishlist-empty-row">
                                                <td colspan="7" class="text-center wishlist-empty">Wishlist is empty.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- wishlist are end-->

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const updateWishlistPage = (data, row) => {
                    if (typeof updateHeaderCounts === 'function') {
                        updateHeaderCounts(data);
                    }
                    if (row && data.removed) {
                        row.remove();
                    } else if (row) {
                        const input = row.querySelector('.cart-plus-minus-box');
                        if (input && typeof data.quantity !== 'undefined') input.value = data.quantity;
                    }
                    if (!document.querySelector('[data-wishlist-row]')) {
                        const tbody = document.querySelector('.wishlist-table tbody');
                        if (tbody) {
                            tbody.innerHTML = '<tr class="wishlist-empty-row"><td colspan="7" class="text-center wishlist-empty">Wishlist is empty.</td></tr>';
                        }
                        document.querySelector('.wishlist-heading .wishlist-confirm-remove')?.remove();
                    }
                };

                const requestWishlistAction = (url, row) => {
                    return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                        .then(async (response) => {
                            const data = await response.json().catch(() => ({}));
                            if (!response.ok) throw new Error(data.error || 'Unable to update wishlist.');
                            updateWishlistPage(data, row);
                            return data;
                        });
                };

                document.querySelectorAll('.wishlist-qty-action').forEach((link) => {
                    link.addEventListener('click', function (event) {
                        event.preventDefault();
                        requestWishlistAction(this.href, this.closest('[data-wishlist-row]')).catch((error) => {
                            Swal.fire({ title: 'Cannot Update Wishlist', text: error.message, icon: 'error', confirmButtonColor: '#cc0000' });
                        });
                    });
                });

                document.querySelectorAll('.wishlist-move-cart').forEach((link) => {
                    link.addEventListener('click', function (event) {
                        event.preventDefault();
                        requestWishlistAction(this.href, this.closest('[data-wishlist-row]')).then(() => {
                            Swal.fire({ title: 'Added to Cart!', text: 'The product has been moved to your cart.', icon: 'success', timer: 1500, showConfirmButton: false });
                        }).catch((error) => {
                            Swal.fire({ title: 'Cannot Move Item', text: error.message, icon: 'error', confirmButtonColor: '#cc0000' });
                        });
                    });
                });

                document.querySelectorAll('.wishlist-confirm-remove').forEach((link) => {
                    link.addEventListener('click', function (event) {
                        event.preventDefault();
                        const targetUrl = this.href;
                        const row = this.closest('[data-wishlist-row]');
                        const title = this.dataset.title || 'Remove item?';
                        const text = this.dataset.text || 'This item will be removed from your wishlist.';

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
                                requestWishlistAction(targetUrl, row).catch((error) => {
                                    Swal.fire({ title: 'Cannot Update Wishlist', text: error.message, icon: 'error', confirmButtonColor: '#cc0000' });
                                });
                            }
                        });
                    });
                });
            });
        </script>
@endsection

