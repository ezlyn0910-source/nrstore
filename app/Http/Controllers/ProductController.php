<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $category = $request->get('category');
        $brand = $request->get('brand');
        $sort = $request->get('sort', 'name');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        // Start query
        $query = Product::with('category');

        // Apply filters
        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        if ($brand) {
            $query->where('brand', $brand);
        }

        if ($minPrice && $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        // Apply sorting
        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        // Get paginated products
        $products = $query->paginate(9);

        // Get recommended products
        $recommendedProducts = Product::recommended()->get();

        // Get categories for filter
        $categories = Category::all();

        // Return the productpage view (now in root of views folder)
        return view('productpage', compact('products', 'recommendedProducts', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $id)
                                ->limit(4)
                                ->get();

        // If you create a detail page later, you can use:
        // return view('productdetail', compact('product', 'relatedProducts'));
        return view('productpage', compact('product', 'relatedProducts'));
    }
}