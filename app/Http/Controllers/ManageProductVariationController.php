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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variations', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['product_id'] = $product->id;
        $validated['is_active'] = $request->has('is_active');

        Variation::create($validated);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($variation->image) {
                Storage::disk('public')->delete($variation->image);
            }
            
            $imagePath = $request->file('image')->store('variations', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_active'] = $request->has('is_active');

        $variation->update($validated);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Variation updated successfully.');
    }

    /**
     * Remove the specified variation from storage.
     */
    public function destroy(Product $product, Variation $variation)
    {
        // Delete variation image
        if ($variation->image) {
            Storage::disk('public')->delete($variation->image);
        }

        $variation->delete();

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Variation deleted successfully.');
    }
}