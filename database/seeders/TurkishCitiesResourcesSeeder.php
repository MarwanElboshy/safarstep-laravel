<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\Car;
use App\Models\Tenant;

class TurkishCitiesResourcesSeeder extends Seeder
{
    public function run(): void
    {
        // Get first tenant for seeding
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->error('No tenant found. Please create a tenant first.');
            return;
        }

        $this->command->info('Seeding tours and transport for Turkish cities...');

        $cities = ['Adana', 'Adapazarı', 'Adilcevaz'];

        foreach ($cities as $cityName) {
            // Create or get destination
            $destination = Destination::firstOrCreate(
                ['city' => $cityName, 'country' => 'Turkey'],
                [
                    'name' => $cityName,
                    'slug' => \Illuminate\Support\Str::slug($cityName),
                    'description' => "Beautiful city of {$cityName} in Turkey",
                    'region' => 'Turkey',
                ]
            );

            // Seed tours based on city
            $tours = $this->getToursByCity($cityName);
            foreach ($tours as $tourData) {
                $slug = \Illuminate\Support\Str::slug($tourData['name']);
                Tour::updateOrCreate(
                    ['slug' => $slug],
                    array_merge($tourData, [
                        'destination_id' => $destination->id,
                        'status' => 'active',
                    ])
                );
            }

            // Seed transport (cars)
            $cars = $this->getCarsByCity($cityName);
            foreach ($cars as $carData) {
                Car::updateOrCreate(
                    ['license_plate' => $carData['license_plate']],
                    array_merge($carData, [
                        'destination_id' => $destination->id,
                        'status' => 'available',
                    ])
                );
            }
        }

