<?php
// Cart.php
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
        if ($user) {
            return static::firstOrCreate(['user_id' => $user->id]);
        }
        
        if ($sessionId) {
            return static::firstOrCreate(['session_id' => $sessionId]);
        }
        
        return null;
    }

    /**
     * Calculate cart totals
     */
    public function calculateTotals()
    {
        $total = 0;
        $count = 0;

        foreach ($this->items as $item) {
            $total += $item->quantity * $item->price;
            $count += $item->quantity;
        }

        $this->update([
            'total_amount' => $total,
            'item_count' => $count
        ]);

        return $this;
    }
}