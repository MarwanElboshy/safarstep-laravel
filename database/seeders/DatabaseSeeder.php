<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            CurrencySeeder::class,
            TenantSeeder::class,
            DestinationSeeder::class,
            HotelSeeder::class,
            AddOnSeeder::class,
            TagSeeder::class,
        ]);

        // Sample user for local testing
        if (class_exists(User::class) && !User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}