        $this->command->info('Successfully seeded tours and transport for Turkish cities!');
    }

    private function getToursByCity($city)
    {
        $tours = [
            'Adana' => [
                [
                    'name' => 'Sabancı Central Mosque Tour',
                    'description' => 'Visit one of the largest mosques in Turkey with stunning Ottoman architecture',
                    'guide_name' => 'Local Expert',
                    'capacity' => 20,
                    'booked_seats' => 0,
                    'price_per_person' => 25.00,
                    'start_time' => '09:00',
                    'end_time' => '11:00',
                    'includes' => ['Guide', 'Entry tickets', 'Transportation'],
                    'itinerary' => ['Meet at hotel', 'Drive to mosque', 'Guided tour', 'Return to hotel'],
                ],
                [
                    'name' => 'Adana Food Walking Tour',
                    'description' => 'Discover the famous Adana kebab and local cuisine on this culinary adventure',
                    'guide_name' => 'Food Expert',
                    'capacity' => 15,
                    'booked_seats' => 0,
                    'price_per_person' => 45.00,
                    'start_time' => '18:00',
                    'end_time' => '21:00',
                    'includes' => ['Guide', 'Food tastings', 'Drinks'],
                    'itinerary' => ['Meet at bazaar', 'Visit 5 restaurants', 'Local market tour', 'End at cafe'],
                ],
                [
                    'name' => 'Seyhan River Boat Tour',
                    'description' => 'Relaxing boat tour along the scenic Seyhan River with city views',
                    'guide_name' => 'Boat Captain',
                    'capacity' => 30,
                    'booked_seats' => 0,
                    'price_per_person' => 20.00,
                    'start_time' => '16:00',
                    'end_time' => '18:00',
                    'includes' => ['Boat ride', 'Snacks', 'Drinks'],
                    'itinerary' => ['Board at pier', 'River cruise', 'Photo stops', 'Return to pier'],
                ],
                [
                    'name' => 'Adana Historical City Tour',
                    'description' => 'Explore ancient bridges, historic markets, and Ottoman architecture',
                    'guide_name' => 'History Guide',
                    'capacity' => 25,
                    'booked_seats' => 0,
                    'price_per_person' => 55.00,
                    'start_time' => '10:00',
                    'end_time' => '16:00',
                    'includes' => ['Guide', 'Entry tickets', 'Lunch', 'Transportation'],
                    'itinerary' => ['Stone Bridge', 'Grand Bazaar', 'Ulu Camii', 'Old quarter', 'Lunch break'],
                ],
            ],
            'Adapazarı' => [
                [
                    'name' => 'Sapanca Lake Day Trip',
                    'description' => 'Beautiful lakeside visit with nature walks and local cuisine',
                    'guide_name' => 'Nature Guide',
                    'capacity' => 20,
                    'booked_seats' => 0,
                    'price_per_person' => 65.00,
                    'start_time' => '08:00',
                    'end_time' => '17:00',
                    'includes' => ['Guide', 'Lunch', 'Transportation', 'Lake activities'],
                    'itinerary' => ['Depart city', 'Lake viewing', 'Nature walk', 'Traditional lunch', 'Return'],
                ],
                [
                    'name' => 'Maşukiye Nature Tour',
                    'description' => 'Visit stunning waterfalls and lush forests in Maşukiye village',
                    'guide_name' => 'Eco Guide',
                    'capacity' => 15,
                    'booked_seats' => 0,
                    'price_per_person' => 70.00,
                    'start_time' => '09:00',
                    'end_time' => '16:00',
                    'includes' => ['Guide', 'Lunch', 'Transportation', 'Waterfall entry'],
                    'itinerary' => ['Drive to Maşukiye', 'Waterfall visit', 'Village lunch', 'Forest walk', 'Return'],
                ],
                [
                    'name' => 'Sakarya River Rafting',
                    'description' => 'Exciting whitewater rafting adventure on Sakarya River',
                    'guide_name' => 'Rafting Instructor',
                    'capacity' => 12,
                    'booked_seats' => 0,
                    'price_per_person' => 55.00,
                    'start_time' => '10:00',
                    'end_time' => '14:00',
                    'includes' => ['Equipment', 'Instructor', 'Lunch', 'Transportation'],
                    'itinerary' => ['Safety briefing', 'River rafting', 'Lunch break', 'Return'],
                ],
            ],
            'Adilcevaz' => [
                [
                    'name' => 'Van Lake Boat Tour',
                    'description' => 'Explore the beautiful Van Lake with stunning mountain views',
                    'guide_name' => 'Lake Guide',
                    'capacity' => 25,
                    'booked_seats' => 0,
                    'price_per_person' => 40.00,
                    'start_time' => '09:00',
                    'end_time' => '12:00',
                    'includes' => ['Boat ride', 'Guide', 'Snacks'],
                    'itinerary' => ['Board at harbor', 'Lake cruise', 'Photo opportunities', 'Return to shore'],
                ],
                [
                    'name' => 'Adilcevaz Castle Tour',
                    'description' => 'Visit the historic medieval castle overlooking Van Lake',
                    'guide_name' => 'History Expert',
                    'capacity' => 20,
                    'booked_seats' => 0,
                    'price_per_person' => 30.00,
                    'start_time' => '14:00',
                    'end_time' => '16:00',
                    'includes' => ['Guide', 'Entry ticket', 'Transportation'],
                    'itinerary' => ['Drive to castle', 'Guided tour', 'City views', 'Return'],
                ],
                [
                    'name' => 'Armenian Churches Tour',
                    'description' => 'Explore ancient Armenian churches and monasteries in the region',
                    'guide_name' => 'Cultural Guide',
                    'capacity' => 18,
                    'booked_seats' => 0,
                    'price_per_person' => 50.00,
                    'start_time' => '10:00',
                    'end_time' => '15:00',
                    'includes' => ['Guide', 'Entry tickets', 'Lunch', 'Transportation'],
                    'itinerary' => ['Church of St. George', 'Monastery visit', 'Lunch', 'Historical sites', 'Return'],
                ],
            ],
        ];

        return $tours[$city] ?? [];
    }

    private function getCarsByCity($city)
    {
        $cars = [
            'Adana' => [
                [
                    'name' => 'Private Car Airport Transfer',
                    'license_plate' => '01 ABC 123',
                    'vehicle_type' => 'sedan',
                    'capacity' => 4,
                    'luggage_capacity' => 2,
                    'daily_rate' => 50.00,
                    'features' => ['AC', 'WiFi', 'Child seat available'],
                    'policies' => ['24h cancellation', 'No smoking', 'Driver speaks English'],
                ],
                [
                    'name' => 'Luxury Van Group Transfer',
                    'license_plate' => '01 XYZ 456',
                    'vehicle_type' => 'van',
                    'capacity' => 8,
                    'luggage_capacity' => 6,
                    'daily_rate' => 120.00,
                    'features' => ['AC', 'WiFi', 'Leather seats', 'USB ports'],
                    'policies' => ['24h cancellation', 'No smoking', 'Professional driver'],
                ],
                [
                    'name' => 'City Bus Public Transport',
                    'license_plate' => '01 BUS 789',
                    'vehicle_type' => 'van',
                    'capacity' => 40,
                    'luggage_capacity' => 20,
                    'daily_rate' => 200.00,
                    'features' => ['AC', 'Standing room', 'Multiple stops'],
                    'policies' => ['Fixed schedule', 'No cancellation'],
                ],
            ],
            'Adapazarı' => [
                [
                    'name' => 'Private Car Transfer',
                    'license_plate' => '54 DEF 321',
                    'vehicle_type' => 'sedan',
                    'capacity' => 4,
                    'luggage_capacity' => 2,
                    'daily_rate' => 55.00,
                    'features' => ['AC', 'GPS', 'Music system'],
                    'policies' => ['24h cancellation', 'No smoking'],
                ],
                [
                    'name' => 'Minibus Nature Tours',
                    'license_plate' => '54 MIN 654',
                    'vehicle_type' => 'van',
                    'capacity' => 15,
                    'luggage_capacity' => 10,
                    'daily_rate' => 150.00,
                    'features' => ['AC', 'Reclining seats', 'Panoramic windows'],
                    'policies' => ['48h cancellation', 'Tour guide included'],
                ],
            ],
            'Adilcevaz' => [
                [
                    'name' => 'Private SUV Mountain Transfer',
                    'license_plate' => '65 SUV 111',
                    'vehicle_type' => 'suv',
                    'capacity' => 5,
                    'luggage_capacity' => 4,
                    'daily_rate' => 80.00,
                    'features' => ['4WD', 'AC', 'Mountain capable', 'Safety equipment'],
                    'policies' => ['24h cancellation', 'Experienced mountain driver'],
                ],
                [
                    'name' => 'Boat Transfer Van Lake',
                    'license_plate' => '65 BOAT 222',
                    'vehicle_type' => 'luxury',
                    'capacity' => 20,
                    'luggage_capacity' => 10,
                    'daily_rate' => 250.00,
                    'features' => ['Life jackets', 'Covered seating', 'Refreshments'],
                    'policies' => ['Weather dependent', '48h cancellation'],
                ],
            ],
        ];

        return $cars[$city] ?? [];
    }
}
