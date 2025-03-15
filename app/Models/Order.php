<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            'user_id',
            'total',
            'status',
            'order_number',
            'payment_method',
            'payment_id',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
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


}
