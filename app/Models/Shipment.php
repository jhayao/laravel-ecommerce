<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    /** @use HasFactory<\Database\Factories\ShipmentFactory> */
    use HasFactory;
    protected $fillable = [
        'order_id',
        'status',
        'tracking_number',
//        'courier',
        'shipped_at',
        'delivered_at',
    ];



    protected $appends = ['status_id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusIdAttribute(): int
    {
        return match ($this->attributes['status']) {
            'pending' => 2,
            'shipped' => 1,
            'delivered' => 3,
            default => 0,
        };
    }
}
