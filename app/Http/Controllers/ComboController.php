<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GiftCombo;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\ReviewVideo;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'combo_id' => ['required', 'integer'],
            'shirt_product_id' => ['required', 'integer'],
            'shirt_variant_id' => ['required', 'integer'],
            'perfume_product_id' => ['required', 'integer'],
            'perfume_variant_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'next' => ['nullable', 'in:cart,checkout'],
        ]);
        $combo = GiftCombo::query()->findOrFail($data['combo_id']);
        [$shirtChoices, $perfumeChoices, $watchChoice, $availabilityIssues] = $this->selectionChoices($combo);
        $shirtChoice = $shirtChoices->first(fn ($choice) => (int) $choice['product']->id === (int) $data['shirt_product_id']);
        $perfumeChoice = $perfumeChoices->first(fn ($choice) => (int) $choice['product']->id === (int) $data['perfume_product_id']);
        $shirtVariant = $shirtChoice ? $shirtChoice['variants']->firstWhere('id', $data['shirt_variant_id']) : null;
        $perfumeVariant = $perfumeChoice ? $perfumeChoice['variants']->firstWhere('id', $data['perfume_variant_id']) : null;
        if (! $shirtVariant || ! $perfumeVariant || ! $watchChoice) {
            return back()->withErrors(['combo' => $availabilityIssues[0] ?? 'Choose an available shirt, size, and perfume for this box.'])->withInput();
        }
        $quantity = (int) $data['quantity'];
        if ($quantity > (int) $combo->stock_quantity) {
            return back()->withErrors(['combo' => 'The selected box quantity exceeds available box stock.'])->withInput();
        }
        foreach ([$shirtVariant, $perfumeVariant, $watchChoice['variant']] as $variant) {
            if ($quantity > (int) $variant->product_qty) {
                return back()->withErrors(['combo' => 'The selected box quantity exceeds available stock.'])->withInput();
            }
        }
        $cart = [];
        foreach ([
            [$shirtChoice['product'], $shirtVariant],
            [$watchChoice['product'], $watchChoice['variant']],
            [$perfumeChoice['product'], $perfumeVariant],
        ] as [$product, $variant]) {
            $cart[$product->id.'_'.$variant->id] = [
                'quantity' => $quantity,
                'variant_id' => $variant->id,
                'source' => 'combo',
                'combo_id' => $combo->id,
            ];
        }
        session(['cart' => $cart]);
        session()->forget('checkout_coupon');

        return redirect(($data['next'] ?? 'checkout') === 'cart' ? 'cart' : 'checkout');
    }

    private function selectionChoices(GiftCombo $combo): array
    {
        $categories = Category::query()->select('id', 'category_name')->get();
        $productsFor = static function (string $type) use ($categories) {
            $ids = $categories->filter(fn ($category) => str_contains(strtolower((string) $category->category_name), $type))
                ->pluck('id');

            return Product::query()->whereNull('deleted_at')->whereIn('category_id', $ids)
                ->with(['variants' => fn ($query) => $query->orderBy('id')])
                ->orderBy('id')->get();
        };
        $allowedSizes = collect(explode(',', (string) $combo->shirt_size))
            ->map(fn ($size) => strtoupper(trim($size)))->filter();
        $shirtChoices = $productsFor('shirt')->map(function ($product) use ($allowedSizes) {
            $variants = $product->variants->filter(function ($variant) use ($allowedSizes) {
                $size = strtoupper(trim((string) ($variant->value ?: $variant->varient ?: $variant->size_value)));

                return (int) $variant->product_qty > 0 && ($allowedSizes->isEmpty() || $allowedSizes->contains($size));
            })->values();

            $firstVariant = $variants->first();
            $details = $firstVariant && is_string($firstVariant->varient_details)
                ? json_decode($firstVariant->varient_details, true) : ($firstVariant->varient_details ?? []);
            $colorName = trim((string) ((is_array($details) ? ($details['color_name'] ?? null) : null) ?: $product->product_name));

            return [
                'product' => $product,
                'variants' => $variants,
                'color_label' => house_color_label($colorName),
                'swatch' => house_color_swatch($product->color ?: $colorName),
            ];
        })->filter(fn ($choice) => $choice['variants']->isNotEmpty())->values();
        $volume = strtoupper(preg_replace('/\s+/', '', (string) $combo->perfume_ml));
        $perfumeProducts = $productsFor('perfume');
        $perfumeChoices = $perfumeProducts->map(function ($product) use ($volume) {
            $variants = $product->variants->filter(function ($variant) use ($volume) {
                $value = strtoupper(preg_replace('/\s+/', '', (string) ($variant->value ?: $variant->varient ?: $variant->size_value)));

                return (int) $variant->product_qty > 0 && ($volume === '' || $value === $volume);
            })->values();

            return ['product' => $product, 'variants' => $variants];
        })->filter(fn ($choice) => $choice['variants']->isNotEmpty())->values();
        $model = strtolower(trim((string) $combo->watch_model));
        $watchChoice = null;
        $watchMatch = false;
        foreach ($productsFor('watch') as $watch) {
            if ($model !== '' && ! str_contains(strtolower($watch->product_name), $model)) {
                continue;
            }
            $matchingVariants = $watch->variants->filter(function ($variant) use ($model) {
                $value = strtolower(trim((string) ($variant->value ?: $variant->varient ?: $variant->size_value)));

                return $model === '' || str_contains($value, $model);
            });
            if ($matchingVariants->isNotEmpty()) {
                $watchMatch = true;
            }
            $variant = $matchingVariants->first(fn ($variant) => (int) $variant->product_qty > 0);
            if ($variant) {
                $watchChoice = ['product' => $watch, 'variant' => $variant];
                break;
            }
        }

        $issues = [];
        if ((int) $combo->stock_quantity <= 0) {
            $issues[] = 'The box itself has no stock.';
        }
        if ($shirtChoices->isEmpty()) {
            $issues[] = 'No shirt is available in the configured sizes.';
        }
        if ($perfumeChoices->isEmpty()) {
            $perfumeMatch = $perfumeProducts->contains(fn ($product) => $product->variants->contains(function ($variant) use ($volume) {
                $value = strtoupper(preg_replace('/\s+/', '', (string) ($variant->value ?: $variant->varient ?: $variant->size_value)));
                return $volume === '' || $value === $volume;
            }));
            $issues[] = $perfumeMatch ? 'The configured perfume volume is out of stock.'
                : 'The configured perfume volume ('.$combo->perfume_ml.') does not match an available perfume variant.';
        }
        if (! $watchChoice) {
            $issues[] = $watchMatch ? 'The configured watch is out of stock.'
                : 'The configured watch model ('.$combo->watch_model.') does not match an available watch.';
        }

        return [$shirtChoices, $perfumeChoices, $watchChoice, $issues];
    }

    public function index()
    {
        $combos = GiftCombo::query()
            ->leftJoin('gift_categories', 'gift_combos.gift_category_id', '=', 'gift_categories.id')
            ->leftJoin('sub_categories', 'gift_combos.gift_subcategory_id', '=', 'sub_categories.id')
            ->select('gift_combos.*', 'gift_categories.category_name as gift_category_name', 'sub_categories.subcategory_name as gift_subcategory_name')
            ->orderBy('gift_combos.id', 'desc')
            ->get();
        $subcategories = Subcategory::query()
            ->where('category_display', 'SIGNATURE BOX')
            ->get();
        $watchSeries = $combos->pluck('watch_model')
            ->filter()
            ->unique();
        $shirtColors = $combos->pluck('shirt_color')
            ->filter()
            ->unique();
        $occasions = $combos->pluck('features')
            ->filter()
            ->map(function ($features) {
                return array_map('trim', explode(',', $features));
            })
            ->flatten()
            ->unique()
            ->values();
        $currentCategory = Category::query()
            ->where('category_name', 'SIGNATURE BOX')
            ->orWhere('category_name', 'like', '%box%')
            ->first();

        return view('pages.combos', compact('combos', 'subcategories', 'watchSeries', 'shirtColors', 'occasions', 'currentCategory'));
    }

    public function show($id)
    {
        $combo = GiftCombo::query()
            ->leftJoin('gift_categories', 'gift_combos.gift_category_id', '=', 'gift_categories.id')
            ->leftJoin('sub_categories', 'gift_combos.gift_subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('units', 'gift_combos.unit_id', '=', 'units.id')
            ->select('gift_combos.*', 'gift_categories.category_name as gift_category_name', 'sub_categories.subcategory_name as gift_subcategory_name', 'units.unit_name as unit_name')
            ->where('gift_combos.id', $id)
            ->first();
        if (! $combo) {
            return abort(404);
        }
        $comboDetails = [];
        if (! empty($combo->combo_details)) {
            $comboDetails = is_string($combo->combo_details) ? json_decode($combo->combo_details, true) : (array) $combo->combo_details;
        }
        $products = [];
        // 1. Shirt
        if ($combo->shirt_size || $combo->shirt_color || ! empty($comboDetails['item_1_name'])) {
            $shirt = clone Product::query()
                ->where('cate_name', 'like', '%shirt%')
                ->first() ?: clone Product::query()
                ->skip(1)
                ->first();
            if ($shirt) {
                $shirt->label = $comboDetails['item_1_name'] ?? 'Shirt';
                $shirt->combo_val = ($combo->shirt_color ?: 'Standard Tone').' / Size: '.($combo->shirt_size ?: 'Standard Fit');
                $products[] = $shirt;
            }
        }
        // 2. Watch
        if ($combo->watch_model || ! empty($comboDetails['item_2_name'])) {
            $watch = clone Product::query()
                ->where('cate_name', 'like', '%watch%')
                ->first() ?: clone Product::query()
                ->first();
            if ($watch) {
                $watch->label = $comboDetails['item_2_name'] ?? 'Watch';
                $watch->combo_val = $combo->watch_model ?: 'Automatic Movement';
                $products[] = $watch;
            }
        }
        // 3. Perfume
        if ($combo->perfume_ml || ! empty($comboDetails['item_3_name'])) {
            $perfume = clone Product::query()
                ->where('cate_name', 'like', '%perfume%')
                ->first() ?: clone Product::query()
                ->skip(2)
                ->first();
            if ($perfume) {
                $perfume->label = $comboDetails['item_3_name'] ?? 'Perfume';
                $perfume->combo_val = $combo->perfume_ml ? is_numeric($combo->perfume_ml) ? $combo->perfume_ml.'ml' : $combo->perfume_ml : '50ml EDP';
                $products[] = $perfume;
            }
        }
        if (empty($products)) {
            $generic = Product::query()
                ->limit(3)
                ->get();
            foreach ($generic as $idx => $gen) {
                $itemKey = 'item_'.($idx + 1).'_name';
                $gen->label = ! empty($comboDetails[$itemKey]) ? $comboDetails[$itemKey] : 'Item '.($idx + 1);
                $gen->combo_val = 'Standard';
                $products[] = $gen;
            }
        }
        [$shirtChoices, $perfumeChoices, $watchChoice, $availabilityIssues] = $this->selectionChoices($combo);
        if ($shirtChoices->isNotEmpty() && $perfumeChoices->isNotEmpty() && $watchChoice) {
            $defaultShirt = $shirtChoices->first(fn ($choice) => strcasecmp((string) $choice['product']->color, (string) $combo->shirt_color) === 0)
                ?? $shirtChoices->first();
            $products = [$defaultShirt['product'], $watchChoice['product'], $perfumeChoices->first()['product']];
        }
        $comboReviews = Review::query()
            ->where('combo_id', $combo->id)
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();
        $comboReviewCount = $comboReviews->count();
        $comboAvgRating = $comboReviewCount > 0 ? round((float) $comboReviews->avg('ratings'), 1) : 0;
        $canReviewCombo = false;
        $hasOrderedCombo = false;
        $comboOrderDelivered = false;
        $userComboReview = null;
        if (auth()->check()) {
            $comboProductIds = collect($products)->pluck('id')
                ->filter()
                ->all();
            $comboOrders = collect();
            if (! empty($comboProductIds)) {
                $comboOrders = ProductOrder::query()
                    ->join('product_order_items', 'product_order_items.order_id', '=', 'product_orders.id')
                    ->where('product_orders.user_id', auth()->id())
                    ->whereIn('product_order_items.product_id', $comboProductIds)
                    ->whereNotIn('product_orders.status', ['cancelled', 'return'])
                    ->select('product_orders.status')
                    ->get();
            }
            if ($comboOrders->isEmpty()) {
                $comboOrders = ProductOrder::query()
                    ->where('user_id', auth()->id())
                    ->whereNotIn('status', ['cancelled', 'return'])
                    ->select('status')
                    ->get();
            }
            $hasOrderedCombo = $comboOrders->isNotEmpty();
            $comboOrderDelivered = $comboOrders->contains(function ($order) {
                $st = strtolower(trim((string) ($order->status ?? '')));

                return $st === 'delivered' || $st === '4';
            });
            $canReviewCombo = $comboOrderDelivered;
            $userComboReview = Review::query()
                ->where('combo_id', $combo->id)
                ->where('user_id', auth()->id())
                ->orderByDesc('id')
                ->first();
        }
        $reviewVideos = ReviewVideo::query()
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();

        return view('pages.combo_details', compact('combo', 'products', 'shirtChoices', 'perfumeChoices', 'watchChoice', 'availabilityIssues', 'reviewVideos', 'comboReviews', 'comboReviewCount', 'comboAvgRating', 'canReviewCombo', 'hasOrderedCombo', 'comboOrderDelivered', 'userComboReview'));
    }

    public function storeReview(Request $request)
    {
        $data = $request->validate([
            'combo_id' => ['required', 'integer'],
            'ratings' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['required', 'string', 'max:600'],
        ]);
        $combo = GiftCombo::query()
            ->where('id', $data['combo_id'])
            ->first();
        abort_if(! $combo, 404);
        $comboOrders = ProductOrder::query()
            ->where('user_id', auth()->id())
            ->whereNotIn('status', ['cancelled', 'return'])
            ->select('status')
            ->get();
        if ($comboOrders->isEmpty()) {
            return back()->withErrors(['review' => 'Only verified customers who purchased this signature box can submit a review.'])
                ->withInput();
        }
        $isDelivered = $comboOrders->contains(function ($order) {
            $st = strtolower(trim((string) ($order->status ?? '')));

            return $st === 'delivered' || $st === '4';
        });
        if (! $isDelivered) {
            return back()->withErrors(['review' => 'Your order is currently in progress. You can submit a review once your signature box has been delivered.'])
                ->withInput();
        }
        $alreadyReviewed = Review::query()
            ->where('combo_id', $combo->id)
            ->where('user_id', auth()->id())
            ->exists();
        if ($alreadyReviewed) {
            return back()->withErrors(['review' => 'You have already submitted a review for this Signature Box.']);
        }
        $reviewData = [
            'name' => auth()->user()->name,
            'prod_id' => 0,
            'review' => $data['review'],
            'ratings' => $data['ratings'],
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $reviewData['combo_id'] = $combo->id;
        $reviewData['user_id'] = auth()->id();
        $reviewData['email'] = auth()->user()->email;
        Review::query()
            ->create($reviewData);

        return back()->with('review_success', 'Your review has been submitted and is awaiting admin approval.');
    }
}
