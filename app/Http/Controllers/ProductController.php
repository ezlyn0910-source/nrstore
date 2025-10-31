<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters - use singular 'brand' to match your current code
        $brand = $request->get('brand');
        $laptopTypes = $request->get('laptop_type', []);
        $desktopTypes = $request->get('desktop_type', []);
        $sort = $request->get('sort', 'default');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        // Start query
        $query = Product::query();

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

        // Apply sorting
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

        // Get paginated products
        $products = $query->paginate(9);

        // Get recommended products
        $recommendedProducts = Product::inRandomOrder()->limit(8)->get();

        // Get categories
        $categories = Category::all();

        // Get unique brands for filter dropdown
        $brandsList = Product::select('brand')->distinct()->pluck('brand')->filter();

        return view('productpage', compact('products', 'recommendedProducts', 'categories', 'brandsList'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $relatedProducts = Product::where('brand', $product->brand)
                                ->where('id', '!=', $id)
                                ->limit(4)
                                ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}