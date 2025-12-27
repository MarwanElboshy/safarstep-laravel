<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = ['country_id', 'name', 'slug', 'latitude', 'longitude', 'place_id', 'place_data', 'formatted_address'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'place_data' => 'json',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class, 'city', 'name');
    }
}
