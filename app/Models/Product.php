<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'brand',
        'ram',
        'storage',
        'processor',
        'stock_quantity',
        'is_featured',
        'is_recommended', 
        'is_active',
        'slug',
    ];

    protected $casts = [
        // Remove until columns exist:
        // 'is_featured' => 'boolean',
        // 'is_recommended' => 'boolean',
        // 'is_active' => 'boolean',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(Variation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('created_at');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    // Basic scopes without is_active, is_featured, etc.
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('brand', 'like', "%{$searchTerm}%");
        });
    }

    // Accessors - remove references to missing columns
    public function getTotalStockAttribute()
    {
        if ($this->relationLoaded('variations') && $this->variations->isNotEmpty()) {
            return $this->variations->sum('stock');
        }
        return $this->stock_quantity;
    }

    public function getHasVariationsAttribute()
    {
        return $this->relationLoaded('variations') && $this->variations->isNotEmpty();
    }

    public function getMainImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        if ($this->relationLoaded('images')) {
            $primaryImage = $this->images->where('is_primary', true)->first();
            if ($primaryImage) {
                return $primaryImage->image_url;
            }
        }
        
        return asset('images/default-product.png');
    }

    public function getMinPriceAttribute()
    {
        if ($this->relationLoaded('variations') && $this->variations->isNotEmpty()) {
            $minPrice = $this->variations->min('price');
            return $minPrice ?? $this->price;
        }
        return $this->price;
    }

    public function getMaxPriceAttribute()
    {
        if ($this->relationLoaded('variations') && $this->variations->isNotEmpty()) {
            $maxPrice = $this->variations->max('price');
            return $maxPrice ?? $this->price;
        }
        return $this->price;
    }

    public function getStockStatusAttribute()
    {
        $totalStock = $this->total_stock;
        
        if ($totalStock === 0) {
            return 'out_of_stock';
        } elseif ($totalStock < 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusLabelAttribute()
    {
        switch ($this->stock_status) {
            case 'out_of_stock':
                return 'Out of Stock';
            case 'low_stock':
                return 'Low Stock';
            case 'in_stock':
                return 'In Stock';
            default:
                return 'Unknown';
        }
    }

    /**
     * Check if product is in stock
     */
    public function getInStockAttribute()
    {
        return $this->total_stock > 0;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'RM ' . number_format($this->price, 2);
    }
}