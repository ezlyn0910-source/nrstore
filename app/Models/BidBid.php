<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BidBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_id',
        'user_id',
        'amount',
        'is_auto_bid',
        'max_auto_bid',
        'ip_address',
        'outbid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'max_auto_bid' => 'decimal:2',
        'is_auto_bid' => 'boolean',
        'outbid_at' => 'datetime'
    ];

    protected $appends = [
        'formatted_amount'
    ];

    // Relationships
    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'RM ' . number_format($this->amount, 2);
    }
}