<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_id',
        'starting_price',
        'current_price',
        'reserve_price',
        'bid_increment',
        'start_time',
        'end_time',
        'status',
        'bid_count',
        'winner_id',
        'winning_bid_amount',
        'terms_conditions',
        'auto_extend',
        'extension_minutes'
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'reserve_price' => 'decimal:2',
        'bid_increment' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'winning_bid_amount' => 'decimal:2',
        'auto_extend' => 'boolean',
    ];

    protected $appends = [
        'time_remaining',
        'is_active',
        'has_ended',
        'has_started',
        'reserve_met',
        'formatted_starting_price',
        'formatted_current_price',
        'formatted_winning_bid_amount'
    ];


    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(BidBid::class)->orderBy('amount', 'desc');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function highestBid()
    {
        return $this->hasOne(BidBid::class)->orderBy('amount', 'desc');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'active')
                    ->where('start_time', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors
    public function getTimeRemainingAttribute()
    {
        if (!$this->end_time) return null;
        return now()->diff($this->end_time);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               $this->start_time <= now() && 
               $this->end_time > now();
    }

    public function getHasEndedAttribute()
    {
        return $this->end_time < now() || $this->status === 'completed';
    }

    public function getHasStartedAttribute()
    {
        return $this->start_time <= now();
    }

    public function getReserveMetAttribute()
    {
        if (!$this->reserve_price) return true;
        return $this->current_price >= $this->reserve_price;
    }

    public function getFormattedStartingPriceAttribute()
    {
        return 'RM ' . number_format($this->starting_price, 2);
    }

    public function getFormattedCurrentPriceAttribute()
    {
        return 'RM ' . number_format($this->current_price, 2);
    }

    public function getFormattedWinningBidAmountAttribute()
    {
        if (!$this->winning_bid_amount) return 'N/A';
        return 'RM ' . number_format($this->winning_bid_amount, 2);
    }
}