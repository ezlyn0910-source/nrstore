<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class TempUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name', 
        'name', 
        'email',
        'phone',
        'country_code',
        'password',
        'token',
        'expires_at',
        'registration_data',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'registration_data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tempUser) {
            // Auto-generate name from first and last name
            if (empty($tempUser->name) && !empty($tempUser->first_name)) {
                $tempUser->name = trim($tempUser->first_name . ' ' . ($tempUser->last_name ?? ''));
            }
            
            // Auto-generate token if not set
            if (empty($tempUser->token)) {
                $tempUser->token = bin2hex(random_bytes(32));
            }
            
            // Set expiration if not set
            if (empty($tempUser->expires_at)) {
                $tempUser->expires_at = now()->addHours(24);
            }
        });
    }

    protected $dates = ['expires_at'];

    public function isExpired()
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }


}