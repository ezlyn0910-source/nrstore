<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'is_recommended'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scope for featured products
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope for recommended products
    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    // Scope for category filter
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Scope for brand filter
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    // Scope for price range
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
}