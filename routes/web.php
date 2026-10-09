<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthPageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BulkOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'show'])->name('sitemap');

Route::get('/', [HomeController::class, 'index']);
Route::get('/account', [AccountController::class, 'index'])->middleware('auth');

Route::get('/collections', [CatalogController::class, 'collections']);

Route::get('/wishlist', [WishlistController::class, 'index']);

Route::get('/cart', [CartController::class, 'index']);

Route::get('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');

Route::get('/buy-now/{product}', [CartController::class, 'buyNow'])->name('buy.now');

Route::get('/cart/remove/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/cart/qty/{cartKey}/{action}', [CartController::class, 'quantity'])->where('action', 'inc|dec')->name('cart.qty');
Route::get('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
Route::get('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::get('/wishlist/qty/{product}/{action}', [WishlistController::class, 'quantity'])->where('action', 'inc|dec')->name('wishlist.qty');
Route::get('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
Route::get('/wishlist/cart/{product}', [WishlistController::class, 'moveToCart'])->name('wishlist.cart');
Route::get('/product-image/{filename}', [MediaController::class, 'productImage'])->where('filename', '[A-Za-z0-9._-]+')->name('product.image');
Route::get('/product-media/{filename}', [MediaController::class, 'productMedia'])->where('filename', '[A-Za-z0-9._-]+')->name('product.media');
Route::get('/category-image/{filename}', [MediaController::class, 'categoryImage'])->where('filename', '[A-Za-z0-9._-]+')->name('category.image');

Route::get('/category-banner/{filename}', [MediaController::class, 'categoryBanner'])->where('filename', '[A-Za-z0-9._-]+')->name('category.banner');
Route::get('/web-image/{filename}', [MediaController::class, 'webImage'])->where('filename', '[A-Za-z0-9._-]+')->name('web.image');
Route::get('/web-video/{filename}', [MediaController::class, 'webVideo'])->where('filename', '[A-Za-z0-9._-]+')->name('web.video');
Route::get('/instagram-image/{filename}', [MediaController::class, 'instagramImage'])->where('filename', '[A-Za-z0-9._-]+')->name('instagram.image');

Route::get('/uploads/{path}', [MediaController::class, 'upload'])->where('path', '.*');

Route::get('/contact', [ContactController::class, 'index']);
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/bulk-order', [BulkOrderController::class, 'index'])->name('bulk.order');

Route::post('/bulk-order', [BulkOrderController::class, 'store'])->name('bulk.order.store');
Route::get('/blog', [BlogController::class, 'index']);

Route::get('/blog-image/{filename}', [MediaController::class, 'blogImage'])->where('filename', '[A-Za-z0-9._-]+')->name('blog.image');

Route::get('/blog/{slug}', [BlogController::class, 'show'])->where('slug', '[A-Za-z0-9._-]+')->name('blog.show');

Route::get('/shop', [CatalogController::class, 'index'])->name('shop');
Route::get('/category/{slug}', [CatalogController::class, 'index'])->where('slug', '[A-Za-z0-9._-]+')->name('category.show');
Route::get('/shop/{slug}', [CatalogController::class, 'index'])->where('slug', '[A-Za-z0-9._-]+');
Route::post('/combo/checkout', [ComboController::class, 'checkout']);

Route::get('/combos', [ComboController::class, 'index']);

Route::get('/combos/{id}', [ComboController::class, 'show']);

Route::post('/combos/review', [ComboController::class, 'storeReview'])->middleware('auth')->name('combo.review.store');

Route::get('/about', [PageController::class, 'about']);
Route::get('/faq', [PageController::class, 'faq']);

Route::get('/terms-condition', [PageController::class, 'termsCondition']);
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/return-refund-policy', [PageController::class, 'returnRefundPolicy']);
Route::get('/shipping-policy', [PageController::class, 'shippingPolicy'])->name('shipping.policy');
Route::get('/exchange-policy', [PageController::class, 'exchangePolicy'])->name('exchange.policy');
Route::get('/automatic-watch-exchange-policy', [PageController::class, 'automaticWatchExchangePolicy'])->name('automatic-watch-exchange.policy');
Route::get('/cookies-policy', [PageController::class, 'cookiesPolicy']);
Route::get('/single-product', [ProductController::class, 'show']);
Route::post('/single-product/review', [ProductController::class, 'storeReview'])->middleware('auth')->name('product.review.store');
Route::get('/register', [AuthPageController::class, 'register'])->middleware('guest')->name('register');
Route::get('/login', [AuthPageController::class, 'login'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.store');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest')->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthPageController::class, 'forgotPassword'])->middleware('guest')->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])
    ->middleware('guest')->name('password.email');

Route::get('/verify-password-otp', [AuthPageController::class, 'verifyOtp'])->middleware('guest')->name('password.otp.form');

Route::post('/verify-password-otp', [AuthController::class, 'verifyPasswordResetOtp'])
    ->middleware('guest')->name('password.otp.verify');

Route::get('/reset-password', [AuthPageController::class, 'resetPassword'])->middleware('guest')->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('guest')->name('password.update');
Route::post('/account/profile', [AuthController::class, 'updateProfile'])->middleware('auth')->name('account.profile.update');
Route::post('/account/password', [AuthController::class, 'updatePassword'])->middleware('auth')->name('account.password.update');
Route::post('/account/address', [AddressController::class, 'store'])->middleware('auth')->name('account.address.store');
Route::post('/account/address/{id}', [AddressController::class, 'update'])->middleware('auth')->name('account.address.update');
Route::post('/account/address/{id}/default', [AddressController::class, 'setDefault'])->middleware('auth')->name('account.address.default');
Route::post('/account/address/{id}/delete', [AddressController::class, 'destroy'])->middleware('auth')->name('account.address.delete');
Route::get('/checkout', [CheckoutController::class, 'index']);

Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.place');
Route::get('/checkout/cashfree/return', [PaymentController::class, 'returnFromCashfree'])->name('checkout.cashfree.return');
Route::post('/checkout/cashfree/verify', [PaymentController::class, 'verify'])->name('checkout.cashfree.verify');
Route::post('/checkout/cashfree/webhook', [PaymentController::class, 'webhook'])->name('checkout.cashfree.webhook');
Route::post('/checkout/shipping-quote', [CheckoutController::class, 'shippingQuote'])->name('checkout.shipping.quote');
Route::post('/checkout/coupon', [CouponController::class, 'apply'])->name('checkout.coupon.apply');
Route::post('/checkout/coupon/remove', [CouponController::class, 'remove'])->name('checkout.coupon.remove');
Route::view('/thankyou', 'pages.thankyou');
