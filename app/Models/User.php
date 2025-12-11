<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'role',
        'email_verified_at',
        'last_login_ip',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include suspended users.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the addresses for the user.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the user's default shipping address.
     */
    public function defaultShippingAddress()
    {
        return $this->addresses()
            ->where('type', 'shipping')
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get the user's default billing address.
     */
    public function defaultBillingAddress()
    {
        return $this->addresses()
            ->where('type', 'billing')
            ->where('is_default', true)
            ->first();
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'user_favorites', 'user_id', 'product_id')
                    ->withTimestamps();
    }

    /**
     * Get the cart for the user.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get cart items count for the user
     */
    public function getCartItemsCountAttribute()
    {
        $cart = $this->cart;
        return $cart ? $cart->item_count : 0;
    }

    /**
     * Check if user has any orders
     */
    public function hasOrders(): bool
    {
        return $this->orders()->exists();
    }

    /**
     * Get user's order statistics
     */
    public function getOrderStatsAttribute()
    {
        return [
            'total_orders' => $this->orders()->count(),
            'total_spent' => $this->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders' => $this->orders()->where('status', 'pending')->count(),
        ];
    }

    public function bidBids(): HasMany
    {
        return $this->hasMany(BidBid::class);
    }

    public function wonBids(): HasMany
    {
        return $this->hasMany(Bid::class, 'winner_id');
    }

    public function activeBidParticipation()
    {
        return $this->bidBids()
                    ->whereHas('bid', function($query) {
                        $query->active();
                    })
                    ->with('bid.product')
                    ->get()
                    ->unique('bid_id');
    }

    public function getBiddingStatsAttribute()
    {
        $totalBids = $this->bidBids()->count();
        $wonBids = $this->wonBids()->count();
        $activeBids = $this->activeBidParticipation()->count();

        return [
            'total_bids_placed' => $totalBids,
            'bids_won' => $wonBids,
            'active_participations' => $activeBids,
            'success_rate' => $totalBids > 0 ? round(($wonBids / $totalBids) * 100, 2) : 0,
        ];
    }

    /**
     * Check if user can place bid (based on status)
     */
    public function canPlaceBid(): bool
    {
        return $this->isActive() && !$this->isSuspended();
    }

    /**
     * Scope for users who can participate in bids
     */
    public function scopeCanBid($query)
    {
        return $query->active();
    }

}