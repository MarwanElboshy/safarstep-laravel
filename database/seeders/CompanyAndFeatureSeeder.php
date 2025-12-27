<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\OfferFeature;
use App\Models\Tenant;

class CompanyAndFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->error('No tenant found. Run tenant seeder first.');
            return;
        }

        $this->command->info('Seeding companies for tenant: ' . $tenant->name);

        // Create sample B2B companies
        $companies = [
            [
                'tenant_id' => $tenant->id,
                'name' => 'Global Travel Agency',
                'contact_person' => 'Sarah Johnson',
                'phone' => '+1-555-0101',
                'email' => 'sarah@globaltravel.com',
                'country' => 'USA',
                'city' => 'New York',
                'address' => '123 Broadway, Suite 400',
                'tax_number' => 'US-123456789',
                'status' => 'active',
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Mediterranean Tours Ltd',
                'contact_person' => 'Marco Rossi',
                'phone' => '+39-06-12345678',
                'email' => 'marco@medtours.it',
                'country' => 'Italy',
                'city' => 'Rome',
                'address' => 'Via del Corso 100',
                'tax_number' => 'IT-98765432',
                'status' => 'active',
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Asia Pacific Travel Group',
                'contact_person' => 'Yuki Tanaka',
                'phone' => '+81-3-1234-5678',
                'email' => 'yuki@aptg.jp',
                'country' => 'Japan',
                'city' => 'Tokyo',
                'address' => 'Shibuya-ku, Tokyo 150-0001',
                'tax_number' => 'JP-123-4567-8901',
                'status' => 'active',
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Emirates Business Travel',
                'contact_person' => 'Ahmed Al Maktoum',
                'phone' => '+971-4-123-4567',
                'email' => 'ahmed@ebt.ae',
                'country' => 'UAE',
                'city' => 'Dubai',
                'address' => 'Sheikh Zayed Road, Dubai',
                'tax_number' => 'AE-555-123-456',
                'status' => 'active',
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Turkish Holiday Partners',
                'contact_person' => 'Ayşe Yılmaz',
                'phone' => '+90-212-555-0123',
                'email' => 'ayse@thp.com.tr',
                'country' => 'Turkey',
                'city' => 'Istanbul',
                'address' => 'Taksim Square, Istanbul',
                'tax_number' => 'TR-1234567890',
                'status' => 'active',
            ],
        ];

        foreach ($companies as $companyData) {
            Company::create($companyData);
        }

        $this->command->info('✓ Created ' . count($companies) . ' companies');

        // Create sample offer features (inclusions/exclusions)
        $this->command->info('Seeding offer features...');

        $features = [
            // Global Inclusions
            ['tenant_id' => $tenant->id, 'name' => 'Airport pickup and drop-off', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Daily breakfast at hotel', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Private air-conditioned vehicle', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'English-speaking guide', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Entrance fees to mentioned attractions', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Hotel taxes and service charges', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Bottled water during tours', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => '24/7 customer support', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'All local taxes and fees', 'type' => 'inclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Travel insurance', 'type' => 'inclusion', 'is_global' => true],
            
            // Global Exclusions
            ['tenant_id' => $tenant->id, 'name' => 'International airfare', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Visa fees', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Personal expenses', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Meals not mentioned in itinerary', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Tips and gratuities', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Optional activities and tours', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Travel insurance (if not selected)', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'COVID-19 tests', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Extra baggage fees', 'type' => 'exclusion', 'is_global' => true],
            ['tenant_id' => $tenant->id, 'name' => 'Any services not explicitly mentioned', 'type' => 'exclusion', 'is_global' => true],
        ];

        foreach ($features as $featureData) {
            OfferFeature::create($featureData);
        }

        $this->command->info('✓ Created ' . count($features) . ' offer features');
        $this->command->info('✓ Seeding completed successfully!');
    }
}
