<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
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