<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Location\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelsCombinedTest extends TestCase
{
    use RefreshDatabase;

    public function test_combined_returns_google_results_and_annotations(): void
    {
        $this->artisan('migrate');
        Tenant::factory()->create(['id' => 'demo-tenant', 'name' => 'Demo Tenant']);
        $user = User::factory()->create(['tenant_id' => 'demo-tenant']);
        $this->actingAs($user);

        // Bind a fake LocationService
        $fake = new class extends LocationService {
            public function setTenantId(string $tenantId): self { return $this; }
            public function textSearchHotels(string $query, string $city, array $options = []): array {
                return [
                    ['name' => 'Adana Grand Hotel', 'address' => 'Adana, Turkey', 'lat' => 37.0, 'lng' => 35.3, 'place_id' => 'PLACE_1'],
                    ['name' => 'City Lodge Adana', 'address' => 'Adana, Turkey', 'lat' => 37.1, 'lng' => 35.2, 'place_id' => 'PLACE_2'],
                ];
            }
        };
        app()->instance(LocationService::class, $fake);

        // Seed one matching hotel with place_id
        $dest = Destination::factory()->create(['city' => 'Adana', 'country' => 'Turkey']);
        $hotel = Hotel::factory()->create(['destination_id' => $dest->id, 'name' => 'Adana Grand Hotel', 'place_id' => 'PLACE_1']);

        $resp = $this->withHeader('X-Tenant-ID', 'demo-tenant')
            ->getJson('/api/v1/resources/hotels/combined?search=grand&city=Adana');
        $resp->assertStatus(200)->assertJson(['success' => true]);
        $data = $resp->json('data');
        $this->assertCount(2, $data);
        $this->assertTrue($data[0]['exists']);
        $this->assertSame('Adana Grand Hotel', $data[0]['name']);
        $this->assertFalse($data[1]['exists']);
        $this->assertArrayHasKey('image_url', $data[0]);
    }
}
