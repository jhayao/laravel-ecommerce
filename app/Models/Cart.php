<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

  public function products(): HasMany
  {
    return $this->hasMany(CartItem::class);
  }
}
