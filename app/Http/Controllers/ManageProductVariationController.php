<?php
// app/Http/Controllers/ManageProductVariationController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManageProductVariationController extends Controller
{
    /**
     * Show the form for creating a new variation.
     */
    public function create(Product $product)
    {
        return view('admin.products.variations.create', compact('product'));
    }

    /**
     * Store a newly created variation in storage.
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:variations,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'model' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'storage_type' => 'nullable|string|max:255',
            'graphics_card' => 'nullable|string|max:255',
            'screen_size' => 'nullable|string|max:255',
            'os' => 'nullable|string|max:255',
            'warranty' => 'nullable|string|max:255',
            'voltage' => 'nullable|string|max:255',
        ]);

        // Handle image upload - Store in public directory
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            
            // Generate unique filename
            $imageName = 'variation_' . $product->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/products/variations/' . $imageName;  // Relative path
            
            // Create directory if it doesn't exist
            $directory = public_path('images/products/variations');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move image to public directory
            $image->move($directory, $imageName);
            
            // Store relative path in validated data
            $validated['image'] = $imagePath;
        }

        // Set product ID and active status
        $validated['product_id'] = $product->id;
        $validated['is_active'] = $request->has('is_active');

        // Create the variation
        Variation::create($validated);

        // Update product's stock quantity if it has variations
        if ($product->has_variations) {
            $totalStock = Variation::where('product_id', $product->id)->sum('stock');
            $product->update(['stock_quantity' => $totalStock]);
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Variation created successfully.');
    }

    /**
     * Show the form for editing the specified variation.
     */
    public function edit(Product $product, Variation $variation)
    {
        return view('admin.products.variations.edit', compact('product', 'variation'));
    }

    /**
     * Update the specified variation in storage.
     */
    public function update(Request $request, Product $product, Variation $variation)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:variations,sku,' . $variation->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'model' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'storage_type' => 'nullable|string|max:255',
            'graphics_card' => 'nullable|string|max:255',
            'screen_size' => 'nullable|string|max:255',
            'os' => 'nullable|string|max:255',
            'warranty' => 'nullable|string|max:255',
            'voltage' => 'nullable|string|max:255',
        ]);

        // Handle image upload - Store in public directory
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image from public directory if it exists
            if ($variation->image && file_exists(public_path($variation->image))) {
                unlink(public_path($variation->image));
            }
            
            $image = $request->file('image');
            
            // Generate unique filename
            $imageName = 'variation_' . $product->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/products/variations/' . $imageName;  // Relative path
            
            // Create directory if it doesn't exist
            $directory = public_path('images/products/variations');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move image to public directory
            $image->move($directory, $imageName);
            
            // Store relative path in validated data
            $validated['image'] = $imagePath;
        }

        // Set active status
        $validated['is_active'] = $request->has('is_active');

        // Update the variation
        $variation->update($validated);

        // Update product's stock quantity if it has variations
        if ($product->has_variations) {
            $totalStock = Variation::where('product_id', $product->id)->sum('stock');
            $product->update(['stock_quantity' => $totalStock]);
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Variation updated successfully.');
    }

    /**
     * Remove the specified variation from storage.
     */
    public function destroy(Product $product, Variation $variation)
    {
        // Delete variation image from public directory if it exists
        if ($variation->image && file_exists(public_path($variation->image))) {
            unlink(public_path($variation->image));
        }

        // Delete the variation
        $variation->delete();

        // Update product's stock quantity if it has variations
        if ($product->has_variations) {
            $remainingVariations = Variation::where('product_id', $product->id)->count();
            
            if ($remainingVariations > 0) {
                $totalStock = Variation::where('product_id', $product->id)->sum('stock');
                $product->update(['stock_quantity' => $totalStock]);
            } else {
                // No variations left, disable variations on product
                $product->update([
                    'has_variations' => false,
                    'stock_quantity' => $product->stock_quantity
                ]);
            }
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Variation deleted successfully.');
    }

    /**
     * Toggle variation active status
     */
    public function toggleActive(Product $product, Variation $variation)
    {
        try {
            $variation->update(['is_active' => !$variation->is_active]);
            
            $status = $variation->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Variation {$status} successfully!");
                
        } catch (\Exception $e) {
            \Log::error('Toggle variation active error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update variation status.');
        }
    }

    /**
     * Delete variation image only
     */
    public function deleteImage(Product $product, Variation $variation)
    {
        try {
            // Check if variation has an image
            if (!$variation->image) {
                return redirect()->back()->with('error', 'Variation does not have an image.');
            }

            // Delete the image file from public directory
            $imagePath = public_path($variation->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Update variation to remove image reference
            $variation->update(['image' => null]);

            return redirect()->back()->with('success', 'Variation image deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Delete variation image error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete variation image: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update variation stock
     */
    public function bulkUpdateStock(Request $request, Product $product)
    {
        $request->validate([
            'variations' => 'required|array',
            'variations.*.id' => 'required|exists:variations,id',
            'variations.*.stock' => 'required|integer|min:0',
        ]);

        try {
            foreach ($request->variations as $variationData) {
                $variation = Variation::find($variationData['id']);
                if ($variation && $variation->product_id == $product->id) {
                    $variation->update(['stock' => $variationData['stock']]);
                }
            }

            // Update product's total stock
            if ($product->has_variations) {
                $totalStock = Variation::where('product_id', $product->id)->sum('stock');
                $product->update(['stock_quantity' => $totalStock]);
            }

            return redirect()->back()->with('success', 'Variation stock updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Bulk update variation stock error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update variation stock.');
        }
    }
}