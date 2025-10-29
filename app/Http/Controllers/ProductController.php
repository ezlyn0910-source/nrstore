<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //Display All Products
    public function index()
    {
        $products = Product::with(['category', 'variations'])
            ->withCount('variations')
            ->latest()
            ->paginate(10);
        
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    //Show form to create a new product
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Store new product in Database
    public function store(Request $request)
    {
        // Validate product data
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'nullable|numeric|min:0',
            'total_stock' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'has_variations' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the product
        $product = Product::create($validated);


        // Create variations if provided
        if ($request->has('variations') && is_array($request->variations)) {
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
                    'image' => 'nullable|string', // Base64 image data
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

                // Handle variation image (base64 to file)
                if (!empty($variationDataValidated['image']) && strpos($variationDataValidated['image'], 'data:image') === 0) {
                    $imageData = $variationDataValidated['image'];
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
                    $imageName = 'variation_' . time() . '_' . uniqid() . '.png';
                    $imagePath = 'variations/' . $imageName;
                    
                    \Storage::disk('public')->put($imagePath, $imageData);
                    $variationDataValidated['image'] = $imagePath;
                } else {
                    unset($variationDataValidated['image']);
                }

                $product->variations()->create($variationDataValidated);
            }
        }

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        }

    //Display single product details
    public function show(Product $product)
    {
        $product->load(['category', 'variations']);
        return view('products.show', compact('product'));
    }
    
    //show edit form
    public function edit(Product $product) 
    {
        $categories = Category::all();
        $product->load('variations');
        return view('products.edit', compact('product', 'categories'));
    }

    //update product Database
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'nullable|numeric|min:0',
            'total_stock' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    //delete product
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // Show form to add variation to existing product
    public function createVariation(Product $product)
    {
        return view('products.variations.create', compact('product'));
    }

    // Store new variation for existing product
    public function storeVariation(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:100|unique:variations,sku',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Electronics specifications
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
            $validated['image'] = $request->file('image')->store('variations', 'public');
        }

        $product->variations()->create($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Variation added successfully.');
    }

    // Edit variation form
    public function editVariation(Product $product, Variation $variation)
    {
        return view('products.variations.edit', compact('product', 'variation'));
    }

    // Update variation
    public function updateVariation(Request $request, Product $product, Variation $variation)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:100|unique:variations,sku,' . $variation->id,
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Electronics specifications
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
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($variation->image) {
                \Storage::disk('public')->delete($variation->image);
            }
            $validated['image'] = $request->file('image')->store('variations', 'public');
        }

        $variation->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Variation updated successfully.');
    }

    // Delete variation
    public function destroyVariation(Product $product, Variation $variation)
    {
        // Delete image if exists
        if ($variation->image) {
            \Storage::disk('public')->delete($variation->image);
        }

        $variation->delete();

        return redirect()->route('products.show', $product)
            ->with('success', 'Variation deleted successfully.');
    }
}