<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products for public (customer) view
     */
    public function index(Request $request)
    {
        $category = $request->get('category');
        $brand = $request->get('brand');
        $laptopTypes = $request->get('laptop_type', []);
        $desktopTypes = $request->get('desktop_type', []);
        $sort = $request->get('sort', 'default');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        $query = Product::query()->active();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Category
        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // Apply brand filter - single brand selection
        if ($brand) {
            $query->where('brand', $brand);
        }

        // Apply type filters
        if (!empty($laptopTypes)) {
            $query->where('type', 'laptop')
                  ->whereIn('sub_type', $laptopTypes);
        }

        if (!empty($desktopTypes)) {
            $query->where('type', 'desktop')
                  ->whereIn('sub_type', $desktopTypes);
        }

        // Apply price range filter
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // PAGINATION: Show 9 products per page
        $products = $query->with(['variations' => function($query) {
            $query->active();
        }])->paginate(9);

        // Get recommended products
        $recommendedProducts = Product::active()->with(['variations' => function($query) {
            $query->active();
        }])->inRandomOrder()->limit(8)->get();

        // Get categories
        $categories = Category::all();

        // Get unique brands for filter dropdown
        $brandsList = Product::active()->select('brand')->distinct()->pluck('brand')->filter();

        return view('products.index', compact('products', 'recommendedProducts', 'categories', 'brandsList'));
    }

    /**
     * Display single product for public (customer) view
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'images', 'variations' => function($query) {
            $query->active();
        }])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->active()
                                ->limit(4)
                                ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show product by slug (alternative method)
     */
    public function showBySlug(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['category', 'images', 'variations' => function($query) {
            $query->active();
        }]);
        
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->active()
                                ->limit(4)
                                ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Get product variations for AJAX request
     */
    public function getVariations($productId)
    {
        $product = Product::with(['variations' => function($query) {
            $query->active();
        }])->active()->findOrFail($productId);

        return response()->json([
            'success' => true,
            'product' => $product,
            'variations' => $product->variations,
            'has_variations' => $product->has_variations
        ]);
    }
}