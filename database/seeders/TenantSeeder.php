<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample tenants with branding (matching SafarStep brand identity)
        $tenants = [
            [
                'name' => 'SafarStep Tourism',
                'slug' => 'safarstep',
                'primary_color' => '#2A50BC',
                'secondary_color' => '#10B981',
                'accent_color' => '#1d4ed8',
                'settings' => [
                    'timezone' => 'UTC',
                    'currency' => 'USD',
                    'company_address' => '123 Tourism Street, Travel City, TC 12345',
                    'company_phone' => '+1-555-0123',
                    'company_email' => 'info@safarstep.com',
                    'booking_prefix' => 'BK',
                    'invoice_prefix' => 'INV',
                    'payment_prefix' => 'PAY',
                    'voucher_prefix' => 'VCH',
                    'offer_prefix' => 'OFF',
                ],
            ],
            [
                'name' => 'SafarStep Demo',
                'slug' => 'safarstep-demo',
                'primary_color' => '#2A50BC',  // SafarStep brand primary
                'secondary_color' => '#10B981', // SafarStep brand secondary
                'accent_color' => '#f59e0b',
                'settings' => [
                    'timezone' => 'UTC',
                    'currency' => 'USD',
                    'company_address' => '123 Tourism Street, Travel City, TC 12345',
                    'company_phone' => '+1-555-0123',
                    'company_email' => 'info@safarstep-demo.com',
                    'booking_prefix' => 'BK',
                    'invoice_prefix' => 'INV',
                    'payment_prefix' => 'PAY',
                    'voucher_prefix' => 'VCH',
                    'offer_prefix' => 'OFF',
                ],
            ],
            [
                'name' => 'Middle East Tours',
                'slug' => 'me-tours',
                'primary_color' => '#dc2626',
                'secondary_color' => '#059669',
                'accent_color' => '#d97706',
                'settings' => [
                    'timezone' => 'Asia/Dubai',
                    'currency' => 'AED',
                    'company_address' => 'Dubai Mall, Downtown Dubai, UAE',
                    'company_phone' => '+971-4-555-0123',
                    'company_email' => 'info@me-tours.ae',
                    'booking_prefix' => 'MET',
                    'invoice_prefix' => 'INV',
                    'payment_prefix' => 'PAY',
                    'voucher_prefix' => 'VCH',
                    'offer_prefix' => 'OFF',
                ],
            ],
        ];

        foreach ($tenants as $tenant) {
            Tenant::firstOrCreate(
                ['slug' => $tenant['slug']],
                array_merge($tenant, ['id' => Str::uuid()])
            );
        }
    }
}

