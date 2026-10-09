<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request)
    {
        $productKey = $request->input('product') ?: ($request->input('slug') ?: $request->input('id'));
        if (! $productKey) {
            $firstProduct = Product::query()
                ->whereNull('deleted_at')
                ->first();
            if ($firstProduct && ! empty($firstProduct->slug)) {
                return redirect('/single-product?slug='.$firstProduct->slug);
            }

            return redirect()->route('shop');
        }
        $productQuery = Product::query()->forStorefront();
        $product = $productQuery->where(function ($query) use ($productKey) {
            $query->where('p.slug', $productKey);
            if (is_numeric($productKey)) {
                $query->orWhere('p.id', $productKey);
            }
        })
            ->first();
        abort_if(! $product, 404);
        $variants = $product->variants()
            ->orderBy('id')
            ->get();
        $childImages = $product->images()
            ->orderBy('id')
            ->pluck('product_child_image');
        $thumbImages = $childImages->filter()
            ->map(fn ($image) => house_product_media_url($image))
            ->values();
        $galleryImages = ($thumbImages->isNotEmpty() ? $thumbImages : collect(house_product_images($product))->merge($variants->pluck('varient_img')
            ->filter()
            ->map(fn ($image) => house_product_media_url($image))))->filter()
            ->unique()
            ->values();
        $sizeOptions = $variants->map(function ($variant) {
            return trim((string) (($variant->value ?: $variant->varient) ?: $variant->size_value));
        })
            ->filter(fn ($value) => $value !== '' && $value !== '0')
            ->unique()
            ->values();
        $colorOptions = $variants->map(function ($variant) {
            $details = is_string($variant->varient_details) ? json_decode($variant->varient_details, true) : $variant->varient_details;
            $details = is_array($details) ? $details : [];

            return trim((string) ($details['color_name'] ?? $variant->varient_name ?? ''));
        })
            ->filter(fn ($value) => $value !== '' && $value !== '0')
            ->map(fn ($value) => ['value' => $value, 'label' => house_color_label($value), 'swatch' => house_color_swatch($value)])
            ->unique('label')
            ->values();
        $productColor = trim((string) ($product->color ?? ''));
        if ($colorOptions->count() === 1 && preg_match('/^#[0-9a-fA-F]{6}$/', $productColor)) {
            $colorOptions = $colorOptions->map(function (array $option) use ($productColor) {
                $option['swatch'] = $productColor;

                return $option;
            });
        }
        $familyName = static function (Product $item, string $color): string {
            $name = trim((string) $item->product_name);
            foreach (array_unique([$color, house_color_label($color)]) as $colorName) {
                if ($colorName !== '') {
                    $name = preg_replace('/(?<![[:alnum:]])'.preg_quote($colorName, '/').'(?![[:alnum:]])/iu', ' ', $name);
                }
            }

            return trim((string) preg_replace('/[^\pL\pN]+/u', ' ', mb_strtolower($name)));
        };
        $currentFamily = $familyName($product, $productColor ?: (string) ($colorOptions->first()['value'] ?? ''));
        $productColorLabel = house_color_label($productColor);
        $sharedNameSuffix = static function (Product $current, Product $other): ?int {
            $currentWords = preg_split('/[^\pL\pN]+/u', mb_strtolower(trim((string) $current->product_name)), -1, PREG_SPLIT_NO_EMPTY);
            $otherWords = preg_split('/[^\pL\pN]+/u', mb_strtolower(trim((string) $other->product_name)), -1, PREG_SPLIT_NO_EMPTY);
            $shared = 0;
            while ($shared < min(count($currentWords), count($otherWords))
                && $currentWords[count($currentWords) - 1 - $shared] === $otherWords[count($otherWords) - 1 - $shared]) {
                $shared++;
            }
            if ($shared < 4 || $shared === count($currentWords) || $shared === count($otherWords)) {
                return null;
            }

            return $shared;
        };
        $otherColorProducts = collect();
        if ($currentFamily !== '') {
            $otherColorProducts = Product::query()
                ->whereNull('deleted_at')
                ->where('id', '!=', $product->id)
                ->when($product->category_id, fn ($query) => $query->where('category_id', $product->category_id))
                ->when($product->subcate_name, fn ($query) => $query->where('subcate_name', $product->subcate_name))
                ->with('variants:id,product_id,varient_name,varient_details')
                ->get(['id', 'category_id', 'subcate_name', 'product_name', 'slug', 'color'])
                ->map(function (Product $item) use ($familyName, $currentFamily, $product, $sharedNameSuffix) {
                    $color = trim((string) ($item->color ?? ''));
                    if ($color === '') {
                        $variant = $item->variants->first();
                        $details = $variant && is_string($variant->varient_details)
                            ? json_decode($variant->varient_details, true) : ($variant->varient_details ?? []);
                        $color = trim((string) ((is_array($details) ? ($details['color_name'] ?? null) : null) ?? ($variant->varient_name ?? '')));
                    }
                    $sharedSuffix = $sharedNameSuffix($product, $item);
                    if ($color === '' || ($familyName($item, $color) !== $currentFamily && $sharedSuffix === null)) {
                        return null;
                    }

                    return ['product' => $item, 'value' => $color, 'shared_suffix' => $sharedSuffix, 'swatch' => house_color_swatch($color)];
                })
                ->filter()
                ->values();
            $familySuffix = $otherColorProducts->pluck('shared_suffix')->filter()->min();
            $namePrefix = static function (Product $item, int $suffix): string {
                $words = preg_split('/[^\pL\pN]+/u', mb_strtolower(trim((string) $item->product_name)), -1, PREG_SPLIT_NO_EMPTY);

                return ucwords(implode(' ', array_slice($words, 0, -$suffix)));
            };
            if ($familySuffix && str_starts_with($productColor, '#')) {
                $productColorLabel = $namePrefix($product, $familySuffix);
            }
            $otherColorProducts = $otherColorProducts
                ->map(function (array $option) use ($familySuffix, $namePrefix) {
                    $option['label'] = $familySuffix && str_starts_with($option['value'], '#')
                        ? $namePrefix($option['product'], $familySuffix)
                        : house_color_label($option['value']);

                    return $option;
                })
                ->unique(fn ($option) => mb_strtolower($option['label']))
                ->values();
        }
        $relatedProducts = Product::query()->forStorefront()->where('p.id', '!=', $product->id)
            ->when($product->category_id, fn ($query) => $query->where('p.category_id', $product->category_id))
            ->orderByDesc('p.id')
            ->limit(8)
            ->get();
        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::query()->forStorefront()->where('p.id', '!=', $product->id)
                ->orderByDesc('p.id')
                ->limit(8)
                ->get();
        }
        $reviews = $product->reviews()
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();
        $reviewCount = $reviews->count();
        $avgRating = $reviewCount > 0 ? round((float) $reviews->avg('ratings'), 1) : 0;
        $canReview = false;
        $hasPurchased = false;
        $isDelivered = false;
        $userReview = null;
        if (auth()->check()) {
            $productOrders = ProductOrder::query()
                ->join('product_order_items', 'product_order_items.order_id', '=', 'product_orders.id')
                ->where('product_orders.user_id', auth()->id())
                ->where('product_order_items.product_id', $product->id)
                ->whereNotIn('product_orders.status', ['cancelled', 'return'])
                ->select('product_orders.status')
                ->get();
            $hasPurchased = $productOrders->isNotEmpty();
            $isDelivered = $productOrders->contains(function ($order) {
                $st = strtolower(trim((string) ($order->status ?? '')));

                return $st === 'delivered' || $st === '4';
            });
            $canReview = $isDelivered;
            $userReview = Review::query()
                ->where('prod_id', $product->id)
                ->where('user_id', auth()->id())
                ->orderByDesc('id')
                ->first();
        }

        return view('pages.shopdetails', compact('product', 'variants', 'galleryImages', 'sizeOptions', 'colorOptions', 'productColorLabel', 'otherColorProducts', 'relatedProducts', 'reviews', 'reviewCount', 'avgRating', 'canReview', 'hasPurchased', 'isDelivered', 'userReview'));
    }

    public function storeReview(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'ratings' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['required', 'string', 'max:600'],
        ]);
        $product = Product::query()
            ->where('id', $data['product_id'])
            ->first();
        abort_if(! $product, 404);
        $productOrders = ProductOrder::query()
            ->join('product_order_items', 'product_order_items.order_id', '=', 'product_orders.id')
            ->where('product_orders.user_id', auth()->id())
            ->where('product_order_items.product_id', $product->id)
            ->whereNotIn('product_orders.status', ['cancelled', 'return'])
            ->select('product_orders.status')
            ->get();
        if ($productOrders->isEmpty()) {
            return back()->withErrors(['review' => 'Only customers who ordered this product can submit a review.'])
                ->withInput();
        }
        $isDelivered = $productOrders->contains(function ($order) {
            $st = strtolower(trim((string) ($order->status ?? '')));

            return $st === 'delivered' || $st === '4';
        });
        if (! $isDelivered) {
            return back()->withErrors(['review' => 'Your order is currently in progress. You can submit a review once your product has been delivered.'])
                ->withInput();
        }
        $alreadyReviewed = Review::query()
            ->where('prod_id', $product->id)
            ->where('user_id', auth()->id())
            ->exists();
        if ($alreadyReviewed) {
            return back()->withErrors(['review' => 'You have already submitted a review for this product.']);
        }
        $reviewData = [
            'name' => auth()->user()->name,
            'prod_id' => $product->id,
            'review' => $data['review'],
            'ratings' => $data['ratings'],
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $reviewData['user_id'] = auth()->id();
        $reviewData['email'] = auth()->user()->email;
        Review::query()
            ->create($reviewData);

        return back()->with('review_success', 'Your review has been submitted for approval.');
    }
}
