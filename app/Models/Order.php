<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
            'customer_id',
            'total',
            'status',
            'order_number',
            'payment_method',
            'payment_id',
        ];

    protected $appends = ['status_id'];

    protected $with = ['customer', 'items', 'payment'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getTotalPriceAttribute()
    {
        return $this->items->sum('price');
    }

    public function getTotalDiscountAttribute()
    {
        return $this->items->sum('discount');
    }

    public function getTotalAttribute()
    {
        return $this->items->sum('total');
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }

    public function getStatusIdAttribute(): int
    {
      $value = $this->status;
      return match (Str::lower($value)) {
        'pending' => 4,
        'processing' => 3,
        'completed' => 2,
        default => 1,
      };
    }

}
