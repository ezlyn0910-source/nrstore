<?php
// CartItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variation_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    protected $appends = [
        'subtotal',
        'product_name',
        'image_url'
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
        return $this->quantity * $this->price;
    }

    /**
     * Get product name
     */
    public function getProductNameAttribute()
    {
        if ($this->variation_id && $this->relationLoaded('variation')) {
            return $this->product->name . ' - ' . $this->variation->model;
        }
        
        return $this->product->name ?? 'Product';
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->variation_id && $this->relationLoaded('variation')) {
            return $this->variation->image_url;
        }
        
        return $this->product->main_image_url ?? asset('images/default-product.png');
    }

    /**
     * Increase quantity
     */
    public function increaseQuantity($quantity = 1)
    {
        $this->increment('quantity', $quantity);
        $this->cart->calculateTotals();
        return $this;
    }

    /**
     * Decrease quantity
     */
    public function decreaseQuantity($quantity = 1)
    {
        $this->decrement('quantity', $quantity);
        
        if ($this->quantity <= 0) {
            $this->delete();
        } else {
            $this->cart->calculateTotals();
        }
        
        return $this;
    }
}