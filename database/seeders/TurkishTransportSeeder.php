<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Car;
use App\Models\Flight;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TurkishTransportSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'city' => 'Adana',
                'country' => 'Turkey',
                'latitude' => 37.0,
                'longitude' => 35.3213,
            ],
            [
                'city' => 'Adapazarı',
                'country' => 'Turkey',
                'latitude' => 40.7833,
                'longitude' => 30.4070,
            ],
            [
                'city' => 'Adilcevaz',
                'country' => 'Turkey',
                'latitude' => 38.8039,
                'longitude' => 42.7336,
            ],
        ];

        $destinations = [];
        foreach ($cities as $c) {
            $destinations[$c['city']] = Destination::firstOrCreate(
                [
                    'city' => $c['city'],
                    'country' => $c['country'],
                ],
                [
                    'name' => $c['city'],
                    'slug' => Str::slug($c['city'].'-'.$c['country']),
                    'latitude' => $c['latitude'],
                    'longitude' => $c['longitude'],
                    'description' => null,
                    'region' => null,
                    'highlights' => [],
                ]
            );
        }

        // Seed cars with various vehicle types per destination
        $carTypes = [
            ['vehicle_type' => 'economy', 'capacity' => 4, 'luggage_capacity' => 2, 'daily_rate' => 45.00],
            ['vehicle_type' => 'sedan', 'capacity' => 4, 'luggage_capacity' => 2, 'daily_rate' => 65.00],
            ['vehicle_type' => 'suv', 'capacity' => 5, 'luggage_capacity' => 4, 'daily_rate' => 85.00],
            ['vehicle_type' => 'van', 'capacity' => 8, 'luggage_capacity' => 6, 'daily_rate' => 110.00],
            ['vehicle_type' => 'luxury', 'capacity' => 4, 'luggage_capacity' => 2, 'daily_rate' => 150.00],
        ];

        foreach ($destinations as $cityName => $destination) {
            foreach ($carTypes as $ct) {
                Car::firstOrCreate(
                    [
                        'destination_id' => $destination->id,
                        'name' => $cityName.' '.$ct['vehicle_type'],
                        'vehicle_type' => $ct['vehicle_type'],
                    ],
                    [
                        'license_plate' => strtoupper(substr($cityName, 0, 3)).'-'.rand(100, 999),
                        'capacity' => $ct['capacity'],
                        'luggage_capacity' => $ct['luggage_capacity'],
                        'daily_rate' => $ct['daily_rate'],
                        'features' => ['AC', 'GPS'],
                        'policies' => ['fuel' => 'full-to-full'],
                        'status' => 'available',
                    ]
                );
            }
        }

        // Seed a couple of sample flights touching Adana for demo purposes
        Flight::firstOrCreate(
            [
                'flight_code' => 'TK2401',
            ],
            [
                'airline' => 'Turkish Airlines',
                'from_city' => 'Istanbul',
                'to_city' => 'Adana',
                'departure_time' => now()->addDays(7)->setTime(9, 30),
                'arrival_time' => now()->addDays(7)->setTime(11, 5),
                'duration_minutes' => 95,
                'stops' => 0,
                'total_seats' => 180,
                'available_seats' => 120,
                'base_fare' => 75.00,
                'amenities' => ['snacks', 'wifi'],
                'baggage_policy' => ['carry_on' => '8kg', 'checked' => '20kg'],
                'status' => 'available',
            ]
        );
        Flight::firstOrCreate(
            [
                'flight_code' => 'PC3102',
            ],
            [
                'airline' => 'Pegasus',
                'from_city' => 'Adana',
                'to_city' => 'Istanbul',
                'departure_time' => now()->addDays(10)->setTime(18, 45),
                'arrival_time' => now()->addDays(10)->setTime(20, 20),
                'duration_minutes' => 95,
                'stops' => 0,
                'total_seats' => 180,
                'available_seats' => 150,
                'base_fare' => 68.50,
                'amenities' => ['snacks'],
                'baggage_policy' => ['carry_on' => '8kg', 'checked' => '15kg'],
                'status' => 'available',
            ]
        );

        echo "✅ Seeded Turkish destinations (".implode(', ', array_keys($destinations)).") with cars and sample flights\n";
    }
}
