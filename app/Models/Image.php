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
    return Storage::disk('r2')->url($value);
  }

  public function getRawImageAttribute(): string
  {
    return $this->attributes['image'];
  }


}
