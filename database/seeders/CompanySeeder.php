<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $companies = [
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Al-Ahly Travel',
                    'email' => 'contact@alahly-travel.com',
                    'phone' => '+966-1-2345678',
                    'address' => '123 Business Ave, Riyadh, Saudi Arabia',
                    'country' => 'Saudi Arabia',
                    'city' => 'Riyadh',
                    'tax_number' => 'SA123456789',
                    'contact_person' => 'Ahmed Al-Ahly',
                    'status' => 'active',
                ],
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Emirates Business Tours',
                    'email' => 'info@emiratestours.com',
                    'phone' => '+971-4-3334444',
                    'address' => '456 Trade Center, Dubai, UAE',
                    'country' => 'United Arab Emirates',
                    'city' => 'Dubai',
                    'tax_number' => 'AE987654321',
                    'contact_person' => 'Fatima Al-Mansouri',
                    'status' => 'active',
                ],
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Mediterranean Holidays Ltd',
                    'email' => 'sales@medholidays.com',
                    'phone' => '+44-207-1234567',
                    'address' => '789 Tourism Street, London, UK',
                    'country' => 'United Kingdom',
                    'city' => 'London',
                    'tax_number' => 'GB123456789',
                    'contact_person' => 'James Smith',
                    'status' => 'active',
                ],
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Istanbul Explorer Travel Agency',
                    'email' => 'hello@istanbulexplorer.com',
                    'phone' => '+90-212-5551234',
                    'address' => '321 Golden Horn Road, Istanbul, Turkey',
                    'country' => 'Turkey',
                    'city' => 'Istanbul',
                    'tax_number' => 'TR12345678',
                    'contact_person' => 'Ayşe Yılmaz',
                    'status' => 'active',
                ],
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Nile Cruise & Tours',
                    'email' => 'bookings@nilecruz.eg',
                    'phone' => '+20-2-25551234',
                    'address' => '654 Nile Street, Cairo, Egypt',
                    'country' => 'Egypt',
                    'city' => 'Cairo',
                    'tax_number' => 'EG987654321',
                    'contact_person' => 'Mohamed Hassan',
                    'status' => 'active',
                ],
            ];

            foreach ($companies as $company) {
                Company::firstOrCreate(
                    [
                        'tenant_id' => $company['tenant_id'],
                        'name' => $company['name'],
                    ],
                    $company
                );
            }
        }

        echo "✅ Companies seeded: " . $tenants->count() * count($companies) . "\n";
    }
}
