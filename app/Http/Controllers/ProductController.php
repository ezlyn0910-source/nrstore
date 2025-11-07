<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Check if this is an admin request or public request
        if (request()->is('admin/*')) {
            // ADMIN: Return manageproduct index view with enhanced filtering
            $query = Product::with(['category', 'variations', 'images']);
            
            // Search functionality
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
                });
            }
            
            // Category filter
            if ($request->has('category') && $request->category != '') {
                $query->where('category_id', $request->category);
            }
            
            // Status filter
            if ($request->has('status') && $request->status != '') {
                switch ($request->status) {
                    case 'in_stock':
                        $query->where('stock_quantity', '>', 0);
                        break;
                    case 'low_stock':
                        $query->where('stock_quantity', '<', 10)->where('stock_quantity', '>', 0);
                        break;
                    case 'out_of_stock':
                        $query->where('stock_quantity', 0);
                        break;
                    case 'featured':
                        $query->where('is_featured', true);
                        break;
                    case 'active':
                        $query->where('is_active', true);
                        break;
                    case 'inactive':
                        $query->where('is_active', false);
                        break;
                }
            }

            // Show inactive products
            if (!$request->has('show_inactive')) {
                $query->where('is_active', true);
            }
            
            // Sort functionality
            $sort = $request->get('sort', 'created_at');
            $order = $request->get('order', 'desc');
            $query->orderBy($sort, $order);

            $products = $query->paginate(10);
            $categories = Category::all();
            
            return view('manageproduct.index', compact('products', 'categories'));
        } else {
            // PUBLIC: Return productpage view with pagination
            return $this->publicIndex($request);
        }
    }

    public function show($id)
    {
        $product = Product::with(['category', 'variations', 'images'])->findOrFail($id);
        
        if (request()->is('admin/*')) {
            return view('manageproduct.show', compact('product'));
        } else {
            $relatedProducts = Product::where('category_id', $product->category_id)
                                    ->where('id', '!=', $id)
                                    ->limit(4)
                                    ->get();
            return view('productpage', compact('product', 'relatedProducts'));
        }
    }

    public function create()
    {
        // FIXED: Remove the status condition since categories table doesn't have status column
        $categories = Category::all();
        return view('manageproduct.create', compact('categories'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Enhanced validation with better error messages
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'ram' => 'nullable|string|max:100',
                'storage' => 'nullable|string|max:100',
                'processor' => 'nullable|string|max:255',
                'stock_quantity' => 'required|integer|min:0',
                'is_featured' => 'boolean',
                'is_recommended' => 'boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'product_images' => 'nullable|array|max:5',
                'product_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'has_variations' => 'boolean',
                'variations' => 'nullable|array',
                'variations.*.sku' => 'required_if:has_variations,1|string|max:100|distinct',
                'variations.*.price' => 'nullable|numeric|min:0',
                'variations.*.stock' => 'required_if:has_variations,1|integer|min:0',
                'variations.*.model' => 'nullable|string|max:255',
                'variations.*.processor' => 'nullable|string|max:255',
                'variations.*.ram' => 'nullable|string|max:100',
                'variations.*.storage' => 'nullable|string|max:100',
                'variations.*.storage_type' => 'nullable|string|max:50',
                'variations.*.graphics_card' => 'nullable|string|max:255',
                'variations.*.screen_size' => 'nullable|string|max:50',
                'variations.*.os' => 'nullable|string|max:255',
                'variations.*.warranty' => 'nullable|string|max:100',
                'variations.*.voltage' => 'nullable|string|max:50',
            ], [
                'variations.*.sku.required_if' => 'SKU is required for all variations when variations are enabled.',
                'variations.*.sku.distinct' => 'Duplicate SKU found. Each variation must have a unique SKU.',
                'variations.*.stock.required_if' => 'Stock is required for all variations when variations are enabled.',
                'product_images.max' => 'You can upload maximum 5 additional images.',
                'product_images.*.max' => 'Each image must be less than 2MB.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the validation errors below.');
            }

            $validated = $validator->validated();

            // Create product with all necessary fields
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'brand' => $validated['brand'] ?? null,
                'ram' => $validated['ram'] ?? null,
                'storage' => $validated['storage'] ?? null,
                'processor' => $validated['processor'] ?? null,
                'stock_quantity' => $validated['stock_quantity'],
                'is_featured' => $validated['is_featured'] ?? false,
                'is_recommended' => $validated['is_recommended'] ?? false,
                'has_variations' => $validated['has_variations'] ?? false,
                'is_active' => true,
            ]);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image');
                $imageName = 'product_' . $product->id . '_main_' . time() . '.' . $mainImage->getClientOriginalExtension();
                $imagePath = $mainImage->storeAs('products', $imageName, 'public');
                
                // Update product with main image
                $product->update(['image' => $imagePath]);
                
                // Create primary product image record
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => true,
                    'sort_order' => 0
                ]);
            }

            // Handle additional product images
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $index => $image) {
                    if ($index >= 5) break; // Limit to 5 images
                    
                    $imageName = 'product_' . $product->id . '_gallery_' . ($index + 1) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products/gallery', $imageName, 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => false,
                        'sort_order' => $index + 1
                    ]);
                }
            }

            // Handle product variations
            $hasVariations = $request->has('has_variations') && $request->has_variations;
            
            if ($hasVariations && $request->has('variations') && is_array($request->variations)) {
                foreach ($request->variations as $variationIndex => $variationData) {
                    // Skip if essential data is missing
                    if (empty($variationData['sku']) || !isset($variationData['stock'])) {
                        continue;
                    }

                    $variation = Variation::create([
                        'product_id' => $product->id,
                        'sku' => $variationData['sku'],
                        'price' => $variationData['price'] ?? $product->price,
                        'stock' => $variationData['stock'],
                        'model' => $variationData['model'] ?? null,
                        'processor' => $variationData['processor'] ?? null,
                        'ram' => $variationData['ram'] ?? null,
                        'storage' => $variationData['storage'] ?? null,
                        'storage_type' => $variationData['storage_type'] ?? null,
                        'graphics_card' => $variationData['graphics_card'] ?? null,
                        'screen_size' => $variationData['screen_size'] ?? null,
                        'os' => $variationData['os'] ?? null,
                        'warranty' => $variationData['warranty'] ?? null,
                        'voltage' => $variationData['voltage'] ?? null,
                        'is_active' => true,
                    ]);

                    // Handle variation image upload if provided
                    if (isset($variationData['image_file']) && $variationData['image_file'] instanceof \Illuminate\Http\UploadedFile) {
                        $variationImage = $variationData['image_file'];
                        $imageName = 'variation_' . $variation->id . '_' . time() . '.' . $variationImage->getClientOriginalExtension();
                        $imagePath = $variationImage->storeAs('products/variations', $imageName, 'public');
                        
                        $variation->update(['image' => $imagePath]);
                    }
                }
                
                // Update product to indicate it has variations
                $product->update(['has_variations' => true]);
                
                // Update total stock based on variations
                $totalVariationStock = Variation::where('product_id', $product->id)->sum('stock');
                $product->update(['stock_quantity' => $totalVariationStock]);
            }

            DB::commit();

            return redirect()->route('admin.manageproduct.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Product creation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to create product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'variations', 'images'])->findOrFail($id);
        $categories = Category::all();
        return view('manageproduct.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $product = Product::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'ram' => 'nullable|string|max:100',
                'storage' => 'nullable|string|max:100',
                'processor' => 'nullable|string|max:255',
                'stock_quantity' => 'required|integer|min:0',
                'is_featured' => 'boolean',
                'is_recommended' => 'boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'product_images' => 'nullable|array',
                'product_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $product->update($validated);

            // Handle main image update
            if ($request->hasFile('main_image')) {
                // Delete old image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $imagePath = $request->file('main_image')->store('products', 'public');
                $product->update(['image' => $imagePath]);
                
                // Update primary product image
                $primaryImage = $product->images()->where('is_primary', true)->first();
                if ($primaryImage) {
                    $primaryImage->update(['image_path' => $imagePath]);
                } else {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => true,
                        'sort_order' => 0
                    ]);
                }
            }

            // Handle additional images
            if ($request->hasFile('product_images')) {
                $currentMaxOrder = $product->images()->max('sort_order') ?? 0;
                
                foreach ($request->file('product_images') as $index => $image) {
                    $imagePath = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => false,
                        'sort_order' => $currentMaxOrder + $index + 1
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.manageproduct.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $product = Product::with(['images', 'variations'])->findOrFail($id);

            // Delete images from storage
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            // Delete variation images
            foreach ($product->variations as $variation) {
                if ($variation->image && Storage::disk('public')->exists($variation->image)) {
                    Storage::disk('public')->delete($variation->image);
                }
            }

            // Delete the product (hard delete for now)
            $product->delete();

            DB::commit();

            return redirect()->route('admin.manageproduct.index')
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,featured,unfeatured,recommended,unrecommended',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        try {
            $products = Product::whereIn('id', $request->product_ids)->get();

            switch ($request->action) {
                case 'delete':
                    foreach ($products as $product) {
                        $this->destroy($product->id);
                    }
                    $message = 'Selected products deleted successfully!';
                    break;
                    
                case 'featured':
                    Product::whereIn('id', $request->product_ids)->update(['is_featured' => true]);
                    $message = 'Selected products marked as featured!';
                    break;
                    
                case 'unfeatured':
                    Product::whereIn('id', $request->product_ids)->update(['is_featured' => false]);
                    $message = 'Selected products unmarked as featured!';
                    break;
                    
                case 'recommended':
                    Product::whereIn('id', $request->product_ids)->update(['is_recommended' => true]);
                    $message = 'Selected products marked as recommended!';
                    break;
                    
                case 'unrecommended':
                    Product::whereIn('id', $request->product_ids)->update(['is_recommended' => false]);
                    $message = 'Selected products unmarked as recommended!';
                    break;
            }

            return redirect()->route('admin.manageproduct.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }
    }

    public function updateStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);

        return redirect()->back()->with('success', 'Product status updated successfully!');
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function showBySlug($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return $this->show($product->id);
    }

    private function publicIndex(Request $request)
    {
        $category = $request->get('category');
        // Get filter parameters - use singular 'brand' to match your current code
        $brand = $request->get('brand');
        $laptopTypes = $request->get('laptop_type', []);
        $desktopTypes = $request->get('desktop_type', []);
        $sort = $request->get('sort', 'default');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        $query = Product::query();

        //Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        //Category
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
        $products = $query->paginate(9);

        // Get recommended products
        $recommendedProducts = Product::inRandomOrder()->limit(8)->get();

        // Get categories
        $categories = Category::all();

        // Get unique brands for filter dropdown
        $brandsList = Product::select('brand')->distinct()->pluck('brand')->filter();

        return view('productpage', compact('products', 'recommendedProducts', 'categories', 'brandsList'));
    }
}
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