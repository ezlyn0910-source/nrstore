<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'billing_address_id',
        'order_number',
        'total_amount',
        'shipping_cost',
        'tax_amount',
        'discount_amount',
        'status',
        'tracking_number',
        'payment_method',
        'notes',
        'shipped_at',
        'cancelled_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_total_amount',
        'status_label',
        'is_shipped',
        'is_cancelled'
    ];

    /**
     * Status constants for consistency
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Get the billing address for the order.
     */
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the status history for the order.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute()
    {
        return 'RM ' . number_format($this->total_amount, 2);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PAID => 'Paid',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_CANCELLED => 'Cancelled',
            default => ucfirst($this->status)
        };
    }

    /**
     * Check if order is paid
     */
    public function getIsPaidAttribute(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_PROCESSING, self::STATUS_SHIPPED]);
    }

    /**
     * Check if order is shipped
     */
    public function getIsShippedAttribute(): bool
    {
        return $this->status === self::STATUS_SHIPPED && !is_null($this->shipped_at);
    }

    /**
     * Check if order is cancelled
     */
    public function getIsCancelledAttribute(): bool
    {
        return $this->status === self::STATUS_CANCELLED && !is_null($this->cancelled_at);
    }

    /**
     * Calculate order totals
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->orderItems->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        $this->total_amount = $subtotal + $this->shipping_cost + $this->tax_amount - $this->discount_amount;
        $this->save();
    }

    /**
     * Update order status with history tracking
     */
    public function updateStatus(string $status, string $notes = null): void
    {
        $oldStatus = $this->status;
        $this->status = $status;

        // Update timestamps based on status
        switch ($status) {
            case self::STATUS_SHIPPED:
                $this->shipped_at = $this->shipped_at ?? now();
                break;
            case self::STATUS_CANCELLED:
                $this->cancelled_at = $this->cancelled_at ?? now();
                break;
        }

        $this->save();

        // Record status history
        if ($oldStatus !== $status) {
            OrderStatusHistory::create([
                'order_id' => $this->id,
                'status' => $status,
                'notes' => $notes ?? "Status changed from {$oldStatus} to {$status}"
            ]);
        }
    }

    /**
     * Set tracking number
     */
    public function setTrackingNumber(string $trackingNumber): void
    {
        $this->tracking_number = $trackingNumber;
        $this->save();
    }

    /**
     * Scope pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope paid orders
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope processing orders
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Scope shipped orders
     */
    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    /**
     * Scope cancelled orders
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope orders with tracking
     */
    public function scopeWithTracking($query)
    {
        return $query->whereNotNull('tracking_number');
    }

    /**
     * Generate order number
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', $prefix . $date . '%')->latest()->first();

        if ($lastOrder) {
            $number = intval(substr($lastOrder->order_number, -4)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method for automatic order number generation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
            
            // Set default status to pending if not set
            if (empty($order->status)) {
                $order->status = self::STATUS_PENDING;
            }
        });
    }
}