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
            TenantSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class,
            CurrencySeeder::class,
            AuthSeeder::class, // Add auth seeder with real credentials
            UsersTableSeeder::class, // Additional users for testing
            DestinationSeeder::class,
            HotelSeeder::class,
            AddOnSeeder::class,
              TurkishTransportSeeder::class,
              CompanySeeder::class,
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
