<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
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
                'storage_type' => 'nullable|string|max:100',
                'processor' => 'nullable|string|max:255',
                'graphics_card' => 'nullable|string|max:255',
                'screen_size' => 'nullable|string|max:255',
                'os' => 'nullable|string|max:255',
                'warranty' => 'nullable|string|max:255',
                'stock_quantity' => 'required|integer|min:0',
                'is_featured' => 'boolean',
                'is_recommended' => 'boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'product_images' => 'nullable|array|max:5',
                'product_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'has_variations' => 'boolean',
                'variations' => 'nullable|array',
                'variations.*.sku' => 'required_if:has_variations,1|string|max:100|distinct',
                'variations.*.price' => 'nullable|numeric|min:0',
                'variations.*.stock' => 'required_if:has_variations,1|integer|min:0',
                'variations.*.model' => 'nullable|string|max:255',
                'variations.*.processor' => 'nullable|string|max:255',
                'variations.*.ram' => 'nullable|string|max:100',
                'variations.*.storage' => 'nullable|string|max:100',
                'variations.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'variations.*.is_active' => 'boolean',
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
                'storage_type' => $validated['storage_type'] ?? null,
                'processor' => $validated['processor'] ?? null,
                'graphics_card' => $request->input('graphics_card') ?? null, 
                'screen_size' => $request->input('screen_size') ?? null, 
                'os' => $request->input('os') ?? null,  
                'warranty' => $request->input('warranty') ?? null,  
                'stock_quantity' => $validated['stock_quantity'],
                'is_featured' => $validated['is_featured'] ?? false,
                'is_recommended' => $validated['is_recommended'] ?? false,
                'has_variations' => $validated['has_variations'] ?? false,
                'is_active' => true,
                'slug' => $this->generateSlug($validated['name']),
            ]);

            // Create necessary directories
            $this->createImageDirectories();

            // Handle main image upload
            if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
                $mainImage = $request->file('main_image');
                $imageName = 'product_' . $product->id . '_main_' . time() . '.' . $mainImage->getClientOriginalExtension();
                $imagePath = 'images/products/' . $imageName;  // Relative path from public directory
                
                // Move to public directory
                $mainImage->move(public_path('images/products'), $imageName);
                
                // Update product with main image - store the relative path
                $product->update(['image' => $imagePath]);
                
                // Create primary product image record
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,  // Store relative path
                    'is_primary' => true,
                    'sort_order' => 0
                ]);
            }

            // Additional images section:
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $index => $image) {
                    if ($image && $image->isValid() && $index < 5) {
                        $imageName = 'product_' . $product->id . '_gallery_' . ($index + 1) . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $imagePath = 'images/products/gallery/' . $imageName;  // Relative path
                        
                        // Move to public gallery directory
                        $image->move(public_path('images/products/gallery'), $imageName);
                        
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,  // Store relative path
                            'is_primary' => false,
                            'sort_order' => $index + 1
                        ]);
                    }
                }
            }

            // Handle product variations - FIXED VERSION
            $hasVariations = $request->has('has_variations') && $request->has_variations;
            
            if ($hasVariations && $request->has('variations') && is_array($request->variations)) {
                foreach ($request->variations as $variationIndex => $variationData) {
                    // Skip if essential data is missing or null
                    if (empty($variationData['sku']) || !isset($variationData['stock'])) {
                        continue;
                    }

                    // Debug: Log variation data
                    \Log::info('Creating variation:', $variationData);

                    $variation = Variation::create([
                        'product_id' => $product->id,
                        'sku' => $variationData['sku'],
                        'price' => isset($variationData['price']) && $variationData['price'] !== '' 
                            ? $variationData['price'] 
                            : $product->price,
                        'stock' => $variationData['stock'],
                        'model' => $variationData['model'] ?? null,
                        'processor' => $variationData['processor'] ?? null,
                        'ram' => $variationData['ram'] ?? null,
                        'storage' => $variationData['storage'] ?? null,
                        'is_active' => isset($variationData['is_active']) ? true : true, // Default to true
                    ]);

                    // Handle variation image upload if provided
                    if (isset($variationData['image_file']) && 
                        $variationData['image_file'] instanceof \Illuminate\Http\UploadedFile &&
                        $variationData['image_file']->isValid()) {
                        
                        $variationImage = $variationData['image_file'];
                        $imageName = 'variation_' . $variation->id . '_' . time() . '.' . $variationImage->getClientOriginalExtension();
                        $imagePath = 'images/products/variations/' . $imageName;
                        
                        // Create directory if it doesn't exist
                        $directory = public_path('images/products/variations');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0755, true);
                        }
                        
                        // Move to public variations directory
                        $variationImage->move($directory, $imageName);
                        
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'ram' => 'nullable|string|max:100',
                'storage' => 'nullable|string|max:100',
                'storage_type' => 'nullable|string|max:100',
                'processor' => 'nullable|string|max:255',
                'graphics_card' => 'nullable|string|max:255',
                'screen_size' => 'nullable|string|max:255',
                'os' => 'nullable|string|max:255',
                'warranty' => 'nullable|string|max:255',
                'stock_quantity' => 'required|integer|min:0',
                'is_featured' => 'boolean',
                'is_recommended' => 'boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'product_images' => 'nullable|array',
                'product_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'is_active' => 'boolean',
                'has_variations' => 'boolean',
                'variations' => 'nullable|array',
                'variations.*.sku' => 'required_if:has_variations,1|string|max:100',
                'variations.*.price' => 'nullable|numeric|min:0',
                'variations.*.stock' => 'required_if:has_variations,1|integer|min:0',
                'variations.*.model' => 'nullable|string|max:255',
                'variations.*.processor' => 'nullable|string|max:255',
                'variations.*.ram' => 'nullable|string|max:100',
                'variations.*.storage' => 'nullable|string|max:100',
                'variations.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'variations.*.is_active' => 'boolean',
                'existing_variations' => 'nullable|array',
                'existing_variations.*.sku' => 'required|string|max:100',
                'existing_variations.*.price' => 'nullable|numeric|min:0',
                'existing_variations.*.stock' => 'required|integer|min:0',
                'existing_variations.*.model' => 'nullable|string|max:255',
                'existing_variations.*.processor' => 'nullable|string|max:255',
                'existing_variations.*.ram' => 'nullable|string|max:100',
                'existing_variations.*.storage' => 'nullable|string|max:100',
                'existing_variations.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'existing_variations.*.is_active' => 'boolean',
                'delete_variations' => 'nullable|array',
                'delete_variations.*' => 'exists:variations,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the validation errors below.');
            }

            $validated = $validator->validated();

            // Update slug if name changed
            if ($product->name !== $validated['name']) {
                $validated['slug'] = $this->generateSlug($validated['name'], $product->id);
            }

            // Update product basic info
            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'brand' => $validated['brand'] ?? null,
                'ram' => $validated['ram'] ?? null,
                'storage' => $validated['storage'] ?? null,
                'storage_type' => $validated['storage_type'] ?? null,
                'processor' => $validated['processor'] ?? null,
                'graphics_card' => $request->input('graphics_card') ?? null, 
                'screen_size' => $request->input('screen_size') ?? null, 
                'os' => $request->input('os') ?? null,  
                'warranty' => $request->input('warranty') ?? null,
                'stock_quantity' => $validated['stock_quantity'],
                'is_featured' => $validated['is_featured'] ?? false,
                'is_recommended' => $validated['is_recommended'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'has_variations' => $validated['has_variations'] ?? false,
                'slug' => $validated['slug'] ?? $product->slug,
            ]);

            // Create necessary directories
            $this->createImageDirectories();

            // Handle main image update
            if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
                // Delete old image from public directory
                if ($product->image && $product->image !== 'images/default-product.png') {
                    $oldImagePath = public_path($product->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $imageName = 'product_' . $product->id . '_main_' . time() . '.' . $request->file('main_image')->getClientOriginalExtension();
                $imagePath = 'images/products/' . $imageName;
                
                // Move to public directory
                $request->file('main_image')->move(public_path('images/products'), $imageName);
                
                // Update product image
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
                $currentMaxOrder = $product->images()->where('is_primary', false)->max('sort_order') ?? 0;
                
                foreach ($request->file('product_images') as $index => $image) {
                    // Check if file is valid and not null
                    if ($image && $image->isValid()) {
                        $imageName = 'product_' . $product->id . '_gallery_' . time() . '_' . ($index + 1) . '.' . $image->getClientOriginalExtension();
                        $imagePath = 'images/products/gallery/' . $imageName;
                        
                        // Move to public gallery directory
                        $image->move(public_path('images/products/gallery'), $imageName);
                        
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_primary' => false,
                            'sort_order' => $currentMaxOrder + $index + 1
                        ]);
                    }
                }
            }

            // Handle deletion of existing images
            if ($request->has('delete_images') && is_array($request->delete_images)) {
                foreach ($request->delete_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        // Delete the physical file
                        if ($image->image_path && $image->image_path !== 'images/default-product.png') {
                            $filePath = public_path($image->image_path);
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                        }
                        // Delete the database record
                        $image->delete();
                    }
                }
            }

            // Handle product variations update
            $hasVariations = $request->has('has_variations') && $request->has_variations;
            
            // Delete variations marked for deletion
            if ($request->has('delete_variations') && is_array($request->delete_variations)) {
                foreach ($request->delete_variations as $variationId) {
                    $variation = Variation::find($variationId);
                    if ($variation && $variation->product_id == $product->id) {
                        // Delete variation image if exists
                        if ($variation->image && file_exists(public_path($variation->image))) {
                            unlink(public_path($variation->image));
                        }
                        $variation->delete();
                    }
                }
            }

            // Update existing variations
            if ($request->has('existing_variations') && is_array($request->existing_variations)) {
                foreach ($request->existing_variations as $variationId => $variationData) {
                    $variation = Variation::find($variationId);
                    if ($variation && $variation->product_id == $product->id) {
                        $updateData = [
                            'sku' => $variationData['sku'],
                            'price' => isset($variationData['price']) && $variationData['price'] !== '' 
                                ? $variationData['price'] 
                                : $product->price,
                            'stock' => $variationData['stock'],
                            'model' => $variationData['model'] ?? null,
                            'processor' => $variationData['processor'] ?? null,
                            'ram' => $variationData['ram'] ?? null,
                            'storage' => $variationData['storage'] ?? null,
                            'is_active' => isset($variationData['is_active']) ? true : false,
                        ];

                        // Handle variation image update if provided
                        if (isset($variationData['image_file']) && 
                            $variationData['image_file'] instanceof \Illuminate\Http\UploadedFile &&
                            $variationData['image_file']->isValid()) {
                            
                            // Delete old image if exists
                            if ($variation->image && file_exists(public_path($variation->image))) {
                                unlink(public_path($variation->image));
                            }
                            
                            $variationImage = $variationData['image_file'];
                            $imageName = 'variation_' . $variation->id . '_' . time() . '.' . $variationImage->getClientOriginalExtension();
                            $imagePath = 'images/products/variations/' . $imageName;
                            
                            // Create directory if it doesn't exist
                            $directory = public_path('images/products/variations');
                            if (!file_exists($directory)) {
                                mkdir($directory, 0755, true);
                            }
                            
                            // Move to public variations directory
                            $variationImage->move($directory, $imageName);
                            
                            $updateData['image'] = $imagePath;
                        }

                        $variation->update($updateData);

                        // Update cart items when variation price changes
                        if ($oldPrice != $variation->price) {
                            CartItem::where('product_id', $product->id)
                                ->where('variation_id', $variation->id)
                                ->update(['price' => $variation->price]);
                        }
                    }
                }
            }

            // Add new variations
            if ($hasVariations && $request->has('variations') && is_array($request->variations)) {
                foreach ($request->variations as $variationIndex => $variationData) {
                    // Skip if essential data is missing or null
                    if (empty($variationData['sku']) || !isset($variationData['stock'])) {
                        continue;
                    }

                    $variation = Variation::create([
                        'product_id' => $product->id,
                        'sku' => $variationData['sku'],
                        'price' => isset($variationData['price']) && $variationData['price'] !== '' 
                            ? $variationData['price'] 
                            : $product->price,
                        'stock' => $variationData['stock'],
                        'model' => $variationData['model'] ?? null,
                        'processor' => $variationData['processor'] ?? null,
                        'ram' => $variationData['ram'] ?? null,
                        'storage' => $variationData['storage'] ?? null,
                        'is_active' => isset($variationData['is_active']) ? true : true,
                    ]);

                    // Handle variation image upload if provided
                    if (isset($variationData['image_file']) && 
                        $variationData['image_file'] instanceof \Illuminate\Http\UploadedFile &&
                        $variationData['image_file']->isValid()) {
                        
                        $variationImage = $variationData['image_file'];
                        $imageName = 'variation_' . $variation->id . '_' . time() . '.' . $variationImage->getClientOriginalExtension();
                        $imagePath = 'images/products/variations/' . $imageName;
                        
                        // Create directory if it doesn't exist
                        $directory = public_path('images/products/variations');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0755, true);
                        }
                        
                        // Move to public variations directory
                        $variationImage->move($directory, $imageName);
                        
                        $variation->update(['image' => $imagePath]);
                    }
                }
                
                // Update product to indicate it has variations
                $product->update(['has_variations' => true]);
            }

            // Update total stock based on variations if product has variations
            if ($product->has_variations) {
                $totalVariationStock = Variation::where('product_id', $product->id)->sum('stock');
                $product->update(['stock_quantity' => $totalVariationStock]);
            }

            // Update cart items when product price changes (add this after $product->update() line)
            if ($product->price != $request->price) {
                // Update all cart items with the new price
                CartItem::where('product_id', $product->id)
                    ->whereNull('variation_id') // Only update non-variation items
                    ->update(['price' => $request->price]);
                
                // Also update variation cart items if product has variations
                if ($product->has_variations) {
                    // For each variation, update corresponding cart items
                    foreach ($product->variations as $variation) {
                        CartItem::where('product_id', $product->id)
                            ->where('variation_id', $variation->id)
                            ->update(['price' => $variation->price]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.manageproduct.index')
                            ->with([
                                'success' => 'Product has been updated.',
                                'status'  => 'Product has been updated.',
                            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
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

            // Delete main product image from public directory
            if ($product->image && $product->image !== 'images/default-product.png') {
                $mainImagePath = public_path($product->image);
                if (file_exists($mainImagePath)) {
                    unlink($mainImagePath);
                }
            }

            // Delete additional images from public directory
            foreach ($product->images as $image) {
                $filePath = public_path($image->image_path);
                if ($image->image_path !== 'images/default-product.png' && file_exists($filePath)) {
                    unlink($filePath);
                }
                // Delete the database record
                $image->delete();
            }

            // Delete variation images from public directory
            foreach ($product->variations as $variation) {
                if ($variation->image && file_exists(public_path($variation->image))) {
                    unlink(public_path($variation->image));
                }
                // Delete variation record
                $variation->delete();
            }

            // Delete the product
            $product->delete();

            DB::commit();

            return redirect()->route('admin.manageproduct.index')
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product deletion error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
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
                    // Load products with relations for proper deletion
                    $products = Product::with(['images', 'variations'])->whereIn('id', $request->product_ids)->get();
                    
                    foreach ($products as $product) {
                        // Delete images from public directory
                        if ($product->image && $product->image !== 'images/default-product.png') {
                            $mainImagePath = public_path($product->image);
                            if (file_exists($mainImagePath)) {
                                unlink($mainImagePath);
                            }
                        }

                        foreach ($product->images as $image) {
                            $filePath = public_path($image->image_path);
                            if ($image->image_path !== 'images/default-product.png' && file_exists($filePath)) {
                                unlink($filePath);
                            }
                        }

                        foreach ($product->variations as $variation) {
                            if ($variation->image && file_exists(public_path($variation->image))) {
                                unlink(public_path($variation->image));
                            }
                        }
                    }
                    
                    // Delete products
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
            \Log::error('Bulk action error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product featured status
     */
    public function toggleFeatured(Product $product)
    {
        try {
            $product->update(['is_featured' => !$product->is_featured]);
            
            $status = $product->is_featured ? 'featured' : 'unfeatured';
            return redirect()->back()
                ->with('success', "Product {$status} status updated successfully!");
                
        } catch (\Exception $e) {
            \Log::error('Toggle featured error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update featured status.');
        }
    }

    /**
     * Toggle product active status
     */
    public function toggleActive(Product $product)
    {
        try {
            $product->update(['is_active' => !$product->is_active]);
            
            $status = $product->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Product {$status} successfully!");
                
        } catch (\Exception $e) {
            \Log::error('Toggle active error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update active status.');
        }
    }

    /**
     * Delete product image
     */
    public function deleteImage(ProductImage $image)
    {
        try {
            // Delete the physical file from public directory
            if ($image->image_path && $image->image_path !== 'images/default-product.png') {
                $filePath = public_path($image->image_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete the database record
            $image->delete();

            return redirect()->back()->with('success', 'Image deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Delete image error: ' . $e->getMessage());
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

    /**
     * Create necessary image directories
     */
    private function createImageDirectories()
    {
        $directories = [
            'images',
            'images/products',
            'images/products/gallery',
            'images/products/variations'
        ];

        foreach ($directories as $directory) {
            $path = public_path($directory);
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

    /**
     * Debug method to check file upload issues
     */
    public function debugUpload(Request $request)
    {
        try {
            $debugInfo = [
                'has_main_image' => $request->hasFile('main_image'),
                'main_image_valid' => $request->hasFile('main_image') ? $request->file('main_image')->isValid() : false,
                'main_image_error' => $request->hasFile('main_image') ? $request->file('main_image')->getError() : 'No file',
                'main_image_size' => $request->hasFile('main_image') ? $request->file('main_image')->getSize() : 0,
                'main_image_mime' => $request->hasFile('main_image') ? $request->file('main_image')->getMimeType() : 'N/A',
                'product_images_count' => $request->hasFile('product_images') ? count($request->file('product_images')) : 0,
                'all_files' => array_keys($request->allFiles()),
                'public_dir_exists' => file_exists(public_path('images/products')),
                'public_dir_writable' => is_writable(public_path('images/products')),
            ]; 

            return response()->json($debugInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}