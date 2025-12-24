<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Dubai',
                'slug' => 'dubai',
                'description' => 'Luxury destination in the UAE with world-class shopping and beaches',
                'city' => 'Dubai',
                'country' => 'United Arab Emirates',
                'region' => 'Middle East',
                'latitude' => 25.2048,
                'longitude' => 55.2708,
                'highlights' => ['Burj Khalifa', 'Palm Jumeirah', 'Dubai Mall', 'Desert Safari'],
            ],
            [
                'name' => 'Cairo',
                'slug' => 'cairo',
                'description' => 'Egypt\'s vibrant capital, home to ancient pyramids and museums',
                'city' => 'Cairo',
                'country' => 'Egypt',
                'region' => 'Middle East',
                'latitude' => 30.0444,
                'longitude' => 31.2357,
                'highlights' => ['Great Pyramids', 'Sphinx', 'Egyptian Museum', 'Khan El-Khalili Bazaar'],
            ],
            [
                'name' => 'Petra',
                'slug' => 'petra',
                'description' => 'UNESCO World Heritage site with stunning rose-carved rock formations',
                'city' => 'Wadi Musa',
                'country' => 'Jordan',
                'region' => 'Middle East',
                'latitude' => 30.3285,
                'longitude' => 35.4444,
                'highlights' => ['The Treasury', 'Monastery', 'Royal Tombs', 'High Place of Sacrifice'],
            ],
            [
                'name' => 'Amman',
                'slug' => 'amman',
                'description' => 'Jordan\'s bustling capital on the hills with Roman ruins',
                'city' => 'Amman',
                'country' => 'Jordan',
                'region' => 'Middle East',
                'latitude' => 31.9454,
                'longitude' => 35.9284,
                'highlights' => ['Roman Theater', 'Citadel', 'Dead Sea', 'Jerash Ruins'],
            ],
            [
                'name' => 'Istanbul',
                'slug' => 'istanbul',
                'description' => 'Transcontinental city blending Eastern and Western culture',
                'city' => 'Istanbul',
                'country' => 'Turkey',
                'region' => 'Middle East',
                'latitude' => 41.0082,
                'longitude' => 28.9784,
                'highlights' => ['Blue Mosque', 'Hagia Sophia', 'Topkapi Palace', 'Grand Bazaar'],
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::firstOrCreate(
                ['slug' => $destination['slug']],
                $destination
            );
        }
    }
}
