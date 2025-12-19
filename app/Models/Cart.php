<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total_amount',
        'item_count'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'item_count' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get cart for user or session
     */
    public static function getCart($user = null, $sessionId = null)
    {
        try {
            // Guest only
            if (!$user) {
                return $sessionId ? static::firstOrCreate(['session_id' => $sessionId]) : null;
            }

            // Logged-in: get/create user cart
            $userCart = static::firstOrCreate(['user_id' => $user->id]);

            // If no session id, just return user cart
            if (!$sessionId) return $userCart;

            // Find session cart (guest cart)
            $sessionCart = static::where('session_id', $sessionId)->first();

            // If there is a session cart and it’s different from the user cart, merge
            if ($sessionCart && $sessionCart->id !== $userCart->id) {

                // Move/merge items from session cart to user cart
                foreach ($sessionCart->items as $item) {

                    $existing = $userCart->items()
                        ->where('product_id', $item->product_id)
                        ->where('variation_id', $item->variation_id)
                        ->first();

                    if ($existing) {
                        $existing->quantity += $item->quantity;
                        $existing->save();

                        $item->delete();
                    } else {
                        $item->cart_id = $userCart->id;
                        $item->save();
                    }
                }

                // Delete session cart after merge
                $sessionCart->delete();
            }

            // Ensure user cart is returned
            return $userCart;

        } catch (\Exception $e) {
            \Log::error('Cart::getCart error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculate cart totals
     */
    public function calculateTotals()
    {
        $total = 0;
        $count = 0;

        foreach ($this->items()->get() as $item) {
            $total += $item->quantity * $item->price;
            $count += $item->quantity;
        }

        $this->update([
            'total_amount' => $total,
            'item_count' => $count
        ]);

        return $this;
    }

    /**
     * Get the cart items with product and variation information
     */
    public function getCartItemsWithProducts()
    {
        return $this->items()->with(['product', 'variation'])->get();
    }

    /**
     * Get cart subtotal
     */
    public function getSubtotal()
    {
        return $this->items->sum(function($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty()
    {
        return $this->items->count() === 0;
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        return $this->items()->delete();
    }
}