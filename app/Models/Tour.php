<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tour extends Model
{
    protected $fillable = ['destination_id', 'name', 'slug', 'description', 'guide_name', 'capacity', 'booked_seats', 'price_per_person', 'start_time', 'end_time', 'itinerary', 'includes', 'status'];

    protected $casts = [
        'itinerary' => 'array',
        'includes' => 'array',
        'price_per_person' => 'decimal:2',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
