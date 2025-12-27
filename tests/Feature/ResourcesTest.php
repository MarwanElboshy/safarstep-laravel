<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Migrate
        $this->artisan('migrate');
    }

    public function test_hotels_search_by_city(): void
    {
        $destIst = Destination::factory()->create(['city' => 'Istanbul', 'country' => 'Turkey']);
        $destTrb = Destination::factory()->create(['city' => 'Trabzon', 'country' => 'Turkey']);
        Hotel::factory()->create(['destination_id' => $destIst->id, 'name' => 'Blue Hotel', 'stars' => 5, 'status' => 'active']);
        Hotel::factory()->create(['destination_id' => $destTrb->id, 'name' => 'Green Lodge', 'stars' => 3, 'status' => 'active']);

        $user = User::factory()->create(['tenant_id' => 'demo-tenant']);
        $this->actingAs($user);

        $response = $this->withHeader('X-Tenant-ID', 'demo-tenant')
            ->getJson('/api/v1/resources/hotels?city=Istanbul');
        $response->assertStatus(200)->assertJson(['success' => true]);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertSame('Blue Hotel', $data[0]['name']);
        $this->assertSame(5, $data[0]['stars']);
    }

    public function test_tours_search_by_city(): void
    {
        $destIst = Destination::factory()->create(['city' => 'Istanbul', 'country' => 'Turkey']);
        $destTrb = Destination::factory()->create(['city' => 'Trabzon', 'country' => 'Turkey']);
        Tour::factory()->create(['destination_id' => $destIst->id, 'name' => 'Bosphorus Cruise', 'price_per_person' => 50, 'status' => 'active']);
        Tour::factory()->create(['destination_id' => $destTrb->id, 'name' => 'Uzungöl Trip', 'price_per_person' => 40, 'status' => 'active']);

        $user = User::factory()->create(['tenant_id' => 'demo-tenant']);
        $this->actingAs($user);

        $response = $this->withHeader('X-Tenant-ID', 'demo-tenant')
            ->getJson('/api/v1/resources/tours?city=Istanbul');
        $response->assertStatus(200)->assertJson(['success' => true]);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertSame('Bosphorus Cruise', $data[0]['name']);
    }

    public function test_add_hotel_from_place_creates_tenant_hotel(): void
    {
        \App\Models\Tenant::create(['id' => 'demo-tenant', 'name' => 'Demo Tenant', 'slug' => 'demo-tenant']);
        $user = User::factory()->create(['tenant_id' => 'demo-tenant']);
        $this->actingAs($user);

        $payload = [
            'place_id' => 'ChIJN1t_tDeuEmsRUsoyG83frY4',
            'name' => 'Demo Hotel',
            'address' => '123 Demo Street, Istanbul, Turkey',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'latitude' => 41.0082,
            'longitude' => 28.9784,
            'stars' => 5,
            'currency' => 'USD',
            'base_price_per_night' => 150,
            'tax_rate' => 8,
            'extra_bed_price' => 30,
            'meal_plan' => 'BB',
            'room_types' => [
                ['name' => 'Standard Room', 'capacity' => 2, 'base_price' => 150],
                ['name' => 'Family Suite', 'capacity' => 4, 'base_price' => 250],
            ],
        ];

        $response = $this->withHeader('X-Tenant-ID', 'demo-tenant')
            ->postJson('/api/v1/resources/hotels/add', $payload);

        $response->assertStatus(201)->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertSame('Demo Hotel', $data['hotel']['name']);
        $this->assertSame('Istanbul', $data['hotel']['city']);
        $this->assertSame('demo-tenant', $data['tenantHotel']['tenant_id']);
        $this->assertSame('USD', $data['tenantHotel']['currency']);
        $this->assertCount(2, $data['tenantHotel']['room_types']);
    }
}
