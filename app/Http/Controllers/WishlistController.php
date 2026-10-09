<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return view('pages.wishlist', [
            'wishlistProducts' => house_products_from_session('wishlist'),
        ]);
    }

    public function add(Request $request, $product)
    {
        abort_unless(Product::query()->forStorefront()->where('p.id', $product)->exists(), 404);

        $variantId = (int) $request->input('variant_id', 0);
        $variant = ProductVariant::query()
            ->where('id', $variantId)
            ->where('product_id', $product)
            ->first();
        $variantId = $variant ? $variant->id : null;
        $wishlistKey = $product.($variantId ? '_'.$variantId : '');
        $wishlist = session('wishlist', []);
        if (isset($wishlist[$wishlistKey])) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'info' => 'This product is already in your wishlist.',
                    'cart_count' => house_session_item_count('cart'),
                    'wishlist_count' => house_session_item_count('wishlist'),
                ]);
            }

            return back()->with('info', 'This product is already in your wishlist.');
        }

        $wishlist[$wishlistKey] = [
            'quantity' => 1,
            'variant_id' => $variantId ?: null,
            'source' => 'wishlist',
        ];
        session(['wishlist' => $wishlist]);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return back()
            ->with('success', 'Product added to wishlist successfully.')
            ->with('success_type', 'wishlist');
    }

    public function remove(Request $request, $product)
    {
        $wishlist = session('wishlist', []);
        unset($wishlist[$product]);
        session(['wishlist' => $wishlist]);
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'removed' => true,
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return back();
    }

    public function quantity(Request $request, $product, $action)
    {
        abort_unless(in_array($action, ['inc', 'dec'], true), 404);

        $wishlist = session('wishlist', []);
        $item = $wishlist[$product] ?? 0;
        $item = is_array($item) ? $item : ['quantity' => $item, 'source' => 'wishlist'];
        $qty = max(0, (int) ($item['quantity'] ?? 0));

        if ($action === 'inc') {
            $item['quantity'] = $qty + 1;
            $wishlist[$product] = $item;
        } else {
            $qty--;

            if ($qty <= 0) {
                unset($wishlist[$product]);
            } else {
                $item['quantity'] = $qty;
                $wishlist[$product] = $item;
            }
        }

        session(['wishlist' => $wishlist]);
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'removed' => ! isset($wishlist[$product]),
                'quantity' => max(0, (int) (is_array($wishlist[$product] ?? null) ? ($wishlist[$product]['quantity'] ?? 0) : ($wishlist[$product] ?? 0))),
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return back();
    }

    public function clear(Request $request)
    {
        session()->forget('wishlist');
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'cleared' => true,
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return back();
    }

    public function moveToCart(Request $request, $product)
    {
        $productId = (int) explode('_', $product)[0];
        abort_unless(Product::query()->forStorefront()->where('p.id', $productId)->exists(), 404);

        $cart = session('cart', []);
        $wishlist = session('wishlist', []);
        $current = $cart[$product] ?? 0;
        $quantity = is_array($current) ? (int) ($current['quantity'] ?? 0) : (int) $current;
        $wishlistItem = $wishlist[$product] ?? 1;
        $wishlistQuantity = is_array($wishlistItem) ? (int) ($wishlistItem['quantity'] ?? 1) : (int) $wishlistItem;
        $wishlistVariantId = is_array($wishlistItem) ? ($wishlistItem['variant_id'] ?? null) : null;
        $cart[$product] = [
            'quantity' => $quantity + max(1, $wishlistQuantity),
            'variant_id' => $wishlistVariantId,
            'source' => 'cart',
        ];
        unset($wishlist[$product]);
        session(['cart' => $cart]);
        session(['wishlist' => $wishlist]);
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'removed' => true,
                'cart_count' => house_session_item_count('cart'),
                'wishlist_count' => house_session_item_count('wishlist'),
            ]);
        }

        return redirect('cart')
            ->with('success', 'Product moved to cart successfully.')
            ->with('success_type', 'cart');
    }
}
