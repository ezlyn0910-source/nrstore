<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


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
        'has_variations',
        'slug',
        'type',
        'sub_type'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_recommended' => 'boolean',
        'is_active' => 'boolean',
        'has_variations' => 'boolean',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer'
    ];

    // Add these attributes to be included in JSON responses
    protected $appends = [
        'main_image_url',
        'total_stock',
        'stock_status',
        'stock_status_label',
        'in_stock',
        'formatted_price',
        'min_price',
        'max_price'
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

    // Basic scopes
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    public function scopeWithVariations($query)
    {
        return $query->where('has_variations', true);
    }

    public function scopeWithoutVariations($query)
    {
        return $query->where('has_variations', false);
    }

    // Accessors
    public function getTotalStockAttribute()
    {
        if ($this->has_variations && $this->relationLoaded('variations')) {
            return $this->variations->sum('stock');
        }
        return $this->stock_quantity;
    }

    public function getHasVariationsAttribute()
    {
        return $this->attributes['has_variations'] ?? false;
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
            
            // Fallback to any image
            $firstImage = $this->images->first();
            if ($firstImage) {
                return $firstImage->image_url;
            }
        }
        
        return asset('images/default-product.png');
    }

    public function getMinPriceAttribute()
    {
        if ($this->has_variations && $this->relationLoaded('variations') && $this->variations->isNotEmpty()) {
            $minPrice = $this->variations->min('price');
            return $minPrice ?? $this->price;
        }
        return $this->price;
    }

    public function getMaxPriceAttribute()
    {
        if ($this->has_variations && $this->relationLoaded('variations') && $this->variations->isNotEmpty()) {
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

    /**
     * Get gallery images (non-primary)
     */
    public function getGalleryImagesAttribute()
    {
        if ($this->relationLoaded('images')) {
            return $this->images->where('is_primary', false);
        }
        return collect();
    }

    /**
     * Check if product has gallery images
     */
    public function getHasGalleryImagesAttribute()
    {
        return $this->gallery_images->isNotEmpty();
    }

    /**
     * Boot method for automatic slug generation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                
                // Ensure slug is unique
                $originalSlug = $product->slug;
                $count = 1;
                while (static::where('slug', $product->slug)->exists()) {
                    $product->slug = $originalSlug . '-' . $count++;
                }
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                
                // Ensure slug is unique
                $originalSlug = $product->slug;
                $count = 1;
                while (static::where('slug', $product->slug)->where('id', '!=', $product->id)->exists()) {
                    $product->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }
}