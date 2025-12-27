<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'city', 'country', 'region', 'latitude', 'longitude', 'highlights'];

    protected $casts = [
        'highlights' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_destinations')->withPivot('sequence', 'nights', 'activities');
    }
}
