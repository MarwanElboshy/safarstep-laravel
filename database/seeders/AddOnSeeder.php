<?php

namespace Database\Seeders;

use App\Models\AddOn;
use Illuminate\Database\Seeder;

class AddOnSeeder extends Seeder
{
    public function run(): void
    {
        $addOns = [
            ['name' => 'Travel Insurance', 'slug' => 'travel-insurance', 'category' => 'insurance', 'price' => 50, 'pricing_type' => 'per_person'],
            ['name' => 'Airport Transfer', 'slug' => 'airport-transfer', 'category' => 'transportation', 'price' => 100, 'pricing_type' => 'per_booking'],
            ['name' => 'Guided Tour', 'slug' => 'guided-tour', 'category' => 'activity', 'price' => 75, 'pricing_type' => 'per_person'],
            ['name' => 'Breakfast Upgrade', 'slug' => 'breakfast-upgrade', 'category' => 'meal', 'price' => 20, 'pricing_type' => 'per_night'],
            ['name' => 'Spa Treatment', 'slug' => 'spa-treatment', 'category' => 'service', 'price' => 150, 'pricing_type' => 'per_person'],
            ['name' => 'Dinner Show', 'slug' => 'dinner-show', 'category' => 'activity', 'price' => 120, 'pricing_type' => 'per_person'],
            ['name' => 'Private Jet Charter', 'slug' => 'private-jet', 'category' => 'transportation', 'price' => 5000, 'pricing_type' => 'per_booking'],
            ['name' => 'Desert Safari', 'slug' => 'desert-safari', 'category' => 'activity', 'price' => 100, 'pricing_type' => 'per_person'],
        ];

        foreach ($addOns as $addOn) {
            AddOn::firstOrCreate(
                ['slug' => $addOn['slug']],
                $addOn
            );
        }
    }
}
