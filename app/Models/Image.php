<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;

    protected $fillable = ['image'];
    protected $appends = ['raw_image'];

  public function products(): BelongsToMany
  {
    return $this->belongsToMany(Product::class, 'product_images', 'image_id', 'product_id');
  }

  public function getImageAttribute($value): string
  {
    return 'https://pub-e64b48d6794a40709a9461dc60f7f881.r2.dev/public/' .$value;
  }

  public function getRawImageAttribute(): string
  {
    return $this->attributes['image'];
  }


}
