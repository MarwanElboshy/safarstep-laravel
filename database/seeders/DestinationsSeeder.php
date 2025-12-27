<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Destination;
use App\Models\Area;
use App\Models\Hotel;
use App\Models\Tour;
use App\Models\Car;
use App\Models\Flight;
use App\Models\AddOn;

class DestinationsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Turkey' => [
                'Istanbul' => ['Sultanahmet','Taksim','Kadikoy'],
                'Antalya' => ['Lara','Konyaalti','Belek'],
                'Cappadocia' => ['Goreme','Uchisar','Urgup']
            ],
            'United Arab Emirates' => [
                'Dubai' => ['Deira','Downtown','JBR'],
                'Abu Dhabi' => ['Corniche','Saadiyat','Al Maryah']
            ],
            'Egypt' => [
                'Cairo' => ['Zamalek','Heliopolis','Maadi'],
                'Hurghada' => ['Sakkala','El Dahar','Soma Bay']
            ],
        ];

        foreach ($data as $country => $cities) {
            foreach ($cities as $city => $areas) {
                $destination = Destination::firstOrCreate([
                    'slug' => Str::slug($country.'-'.$city)
                ], [
                    'name' => $city,
                    'description' => null,
                    'city' => $city,
                    'country' => $country,
                    'region' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'highlights' => []
                ]);

                foreach ($areas as $areaName) {
                    Area::firstOrCreate([
                        'slug' => Str::slug($city.'-'.$areaName)
                    ], [
                        'destination_id' => $destination->id,
                        'name' => $areaName,
                        'description' => null,
                        'latitude' => null,
                        'longitude' => null,
                    ]);
                }

                // Seed a few hotels
                Hotel::firstOrCreate([
                    'slug' => Str::slug($city.'-grand-hotel')
                ], [
                    'destination_id' => $destination->id,
                    'name' => $city.' Grand Hotel',
                    'description' => 'A centrally located hotel in '.$city,
                    'stars' => 5,
                    'address' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'amenities' => ['wifi','breakfast','pool'],
                    'policies' => [],
                    'contact_phone' => null,
                    'contact_email' => null,
                    'base_price_per_night' => 120,
                    'status' => 'active',
                ]);

                // Seed a tour
                Tour::firstOrCreate([
                    'slug' => Str::slug($city.'-city-tour')
                ], [
                    'destination_id' => $destination->id,
                    'name' => $city.' City Tour',
                    'description' => 'Guided city highlights tour',
                    'guide_name' => null,
                    'capacity' => 30,
                    'booked_seats' => 0,
                    'price_per_person' => 45,
                    'start_time' => '09:00:00',
                    'end_time' => '13:00:00',
                    'itinerary' => json_encode(['Museum','Old Town','Panorama']),
                    'includes' => json_encode(['Guide','Transport','Tickets']),
                    'status' => 'active',
                ]);

                // Seed a car
                Car::firstOrCreate([
                    'license_plate' => strtoupper(Str::random(3)).'-'.rand(1000,9999)
                ], [
                    'destination_id' => $destination->id,
                    'name' => $city.' Sedan',
                    'vehicle_type' => 'sedan',
                    'capacity' => 4,
                    'luggage_capacity' => 2,
                    'daily_rate' => 40,
                    'features' => ['AC','Automatic'],
                    'policies' => [],
                    'status' => 'available',
                ]);

                // Seed sample flights between cities
                Flight::firstOrCreate([
                    'flight_code' => strtoupper(Str::random(2)).rand(100,999)
                ], [
                    'airline' => 'SafarAir',
                    'from_city' => $city,
                    'to_city' => $city,
                    'departure_time' => now()->addDays(7)->setTime(8, 0),
                    'arrival_time' => now()->addDays(7)->setTime(10, 0),
                    'duration_minutes' => 120,
                    'stops' => 0,
                    'total_seats' => 180,
                    'available_seats' => 180,
                    'base_fare' => 60,
                    'amenities' => ['meals','baggage'],
                    'baggage_policy' => ['23kg'],
                    'status' => 'available',
                ]);
            }
        }

        // Add-ons global
        AddOn::firstOrCreate([
            'slug' => 'sim-card'
        ], [
            'name' => 'SIM Card',
            'description' => 'Prepaid data SIM card',
            'category' => 'service',
            'price' => 10,
            'pricing_type' => 'per_person',
            'terms' => 'Available upon arrival',
            'status' => 'active',
        ]);
    }
}
