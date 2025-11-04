<?php
// Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'billing_address',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array'
    ];

    protected $appends = [
        'status_label',
        'formatted_total',
        'formatted_subtotal'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate unique order number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute()
    {
        return 'RM ' . number_format($this->total_amount, 2);
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute()
    {
        return 'RM ' . number_format($this->subtotal, 2);
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Calculate order totals
     */
    public function calculateTotals()
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $this->update([
            'subtotal' => $subtotal,
            'total_amount' => $subtotal + $this->tax_amount + $this->shipping_amount
        ]);

        return $this;
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}