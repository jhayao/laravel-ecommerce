<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'street_address',
        'city',
        'province',
        'country',
        'zip_code',
        'phone_number',
        'is_default',
    ];

    protected $appends = ['full_address'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


  public function getFullAddressAttribute(): string
  {
    // Filter out null or empty values to avoid unnecessary commas
    $addressParts = array_filter([
      $this->street_address,
      $this->city,
      $this->province,
      $this->country,
      $this->zip_code
    ]);

    // Convert to a properly formatted string
    return ucwords(implode('<br>', $addressParts));
  }

}
