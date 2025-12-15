<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'quantity',
        'price',
        'total',
        'options',
        'product_name',
        'variation_name',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'options' => 'array',
    ];

    protected $appends = [
        'formatted_price',
        'formatted_total'
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the order item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variation that owns the order item.
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'RM ' . number_format($this->price, 2);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute()
    {
        return 'RM ' . number_format($this->total, 2);
    }

    /**
     * Calculate total before saving
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            $orderItem->total = $orderItem->price * $orderItem->quantity;
        });

        static::updating(function ($orderItem) {
            $orderItem->total = $orderItem->price * $orderItem->quantity;
        });
    }

    /**
     * Calculate total price
     */
    public function calculateTotal(): void
    {
        $this->total = $this->price * $this->quantity;
    }

    /**
     * Scope for items with variations
     */
    public function scopeWithVariations($query)
    {
        return $query->whereNotNull('variation_id');
    }

    /**
     * Scope for items without variations
     */
    public function scopeWithoutVariations($query)
    {
        return $query->whereNull('variation_id');
    }
}