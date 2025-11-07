<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products for public/customer side.
     */
    public function index(Request $request)
    {
        $query = Product::with('category', 'variations')->active();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Filter by price range
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        // Sort options
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $brands = Product::active()->distinct()->pluck('brand')->filter();
        
        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Display the specified product for public.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'variations' => function($query) {
            $query->active()->inStock();
        }, 'images'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();
            
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();

        return view('manageproduct.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show featured products
     */
    public function featured()
    {
        $products = Product::with('category')
            ->active()
            ->featured()
            ->latest()
            ->paginate(12);

        return view('products.featured', compact('products'));
    }

    /**
     * Show recommended products
     */
    public function recommended()
    {
        $products = Product::with('category')
            ->active()
            ->recommended()
            ->latest()
            ->paginate(12);

        return view('products.recommended', compact('products'));
    }

    /**
     * Show products by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->active()
            ->latest()
            ->paginate(12);

        return view('products.category', compact('products', 'category'));
    }

    /**
     * Quick search for navbar
     */
    public function quickSearch(Request $request)
    {
        $searchTerm = $request->input('q');
        
        $products = Product::active()
            ->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('brand', 'like', "%{$searchTerm}%")
            ->limit(5)
            ->get(['id', 'name', 'slug', 'brand', 'price', 'image']);

        return response()->json($products);
    }
}