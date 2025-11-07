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

class ManageProductController extends Controller
{
    /**
     * Display a listing of the products for admin.
     */
    public function index()
    {
        $products = Product::with(['category', 'images', 'variations'])
            ->latest()
            ->paginate(10);

        // Add these statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();
        $categories = Category::all();

        return view('manageproduct.index', compact(
            'products', 
            'totalProducts', 
            'activeProducts', 
            'featuredProducts', 
            'lowStockProducts',
            'categories'
        ));
    }

    /**
     * Display products for public (customer) view
     */
    public function publicIndex()
    {
        $products = Product::with(['category', 'images'])
            ->active()
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    /**
     * Display single product for public (customer) view
     */
    public function publicShow($slug)
    {
        $product = Product::with(['category', 'images', 'variations'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }

    /**
     * Show product by slug (alternative method)
     */
    public function showBySlug(Product $product)
    {
        $product->load(['category', 'images', 'variations']);
        return view('products.show', compact('product'));
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
        // Validate the request
        $validated = $this->validateProductRequest($request);

        DB::beginTransaction();

        try {
            // Create the product
            $product = Product::create($validated['product_data']);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $this->handleMainImageUpload($product, $request->file('main_image'));
            }

            // Handle gallery images upload
            if ($request->hasFile('product_images')) {
                $this->handleGalleryImagesUpload($product, $request->file('product_images'));
            }

            // Handle variations if product has variations
            if ($request->has_variations && isset($validated['variations_data'])) {
                $this->handleVariationsCreation($product, $validated['variations_data'], $request);
            }

            DB::commit();

            return redirect()->route('admin.manageproduct.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete any uploaded files if transaction fails
            if (isset($product)) {
                $this->cleanupProductFiles($product);
            }

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
        return view('admin.manageproduct.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load(['images', 'variations']);
        
        return view('admin.manageproduct.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate the request
        $validated = $this->validateProductRequest($request, $product);

        DB::beginTransaction();

        try {
            // Update the product
            $product->update($validated['product_data']);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $this->handleMainImageUpload($product, $request->file('main_image'));
            }

            // Handle gallery images upload
            if ($request->hasFile('product_images')) {
                $this->handleGalleryImagesUpload($product, $request->file('product_images'));
            }

            // Handle variations
            if ($request->has_variations && isset($validated['variations_data'])) {
                $this->handleVariationsUpdate($product, $validated['variations_data'], $request);
            } else {
                // If no variations, delete existing variations
                $product->variations()->delete();
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

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            // Delete product files and related records
            $this->cleanupProductFiles($product);
            
            // Delete the product
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

    /**
     * Validate product request data
     */
    private function validateProductRequest(Request $request, Product $product = null)
    {
        $productId = $product ? $product->id : null;

        $baseRules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'processor' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:100',
            'storage' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_recommended' => 'boolean',
            'has_variations' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images' => 'nullable|array',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add slug validation for create/update
        if ($productId) {
            $baseRules['slug'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products')->ignore($productId)
            ];
        } else {
            $baseRules['slug'] = 'nullable|string|max:255|unique:products';
        }

        $validated = $request->validate($baseRules);

        // Prepare product data
        $productData = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'brand' => $validated['brand'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
            'processor' => $validated['processor'],
            'ram' => $validated['ram'],
            'storage' => $validated['storage'],
            'is_featured' => $request->boolean('is_featured'),
            'is_recommended' => $request->boolean('is_recommended'),
            'has_variations' => $request->boolean('has_variations'),
        ];

        // Handle slug generation
        if (empty($validated['slug']) && !empty($validated['name'])) {
            $productData['slug'] = Str::slug($validated['name']);
            
            // Ensure slug is unique
            $originalSlug = $productData['slug'];
            $count = 1;
            while (Product::where('slug', $productData['slug'])->when($productId, function($query) use ($productId) {
                return $query->where('id', '!=', $productId);
            })->exists()) {
                $productData['slug'] = $originalSlug . '-' . $count++;
            }
        } elseif (!empty($validated['slug'])) {
            $productData['slug'] = Str::slug($validated['slug']);
        }

        $result = ['product_data' => $productData];

        // Validate variations if product has variations
        if ($request->has_variations) {
            $variationRules = [
                'variations' => 'required|array|min:1',
                'variations.*.sku' => 'required|string|max:255',
                'variations.*.price' => 'nullable|numeric|min:0',
                'variations.*.stock' => 'required|integer|min:0',
                'variations.*.model' => 'nullable|string|max:255',
                'variations.*.processor' => 'nullable|string|max:255',
                'variations.*.ram' => 'nullable|string|max:100',
                'variations.*.storage' => 'nullable|string|max:255',
                'variations.*.storage_type' => 'nullable|string|max:50',
                'variations.*.graphics_card' => 'nullable|string|max:255',
                'variations.*.screen_size' => 'nullable|string|max:100',
                'variations.*.os' => 'nullable|string|max:255',
                'variations.*.warranty' => 'nullable|string|max:255',
                'variations.*.voltage' => 'nullable|string|max:100',
                'variations.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            $validatedVariations = $request->validate($variationRules);
            $result['variations_data'] = $validatedVariations['variations'];
        }

        return $result;
    }

    /**
     * Handle main image upload
     */
    private function handleMainImageUpload(Product $product, $imageFile)
    {
        // Delete old main image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Store new main image
        $imagePath = $imageFile->store('products/' . $product->id, 'public');
        $product->update(['image' => $imagePath]);
    }

    /**
     * Handle gallery images upload
     */
    private function handleGalleryImagesUpload(Product $product, $imageFiles)
    {
        $imagesToCreate = [];
        $sortOrder = $product->images()->max('sort_order') ?? 0;

        foreach ($imageFiles as $imageFile) {
            if ($imageFile->isValid()) {
                $imagePath = $imageFile->store('products/' . $product->id . '/gallery', 'public');
                
                $imagesToCreate[] = [
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => false,
                    'sort_order' => ++$sortOrder,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($imagesToCreate)) {
            ProductImage::insert($imagesToCreate);
        }
    }

    /**
     * Handle variations creation
     */
    private function handleVariationsCreation(Product $product, array $variationsData, Request $request)
    {
        $variationsToCreate = [];

        foreach ($variationsData as $index => $variationData) {
            $variation = [
                'product_id' => $product->id,
                'sku' => $variationData['sku'],
                'price' => $variationData['price'] ?? null,
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
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Handle variation image upload
            if ($request->hasFile("variations.{$index}.image_file")) {
                $imageFile = $request->file("variations.{$index}.image_file");
                $imagePath = $imageFile->store('products/' . $product->id . '/variations', 'public');
                $variation['image'] = $imagePath;
            }

            $variationsToCreate[] = $variation;
        }

        if (!empty($variationsToCreate)) {
            Variation::insert($variationsToCreate);
        }
    }

    /**
     * Handle variations update
     */
    private function handleVariationsUpdate(Product $product, array $variationsData, Request $request)
    {
        $existingVariationIds = $product->variations->pluck('id')->toArray();
        $updatedVariationIds = [];

        foreach ($variationsData as $index => $variationData) {
            $variationData = [
                'sku' => $variationData['sku'],
                'price' => $variationData['price'] ?? null,
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
            ];

            // Handle variation image upload
            if ($request->hasFile("variations.{$index}.image_file")) {
                $imageFile = $request->file("variations.{$index}.image_file");
                $imagePath = $imageFile->store('products/' . $product->id . '/variations', 'public');
                $variationData['image'] = $imagePath;
            }

            // Check if variation exists (you might need to implement variation ID tracking in your form)
            // For simplicity, we'll update or create based on SKU
            $variation = Variation::where('product_id', $product->id)
                ->where('sku', $variationData['sku'])
                ->first();

            if ($variation) {
                $variation->update($variationData);
                $updatedVariationIds[] = $variation->id;
            } else {
                $variationData['product_id'] = $product->id;
                $variationData['is_active'] = true;
                $newVariation = Variation::create($variationData);
                $updatedVariationIds[] = $newVariation->id;
            }
        }

        // Delete variations that were removed
        $variationsToDelete = array_diff($existingVariationIds, $updatedVariationIds);
        if (!empty($variationsToDelete)) {
            Variation::whereIn('id', $variationsToDelete)->delete();
        }
    }

    /**
     * Clean up product files when deletion fails or product is deleted
     */
    private function cleanupProductFiles(Product $product)
    {
        // Delete main image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete gallery images
        foreach ($product->images as $image) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // Delete variation images
        foreach ($product->variations as $variation) {
            if ($variation->image) {
                Storage::disk('public')->delete($variation->image);
            }
        }

        // Delete the entire product directory
        $productDirectory = 'products/' . $product->id;
        if (Storage::disk('public')->exists($productDirectory)) {
            Storage::disk('public')->deleteDirectory($productDirectory);
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
     * Search products
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('search');
        
        $products = Product::with(['category', 'images'])
            ->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('description', 'like', "%{$searchTerm}%")
            ->orWhere('brand', 'like', "%{$searchTerm}%")
            ->latest()
            ->paginate(10);

        return view('admin.manageproduct.index', compact('products'));
    }

    /**
     * Bulk action for products
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $productIds = $request->input('product_ids', []);

        if (empty($productIds)) {
            return redirect()->back()->with('error', 'No products selected.');
        }

        try {
            switch ($action) {
                case 'activate':
                    Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    $message = 'Selected products activated successfully!';
                    break;
                
                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    $message = 'Selected products deactivated successfully!';
                    break;
                
                case 'delete':
                    Product::whereIn('id', $productIds)->delete();
                    $message = 'Selected products deleted successfully!';
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
     * Update product status
     */
    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $product->update(['is_active' => $request->is_active]);

        return redirect()->back()->with('success', 'Product status updated successfully!');
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
}