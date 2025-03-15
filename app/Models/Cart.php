<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
  /** @use HasFactory<\Database\Factories\CartFactory> */
  use HasFactory;

  protected $fillable = ['user_id', 'is_active'];

  protected $hidden = ['created_at', 'updated_at'];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function products(): BelongsToMany
  {
    return $this->belongsToMany(Product::class, 'cart_items')->withPivot('quantity');
  }

  public function getTotalAttribute(): float
  {
    return $this->products->sum(fn($product) => ($product->discounted_price ?? $product->price) * $product->pivot->quantity);
  }
}
