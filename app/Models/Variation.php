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

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'image_url',
        'effective_price',
        'in_stock',
        'formatted_price'
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
        return $this->price ?? $this->product->price;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'RM ' . number_format($this->effective_price, 2);
    }

    /**
     * Get the variation image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return $this->product->main_image_url ?? asset('images/default-product.png');
    }

    /**
     * Scope active variations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope in-stock variations
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope out-of-stock variations
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Check if variation is in stock
     */
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Get full specification as array
     */
    public function getSpecificationsAttribute()
    {
        $specs = [];
        
        if ($this->model) $specs['Model'] = $this->model;
        if ($this->processor) $specs['Processor'] = $this->processor;
        if ($this->ram) $specs['RAM'] = $this->ram;
        if ($this->storage) $specs['Storage'] = $this->storage;
        if ($this->storage_type) $specs['Storage Type'] = $this->storage_type;
        if ($this->graphics_card) $specs['Graphics Card'] = $this->graphics_card;
        if ($this->screen_size) $specs['Screen Size'] = $this->screen_size;
        if ($this->os) $specs['Operating System'] = $this->os;
        if ($this->warranty) $specs['Warranty'] = $this->warranty;
        if ($this->voltage) $specs['Voltage'] = $this->voltage;
        
        return $specs;
    }

    /**
     * Get specifications as HTML
     */
    public function getSpecificationsHtmlAttribute()
    {
        $html = '';
        foreach ($this->specifications as $key => $value) {
            if (!empty($value)) {
                $html .= "<strong>{$key}:</strong> {$value}<br>";
            }
        }
        return $html;
    }
}