<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /** Get the order that owns the status history. **/
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** Get the user who changed the status. **/
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Get status label **/
    public function getStatusLabelAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? ''));

        $map = [
            'pending'    => 'Pending',
            'processing' => 'Processing',
            'paid'       => 'Paid',
            'shipped'    => 'Shipped',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
            'refunded'   => 'Refunded',
            'failed'     => 'Failed',
        ];

        return $map[$status] ?? ucfirst($status ?: 'unknown');
    }

}