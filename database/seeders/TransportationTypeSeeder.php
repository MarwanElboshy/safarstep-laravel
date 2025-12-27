<?php

namespace Database\Seeders;

use App\Models\TransportationType;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TransportationTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Default transportation types for all tenants
        $defaultTypes = [
            ['name' => 'Private Car', 'slug' => 'private-car', 'icon' => 'car', 'description' => 'Luxury sedan or SUV for private transfers', 'sort_order' => 1],
            ['name' => 'Minibus', 'slug' => 'minibus', 'icon' => 'bus', 'description' => '8-14 seater minibus', 'sort_order' => 2],
            ['name' => 'Coach', 'slug' => 'coach', 'icon' => 'bus', 'description' => 'Large group coach (30+ passengers)', 'sort_order' => 3],
            ['name' => 'Flight', 'slug' => 'flight', 'icon' => 'plane', 'description' => 'Domestic or international flight', 'sort_order' => 4],
            ['name' => 'Train', 'slug' => 'train', 'icon' => 'train', 'description' => 'Train journey', 'sort_order' => 5],
            ['name' => 'Cruise', 'slug' => 'cruise', 'icon' => 'ship', 'description' => 'Cruise or ferry', 'sort_order' => 6],
        ];

        // Get all tenants and seed them
        Tenant::all()->each(function ($tenant) use ($defaultTypes) {
            foreach ($defaultTypes as $type) {
                TransportationType::firstOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'slug' => $type['slug'],
                    ],
                    [...$type, 'tenant_id' => $tenant->id, 'is_active' => true]
                );
            }
        });
    }
}
