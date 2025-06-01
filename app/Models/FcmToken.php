<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    protected $fillable = [
        'token',
        'user_id',
        'platform',
        'timestamp',
        'app_version',
        'package_name',
        'is_active'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Get the customer that owns the FCM token.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }

    /**
     * Scope to get only active tokens.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by platform.
     */
    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }
}
