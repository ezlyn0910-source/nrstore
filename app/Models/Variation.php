<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
        'image',
        'is_active',
        'model',
        'processor',
        'ram',
        'storage',
        'storage_type',
        'graphics_card',
        'screen_size',
        'os',
        'warranty',
        'voltage',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the effective price (variation price or fallback to product base price)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->price ?? $this->product->base_price;
    }

    /**
     * Get the variation image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return $this->product->main_image_url;
    }

    /**
     * Scope active variations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if variation is in stock
     */
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }
}