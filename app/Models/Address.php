<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'full_name',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $appends = [
        'formatted_address',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping orders for this address.
     */
    public function shippingOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    /**
     * Get the billing orders for this address.
     */
    public function billingOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'billing_address_id');
    }

    /**
     * Get formatted address attribute
     */
    public function getFormattedAddressAttribute(): string
    {
        $address = $this->address_line_1;
        
        if (!empty($this->address_line_2)) {
            $address .= ', ' . $this->address_line_2;
        }
        
        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->postal_code;
        
        if (!empty($this->country)) {
            $address .= ', ' . $this->country;
        }
        
        return $address;
    }

    /**
     * Scope a query to only include shipping addresses.
     */
    public function scopeShipping($query)
    {
        return $query->where('type', 'shipping');
    }

    /**
     * Scope a query to only include billing addresses.
     */
    public function scopeBilling($query)
    {
        return $query->where('type', 'billing');
    }

    /**
     * Scope a query to only include default addresses.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope a query to only include addresses for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Set as default address and unset others
     */
    public function setAsDefault(): void
    {
        // Unset other default addresses of the same type for this user
        self::where('user_id', $this->user_id)
            ->where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Set this address as default
        $this->update(['is_default' => true]);
    }

    /**
     * Check if this is a shipping address
     */
    public function isShipping(): bool
    {
        return $this->type === 'shipping';
    }

    /**
     * Check if this is a billing address
     */
    public function isBilling(): bool
    {
        return $this->type === 'billing';
    }

    /**
     * Boot method for address model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set as default if it's the first address of this type for the user
        static::creating(function ($address) {
            $existingAddress = self::where('user_id', $address->user_id)
                ->where('type', $address->type)
                ->exists();

            if (!$existingAddress) {
                $address->is_default = true;
            }
        });
    }
}