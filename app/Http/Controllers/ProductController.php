<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with(['category', 'variations'])
            ->withCount('variations')
            ->latest()
            ->paginate(10);
        
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Start database transaction
        DB::beginTransaction();

        try {
            // Validate main product data
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'base_price' => 'nullable|numeric|min:0',
                'total_stock' => 'nullable|integer|min:0',
                'category_id' => 'nullable|exists:categories,id',
                'has_variations' => 'sometimes|boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $imagePath = $image->store('products', 'public');
                $validated['main_image'] = $imagePath;
            }

            // Create the product
            $product = Product::create($validated);

            // Handle multiple product images
            if ($request->hasFile('product_images')) {
                $productImages = [];
                
                foreach ($request->file('product_images') as $key => $image) {
                    $imagePath = $image->store('products/gallery', 'public');
                    
                    $productImages[] = [
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => ($key === 0 && empty($product->main_image)), // Set first as primary if no main image
                        'sort_order' => $key,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                // Bulk insert for better performance
                ProductImage::insert($productImages);
            }

            // Create variations if provided
            if ($request->has('variations') && is_array($request->variations)) {
                $variationsToCreate = [];
                
                foreach ($request->variations as $variationData) {
                    // Skip if SKU is empty
                    if (empty($variationData['sku'])) {
                        continue;
                    }

                    // Validate variation data
                    $variationValidator = Validator::make($variationData, [
                        'sku' => 'required|string|max:100|unique:variations,sku',
                        'price' => 'nullable|numeric|min:0',
                        'stock' => 'required|integer|min:0',
                        'image' => 'nullable|string',
                        'model' => 'nullable|string|max:100',
                        'processor' => 'nullable|string|max:100',
                        'ram' => 'nullable|integer|min:0',
                        'storage' => 'nullable|integer|min:0',
                        'storage_type' => 'nullable|string|max:50',
                        'graphics_card' => 'nullable|string|max:100',
                        'screen_size' => 'nullable|string|max:50',
                        'os' => 'nullable|string|max:100',
                        'warranty' => 'nullable|string|max:100',
                        'voltage' => 'nullable|string|max:50',
                    ]);

                    if ($variationValidator->fails()) {
                        continue; // Skip invalid variations
                    }

                    $variationDataValidated = $variationValidator->validated();
                    $variationDataValidated['product_id'] = $product->id;

                    // Handle variation image (base64 to file)
                    if (!empty($variationDataValidated['image']) && strpos($variationDataValidated['image'], 'data:image') === 0) {
                        $imageData = $variationDataValidated['image'];
                        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
                        $imageName = 'variation_' . time() . '_' . uniqid() . '.png';
                        $imagePath = 'variations/' . $imageName;
                        
                        Storage::disk('public')->put($imagePath, $imageData);
                        $variationDataValidated['image'] = $imagePath;
                    } else {
                        unset($variationDataValidated['image']);
                    }

                    $variationsToCreate[] = $variationDataValidated;
                }

                // Bulk create variations
                if (!empty($variationsToCreate)) {
                    Variation::insert($variationsToCreate);
                }
            }

            // Commit transaction
            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            // Log the error
            \Log::error('Product creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'variations', 'images']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load(['variations', 'images']);
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'base_price' => 'nullable|numeric|min:0',
                'total_stock' => 'nullable|integer|min:0',
                'category_id' => 'nullable|exists:categories,id',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                // Delete old image if exists
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                
                $image = $request->file('main_image');
                $imagePath = $image->store('products', 'public');
                $validated['main_image'] = $imagePath;
            }

            $product->update($validated);

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Product update failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            // Delete main image
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }

            // Delete product images
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }

            // Delete variation images
            foreach ($product->variations as $variation) {
                if ($variation->image && Storage::disk('public')->exists($variation->image)) {
                    Storage::disk('public')->delete($variation->image);
                }
                $variation->delete();
            }

            // Delete the product
            $product->delete();

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Product deletion failed: ' . $e->getMessage());

            return redirect()->route('products.index')
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new variation for the specified product.
     */
    public function createVariation(Product $product)
    {
        return view('products.variations.create', compact('product'));
    }

    /**
     * Store a newly created variation for the specified product.
     */
    public function storeVariation(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:100|unique:variations,sku',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'model' => 'nullable|string|max:100',
            'processor' => 'nullable|string|max:100',
            'ram' => 'nullable|integer|min:0',
            'storage' => 'nullable|integer|min:0',
            'storage_type' => 'nullable|string|max:50',
            'graphics_card' => 'nullable|string|max:100',
            'screen_size' => 'nullable|string|max:50',
            'os' => 'nullable|string|max:100',
            'warranty' => 'nullable|string|max:100',
            'voltage' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validated['image'] = $image->store('variations', 'public');
        }

        $product->variations()->create($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Variation added successfully.');
    }

    /**
     * Show the form for editing the specified variation.
     */
    public function editVariation(Product $product, Variation $variation)
    {
        return view('products.variations.edit', compact('product', 'variation'));
    }

    /**
     * Update the specified variation in storage.
     */
    public function updateVariation(Request $request, Product $product, Variation $variation)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:100|unique:variations,sku,' . $variation->id,
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'model' => 'nullable|string|max:100',
            'processor' => 'nullable|string|max:100',
            'ram' => 'nullable|integer|min:0',
            'storage' => 'nullable|integer|min:0',
            'storage_type' => 'nullable|string|max:50',
            'graphics_card' => 'nullable|string|max:100',
            'screen_size' => 'nullable|string|max:50',
            'os' => 'nullable|string|max:100',
            'warranty' => 'nullable|string|max:100',
            'voltage' => 'nullable|string|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($variation->image) {
                Storage::disk('public')->delete($variation->image);
            }
            
            $image = $request->file('image');
            $validated['image'] = $image->store('variations', 'public');
        }

        // Ensure is_active is set
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $variation->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Variation updated successfully.');
    }

    /**
     * Remove the specified variation from storage.
     */
    public function destroyVariation(Product $product, Variation $variation)
    {
        try {
            // Delete image if exists
            if ($variation->image) {
                Storage::disk('public')->delete($variation->image);
            }

            $variation->delete();

            return redirect()->route('products.show', $product)
                ->with('success', 'Variation deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Variation deletion failed: ' . $e->getMessage());

            return redirect()->route('products.show', $product)
                ->with('error', 'Error deleting variation: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:delete,activate,deactivate',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $productIds = $request->input('product_ids');
        $action = $request->input('action');

        DB::beginTransaction();

        try {
            switch ($action) {
                case 'delete':
                    $products = Product::whereIn('id', $productIds)->get();
                    foreach ($products as $product) {
                        // Delete main image
                        if ($product->main_image) {
                            Storage::disk('public')->delete($product->main_image);
                        }

                        // Delete product images
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
                    }
                    
                    Product::whereIn('id', $productIds)->delete();
                    $message = 'Selected products deleted successfully.';
                    break;

                case 'activate':
                    Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    $message = 'Selected products activated successfully.';
                    break;

                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    $message = 'Selected products deactivated successfully.';
                    break;

                default:
                    throw new \Exception('Invalid bulk action.');
            }

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Bulk action failed: ' . $e->getMessage());

            return redirect()->route('products.index')
                ->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $products = Product::with(['category', 'variations'])
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->withCount('variations')
            ->latest()
            ->paginate(10);

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Get product details for API
     */
    public function getProductDetails(Product $product)
    {
        $product->load(['category', 'variations', 'images']);

        return response()->json([
            'success' => true,
            'product' => $product,
            'main_image_url' => $product->main_image_url,
            'all_image_urls' => $product->all_image_urls,
            'price_range' => $product->price_range,
            'calculated_stock' => $product->calculated_total_stock,
        ]);
    }
}