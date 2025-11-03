<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer'
    ];

    protected $appends = [
        'image_url'
    ];

    /**
     * Get the product that owns the image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/default-product.png');
    }

    /**
     * Get full image path for storage
     */
    public function getFullImagePathAttribute()
    {
        return storage_path('app/public/' . $this->image_path);
    }

    /**
     * Check if image file exists
     */
    public function getImageExistsAttribute()
    {
        return Storage::disk('public')->exists($this->image_path);
    }

    /**
     * Scope primary image
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope gallery images (non-primary)
     */
    public function scopeGallery($query)
    {
        return $query->where('is_primary', false);
    }

    /**
     * Scope ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope for specific product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Boot method to handle image deletion
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($productImage) {
            // Delete the physical file when the record is deleted
            if (Storage::disk('public')->exists($productImage->image_path)) {
                Storage::disk('public')->delete($productImage->image_path);
            }
        });

        static::updating(function ($productImage) {
            // If this image is being set as primary, unset primary from other images of the same product
            if ($productImage->isDirty('is_primary') && $productImage->is_primary) {
                static::where('product_id', $productImage->product_id)
                    ->where('id', '!=', $productImage->id)
                    ->update(['is_primary' => false]);
            }
        });
    }
}