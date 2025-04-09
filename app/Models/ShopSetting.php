<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    //
    protected $fillable = [
        'name',
        'type',
        'value',
        'is_active'
    ];
}
