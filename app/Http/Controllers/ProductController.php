<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        try {
            // Clear any previous output
            if (ob_get_length()) {
                ob_clean();
            }
            
            $product = Product::with(['variations' => function($query) {
                $query->where('is_active', true);
            }])->where('is_active', true)->find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                    'variations' => []
                ], 404)->header('Content-Type', 'application/json');
            }
            
            // Format variations
            $formattedVariations = $product->variations->map(function($variation) {
                return [
                    'id' => $variation->id,
                    'name' => $variation->name,
                    'price' => (float) $variation->price,
                    'effective_price' => (float) ($variation->effective_price ?? $variation->price),
                    'stock' => (int) $variation->stock,
                    'processor' => $variation->processor ?? '',
                    'ram' => $variation->ram ?? '',
                    'storage' => $variation->storage ?? '',
                    'model' => $variation->model ?? '',
                    'sku' => $variation->sku ?? '',
                    'is_active' => (bool) $variation->is_active,
                ];
            })->toArray();
            
            return response()->json([
                'success' => true,
                'product_id' => (int) $productId,
                'product_name' => $product->name,
                'has_variations' => $product->has_variations,
                'variations' => $formattedVariations,
                'count' => count($formattedVariations)
            ])->header('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            \Log::error('Get variations error for product ' . $productId . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'variations' => []
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Process Buy Now request
     */
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:variations,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $product = Product::with('images')->findOrFail($request->product_id);
            
            // Check if product has variations and no variation is selected
            if ($product->has_variations && !$request->variation_id) {
                return response()->json([
                    'success' => false,
                    'requires_variation' => true,
                    'message' => 'Please select a variation for this product.'
                ]);
            }

            // Get variation if selected
            $variation = null;
            if ($request->variation_id) {
                $variation = Variation::where('id', $request->variation_id)
                    ->where('product_id', $product->id)
                    ->first();
                
                if (!$variation) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected variation not available.'
                    ]);
                }
            }

            // Determine unit price
            $unitPrice = $variation ? ($variation->price) : $product->price;
            
            // Calculate total
            $totalAmount = $unitPrice * $request->quantity;

            // Clear any existing buy now session
            session()->forget('buy_now_order');

            // Get image URL
            $imageUrl = null;
            if ($product->images && $product->images->isNotEmpty()) {
                $firstImage = $product->images->first();
                $imageUrl = $firstImage->image_path;
            } elseif ($product->image) {
                $imageUrl = $product->image;
            }

            // Get variation name
            $variationName = null;
            if ($variation) {
                $variationName = trim(implode(' • ', array_filter([
                    $variation->model ?? null,
                    $variation->processor ?? null,
                    $variation->ram ?? null,
                    $variation->storage ?? null,
                ])));
            }

            // Store buy now data in session
            $buyNowData = [
                'is_buy_now' => true,
                'items' => [
                    [
                        'product_id' => $product->id,
                        'variation_id' => $variation ? $variation->id : null,
                        'quantity' => (int)$request->quantity,
                        'price' => $unitPrice,
                        'product_name' => $product->name,
                        'variation_name' => $variationName,
                        'image' => $imageUrl,
                    ]
                ],
                'total' => $totalAmount,
                'timestamp' => now()->timestamp
            ];

            session()->put('buy_now_order', $buyNowData);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Redirecting to checkout...',
                    'redirect_url' => route('checkout.index')
                ]);
            }

            return redirect()->route('checkout.index');

        } catch (\Exception $e) {
            \Log::error('Buy Now error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Get variation display name/specs
     */
    private function getVariationName($variation)
    {
        $specs = [];
        if (!empty($variation->model)) $specs[] = $variation->model;
        if (!empty($variation->processor)) $specs[] = $variation->processor;
        if (!empty($variation->ram)) $specs[] = $variation->ram;
        if (!empty($variation->storage)) $specs[] = $variation->storage;
        
        return implode(' • ', $specs);
    }

    /**
     * Helper: Get product specs for display
     */
    private function getProductSpecs($product, $variation = null)
    {
        if ($variation) {
            return $this->getVariationName($variation);
        }

        $specs = [];
        if (!empty($product->processor)) $specs[] = $product->processor;
        if (!empty($product->ram)) $specs[] = $product->ram;
        if (!empty($product->storage)) $specs[] = $product->storage;
        
        return implode(' • ', $specs);
    }
}