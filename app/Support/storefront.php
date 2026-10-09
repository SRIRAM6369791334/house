<?php

use App\Models\Coupon;
use App\Models\GiftCombo;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingAmountSetting;

if (! function_exists('house_variant_prices')) {
    function house_variant_prices()
    {
        return ProductVariant::query()->priceSummary();
    }
}
if (! function_exists('house_product_query')) {
    function house_product_query()
    {
        return Product::query()->forStorefront();
    }
}
if (! function_exists('house_product_price')) {
    function house_product_price($product)
    {
        return $product->offer_price ?? $product->product_regular_price ?? $product->product_mrp_price ?? $product->mrp_price ?? 0;
    }
}
if (! function_exists('house_product_gst_rate')) {
    function house_product_gst_rate($product)
    {
        return max(0, (float) ($product->product_gst ?? 0));
    }
}
if (! function_exists('house_included_gst_amount')) {
    function house_included_gst_amount($amount, $gstRate)
    {
        $amount = (float) $amount;
        $gstRate = (float) $gstRate;
        if ($amount <= 0 || $gstRate <= 0) {
            return 0;
        }

        return $amount * ($gstRate / (100 + $gstRate));
    }
}
if (! function_exists('house_cart_included_gst')) {
    function house_cart_included_gst($cartProducts)
    {
        return $cartProducts->sum(function ($product) {
            $lineTotal = house_product_price($product) * $product->cart_qty;

            return house_included_gst_amount($lineTotal, house_product_gst_rate($product));
        });
    }
}
if (! function_exists('house_variant_size_value_sql')) {
    function house_variant_size_value_sql($alias = 'product_varient')
    {
        $prefix = $alias ? $alias.'.' : '';

        return "NULLIF(NULLIF(TRIM(COALESCE({$prefix}value, {$prefix}varient, {$prefix}size_value)), ''), '0')";
    }
}
if (! function_exists('house_money')) {
    function house_money($value)
    {
        return '₹'.number_format((float) $value, 0);
    }
}
if (! function_exists('house_product_images')) {
    function house_product_images($product)
    {
        $raw = $product->product_image ?? '';
        $images = [];
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $images = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : preg_split('/[,|]/', $raw);
        }
        $img2 = $product->product_image_2 ?? '';
        if (is_string($img2) && $img2 !== '') {
            $images[] = $img2;
        }
        $images = collect($images)->flatten()
            ->filter()
            ->map(function ($image) {
                $image = trim((string) $image, " \t\n\r\x00\v\"'");
                if ($image === '') {
                    return null;
                }

                return house_main_media_url($image, 'images');
            })
            ->filter()
            ->values();
        if ($images->isEmpty()) {
            $images = collect([asset('images/product/01.jpg')]);
        }
        if ($images->count() === 1) {
            $images->push($images->first());
        }

        return $images;
    }
}
if (! function_exists('house_category_image_url')) {
    function house_category_image_url($image)
    {
        $image = trim((string) $image, " \t\n\r\x00\v\"'");
        if ($image === '') {
            return asset('images/product/01.jpg');
        }

        return house_main_media_url($image, 'images');
    }
}
if (! function_exists('house_category_banner_url')) {
    function house_category_banner_url($banner, $defaultBanner = 'shop.png')
    {
        $banner = trim((string) $banner, " \t\n\r\x00\v\"'");
        if ($banner === '') {
            return asset('images/banner/'.$defaultBanner);
        }

        return house_main_media_url($banner, 'images');
    }
}
if (! function_exists('house_web_image_url')) {
    function house_web_image_url($image)
    {
        $image = trim((string) $image, " \t\n\r\x00\v\"'");
        if ($image === '') {
            return asset('images/banner/add.jpg');
        }

        return house_main_media_url($image, 'images');
    }
}
if (! function_exists('house_main_media_url')) {
    function house_main_media_url($path, $directory = 'images')
    {
        $path = trim((string) $path, " \t\n\r\x00\v\"'");
        if ($path === '') {
            return asset('images/product/01.jpg');
        }
        $mainUrl = rtrim((string) env('MAIN_URL'), '/');
        $directory = trim((string) $directory, '/');
        if ($mainUrl === '') {
            return asset(ltrim($path, '/'));
        }
        if (preg_match('/^https?:\/\//i', $path)) {
            $sourceHost = strtolower((string) parse_url($path, PHP_URL_HOST));
            $mainHost = strtolower((string) parse_url($mainUrl, PHP_URL_HOST));
            if ($sourceHost !== $mainHost && ! in_array($sourceHost, ['127.0.0.1', 'localhost'], true)) {
                return $path;
            }
            $path = ltrim((string) parse_url($path, PHP_URL_PATH), '/');
        } else {
            $path = ltrim($path, '/');
        }
        if (str_starts_with($path, 'storage/')) {
            $path = 'images/'.ltrim(substr($path, strlen('storage/')), '/');
        }
        if (str_starts_with($path, 'images/') || str_starts_with($path, 'uploads/')) {
            return $mainUrl.'/'.$path;
        }
        if ($directory !== '' && ! str_starts_with($path, $directory.'/')) {
            $path = $directory.'/'.$path;
        }

        return $mainUrl.'/'.$path;
    }
}
if (! function_exists('house_main_product_image_url')) {
    function house_main_product_image_url($product)
    {
        $raw = $product->product_image ?? '';
        $images = [];
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $images = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : preg_split('/[,|]/', $raw);
        }
        $image = collect($images)->flatten()
            ->filter()
            ->first();

        return $image ? house_main_media_url($image, 'images') : asset('images/product/01.jpg');
    }
}
if (! function_exists('house_product_media_url')) {
    function house_product_media_url($image)
    {
        $image = trim((string) $image, " \t\n\r\x00\v\"'");
        if ($image === '') {
            return asset('images/product/01.jpg');
        }

        return house_main_media_url($image, 'images');
    }
}
if (! function_exists('house_color_label')) {
    function house_color_label($color)
    {
        $color = trim((string) $color);
        if (preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $names = [
                '#000000' => 'Black',
                '#ffffff' => 'White',
                '#ff0000' => 'Red',
                '#00ff00' => 'Green',
                '#0000ff' => 'Blue',
                '#ffff00' => 'Yellow',
                '#808080' => 'Grey',
                '#c0c0c0' => 'Silver',
                '#800080' => 'Purple',
                '#ffa500' => 'Orange',
                '#ffc0cb' => 'Pink',
                '#a52a2a' => 'Brown',
            ];

            return $names[strtolower($color)] ?? 'Color';
        }

        return $color;
    }
}
if (! function_exists('house_color_swatch')) {
    function house_color_swatch($color, $fallback = '#e5e5e5')
    {
        $color = trim((string) $color);
        if (preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $color)) {
            return $color;
        }

        $key = preg_replace('/[^a-z0-9]+/', ' ', strtolower($color));
        $key = trim($key);
        $swatches = [
            'black' => '#111111',
            'white' => '#ffffff',
            'red' => '#b40016',
            'green' => '#2f7d32',
            'forest green' => '#228b22',
            'olive green' => '#556b2f',
            'mint green' => '#98ff98',
            'blue' => '#1f5f9f',
            'navy' => '#1f2a44',
            'navy blue' => '#1f2a44',
            'sky blue' => '#87ceeb',
            'light blue' => '#add8e6',
            'royal blue' => '#4169e1',
            'dark blue' => '#00008b',
            'yellow' => '#f2c94c',
            'grey' => '#808080',
            'gray' => '#808080',
            'light grey' => '#d3d3d3',
            'light gray' => '#d3d3d3',
            'dark grey' => '#4a4a4a',
            'dark gray' => '#4a4a4a',
            'silver' => '#c0c0c0',
            'steel grey' => '#71797e',
            'steel gray' => '#71797e',
            'charcoal' => '#36454f',
            'charcoal grey' => '#36454f',
            'charcoal gray' => '#36454f',
            'purple' => '#800080',
            'plum' => '#8e4585',
            'dusty plum' => '#8b6f82',
            'mauve' => '#b784a7',
            'dusty mauve' => '#b48b9f',
            'lavender' => '#b57edc',
            'orange' => '#f2994a',
            'pink' => '#ffc0cb',
            'baby pink' => '#f4c2c2',
            'brown' => '#795548',
            'cream' => '#f5f0df',
            'beige' => '#d8c3a5',
            'khaki' => '#c3b091',
            'maroon' => '#800000',
            'wine' => '#722f37',
            'gold' => '#c9a227',
        ];

        return $swatches[$key] ?? $fallback;
    }
}
if (! function_exists('house_products_from_session')) {
    function house_products_from_session($key)
    {
        $items = session($key, []);
        $ids = collect($items)->keys()
            ->map(fn ($id) => (int) explode('_', $id)[0])
            ->filter()
            ->values();
        if ($ids->isEmpty()) {
            return collect();
        }
        $products = house_product_query()->whereIn('p.id', $ids)
            ->orderByDesc('p.id')
            ->get()
            ->keyBy('id');
        $cartProducts = collect();
        foreach ($items as $cartKey => $item) {
            $productId = (int) explode('_', $cartKey)[0];
            if ($products->has($productId)) {
                $product = clone $products->get($productId);
                $item = is_array($item) ? $item : ['quantity' => $item];
                $product->cart_key = $cartKey;
                $product->cart_qty = max(1, (int) ($item['quantity'] ?? 1));
                $product->cart_variant_id = null;
                $product->cart_variant_label = null;
                $product->cart_variant_size = null;
                $product->cart_variant_color = null;
                $product->cart_source = $item['source'] ?? 'cart';
                $product->cart_combo_id = $item['combo_id'] ?? null;
                $stockLimit = (int) ($product->product_qty ?? $product->stock_qty ?? $product->available_stock ?? 10);
                $variantId = (int) ($item['variant_id'] ?? 0);
                if ($variantId > 0) {
                    $variant = ProductVariant::query()
                        ->where('id', $variantId)
                        ->where('product_id', $product->id)
                        ->first();
                    if ($variant) {
                        $size = trim((string) (($variant->value ?: $variant->varient) ?: $variant->size_value));
                        $variantDetails = is_string($variant->varient_details) ? json_decode($variant->varient_details, true) : $variant->varient_details;
                        $variantDetails = is_array($variantDetails) ? $variantDetails : [];
                        $color = house_color_label($variantDetails['color_name'] ?? $variant->varient_name ?? '');
                        $product->cart_variant_id = $variant->id;
                        $product->cart_variant_size = $size !== '' && $size !== '0' ? $size : null;
                        $product->cart_variant_color = trim((string) $color) !== '' && trim((string) $color) !== '0' ? $color : null;
                        $product->cart_variant_label = collect([$size, $color])->filter(fn ($value) => trim((string) $value) !== '' && trim((string) $value) !== '0')
                            ->implode(' / ');
                        $product->offer_price = $variant->offer_price ?? $product->offer_price;
                        $product->mrp_price = $variant->mrp_price ?? $product->mrp_price;
                        $product->product_gst = $variant->product_gst ?? $product->product_gst;
                        $stockLimit = (int) ($variant->product_qty ?? $variant->stock_qty ?? $variant->available_stock ?? 10);
                    }
                }
                $product->cart_stock = $stockLimit;
                $cartProducts->push($product);
            }
        }

        $comboGroups = $cartProducts->where('cart_source', 'combo')->groupBy('cart_combo_id');
        foreach ($comboGroups as $comboId => $group) {
            if (! $comboId || $group->count() !== 3 || $group->pluck('cart_qty')->unique()->count() !== 1) {
                continue;
            }
            $combo = GiftCombo::query()->find($comboId);
            $boxStock = min(10, (int) ($combo?->stock_quantity ?? 0), $group->min('cart_stock'));
            foreach ($group as $product) {
                $product->cart_stock = $boxStock;
            }
            $comboPrice = (float) ($combo?->offer_price > 0 ? $combo->offer_price : ($combo?->mrp_price ?? 0));
            $regularPrices = $group->map(fn ($product) => max(0, (float) house_product_price($product)))->values();
            $regularTotal = $regularPrices->sum();
            if ($comboPrice <= 0 || $regularTotal <= 0) {
                continue;
            }
            $remainingCents = (int) round($comboPrice * 100);
            foreach ($group->values() as $index => $product) {
                $remainingItems = $group->count() - $index - 1;
                $cents = $remainingItems === 0 ? $remainingCents
                    : min($remainingCents - $remainingItems, max(1, (int) round($comboPrice * 100 * $regularPrices[$index] / $regularTotal)));
                $product->offer_price = $cents / 100;
                $remainingCents -= $cents;
            }
        }

        return $cartProducts->values();
    }
}
if (! function_exists('house_checkout_coupon')) {
    function house_checkout_coupon($code)
    {
        $coupon = Coupon::query()
            ->whereRaw('UPPER(codename) = ?', [strtoupper(trim((string) $code))])
            ->first();
        if (! $coupon) {
            return null;
        }

        return [
            'code' => $coupon->codename,
            'type' => (int) $coupon->discounttype === 2 ? 'percentage' : 'fixed',
            'value' => (float) $coupon->discount,
            'min_order_amount' => (float) $coupon->mini_amt,
            'start_date' => $coupon->start_date,
            'end_date' => $coupon->end_date,
        ];
    }
}
if (! function_exists('house_coupon_date_error')) {
    function house_coupon_date_error($coupon)
    {
        if (! $coupon) {
            return 'Invalid coupon code.';
        }
        $today = now()->toDateString();
        if (! empty($coupon['start_date']) && $coupon['start_date'] > $today) {
            return 'This coupon is not active yet.';
        }
        if (! empty($coupon['end_date']) && $coupon['end_date'] < $today) {
            return 'This coupon has expired.';
        }

        return null;
    }
}
if (! function_exists('house_coupon_discount')) {
    function house_coupon_discount($coupon, $subtotal)
    {
        if (! $coupon || $subtotal <= 0) {
            return 0;
        }
        if (house_coupon_date_error($coupon)) {
            return 0;
        }
        if ($subtotal < (float) ($coupon['min_order_amount'] ?? 0)) {
            return 0;
        }
        $discount = $coupon['type'] === 'percentage' ? $subtotal * ((float) $coupon['value'] / 100) : (float) $coupon['value'];

        return min($subtotal, max(0, $discount));
    }
}
if (! function_exists('house_checkout_shipping_amount')) {
    function house_checkout_shipping_amount($subtotal, $shiprocketAmount)
    {
        $configuredAmount = ShippingAmountSetting::query()
            ->where('minimum_amount', '<=', (float) $subtotal)
            ->orderByDesc('minimum_amount')
            ->value('shipping_amount');

        return $configuredAmount === null ? (float) $shiprocketAmount : (float) $configuredAmount;
    }
}
if (! function_exists('house_session_item_count')) {
    function house_session_item_count($key)
    {
        return collect(session($key, []))
            ->filter(fn ($item) => (is_array($item) ? (int) ($item['quantity'] ?? 0) : (int) $item) > 0)
            ->count();
    }
}
