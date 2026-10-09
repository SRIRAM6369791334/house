<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\GiftCombo;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('pages.cart', ['cartProducts' => house_products_from_session('cart')]);
    }

    public function add(Request $request, $product)
    {
        abort_unless(Product::query()->forStorefront()->where('p.id', $product)
            ->exists(), 404);
        $current = 0;
        $quantity = 0;
        $addedQuantity = min(99, max(1, (int) $request->input('quantity', 1)));
        $variantId = (int) $request->input('variant_id', 0);
        $variant = ProductVariant::query()
            ->where('id', $variantId)
            ->where('product_id', $product)
            ->first();
        $variantId = $variant ? $variant->id : null;
        $cartKey = $product.($variantId ? '_'.$variantId : '');
        $cart = session('cart', []);
        if (isset($cart[$cartKey])) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'info' => 'This product is already in your cart.',
                    'cart_count' => house_session_item_count('cart'),
                    'wishlist_count' => house_session_item_count('wishlist'),
                ]);
            }

            return back()->with('info', 'This product is already in your cart.');
        }
        $stockLimit = 10;
        if ($variant) {
            $stockLimit = (int) ($variant->product_qty ?? $variant->stock_qty ?? $variant->available_stock ?? 10);
        } else {
            $mainProduct = Product::query()
                ->where('id', $product)
                ->first();
            if ($mainProduct) {
                $stockLimit = (int) ($mainProduct->product_qty ?? $mainProduct->stock_qty ?? $mainProduct->available_stock ?? 10);
            }
        }
        if ($quantity + $addedQuantity > $stockLimit) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['error' => 'Out of stock'], 400);
            }

            return back()->withErrors(['cart' => 'Cannot add more than available stock.']);
        }
        $cart[$cartKey] = [
            'quantity' => min(99, $quantity + $addedQuantity),
            'variant_id' => $variantId ?: (is_array($current) ? $current['variant_id'] ?? null : null),
            'source' => 'cart',
        ];
        session(['cart' => $cart]);
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return back()->with('success', 'Product added to cart successfully.')
            ->with('success_type', 'cart');
    }

    public function buyNow(Request $request, $product)
    {
        abort_unless(Product::query()->forStorefront()->where('p.id', $product)
            ->exists(), 404);
        $quantity = min(99, max(1, (int) $request->input('quantity', 1)));
        $variantId = (int) $request->input('variant_id', 0);
        $variant = ProductVariant::query()
            ->where('id', $variantId)
            ->where('product_id', $product)
            ->first();
        $variantId = $variant ? $variant->id : null;
        $cartKey = $product.($variantId ? '_'.$variantId : '');
        $stockLimit = 10;
        if ($variant) {
            $stockLimit = (int) ($variant->product_qty ?? $variant->stock_qty ?? $variant->available_stock ?? 10);
        } else {
            $mainProduct = Product::query()
                ->where('id', $product)
                ->first();
            if ($mainProduct) {
                $stockLimit = (int) ($mainProduct->product_qty ?? $mainProduct->stock_qty ?? $mainProduct->available_stock ?? 10);
            }
        }
        if ($quantity > $stockLimit) {
            return back()->with('error', 'Cannot add more than available stock.');
        }
        // Keep existing cart, but if you want buy_now to overwrite, we can keep it as is, or just add to cart.
        // The previous logic cleared the cart. Let's keep it clearing but with the proper key.
        session(['cart' => [$cartKey => [
            'quantity' => $quantity,
            'variant_id' => $variantId ?: null,
            'source' => 'buy_now',
        ]]]);
        session()->forget('checkout_coupon');

        return redirect('checkout');
    }

    public function remove(Request $request, $cartKey)
    {
        $cart = session('cart', []);
        $removedItem = $cart[$cartKey] ?? null;
        if (is_array($removedItem) && ($removedItem['source'] ?? null) === 'combo' && ! empty($removedItem['combo_id'])) {
            foreach ($cart as $key => $item) {
                if (is_array($item) && ($item['source'] ?? null) === 'combo' && ($item['combo_id'] ?? null) == $removedItem['combo_id']) {
                    unset($cart[$key]);
                }
            }
        } else {
            unset($cart[$cartKey]);
        }
        session(['cart' => $cart]);
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $subtotal = house_products_from_session('cart')
                ->sum(fn ($product) => house_product_price($product) * ($product->cart_qty ?? 1));

            return response()->json([
                'success' => true,
                'removed' => true,
                'reload' => is_array($removedItem) && ($removedItem['source'] ?? null) === 'combo',
                'subtotal' => house_money($subtotal),
                'total' => house_money($subtotal),
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return back();
    }

    public function clear(Request $request)
    {
        session()->forget('cart');
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'cleared' => true,
                'subtotal' => house_money(0),
                'total' => house_money(0),
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return back();
    }

    public function quantity(Request $request, $cartKey, $action)
    {
        abort_unless(in_array($action, ['inc', 'dec'], true), 404);
        $cart = session('cart', []);
        if (! isset($cart[$cartKey])) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'removed' => true,
                    'cart_count' => house_session_item_count('cart'),
                    'wishlist_count' => house_session_item_count('wishlist'),
                ]);
            }

            return back();
        }
        $item = $cart[$cartKey];
        $item = is_array($item) ? $item : ['quantity' => $item, 'source' => 'cart'];
        $qty = max(0, (int) ($item['quantity'] ?? 0));
        if (($item['source'] ?? null) === 'combo' && ! empty($item['combo_id'])) {
            $comboKeys = collect($cart)->filter(fn ($entry) => is_array($entry) && ($entry['source'] ?? null) === 'combo'
                && ($entry['combo_id'] ?? null) == $item['combo_id'])->keys();
            $newQty = $action === 'inc' ? $qty + 1 : $qty - 1;
            if ($newQty > 10) {
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json(['error' => 'A box is limited to 10 units per order.'], 400);
                }
                return back()->withErrors(['cart' => 'A box is limited to 10 units per order.']);
            }
            if ($newQty > 0) {
                $combo = GiftCombo::query()->find($item['combo_id']);
                if (! $combo || $newQty > (int) $combo->stock_quantity) {
                    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                        return response()->json(['error' => 'The selected box quantity exceeds available box stock.'], 400);
                    }
                    return back()->withErrors(['cart' => 'The selected box quantity exceeds available box stock.']);
                }
                foreach ($comboKeys as $key) {
                    $variant = ProductVariant::query()->find($cart[$key]['variant_id'] ?? 0);
                    if (! $variant || $newQty > (int) $variant->product_qty) {
                        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                            return response()->json(['error' => 'The selected box quantity exceeds available stock.'], 400);
                        }
                        return back()->withErrors(['cart' => 'The selected box quantity exceeds available stock.']);
                    }
                    $cart[$key]['quantity'] = $newQty;
                }
            } else {
                foreach ($comboKeys as $key) {
                    unset($cart[$key]);
                }
            }
            session(['cart' => $cart]);
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                $subtotal = house_products_from_session('cart')
                    ->sum(fn ($product) => house_product_price($product) * ($product->cart_qty ?? 1));
                return response()->json([
                    'success' => true,
                    'reload' => true,
                    'subtotal' => house_money($subtotal),
                    'total' => house_money($subtotal),
                    'cart_count' => house_session_item_count('cart'),
                    'wishlist_count' => house_session_item_count('wishlist'),
                ]);
            }
            return back();
        }
        if ($action === 'inc') {
            $productId = (int) explode('_', $cartKey)[0];
            $variantId = (int) ($item['variant_id'] ?? 0);
            $stockLimit = 10;
            if ($variantId > 0) {
                $variant = ProductVariant::query()
                    ->where('id', $variantId)
                    ->first();
                if ($variant) {
                    $stockLimit = (int) ($variant->product_qty ?? $variant->stock_qty ?? $variant->available_stock ?? 10);
                }
            } else {
                $mainProduct = Product::query()
                    ->where('id', $productId)
                    ->first();
                if ($mainProduct) {
                    $stockLimit = (int) ($mainProduct->product_qty ?? $mainProduct->stock_qty ?? $mainProduct->available_stock ?? 10);
                }
            }
            if ($qty + 1 > $stockLimit) {
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json([
                        'error' => 'Cannot add more than available stock.',
                        'cart_count' => house_session_item_count('cart'),
                        'wishlist_count' => house_session_item_count('wishlist'),
                    ], 400);
                }

                return back()->with('error', 'Cannot add more than available stock.');
            }
            $item['quantity'] = $qty + 1;
            $cart[$cartKey] = $item;
        } else {
            $qty--;
            if ($qty <= 0) {
                unset($cart[$cartKey]);
            } else {
                $item['quantity'] = $qty;
                $cart[$cartKey] = $item;
            }
        }
        session(['cart' => $cart]);
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $cartProducts = house_products_from_session('cart');
            $subtotal = $cartProducts->sum(fn ($product) => house_product_price($product) * ($product->cart_qty ?? 1));
            $product = $cartProducts->firstWhere('cart_key', $cartKey);
            $lineTotal = $product ? house_product_price($product) * ($product->cart_qty ?? 1) : 0;

            return response()->json([
                'success' => true,
                'removed' => ! $product,
                'quantity' => $product?->cart_qty ?? 0,
                'line_total' => house_money($lineTotal),
                'subtotal' => house_money($subtotal),
                'total' => house_money($subtotal),
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return back();
    }
}
