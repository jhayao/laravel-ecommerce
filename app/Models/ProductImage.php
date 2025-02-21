<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductImage extends Model
{
    /** @use HasFactory<\Database\Factories\ProductImageFactory> */
    use HasFactory;

//    protected $table = 'product_images';

    protected $fillable = ['product_id', 'image_id'];

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
