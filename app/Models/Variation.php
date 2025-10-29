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
        // Electronics specifications
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
        'image',
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
     * Get formatted specifications
     */
    public function getSpecificationsAttribute()
    {
        $specs = [];
        
        if ($this->model) $specs['Model'] = $this->model;
        if ($this->processor) $specs['Processor'] = $this->processor;
        if ($this->ram) $specs['RAM'] = $this->ram . ' GB';
        if ($this->storage) $specs['Storage'] = $this->storage . ' GB ' . ($this->storage_type ?: '');
        if ($this->graphics_card) $specs['Graphics Card'] = $this->graphics_card;
        if ($this->screen_size) $specs['Screen Size'] = $this->screen_size;
        if ($this->os) $specs['Operating System'] = $this->os;
        if ($this->warranty) $specs['Warranty'] = $this->warranty;
        if ($this->voltage) $specs['Voltage'] = $this->voltage;

        return $specs;
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