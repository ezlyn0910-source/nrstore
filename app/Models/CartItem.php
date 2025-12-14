<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variation_id',
        'quantity',
        'price',
        'product_name',
        'image_url'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    protected $appends = [
        'subtotal',
        'display_name',
        'display_image_url',
        'available_stock',
        'in_stock'
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }

    /**
     * Get subtotal for this item
     */
    public function getSubtotalAttribute()
    {
        $price = $this->price;
        
        // If we have a variation and need to get current price
        if ($this->variation_id && $this->relationLoaded('variation') && $this->variation) {
            $price = $this->variation->price ?? $price;
        }
        
        return $price * $this->quantity;
    }

    /**
     * Get display name with variation details
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->product->name ?? 'Product';
        
        if ($this->variation_id && $this->relationLoaded('variation') && $this->variation) {
            $variationDetails = [];
            
            // Add variation-specific details
            if ($this->variation->model) {
                $variationDetails[] = $this->variation->model;
            }
            if ($this->variation->ram) {
                $variationDetails[] = $this->variation->ram;
            }
            if ($this->variation->storage) {
                $variationDetails[] = $this->variation->storage;
            }
            
            if (!empty($variationDetails)) {
                $name .= ' - ' . implode(' | ', $variationDetails);
            }
        }
        
        return $name;
    }

    /**
     * Get the image URL for cart display
     */
    public function getImageUrlAttribute()
    {
        // If image_url is stored in session/cart (for guest users)
        if (isset($this->attributes['image_url']) && $this->attributes['image_url']) {
            $imagePath = $this->attributes['image_url'];
            // Ensure proper path format
            if (!str_starts_with($imagePath, 'http')) {
                if (!str_starts_with($imagePath, 'images/')) {
                    $imagePath = 'images/products/' . ltrim($imagePath, '/');
                }
                return asset($imagePath);
            }
            return $imagePath;
        }
        
        // If we have a product relation
        if ($this->product) {
            // Check for variation image first
            if ($this->variation_id && $this->variation && $this->variation->image) {
                $imagePath = $this->variation->image;
                if (!str_starts_with($imagePath, 'http')) {
                    if (!str_starts_with($imagePath, 'images/')) {
                        $imagePath = 'images/products/' . ltrim($imagePath, '/');
                    }
                    return asset($imagePath);
                }
                return $imagePath;
            }
            
            // Get product's main image
            if ($this->product->main_image_url) {
                return $this->product->main_image_url;
            }
            
            // Try via primary image relationship
            if ($this->product->primaryImage) {
                return $this->product->primaryImage->image_url;
            }
        }
        
        // Fallback to default image
        return asset('images/default-product.png');
    }

    /**
     * Get available stock for this specific item
     */
    public function getAvailableStockAttribute()
    {
        if ($this->variation_id && $this->relationLoaded('variation') && $this->variation) {
            return $this->variation->stock ?? 0;
        }
        
        if ($this->relationLoaded('product') && $this->product) {
            return $this->product->stock_quantity ?? 0;
        }
        
        return 0;
    }

    /**
     * Check if item is in stock
     */
    public function getInStockAttribute()
    {
        return $this->available_stock >= $this->quantity;
    }

    /**
     * Check if item has sufficient stock for a given quantity
     */
    public function hasSufficientStock($quantity = null)
    {
        $requestedQuantity = $quantity ?? $this->quantity;
        return $this->available_stock >= $requestedQuantity;
    }

    /**
     * Get maximum quantity that can be added
     */
    public function getMaxAvailableQuantityAttribute()
    {
        return min($this->available_stock, 99); // Limit to 99 for UX
    }

    /**
     * Increase quantity with stock validation
     */
    public function increaseQuantity($quantity = 1)
    {
        $newQuantity = $this->quantity + $quantity;
        
        // Check stock before increasing
        if ($newQuantity > $this->available_stock) {
            throw new \Exception('Insufficient stock. Only ' . $this->available_stock . ' items available.');
        }
        
        // Check maximum quantity limit
        if ($newQuantity > 99) {
            throw new \Exception('Maximum quantity per item is 99.');
        }
        
        $this->update(['quantity' => $newQuantity]);
        $this->cart->calculateTotals();
        
        return $this;
    }

    /**
     * Decrease quantity
     */
    public function decreaseQuantity($quantity = 1)
    {
        $newQuantity = $this->quantity - $quantity;
        
        if ($newQuantity <= 0) {
            $this->delete();
            return null;
        }
        
        $this->update(['quantity' => $newQuantity]);
        $this->cart->calculateTotals();
        
        return $this;
    }

    /**
     * Update quantity with validation
     */
    public function updateQuantity($newQuantity)
    {
        if ($newQuantity < 1) {
            $this->delete();
            return null;
        }
        
        if ($newQuantity > 99) {
            throw new \Exception('Maximum quantity per item is 99.');
        }
        
        if ($newQuantity > $this->available_stock) {
            throw new \Exception('Insufficient stock. Only ' . $this->available_stock . ' items available.');
        }
        
        $this->update(['quantity' => $newQuantity]);
        $this->cart->calculateTotals();
        
        return $this;
    }

    /**
     * Load all necessary relationships for display
     */
    public function loadForDisplay()
    {
        return $this->load([
            'product' => function($query) {
                $query->select('id', 'name', 'slug', 'brand', 'image', 'stock_quantity', 'has_variations')
                      ->with(['images' => function($q) {
                          $q->orderBy('is_primary', 'desc')->orderBy('sort_order');
                      }]);
            },
            'variation' => function($query) {
                $query->select('id', 'product_id', 'model', 'ram', 'storage', 'processor', 'image', 'stock', 'price');
            }
        ]);
    }

    /**
     * Scope to include stock information
     */
    public function scopeWithStockInfo($query)
    {
        return $query->with([
            'product' => function($q) {
                $q->select('id', 'name', 'stock_quantity', 'has_variations');
            },
            'variation' => function($q) {
                $q->select('id', 'product_id', 'stock');
            }
        ]);
    }

    /**
     * Scope to find items that are out of stock
     */
    public function scopeOutOfStock($query)
    {
        return $query->where(function($q) {
            $q->whereHas('variation', function($subQ) {
                $subQ->whereRaw('variations.stock < cart_items.quantity');
            })->orWhere(function($subQ) {
                $subQ->whereNull('variation_id')
                     ->whereHas('product', function($productQ) {
                         $productQ->whereRaw('products.stock_quantity < cart_items.quantity');
                     });
            });
        });
    }

    /**
     * Scope to find items that are in stock
     */
    public function scopeInStock($query)
    {
        return $query->where(function($q) {
            $q->whereHas('variation', function($subQ) {
                $subQ->whereRaw('variations.stock >= cart_items.quantity');
            })->orWhere(function($subQ) {
                $subQ->whereNull('variation_id')
                     ->whereHas('product', function($productQ) {
                         $productQ->whereRaw('products.stock_quantity >= cart_items.quantity');
                     });
            });
        });
    }

    /**
     * Get stock status for this item
     */
    public function getStockStatusAttribute()
    {
        $availableStock = $this->available_stock;
        $requestedQuantity = $this->quantity;
        
        if ($availableStock === 0) {
            return 'out_of_stock';
        } elseif ($availableStock < $requestedQuantity) {
            return 'insufficient_stock';
        } elseif ($availableStock < 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * Get stock status message
     */
    public function getStockStatusMessageAttribute()
    {
        $availableStock = $this->available_stock;
        
        switch ($this->stock_status) {
            case 'out_of_stock':
                return 'Out of stock';
            case 'insufficient_stock':
                return 'Only ' . $availableStock . ' items available';
            case 'low_stock':
                return 'Low stock - ' . $availableStock . ' items left';
            case 'in_stock':
                return 'In stock';
            default:
                return 'Stock status unknown';
        }
    }
}