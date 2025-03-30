<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
  /** @use HasFactory<\Database\Factories\CustomerFactory> */
  use HasFactory, HasApiTokens, Notifiable;

  protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'email',
    'profile_picture',
    'phone_number',
    'password'
  ];

  protected $with = ['cart', 'address'];

  protected $appends = ['full_name', 'orders_count', 'total_spent'];

  protected $hidden = [
    'password',
    'remember_token',
    'created_at',
    'updated_at'
  ];

  /**
   * Hash the password before saving it to the database
   */
  public function password(): Attribute
  {
    return Attribute::make(
      set: fn($value) => Hash::make($value) // Auto-hashes passwords
    );
  }


  public function cart(): HasOne
  {
    return $this->hasOne(Cart::class);
  }

  public function address(): HasMany
  {
    return $this->hasMany(CustomerAddress::class);
  }

  public function billing_address(): HasOne
  {
    return $this->hasOne(CustomerAddress::class)->where('type', 'billing');
  }

  public function shipping_address(): HasOne
  {
    return $this->hasOne(CustomerAddress::class)->where('type', 'shipping');
  }

  public function getFullNameAttribute() : string
  {
    return ucfirst("{$this->first_name} {$this->middle_name} {$this->last_name}");
  }

  public function getProfilePictureAttribute($value): string
  {
    if (!$value) {
      return asset('assets/img/avatars/15.png');
    }
    return asset($value);
  }

  public function orders(): HasMany
  {
    return $this->hasMany(Order::class, 'customer_id');
  }

  public function getOrdersCountAttribute(): int
  {
    return $this->orders()->count();
  }

  public function getTotalSpentAttribute(): float
  {
    return $this->orders()->sum('total');
  }
}
