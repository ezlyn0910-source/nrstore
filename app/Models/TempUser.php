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
        'email',
        'phone',
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
            $tempUser->token = Str::random(60);
            $tempUser->expires_at = now()->addHours(24); // 24 hours to verify
        });
    }

    protected $dates = ['expires_at'];

    public function isExpired()
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }


}