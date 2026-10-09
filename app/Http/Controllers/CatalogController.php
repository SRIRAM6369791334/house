<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomePromotion;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\WebImage;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request, $slug = null)
    {
        if ($slug) {
            $request->merge(['category' => $slug]);
        }
        $perPage = (int) $request->input('view', 12);
        $perPage = in_array($perPage, [9, 12, 24, 36], true) ? $perPage : 12;
        $productsQuery = Product::query()->forStorefront();
        $effectivePrice = 'COALESCE(v.offer_price, p.product_regular_price, p.product_mrp_price, v.mrp_price, 0)';
        $activeCategoryIds = [];
        $activeSubNames = [];
        if ($request->filled('category')) {
            $catInput = strtolower($request->input('category'));
            if ($catInput === 'fragrance') {
                $catInput = 'perfume';
            }
            if (is_numeric($catInput)) {
                $activeCategoryIds = [$catInput];
                $productsQuery->where('p.category_id', $catInput);
            } else {
                $catIds = Category::query()
                    ->get()
                    ->filter(function ($cat) use ($catInput) {
                        return \Illuminate\Support\Str::slug($cat->category_name) === $catInput;
                    })
                    ->pluck('id');
                if ($catIds->isNotEmpty()) {
                    $activeCategoryIds = $catIds->toArray();
                    $productsQuery->whereIn('p.category_id', $activeCategoryIds);
                } else {
                    $activeCategoryIds = [-1];
                    $productsQuery->where('p.category_id', -1);
                }
            }
        }
        if ($request->filled('sub')) {
            $subInput = $request->input('sub');
            $subNames = Product::query()
                ->whereNotNull('subcate_name')
                ->distinct()
                ->pluck('subcate_name');
            $matchedNames = $subNames->filter(function ($name) use ($subInput) {
                return \Illuminate\Support\Str::slug($name) === $subInput;
            });
            if ($matchedNames->isNotEmpty()) {
                $activeSubNames = $matchedNames->toArray();
                $productsQuery->whereIn('p.subcate_name', $activeSubNames);
            } else {
                $activeSubNames = ['-1'];
                $productsQuery->where('p.subcategory_id', -1);
            }
        }
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $productsQuery->where(function ($query) use ($search) {
                $query->where('p.product_name', 'like', "%{$search}%")
                    ->orWhere('p.cate_name', 'like', "%{$search}%")
                    ->orWhere('c.category_name', 'like', "%{$search}%")
                    ->orWhere('p.subcate_name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('min_price')) {
            $productsQuery->whereRaw($effectivePrice.' >= ?', [(float) $request->input('min_price')]);
        }
        if ($request->filled('max_price')) {
            $productsQuery->whereRaw($effectivePrice.' <= ?', [(float) $request->input('max_price')]);
        }
        if ($request->filled('size')) {
            $productsQuery->whereExists(function ($query) use ($request) {
                $sizeValue = house_variant_size_value_sql('pv_size');
                $query->selectRaw('1')
                    ->from('product_varient as pv_size')
                    ->whereColumn('pv_size.product_id', 'p.id')
                    ->whereRaw($sizeValue.' = ?', [$request->input('size')]);
            });
        }
        if ($request->filled('color')) {
            $productsQuery->where(function ($query) use ($request) {
                $query->where('p.color', $request->input('color'))
                    ->orWhereExists(function ($sub) use ($request) {
                        $sub->selectRaw('1')
                            ->from('product_varient as pv_color')
                            ->whereColumn('pv_color.product_id', 'p.id')
                            ->where(function ($colors) use ($request) {
                                $colors->where('pv_color.varient_details->color_name', $request->input('color'))
                                    ->orWhere('pv_color.varient_name', $request->input('color'));
                            });
                    });
            });
        }
        if ($request->filled('fit')) {
            $fitVal = trim($request->input('fit'));
            $fitKeyword = str_ireplace(['fit', ' '], '', $fitVal);
            $productsQuery->where(function ($q) use ($fitVal, $fitKeyword) {
                $q->where('p.fit', $fitVal)
                    ->orWhere('p.fit', 'like', "%{$fitKeyword}%")
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.product_details, '\$.model_specs.fit_level')) LIKE ?", ["%{$fitKeyword}%"])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.product_details, '\$.specs_table.Fit')) LIKE ?", ["%{$fitKeyword}%"]);
            });
        }
        if ($request->filled('strap')) {
            $strapVal = trim($request->input('strap'));
            $productsQuery->where(function ($q) use ($strapVal) {
                $q->where('p.fit', 'like', "%{$strapVal}%")
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.product_details, '\$.specs_table.Strap')) LIKE ?", ["%{$strapVal}%"]);
            });
        }
        if ($request->filled('scent')) {
            $scentVal = ucfirst(strtolower(trim($request->input('scent'))));
            $productsQuery->where(function ($q) use ($scentVal) {
                $q->whereRaw("CAST(COALESCE(JSON_UNQUOTE(JSON_EXTRACT(p.product_details, '\$.scent_profile.".$scentVal."')), '0') AS UNSIGNED) >= 3")
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.product_details, '\$.scent_notes.top')) LIKE ?", ["%{$scentVal}%"])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.product_details, '\$.scent_notes.heart')) LIKE ?", ["%{$scentVal}%"])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.product_details, '\$.scent_notes.base')) LIKE ?", ["%{$scentVal}%"]);
            });
        }
        if ($request->filled('volume')) {
            $volVal = trim($request->input('volume'));
            $productsQuery->where(function ($q) use ($volVal) {
                $sizeValue = house_variant_size_value_sql('pv_vol');
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.product_details, '\$.specs_table.Volume')) LIKE ?", ["%{$volVal}%"])
                    ->orWhereExists(function ($sub) use ($volVal, $sizeValue) {
                        $sub->selectRaw('1')
                            ->from('product_varient as pv_vol')
                            ->whereColumn('pv_vol.product_id', 'p.id')
                            ->whereRaw($sizeValue.' LIKE ?', ["%{$volVal}%"]);
                    });
            });
        }
        if ($request->filled('features')) {
            $productsQuery->where('p.features', 'like', '%'.$request->input('features').'%');
        }
        match ($request->input('sort')) {
            'name_asc' => $productsQuery->orderBy('p.product_name'),
            'name_desc' => $productsQuery->orderByDesc('p.product_name'),
            'price_low', 'price_asc' => $productsQuery->orderByRaw($effectivePrice.' asc'),
            'price_high', 'price_desc' => $productsQuery->orderByRaw($effectivePrice.' desc'),
            default => $productsQuery->orderByDesc('p.id'),
        };
        $products = $productsQuery->paginate($perPage)
            ->withQueryString();
        $categoryCounts = Product::query()
            ->whereNull('deleted_at')
            ->select('category_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('category_id');
        $categories = Category::query()
            ->from('categories as c')
            ->leftJoinSub($categoryCounts, 'pc', function ($join) {
                $join->on('pc.category_id', '=', 'c.id');
            })
            ->select('c.id', 'c.category_name')
            ->selectRaw('COALESCE(pc.total, 0) as products_count')
            ->orderBy('c.category_name')
            ->get();
        $recentProducts = Product::query()->forStorefront()->orderByDesc('p.id')
            ->limit(8)
            ->get();
        $priceRange = Product::query()
            ->fromSub(Product::query()->forStorefront(), 'shop_products')
            ->selectRaw('MIN(COALESCE(offer_price, product_regular_price, product_mrp_price, mrp_price, 0)) as min_price, MAX(COALESCE(offer_price, product_regular_price, product_mrp_price, mrp_price, 0)) as max_price')
            ->first();
        $sizeValue = house_variant_size_value_sql();
        $sizeOptionsQuery = ProductVariant::query()
            ->join('products as size_products', 'size_products.id', '=', 'product_varient.product_id')
            ->leftJoin('categories as size_categories', 'size_categories.id', '=', 'size_products.category_id')
            ->selectRaw('size_products.category_id, COALESCE(size_categories.category_name, size_products.cate_name, "Other") as category_name, '.$sizeValue.' as size_label')
            ->whereRaw($sizeValue.' IS NOT NULL')
            ->whereNull('size_products.deleted_at');
        if (! empty($activeCategoryIds)) {
            $sizeOptionsQuery->whereIn('size_products.category_id', $activeCategoryIds);
        }
        if (! empty($activeSubNames)) {
            $sizeOptionsQuery->whereIn('size_products.subcate_name', $activeSubNames);
        }
        $sizeOptions = $sizeOptionsQuery->distinct()
            ->orderBy('category_name')
            ->orderBy('size_label')
            ->get();
        $sizeOrder = array_flip(['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', 'XXXL', '3XL', 'XXXXL', '4XL', '5XL']);
        $sortSizes = static function ($sizes) use ($sizeOrder) {
            return $sizes->filter()->unique()->sort(function ($a, $b) use ($sizeOrder) {
                $first = strtoupper(trim((string) $a));
                $second = strtoupper(trim((string) $b));
                $firstRank = $sizeOrder[$first] ?? PHP_INT_MAX;
                $secondRank = $sizeOrder[$second] ?? PHP_INT_MAX;

                return ($firstRank <=> $secondRank) ?: strnatcasecmp($first, $second);
            })->values();
        };
        $sizeOptionsByCategory = $sizeOptions->groupBy('category_name')
            ->map(fn ($items) => $sortSizes($items->pluck('size_label')));
        $sizeOptions = $sortSizes($sizeOptions->pluck('size_label'));
        $colorOptionsQuery = Product::query()
            ->with('variants:id,product_id,varient_details')
            ->whereNull('deleted_at');
        if (! empty($activeCategoryIds)) {
            $colorOptionsQuery->whereIn('category_id', $activeCategoryIds);
        }
        if (! empty($activeSubNames)) {
            $colorOptionsQuery->whereIn('subcate_name', $activeSubNames);
        }
        $colorOptions = $colorOptionsQuery->get(['id', 'color'])
            ->flatMap(function ($product) {
                $names = $product->variants->map(function ($variant) {
                    $details = is_string($variant->varient_details)
                        ? json_decode($variant->varient_details, true) : $variant->varient_details;
                    $name = is_array($details) ? ($details['color_name'] ?? null) : null;

                    return is_string($name) && trim($name) !== '' ? $name : null;
                })->filter()->unique();
                if ($names->isNotEmpty()) {
                    return $names->map(fn ($name) => ['value' => $name, 'label' => $name, 'swatch' => house_color_swatch($name, house_color_swatch($product->color))]);
                }

                return filled($product->color)
                    ? [['value' => $product->color, 'label' => house_color_label($product->color), 'swatch' => house_color_swatch($product->color)]]
                    : [];
            })
            ->unique('value')->sortBy('label')->values();
        $subcatCounts = Product::query()
            ->whereNotNull('subcategory_id')
            ->whereNull('deleted_at')
            ->select('subcategory_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('subcategory_id');
        $allSubcategoriesGrouped = Subcategory::query()
            ->from('sub_categories as s')
            ->leftJoinSub($subcatCounts, 'ps', function ($join) {
                $join->on('ps.subcategory_id', '=', 's.id');
            })
            ->select('s.*')
            ->selectRaw('COALESCE(ps.total, 0) as products_count')
            ->get()
            ->groupBy('category_name');
        $dbFitsQuery = Product::query()
            ->whereNotNull('fit')
            ->where('fit', '!=', '');
        if (! empty($activeCategoryIds)) {
            $dbFitsQuery->whereIn('category_id', $activeCategoryIds);
        }
        if (! empty($activeSubNames)) {
            $dbFitsQuery->whereIn('subcate_name', $activeSubNames);
        }
        $dbFits = $dbFitsQuery->distinct()
            ->orderBy('fit')
            ->pluck('fit');
        $dbFeaturesQuery = Product::query()
            ->whereNotNull('features')
            ->where('features', '!=', '')
            ->whereNull('deleted_at');
        if (! empty($activeCategoryIds)) {
            $dbFeaturesQuery->whereIn('category_id', $activeCategoryIds);
        }
        if (! empty($activeSubNames)) {
            $dbFeaturesQuery->whereIn('subcate_name', $activeSubNames);
        }
        $dbFeatures = $dbFeaturesQuery->distinct()
            ->orderBy('features')
            ->pluck('features');
        $shopBanner = WebImage::query()
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderByDesc('id')
            ->first();
        $instagramImages = HomePromotion::query()
            ->select('id', 'bg_image', 'link_url')
            ->whereNotNull('bg_image')
            ->where('bg_image', '!=', '')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
        $currentCategory = null;
        if (! empty($activeCategoryIds) && $activeCategoryIds[0] > 0) {
            $currentCategory = Category::query()
                ->where('id', $activeCategoryIds[0])
                ->first();
        } elseif ($request->filled('category')) {
            $catSlug = \Illuminate\Support\Str::slug($request->input('category'));
            $currentCategory = Category::query()
                ->get()
                ->first(function ($cat) use ($catSlug) {
                    return \Illuminate\Support\Str::slug($cat->category_name) === $catSlug;
                });
        }
        $detectedCatSlug = strtolower($slug ?: (string) $request->input('category', ''));
        if ($detectedCatSlug === 'fragrance') {
            $detectedCatSlug = 'perfume';
        }
        if (! $detectedCatSlug && $currentCategory) {
            $detectedCatSlug = \Illuminate\Support\Str::slug($currentCategory->category_name);
            if ($detectedCatSlug === 'fragrance') {
                $detectedCatSlug = 'perfume';
            }
        }
        $isShirtCategory = in_array($detectedCatSlug, ['shirts', 'shirt']);
        $isWatchCategory = in_array($detectedCatSlug, ['watches', 'watch']);
        $isPerfumeCategory = in_array($detectedCatSlug, ['perfume', 'fragrance', 'fragrances']);
        // Watch Strap options
        $watchStrapOptions = collect();
        $watchRows = Product::query()
            ->whereIn('category_id', $activeCategoryIds ?: [41])
            ->whereNull('deleted_at')
            ->get(['fit', 'product_details']);
        foreach ($watchRows as $wr) {
            if (! empty($wr->fit)) {
                $watchStrapOptions->push(ucfirst($wr->fit));
            }
            if (! empty($wr->product_details)) {
                $pdet = json_decode($wr->product_details, true);
                if (! empty($pdet['specs_table']['Strap'])) {
                    $watchStrapOptions->push($pdet['specs_table']['Strap']);
                }
            }
        }
        $watchStrapOptions = $watchStrapOptions->unique()
            ->filter()
            ->values();
        if ($watchStrapOptions->isEmpty()) {
            $watchStrapOptions = collect(['904L Stainless Steel', 'Solid 904L Rose Gold Link Bracelet', 'Leather Strap']);
        }
        // Perfume Volume options
        $perfumeVolumeOptions = collect();
        $perfumeRows = Product::query()
            ->whereIn('category_id', $activeCategoryIds ?: [42])
            ->whereNull('deleted_at')
            ->get(['product_details']);
        foreach ($perfumeRows as $pr) {
            if (! empty($pr->product_details)) {
                $pdet = json_decode($pr->product_details, true);
                if (! empty($pdet['specs_table']['Volume'])) {
                    $perfumeVolumeOptions->push($pdet['specs_table']['Volume']);
                }
            }
        }
        $perfumeVolumeOptions = $perfumeVolumeOptions->unique()
            ->filter()
            ->values();
        if ($perfumeVolumeOptions->isEmpty()) {
            $perfumeVolumeOptions = collect(['50ml', '100ml']);
        }
        // Perfume Scent Vibe options
        $perfumeScentOptions = [[
            'key' => 'Aquatic',
            'label' => 'Aquatic',
            'icon' => '🌊',
        ], [
            'key' => 'Woody',
            'label' => 'Woody',
            'icon' => '🌲',
        ], [
            'key' => 'Citrus',
            'label' => 'Citrus',
            'icon' => '🍋',
        ], [
            'key' => 'Fresh',
            'label' => 'Fresh',
            'icon' => '🌿',
        ], [
            'key' => 'Aromatic',
            'label' => 'Aromatic',
            'icon' => '🍂',
        ]];
        // Shirt Fit options
        $shirtFitOptions = ['Slim Fit', 'Regular Fit', 'Super Slim Fit'];
        // Product Reviews aggregation (single fast query)
        $productReviewStats = Review::query()
            ->where('status', 1)
            ->select('prod_id')
            ->selectRaw('COUNT(*) as count, AVG(ratings) as avg_rating')
            ->groupBy('prod_id')
            ->get()
            ->keyBy('prod_id');

        return view('pages.shop', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'recentProducts' => $recentProducts,
            'priceRange' => $priceRange,
            'sizeOptions' => $sizeOptions,
            'sizeOptionsByCategory' => $sizeOptionsByCategory,
            'colorOptions' => $colorOptions,
            'dbFits' => $dbFits,
            'dbFeatures' => $dbFeatures,
            'shopBanner' => $shopBanner,
            'instagramImages' => $instagramImages,
            'totalProducts' => Product::query()
                ->whereNull('deleted_at')
                ->count(),
            'selectedCategory' => $request->input('category'),
            'allSubcategoriesGrouped' => $allSubcategoriesGrouped,
            'isShirtCategory' => $isShirtCategory,
            'isWatchCategory' => $isWatchCategory,
            'isPerfumeCategory' => $isPerfumeCategory,
            'watchStrapOptions' => $watchStrapOptions,
            'perfumeVolumeOptions' => $perfumeVolumeOptions,
            'productReviewStats' => $productReviewStats,
            'perfumeScentOptions' => $perfumeScentOptions,
            'shirtFitOptions' => $shirtFitOptions,
        ]);
    }

    public function collections()
    {
        $collections = Category::query()
            ->select('id', 'category_name', 'category_image', 'category_banner')
            ->where('category_name', '!=', 'SIGNATURE BOX')
            ->orderBy('id')
            ->get();

        return view('pages.collections', compact('collections'));
    }
}
