<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    protected $fillable = ['destination_id', 'name', 'slug', 'description', 'stars', 'address', 'latitude', 'longitude', 'amenities', 'policies', 'contact_phone', 'contact_email', 'base_price_per_night', 'status'];

    protected $casts = [
        'amenities' => 'array',
        'policies' => 'array',
        'base_price_per_night' => 'decimal:2',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
