<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  /** @use HasFactory<\Database\Factories\ProductFactory> */
  use HasFactory, SoftDeletes;

  protected $fillable =
  [
    'name',
    'price',
    'description',
    'sku',
    'barcode',
    'image',
    'discounted_price',
    'status',
    'stock',
    'category_id'
  ];

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }
  public function orders(): BelongsToMany
  {
    return $this->belongsToMany(OrderItem::class);
  }
  public function images(): BelongsToMany
  {
    return $this->belongsToMany(Image::class, 'product_images', 'product_id', 'image_id');
  }
}
