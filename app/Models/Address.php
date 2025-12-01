<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'is_primary',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get is_primary attribute (alias for is_default)
     */
    public function getIsPrimaryAttribute(): bool
    {
        return $this->is_default;
    }

    /**
     * Set is_primary attribute (alias for is_default)
     */
    public function setIsPrimaryAttribute($value): void
    {
        $this->attributes['is_default'] = $value;
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
     * Scope a query to only include primary addresses.
     */
    public function scopePrimary($query)
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
     * Set as primary address and unset others for the same user and type
     */
    public function setAsPrimary(): void
    {
        // Unset other primary addresses of the same type for this user
        self::where('user_id', $this->user_id)
            ->where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Set this address as primary
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
     * Check if this is the primary address
     */
    public function isPrimary(): bool
    {
        return $this->is_default;
    }

    /**
     * Get primary shipping address for user
     */
    public static function getPrimaryShippingAddress($userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('type', 'shipping')
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get primary billing address for user
     */
    public static function getPrimaryBillingAddress($userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('type', 'billing')
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get all shipping addresses for user ordered by primary first
     */
    public static function getShippingAddresses($userId)
    {
        return self::where('user_id', $userId)
            ->where('type', 'shipping')
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all billing addresses for user ordered by primary first
     */
    public static function getBillingAddresses($userId)
    {
        return self::where('user_id', $userId)
            ->where('type', 'billing')
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Boot method for address model
     */
    protected static function boot()
    {
        parent::boot();

        // Set default country to Malaysia if not provided
        static::creating(function ($address) {
            if (empty($address->country)) {
                $address->country = 'Malaysia';
            }

            // Auto-set as primary if it's the first address of this type for the user
            $existingAddress = self::where('user_id', $address->user_id)
                ->where('type', $address->type)
                ->exists();

            if (!$existingAddress && !isset($address->is_default)) {
                $address->is_default = true;
            }
        });

        // Ensure only one primary address per type per user
        static::updated(function ($address) {
            if ($address->is_default) {
                self::where('user_id', $address->user_id)
                    ->where('type', $address->type)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    /**
     * Convert to array for API responses
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'is_default' => $this->is_default,
            'is_primary' => $this->is_primary,
            'formatted_address' => $this->formatted_address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}