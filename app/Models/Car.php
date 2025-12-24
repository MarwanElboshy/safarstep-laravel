<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    protected $fillable = ['destination_id', 'name', 'license_plate', 'vehicle_type', 'capacity', 'luggage_capacity', 'daily_rate', 'features', 'policies', 'status'];

    protected $casts = [
        'features' => 'array',
        'policies' => 'array',
        'daily_rate' => 'decimal:2',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
