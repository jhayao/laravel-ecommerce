<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'amount',
        'status',
        'payment_method',
        'invoice_id',
        'method',
    ];

    protected $appends = ['payment_status_id'];

    public function getPaymentStatusIdAttribute(): int
    {
      return match (Str::lower($this->attributes['status'])) {
        'pending' => 2,
        'success' => 1,
        'failed' => 3,
        default => 0,
      };
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
