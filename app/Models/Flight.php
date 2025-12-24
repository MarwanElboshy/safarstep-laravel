<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = ['flight_code', 'airline', 'from_city', 'to_city', 'departure_time', 'arrival_time', 'duration_minutes', 'stops', 'total_seats', 'available_seats', 'base_fare', 'amenities', 'baggage_policy', 'status'];

    protected $casts = [
        'amenities' => 'array',
        'baggage_policy' => 'array',
        'base_fare' => 'decimal:2',
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];
}
