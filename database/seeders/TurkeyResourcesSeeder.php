<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Flight;
use App\Models\Car;
use App\Models\Tour;
use App\Models\AddOn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TurkeyResourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🇹🇷 Seeding Turkey tourism resources...');

        // Get Turkey country
        $turkey = Country::where('iso2', 'TR')->first();
        
        if (!$turkey) {
            $this->command->error('Turkey not found in countries table. Please run CountriesCitiesSeeder first.');
            return;
        }

        // Create Turkey as a destination if not exists
        $turkeyDestination = Destination::firstOrCreate(
            ['slug' => 'turkey'],
            [
                'name' => 'Turkey',
                'slug' => 'turkey',
                'description' => 'A transcontinental country bridging Europe and Asia, famous for its rich history, stunning landscapes, and vibrant culture.',
                'city' => null,
                'country' => 'Turkey',
                'region' => 'Middle East',
                'latitude' => 38.9637,
                'longitude' => 35.2433,
                'highlights' => json_encode([
                    'Historic Sites',
                    'Beach Resorts',
                    'Natural Wonders',
                    'Cultural Heritage',
                    'Mediterranean Cuisine',
                ]),
            ]
        );

        // Major Turkish cities for testing
        $majorCities = ['Istanbul', 'Ankara', 'Antalya', 'Izmir', 'Bursa', 'Adana', 'Gaziantep', 'Konya'];
        
        $cities = City::where('country_id', $turkey->id)
            ->whereIn('name', $majorCities)
            ->get();

        if ($cities->isEmpty()) {
            $this->command->warn('No major Turkish cities found. Adding any available Turkish cities...');
            $cities = City::where('country_id', $turkey->id)->limit(8)->get();
        }

        if ($cities->isEmpty()) {
            $this->command->error('No Turkish cities found in database. Please seed cities first.');
            return;
        }

        $this->command->info("Found {$cities->count()} Turkish cities to populate with resources.");

        // Create destinations for each city
        $cityDestinations = [];
        foreach ($cities as $city) {
            $cityDestination = Destination::firstOrCreate(
                ['slug' => Str::slug($city->name . '-turkey')],
                [
                    'name' => $city->name,
                    'slug' => Str::slug($city->name . '-turkey'),
                    'description' => "Explore the beautiful city of {$city->name}, Turkey.",
                    'city' => $city->name,
                    'country' => 'Turkey',
                    'region' => 'Turkey',
                    'latitude' => $city->latitude,
                    'longitude' => $city->longitude,
                    'highlights' => json_encode([]),
                ]
            );
            $cityDestinations[$city->id] = $cityDestination;
        }

        // Seed resources
        $this->seedHotels($cityDestinations, $cities);
        $this->seedFlights($cities);
        $this->seedCars($cityDestinations, $cities);
        $this->seedTours($cityDestinations, $cities);
        $this->seedAddOns();

        $this->command->info('✅ Turkey resources seeded successfully!');
    }

    /**
     * Seed hotels for Turkish cities
     */
    private function seedHotels(array $cityDestinations, $cities): void
    {
        $this->command->info('Seeding hotels...');

        $hotelTemplates = [
            // Luxury Hotels
            ['name' => 'Grand Palace Hotel', 'stars' => 5, 'base_price' => 250],
            ['name' => 'Royal Suite Resort', 'stars' => 5, 'base_price' => 300],
            ['name' => 'Crown Plaza Hotel', 'stars' => 5, 'base_price' => 280],
            
            // Mid-range Hotels
            ['name' => 'City Center Hotel', 'stars' => 4, 'base_price' => 120],
            ['name' => 'Comfort Inn & Suites', 'stars' => 4, 'base_price' => 110],
            ['name' => 'Business Hotel', 'stars' => 4, 'base_price' => 130],
            ['name' => 'Garden View Hotel', 'stars' => 3, 'base_price' => 80],
            
            // Budget Hotels
            ['name' => 'Budget Inn', 'stars' => 3, 'base_price' => 60],
            ['name' => 'Express Hotel', 'stars' => 2, 'base_price' => 45],
            ['name' => 'Traveler\'s Lodge', 'stars' => 2, 'base_price' => 40],
        ];

        $amenities = [
            'wifi' => 'Free WiFi',
            'parking' => 'Free Parking',
            'breakfast' => 'Breakfast Included',
            'pool' => 'Swimming Pool',
            'spa' => 'Spa & Wellness',
            'gym' => 'Fitness Center',
            'restaurant' => 'On-site Restaurant',
            'bar' => 'Bar/Lounge',
            'room_service' => '24/7 Room Service',
            'concierge' => 'Concierge Service'
        ];

        $created = 0;
        foreach ($cities as $city) {
            $destination = $cityDestinations[$city->id];
            
            // 3-5 hotels per city
            $hotelsPerCity = rand(3, 5);
            $selectedHotels = collect($hotelTemplates)->random(min($hotelsPerCity, count($hotelTemplates)));

            foreach ($selectedHotels as $template) {
                $name = "{$template['name']} {$city->name}";
                $slug = Str::slug($name);

                // Skip if exists
                if (Hotel::where('slug', $slug)->exists()) {
                    continue;
                }

                // Select amenities based on star rating
                $selectedAmenities = [];
                $amenityCount = $template['stars'] * 2; // More amenities for higher stars
                foreach (array_rand($amenities, min($amenityCount, count($amenities))) as $key) {
                    $selectedAmenities[$key] = $amenities[$key];
                }

                Hotel::create([
                    'destination_id' => $destination->id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => "A {$template['stars']}-star hotel in the heart of {$city->name}, offering comfort and excellent service.",
                    'stars' => $template['stars'],
                    'address' => "{$city->name} City Center, Turkey",
                    'latitude' => $city->latitude ?? 41.0082,
                    'longitude' => $city->longitude ?? 28.9784,
                    'amenities' => $selectedAmenities,
                    'policies' => [
                        'check_in' => '14:00',
                        'check_out' => '12:00',
                        'cancellation' => 'Free cancellation up to 24 hours before check-in',
                        'children' => 'Children of all ages are welcome',
                        'pets' => $template['stars'] >= 4 ? 'Pets allowed on request' : 'No pets allowed'
                    ],
                    'contact_phone' => '+90 212 555 ' . rand(1000, 9999),
                    'contact_email' => Str::slug($name) . '@hotel.com',
                    'base_price_per_night' => $template['base_price'],
                    'status' => 'active'
                ]);
                $created++;
            }
        }

        $this->command->info("✓ Created {$created} hotels");
    }

    /**
     * Seed flights between Turkish cities
     */
    private function seedFlights($cities): void
    {
        $this->command->info('Seeding flights...');

        $airlines = ['Turkish Airlines', 'Pegasus Airlines', 'SunExpress', 'AnadoluJet'];
        
        $created = 0;
        $cityNames = $cities->pluck('name')->toArray();

        // Create flights between major cities
        foreach ($cityNames as $i => $fromCity) {
            foreach ($cityNames as $j => $toCity) {
                if ($i >= $j) continue; // Avoid duplicates and self-flights

                // 2-3 flights per route
                for ($f = 0; $f < rand(2, 3); $f++) {
                    $airline = $airlines[array_rand($airlines)];
                    $flightCode = strtoupper(substr($airline, 0, 2)) . rand(100, 999);

                    if (Flight::where('flight_code', $flightCode)->exists()) {
                        continue;
                    }

                    $departureHour = rand(6, 20);
                    $duration = rand(60, 180); // 1-3 hours

                    Flight::create([
                        'flight_code' => $flightCode,
                        'airline' => $airline,
                        'from_city' => $fromCity,
                        'to_city' => $toCity,
                        'departure_time' => now()->setHour($departureHour)->setMinute(rand(0, 59)),
                        'arrival_time' => now()->setHour($departureHour)->addMinutes($duration),
                        'duration_minutes' => $duration,
                        'stops' => 0,
                        'total_seats' => rand(150, 200),
                        'available_seats' => rand(100, 150),
                        'base_fare' => rand(80, 300),
                        'amenities' => [
                            'wifi' => rand(0, 1) ? 'Available' : 'Not available',
                            'meals' => rand(0, 1) ? 'Complimentary snacks and beverages' : 'Purchase on board',
                            'entertainment' => rand(0, 1) ? 'In-flight entertainment' : 'Not available'
                        ],
                        'baggage_policy' => [
                            'cabin' => '8 kg',
                            'checked' => '20 kg',
                            'extra_fee' => '$25 per additional bag'
                        ],
                        'status' => 'available'
                    ]);
                    $created++;
                }
            }
        }

        $this->command->info("✓ Created {$created} flights");
    }

    /**
     * Seed rental cars
     */
    private function seedCars(array $cityDestinations, $cities): void
    {
        $this->command->info('Seeding rental cars...');

        $carTypes = [
            ['type' => 'economy', 'name' => 'Fiat Egea', 'capacity' => 5, 'luggage' => 2, 'rate' => 35],
            ['type' => 'economy', 'name' => 'Renault Clio', 'capacity' => 5, 'luggage' => 2, 'rate' => 38],
            ['type' => 'compact', 'name' => 'Volkswagen Golf', 'capacity' => 5, 'luggage' => 3, 'rate' => 45],
            ['type' => 'sedan', 'name' => 'Toyota Corolla', 'capacity' => 5, 'luggage' => 3, 'rate' => 55],
            ['type' => 'sedan', 'name' => 'Honda Civic', 'capacity' => 5, 'luggage' => 3, 'rate' => 58],
            ['type' => 'suv', 'name' => 'Nissan Qashqai', 'capacity' => 5, 'luggage' => 4, 'rate' => 75],
            ['type' => 'suv', 'name' => 'Toyota RAV4', 'capacity' => 5, 'luggage' => 4, 'rate' => 80],
            ['type' => 'van', 'name' => 'Mercedes Vito', 'capacity' => 8, 'luggage' => 6, 'rate' => 120],
            ['type' => 'luxury', 'name' => 'BMW 5 Series', 'capacity' => 5, 'luggage' => 3, 'rate' => 150],
        ];

        $features = [
            'air_conditioning' => 'Air Conditioning',
            'automatic' => 'Automatic Transmission',
            'gps' => 'GPS Navigation',
            'bluetooth' => 'Bluetooth',
            'usb' => 'USB Ports',
            'backup_camera' => 'Backup Camera',
            'cruise_control' => 'Cruise Control'
        ];

        $created = 0;
        foreach ($cities as $city) {
            $destination = $cityDestinations[$city->id];
            
            // 5-8 cars per city
            $carsPerCity = rand(5, 8);
            
            foreach (array_rand($carTypes, min($carsPerCity, count($carTypes))) as $index) {
                $carTemplate = $carTypes[$index];
                $plateNumber = sprintf('%02d-%s-%04d', rand(1, 81), strtoupper(Str::random(2)), rand(1000, 9999));

                if (Car::where('license_plate', $plateNumber)->exists()) {
                    continue;
                }

                // Select features
                $selectedFeatures = [];
                $featureCount = $carTemplate['type'] === 'luxury' ? 7 : rand(3, 5);
                foreach (array_rand($features, min($featureCount, count($features))) as $key) {
                    $selectedFeatures[$key] = $features[$key];
                }

                Car::create([
                    'destination_id' => $destination->id,
                    'name' => $carTemplate['name'],
                    'license_plate' => $plateNumber,
                    'vehicle_type' => $carTemplate['type'],
                    'capacity' => $carTemplate['capacity'],
                    'luggage_capacity' => $carTemplate['luggage'],
                    'daily_rate' => $carTemplate['rate'],
                    'features' => $selectedFeatures,
                    'policies' => [
                        'min_age' => 21,
                        'license_required' => 'Valid driver\'s license required',
                        'insurance' => 'Basic insurance included',
                        'fuel_policy' => 'Full to full',
                        'mileage' => 'Unlimited mileage'
                    ],
                    'status' => 'available'
                ]);
                $created++;
            }
        }

        $this->command->info("✓ Created {$created} rental cars");
    }

    /**
     * Seed tours and activities
     */
    private function seedTours(array $cityDestinations, $cities): void
    {
        $this->command->info('Seeding tours...');

        $tourTemplates = [
            [
                'name' => 'Historical City Tour',
                'description' => 'Explore ancient monuments, mosques, and bazaars with expert local guides.',
                'duration' => '4 hours',
                'price' => 45,
                'includes' => ['Professional guide', 'Entrance fees', 'Traditional tea/coffee']
            ],
            [
                'name' => 'Culinary Experience',
                'description' => 'Taste authentic Turkish cuisine and learn traditional cooking techniques.',
                'duration' => '3 hours',
                'price' => 65,
                'includes' => ['Food tasting', 'Cooking class', 'Recipe booklet', 'Chef instructor']
            ],
            [
                'name' => 'Sunset Boat Tour',
                'description' => 'Sail along the coastline and enjoy breathtaking sunset views.',
                'duration' => '2 hours',
                'price' => 55,
                'includes' => ['Boat ride', 'Snacks and beverages', 'Life jackets', 'Captain']
            ],
            [
                'name' => 'Bazaar Shopping Tour',
                'description' => 'Navigate the vibrant bazaars with a local shopping expert.',
                'duration' => '3 hours',
                'price' => 35,
                'includes' => ['Local guide', 'Market insights', 'Bargaining tips']
            ],
            [
                'name' => 'Adventure Hiking Tour',
                'description' => 'Trek through scenic landscapes and discover hidden natural gems.',
                'duration' => '6 hours',
                'price' => 75,
                'includes' => ['Professional guide', 'Hiking equipment', 'Lunch', 'Transportation']
            ]
        ];

        $created = 0;
        foreach ($cities as $city) {
            $destination = $cityDestinations[$city->id];
            
            // 3-4 tours per city
            $toursPerCity = rand(3, 4);
            $selectedTours = collect($tourTemplates)->random(min($toursPerCity, count($tourTemplates)));

            foreach ($selectedTours as $template) {
                $name = "{$template['name']} - {$city->name}";
                $slug = Str::slug($name);

                if (Tour::where('slug', $slug)->exists()) {
                    continue;
                }

                Tour::create([
                    'destination_id' => $destination->id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $template['description'],
                    'guide_name' => fake()->name(),
                    'capacity' => rand(10, 25),
                    'booked_seats' => rand(0, 5),
                    'price_per_person' => $template['price'],
                    'start_time' => sprintf('%02d:00', rand(9, 14)),
                    'end_time' => sprintf('%02d:00', rand(15, 18)),
                    'itinerary' => json_encode([
                        ['time' => 'Morning', 'activity' => 'Meet at the starting point'],
                        ['time' => 'Afternoon', 'activity' => 'Main tour activities'],
                        ['time' => 'Evening', 'activity' => 'Return and farewell']
                    ]),
                    'includes' => $template['includes'],
                    'status' => 'active'
                ]);
                $created++;
            }
        }

        $this->command->info("✓ Created {$created} tours");
    }

    /**
     * Seed add-ons (services applicable across all offers)
     */
    private function seedAddOns(): void
    {
        $this->command->info('Seeding add-ons...');

        $addOns = [
            // Travel Insurance
            ['name' => 'Basic Travel Insurance', 'category' => 'insurance', 'price' => 25, 'pricing_type' => 'per_person', 'description' => 'Covers medical emergencies and trip cancellation.'],
            ['name' => 'Premium Travel Insurance', 'category' => 'insurance', 'price' => 50, 'pricing_type' => 'per_person', 'description' => 'Comprehensive coverage including baggage loss and flight delays.'],
            
            // SIM & Connectivity
            ['name' => 'Local SIM Card (5GB)', 'category' => 'service', 'price' => 15, 'pricing_type' => 'per_person', 'description' => '5GB data + local calls for 7 days.'],
            ['name' => 'Portable WiFi Device', 'category' => 'service', 'price' => 8, 'pricing_type' => 'per_booking', 'description' => 'Unlimited data, daily rental.'],
            
            // Airport Services
            ['name' => 'Airport Fast Track', 'category' => 'service', 'price' => 35, 'pricing_type' => 'per_person', 'description' => 'Skip queues at security and immigration.'],
            ['name' => 'Airport Lounge Access', 'category' => 'service', 'price' => 45, 'pricing_type' => 'per_person', 'description' => 'Access to premium airport lounges with food and beverages.'],
            ['name' => 'Meet & Greet Service', 'category' => 'service', 'price' => 30, 'pricing_type' => 'per_booking', 'description' => 'Personal assistant at airport arrival.'],
            
            // Meals
            ['name' => 'Breakfast Upgrade', 'category' => 'meal', 'price' => 12, 'pricing_type' => 'per_person', 'description' => 'Buffet breakfast at hotel.'],
            ['name' => 'Dinner at Traditional Restaurant', 'category' => 'meal', 'price' => 35, 'pricing_type' => 'per_person', 'description' => '3-course dinner with local specialties.'],
            
            // Activities
            ['name' => 'Turkish Bath Experience', 'category' => 'activity', 'price' => 40, 'pricing_type' => 'per_person', 'description' => 'Traditional hammam with massage and scrub.'],
            ['name' => 'Hot Air Balloon Ride', 'category' => 'activity', 'price' => 180, 'pricing_type' => 'per_person', 'description' => 'Sunrise hot air balloon experience.'],
            ['name' => 'Paragliding Adventure', 'category' => 'activity', 'price' => 120, 'pricing_type' => 'per_person', 'description' => 'Tandem paragliding over scenic landscapes.'],
            
            // Transportation
            ['name' => 'Private Airport Transfer', 'category' => 'transportation', 'price' => 40, 'pricing_type' => 'per_booking', 'description' => 'Private car from/to airport.'],
            ['name' => 'Inter-city Private Transfer', 'category' => 'transportation', 'price' => 80, 'pricing_type' => 'per_booking', 'description' => 'Private transfer between cities.'],
        ];

        $created = 0;
        foreach ($addOns as $addOn) {
            $slug = Str::slug($addOn['name']);
            
            if (AddOn::where('slug', $slug)->exists()) {
                continue;
            }

            AddOn::create([
                'name' => $addOn['name'],
                'slug' => $slug,
                'description' => $addOn['description'],
                'category' => $addOn['category'],
                'price' => $addOn['price'],
                'pricing_type' => $addOn['pricing_type'],
                'terms' => 'Subject to availability. Advance booking recommended.',
                'status' => 'active'
            ]);
            $created++;
        }

        $this->command->info("✓ Created {$created} add-ons");
    }
}
