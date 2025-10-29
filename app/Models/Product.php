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
        'product_name',
        'description',
        'base_price',
        'total_stock',
        'category_id',
        'main_image',
    ];

    /**
     * Eager load variations by default (optional but useful)
     */
    protected $with = ['variations'];

    /**
     * Relationship: a product belongs to a category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: a product has many variations
     */
    public function variations(): HasMany
    {
        return $this->hasMany(Variation::class);
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the primary image
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->primary()->first() ?? $this->images()->ordered()->first();
    }

    /**
     * Get the main image URL
     */
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }
        
        $primaryImage = $this->primary_image;
        if ($primaryImage) {
            return $primaryImage->image_url;
        }
        
        return asset('images/default-product.png');
    }

    /**
     * Get all image URLs
     */
    public function getAllImageUrlsAttribute()
    {
        return $this->images->map(function ($image) {
            return $image->image_url;
        });
    }

    /**
     * Accessor: Calculate total stock from variations
     */
    public function getCalculatedTotalStockAttribute(): int
    {
        return $this->variations->sum('stock') ?? $this->total_stock ?? 0;
    }

    /**
     * Accessor: Get minimum price (either base or variation)
     */
    public function getMinPriceAttribute(): float
    {
        if ($this->variations->isEmpty()) {
            return (float) $this->base_price;
        }

        $minVariationPrice = $this->variations->min('price');
        return (float) ($minVariationPrice ?? $this->base_price);
    }

    /**
     * Accessor: Get maximum price (either base or variation)
     */
    public function getMaxPriceAttribute(): float
    {
        if ($this->variations->isEmpty()) {
            return (float) $this->base_price;
        }

        $maxVariationPrice = $this->variations->max('price');
        return (float) ($maxVariationPrice ?? $this->base_price);
    }

    /**
     * Accessor: Display combined price range (useful in UI)
     */
    public function getPriceRangeAttribute(): string
    {
        if ($this->min_price === $this->max_price) {
            return 'RM ' . number_format($this->min_price, 2);
        }

        return 'RM ' . number_format($this->min_price, 2) . ' - RM ' . number_format($this->max_price, 2);
    }

    /**
     * Check if product has variations
     */
    public function getHasVariationsAttribute()
    {
        return $this->variations()->count() > 0;
    }

    /**
     * Get display price (range if has variations, or single price)
     */
    public function getDisplayPriceAttribute()
    {
        if ($this->has_variations) {
            $minPrice = $this->variations->min('effective_price');
            $maxPrice = $this->variations->max('effective_price');
            
            if ($minPrice == $maxPrice) {
                return 'RM ' . number_format($minPrice, 2);
            }
            
            return 'RM ' . number_format($minPrice, 2) . ' - RM ' . number_format($maxPrice, 2);
        }
        
        return $this->base_price ? 'RM ' . number_format($this->base_price, 2) : 'Price on request';
    }

}
