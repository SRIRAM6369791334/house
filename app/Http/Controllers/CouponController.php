<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $data = $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);

        $coupon = house_checkout_coupon($data['coupon_code']);

        if ($message = house_coupon_date_error($coupon)) {
            return back()
                ->withErrors(['coupon_code' => $message])
                ->withInput();
        }

        $subtotal = house_products_from_session('cart')->sum(fn ($product) => house_product_price($product) * $product->cart_qty);

        if ($subtotal < (float) ($coupon['min_order_amount'] ?? 0)) {
            return back()
                ->withErrors(['coupon_code' => 'This coupon requires a minimum order of '.house_money($coupon['min_order_amount']).'.'])
                ->withInput();
        }

        session(['checkout_coupon' => $coupon]);

        return back()->with('coupon_success', 'Coupon applied successfully.');
    }

    public function remove()
    {
        session()->forget('checkout_coupon');

        return back()->with('coupon_success', 'Coupon removed.');
    }
}
