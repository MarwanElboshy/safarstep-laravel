<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryCityEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // sqlite in memory/file by default; migrate
        $this->artisan('migrate');
    }

    private function actingAsTenantUser(string $tenantId = 'test-tenant'): User
    {
        Tenant::create(['id' => $tenantId, 'name' => 'Test Org', 'slug' => 'test-org']);
        $user = User::factory()->create(['tenant_id' => $tenantId]);
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_countries_index_returns_db_countries()
    {
        $this->actingAsTenantUser();

        Country::create(['name' => 'Egypt', 'slug' => 'egypt', 'iso2' => 'EG', 'iso3' => 'EGY']);
        Country::create(['name' => 'United Arab Emirates', 'slug' => 'uae', 'iso2' => 'AE', 'iso3' => 'ARE']);

        $resp = $this->withHeader('X-Tenant-ID', 'test-tenant')
            ->getJson('/api/v1/countries');

        $resp->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => [['id', 'name', 'iso2', 'iso3']]])
            ->assertJsonFragment(['name' => 'Egypt'])
            ->assertJsonFragment(['name' => 'United Arab Emirates']);
    }

    public function test_cities_by_country_returns_db_cities()
    {
        $this->actingAsTenantUser();

        $country = Country::create(['name' => 'Egypt', 'slug' => 'egypt', 'iso2' => 'EG', 'iso3' => 'EGY']);
        City::create(['country_id' => $country->id, 'name' => 'Cairo', 'slug' => 'cairo', 'latitude' => 30.06263, 'longitude' => 31.24967]);
        City::create(['country_id' => $country->id, 'name' => 'Alexandria', 'slug' => 'alexandria']);

        $resp = $this->withHeader('X-Tenant-ID', 'test-tenant')
            ->postJson('/api/v1/cities/by-country', ['country_name' => 'Egypt']);

        $resp->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => [['id', 'name', 'country_id']]])
            ->assertJsonFragment(['name' => 'Cairo'])
            ->assertJsonFragment(['name' => 'Alexandria']);
    }
}
