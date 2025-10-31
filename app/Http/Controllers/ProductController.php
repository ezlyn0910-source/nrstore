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
            // PUBLIC: Return productpage view (existing code)
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
        $categories = Category::all();
        return view('manageproduct.create', compact('categories'));
    }

    public function store(Request $request)
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
                'has_variations' => 'boolean',
                'variations' => 'nullable|array',
                'variations.*.sku' => 'required_if:has_variations,1|string|max:100',
                'variations.*.price' => 'nullable|numeric|min:0',
                'variations.*.stock' => 'required_if:has_variations,1|integer|min:0',
                'variations.*.image' => 'nullable|string',
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
            ]);

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'brand' => $validated['brand'] ?? null,
                'ram' => $validated['ram'] ?? null,
                'storage' => $validated['storage'] ?? null,
                'processor' => $validated['processor'] ?? null,
                'stock_quantity' => $validated['stock_quantity'],
                'is_featured' => $validated['is_featured'] ?? false,
                'is_recommended' => $validated['is_recommended'] ?? false,
            ]);

            // Handle main image
            if ($request->hasFile('main_image')) {
                $imagePath = $request->file('main_image')->store('products', 'public');
                $product->update(['image' => $imagePath]);
                
                // Create primary product image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => true,
                    'sort_order' => 0
                ]);
            }

            // Handle additional images
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $index => $image) {
                    $imagePath = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => false,
                        'sort_order' => $index + 1
                    ]);
                }
            }

            // Handle variations
            if ($request->has_variations && !empty($validated['variations'])) {
                foreach ($validated['variations'] as $variationData) {
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

                    // Handle variation image (base64)
                    if (!empty($variationData['image'])) {
                        $imageData = $variationData['image'];
                        if (Str::startsWith($imageData, 'data:image')) {
                            $imagePath = $this->saveBase64Image($imageData, "variations/{$variation->id}");
                            $variation->update(['image' => $imagePath]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
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

            return redirect()->route('admin.products.index')
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

            return redirect()->route('admin.products.index')
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

            return redirect()->route('admin.products.index')->with('success', $message);

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

    private function saveBase64Image($base64Image, $path)
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        $fileName = uniqid() . '.png';
        $fullPath = "{$path}/{$fileName}";
        
        Storage::disk('public')->put($fullPath, $imageData);
        
        return $fullPath;
    }

    private function publicIndex(Request $request)
    {
        // Existing public index logic
        $category = $request->get('category');
        $brand = $request->get('brand');
        $sort = $request->get('sort', 'name');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        $query = Product::with('category')->where('is_active', true);

        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        if ($brand) {
            $query->where('brand', $brand);
        }

        if ($minPrice && $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $products = $query->paginate(9);
        $recommendedProducts = Product::recommended()->where('is_active', true)->get();
        $categories = Category::all();

        return view('productpage', compact('products', 'recommendedProducts', 'categories'));
    }
}