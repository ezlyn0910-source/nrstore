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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ManageProductController extends Controller
{
    /**
     * Display a listing of the products for admin.
     */
    public function index(Request $request)
    {
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
        
        // Statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();
        $categories = Category::all();
        
        return view('manageproduct.index', compact(
            'products', 
            'categories',
            'totalProducts', 
            'activeProducts', 
            'featuredProducts', 
            'lowStockProducts'
        ));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('manageproduct.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
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
                'slug' => $this->generateSlug($validated['name']),
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

            return redirect()->route('manageproduct.index')
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

    /**
     * Display the specified product for admin.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variations']);
        return view('manageproduct.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load(['images', 'variations']);
        
        return view('manageproduct.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();
        
        try {
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

            // Update slug if name changed
            if ($product->name !== $validated['name']) {
                $validated['slug'] = $this->generateSlug($validated['name'], $product->id);
            }

            $product->update($validated);

            // Handle main image update
            if ($request->hasFile('main_image_url')) {
                // Delete old image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $imagePath = $request->file('main_image_url')->store('products', 'public');
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

            return redirect()->route('manageproduct.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        
        try {
            $product->load(['images', 'variations']);

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

            // Delete the product
            $product->delete();

            DB::commit();

            return redirect()->route('manageproduct.index')
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for products
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,featured,unfeatured,recommended,unrecommended',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        try {
            switch ($request->action) {
                case 'delete':
                    Product::whereIn('id', $request->product_ids)->delete();
                    $message = 'Selected products deleted successfully!';
                    break;
                
                case 'activate':
                    Product::whereIn('id', $request->product_ids)->update(['is_active' => true]);
                    $message = 'Selected products activated successfully!';
                    break;
                
                case 'deactivate':
                    Product::whereIn('id', $request->product_ids)->update(['is_active' => false]);
                    $message = 'Selected products deactivated successfully!';
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
                    
                default:
                    return redirect()->back()->with('error', 'Invalid action.');
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product featured status
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        return redirect()->back()
            ->with('success', 'Product featured status updated successfully!');
    }

    /**
     * Toggle product active status
     */
    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return redirect()->back()
            ->with('success', 'Product active status updated successfully!');
    }

    /**
     * Delete product image
     */
    public function deleteImage(ProductImage $image)
    {
        try {
            // Delete the physical file
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete the database record
            $image->delete();

            return redirect()->back()->with('success', 'Image deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique slug for product
     */
    private function generateSlug($name, $productId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)
            ->when($productId, function($query) use ($productId) {
                return $query->where('id', '!=', $productId);
            })->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}