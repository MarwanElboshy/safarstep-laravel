<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantHotelCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_tenant_hotel_from_place(): void
    {
        $this->artisan('migrate');
        \App\Models\Tenant::factory()->create(['id' => 'demo-tenant', 'name' => 'Demo Tenant']);
        $user = User::factory()->create(['tenant_id' => 'demo-tenant']);
        $this->actingAs($user);

        $payload = [
            'place_id' => 'ChIJd8BlQ2BZwokR1rGd5_n5RUY',
            'name' => 'Demo Plaza Hotel',
            'address' => '123 Main St, Istanbul, Turkey',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'latitude' => 41.0082,
            'longitude' => 28.9784,
            'stars' => 5,
            'currency' => 'USD',
            'base_price_per_night' => 120.50,
            'tax_rate' => 8.5,
            'extra_bed_price' => 25.00,
            'meal_plan' => 'BB',
            'room_types' => [
                ['name' => 'Standard', 'capacity' => 2, 'base_price' => 120.50],
                ['name' => 'Family', 'capacity' => 4, 'base_price' => 180.00],
            ],
        ];

        $resp = $this->withHeader('X-Tenant-ID', 'demo-tenant')
            ->postJson('/api/v1/tenant-hotels', $payload);

        $resp->assertStatus(200)->assertJson(['success' => true]);
        $data = $resp->json('data');
        $this->assertSame('Demo Plaza Hotel', $data['hotel']['name']);
        $this->assertSame('Istanbul', $data['hotel']['city']);
        $this->assertSame('USD', $data['tenant_hotel']['currency']);
        $this->assertEquals(120.50, $data['tenant_hotel']['base_price_per_night']);
    }
}
