<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $dubai = Destination::where('slug', 'dubai')->first();
        $cairo = Destination::where('slug', 'cairo')->first();
        $amman = Destination::where('slug', 'amman')->first();

        if (!$dubai || !$cairo || !$amman) return;

        $hotels = [
            // Dubai
            [
                'destination_id' => $dubai->id,
                'name' => 'Burj Al Arab',
                'slug' => 'burj-al-arab',
                'description' => 'Iconic luxury hotel shaped like a sail',
                'stars' => 5,
                'address' => 'Jumeirah Road, Dubai',
                'latitude' => 25.1412,
                'longitude' => 55.1851,
                'amenities' => ['WiFi', 'Spa', 'Swimming Pool', 'Multiple Restaurants', 'Beach Access'],
                'policies' => ['Free Cancellation 48 hours before', 'Late Checkout Available', 'Complimentary Airport Transfer'],
                'contact_phone' => '+971 4 301 7777',
                'contact_email' => 'reservations@burjalarab.ae',
                'base_price_per_night' => 800,
                'status' => 'active',
            ],
            [
                'destination_id' => $dubai->id,
                'name' => 'Emirates Towers',
                'slug' => 'emirates-towers',
                'description' => 'Twin tower luxury hotel in Downtown Dubai',
                'stars' => 5,
                'address' => 'Sheikh Zayed Road, Dubai',
                'latitude' => 25.1856,
                'longitude' => 55.2745,
                'amenities' => ['WiFi', 'Business Center', 'Fitness Center', 'Fine Dining'],
                'policies' => ['Cancellation 24 hours before', 'Room Service', 'Valet Parking'],
                'contact_phone' => '+971 4 330 0000',
                'contact_email' => 'rooms@emiratestowers.ae',
                'base_price_per_night' => 350,
                'status' => 'active',
            ],
            // Cairo
            [
                'destination_id' => $cairo->id,
                'name' => 'Nile Hilton',
                'slug' => 'nile-hilton',
                'description' => 'Historic hotel overlooking the Nile River',
                'stars' => 5,
                'address' => 'Corniche El Nil, Cairo',
                'latitude' => 30.0403,
                'longitude' => 31.2359,
                'amenities' => ['River View', 'Pool', 'Egyptian Cuisine', 'Spa'],
                'policies' => ['Flexible Cancellation', 'Free WiFi', 'Airport Transfer Available'],
                'contact_phone' => '+20 2 2728 3000',
                'contact_email' => 'reservations@cairo-hilton.com',
                'base_price_per_night' => 250,
                'status' => 'active',
            ],
            // Amman
            [
                'destination_id' => $amman->id,
                'name' => 'Four Seasons Amman',
                'slug' => 'four-seasons-amman',
                'description' => 'Luxury hotel in the heart of Amman',
                'stars' => 5,
                'address' => 'Mohammed Ali Street, Amman',
                'latitude' => 31.9469,
                'longitude' => 35.9279,
                'amenities' => ['Spa', 'Pool', 'Fine Dining', 'City View'],
                'policies' => ['Premium Cancellation Policy', 'Concierge Services', 'Limousine Service'],
                'contact_phone' => '+962 6 465 5555',
                'contact_email' => 'reservations@fshr.com',
                'base_price_per_night' => 400,
                'status' => 'active',
            ],
        ];

        foreach ($hotels as $hotel) {
            Hotel::firstOrCreate(
                ['slug' => $hotel['slug']],
                $hotel
            );
        }
    }
}
