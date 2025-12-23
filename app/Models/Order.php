<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
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
        'delivered_at',
        'cancelled_at',
        'payment_status',
        'currency',

        // Payment-related
        'payment_gateway',
        'gateway_transaction_id',
        'payment_reference',
        'gateway_meta',
        'paid_at',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'total_amount'    => 'decimal:2',
        'shipping_cost'   => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipped_at'      => 'datetime',
        'delivered_at'    => 'datetime',
        'cancelled_at'    => 'datetime',
        'paid_at'         => 'datetime',
        'gateway_meta'    => 'array',
    ];

    /**
     * Accessors automatically appended when model is converted to array / JSON.
     */
    protected $appends = [
        'formatted_total_amount',
        'status_label',
        'is_shipped',
        'is_delivered',
        'is_cancelled',
        'is_paid',
    ];

    /**
     * STATUS (business fulfilment flow)
     * pending = internal only (payment not confirmed yet)
     * processing = payment confirmed, preparing order
     * shipped = parcel shipped
     * delivered = parcel delivered
     * cancelled = admin only
     */
    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED    = 'shipped';
    public const STATUS_DELIVERED  = 'delivered';
    public const STATUS_CANCELLED  = 'cancelled';

    /**
     * Order status progression (lower → earlier, higher → later)
     */
    public const STATUS_FLOW = [
        self::STATUS_PENDING    => 1,
        self::STATUS_PROCESSING => 2,
        self::STATUS_SHIPPED    => 3,
        self::STATUS_DELIVERED  => 4,
        self::STATUS_CANCELLED  => 99,
    ];

    /**
     * Payment method constants (optional / if you standardize later)
     */
    public const PAYMENT_METHOD_STRIPE    = 'stripe';
    public const PAYMENT_METHOD_TOYYIBPAY = 'toyyibpay';

    /**
     * Payment status constants.
     */
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_PAID    = 'paid';
    public const PAYMENT_STATUS_FAILED  = 'failed';

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias for orderItems to support $order->items or with('items')
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    /**
     * Accessors
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return 'RM ' . number_format((float) $this->total_amount, 2);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING    => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED    => 'Shipped',
            self::STATUS_DELIVERED  => 'Delivered',
            self::STATUS_CANCELLED  => 'Cancelled',
            default                 => ucfirst((string) $this->status),
        };
    }

    public function getPaymentMethodLabelAttribute(): ?string
    {
        // NOTE: your PaymentController stores payment_method like:
        // credit_card / debit_card / online_banking
        return match ($this->payment_method) {
            'credit_card', 'debit_card' => 'Credit/Debit Card (Stripe)',
            'online_banking'            => 'FPX Online Banking (Toyyibpay)',
            self::PAYMENT_METHOD_STRIPE => 'Credit/Debit Card (Stripe)',
            self::PAYMENT_METHOD_TOYYIBPAY => 'FPX Online Banking (Toyyibpay)',
            default                     => $this->payment_method,
        };
    }

    public function getPaymentStatusLabelAttribute(): ?string
    {
        return match ($this->payment_status) {
            self::PAYMENT_STATUS_PENDING => 'Pending',
            self::PAYMENT_STATUS_PAID    => 'Paid',
            self::PAYMENT_STATUS_FAILED  => 'Failed',
            default                      => $this->payment_status,
        };
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === self::PAYMENT_STATUS_PAID;
    }

    public function getIsShippedAttribute(): bool
    {
        return $this->status === self::STATUS_SHIPPED && !is_null($this->shipped_at);
    }

    public function getIsDeliveredAttribute(): bool
    {
        return $this->status === self::STATUS_DELIVERED && !is_null($this->delivered_at);
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->status === self::STATUS_CANCELLED && !is_null($this->cancelled_at);
    }

    /**
     * Calculate order totals
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->orderItems->sum(function ($item) {
            return (int) $item->quantity * (float) $item->price;
        });

        $this->total_amount = $subtotal
            + (float) $this->shipping_cost
            + (float) $this->tax_amount
            - (float) $this->discount_amount;

        $this->save();
    }

    /**
     * ✅ IMPORTANT: Payment confirmed → move order into fulfilment flow.
     * Your desired flow:
     * successful payment → processing → shipped → delivered
     */
    public function markAsPaid(string $gateway, ?string $transactionId = null, $rawPayload = null): void
    {
        $this->payment_gateway        = $gateway;
        $this->gateway_transaction_id = $transactionId;

        if (!is_null($rawPayload)) {
            $this->gateway_meta = is_array($rawPayload) ? $rawPayload : (array) $rawPayload;
        }

        // Payment info
        $this->payment_status = self::PAYMENT_STATUS_PAID;
        $this->paid_at        = $this->paid_at ?? now();

        // Business status should be PROCESSING after successful payment
        $this->updateStatus(self::STATUS_PROCESSING, 'Payment successful via ' . strtoupper($gateway));
    }

    /**
     * Payment failed / cancelled by gateway.
     * Business status becomes CANCELLED and payment_status becomes FAILED.
     */
    public function markAsFailed(string $gateway, ?string $transactionId = null, $rawPayload = null): void
    {
        $this->payment_gateway        = $gateway;
        $this->gateway_transaction_id = $transactionId;

        if (!is_null($rawPayload)) {
            $this->gateway_meta = is_array($rawPayload) ? $rawPayload : (array) $rawPayload;
        }

        $this->payment_status = self::PAYMENT_STATUS_FAILED;

        $this->updateStatus(self::STATUS_CANCELLED, 'Payment failed via ' . strtoupper($gateway));
    }

    /**
     * Update order status with history tracking.
     */
    public function updateStatus(string $status, string $notes = null): void
    {
        if (!array_key_exists($status, self::STATUS_FLOW)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $oldStatus = $this->status;

        // Prevent backward status transition
        if (
            isset(self::STATUS_FLOW[$oldStatus]) &&
            self::STATUS_FLOW[$status] < self::STATUS_FLOW[$oldStatus]
        ) {
            throw new \LogicException(
                "Order status cannot be reverted from {$oldStatus} to {$status}"
            );
        }

        // Prevent resurrecting cancelled orders
        if ($oldStatus === self::STATUS_CANCELLED) {
            throw new \LogicException('Cancelled orders cannot be modified.');
        }

        $this->status = $status;

        switch ($status) {
            case self::STATUS_SHIPPED:
                $this->shipped_at = $this->shipped_at ?? now();
                break;

            case self::STATUS_DELIVERED:
                $this->delivered_at = $this->delivered_at ?? now();
                break;

            case self::STATUS_CANCELLED:
                $this->cancelled_at = $this->cancelled_at ?? now();
                break;
        }

        $this->save();

        if ($oldStatus !== $status) {
            OrderStatusHistory::create([
                'order_id' => $this->id,
                'status'   => $status,
                'notes'    => $notes ?? "Status changed from {$oldStatus} to {$status}",
            ]);
        }
    }

    /**
     * Set tracking number.
     */
    public function setTrackingNumber(string $trackingNumber): void
    {
        $this->tracking_number = $trackingNumber;
        $this->save();
    }

    /**
     * Scopes for order.status
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeWithTracking($query)
    {
        return $query->whereNotNull('tracking_number');
    }

    /**
     * Scopes for payment_status
     */
    public function scopePaymentPending($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_PENDING);
    }

    public function scopePaymentPaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_PAID);
    }

    public function scopePaymentFailed($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_FAILED);
    }

    /**
     * Get all valid statuses
     */
    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
        ];
    }

    /**
     * Generate order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix    = 'ORD';
        $date      = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', $prefix . $date . '%')
            ->latest()
            ->first();

        $number = $lastOrder ? ((int) substr($lastOrder->order_number, -4) + 1) : 1;

        return $prefix . $date . str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method for automatic order number / defaults.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }

            // pending means: created but payment not confirmed yet
            if (empty($order->status)) {
                $order->status = self::STATUS_PENDING;
            }

            if (empty($order->payment_status)) {
                $order->payment_status = self::PAYMENT_STATUS_PENDING;
            }
        });
    }
}