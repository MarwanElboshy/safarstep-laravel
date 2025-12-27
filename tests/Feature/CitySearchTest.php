<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitySearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    private function actingAsTenantUser(string $tenantId = 'test-tenant'): User
    {
        Tenant::create(['id' => $tenantId, 'name' => 'Test Org', 'slug' => 'test-org']);
        $user = User::factory()->create(['tenant_id' => $tenantId]);
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_search_without_country_returns_results()
    {
        $this->actingAsTenantUser();

        $eg = Country::create(['name' => 'Egypt', 'slug' => 'egypt', 'iso2' => 'EG', 'iso3' => 'EGY']);
        $ae = Country::create(['name' => 'United Arab Emirates', 'slug' => 'uae', 'iso2' => 'AE', 'iso3' => 'ARE']);

        City::create(['country_id' => $eg->id, 'name' => 'Cairo', 'slug' => 'cairo']);
        City::create(['country_id' => $eg->id, 'name' => 'Alexandria', 'slug' => 'alexandria']);
        City::create(['country_id' => $ae->id, 'name' => 'Dubai', 'slug' => 'dubai']);

        $resp = $this->withHeader('X-Tenant-ID', 'test-tenant')
            ->getJson('/api/v1/cities/search?query=a');

        $resp->assertOk()->assertJson(['success' => true]);
        $data = $resp->json('data');
        $this->assertNotEmpty($data);
        $names = array_map(fn($c) => $c['name'], $data);
        $this->assertContains('Cairo', $names);
        $this->assertContains('Alexandria', $names);
    }

    public function test_search_with_country_name_filters_results()
    {
        $this->actingAsTenantUser();

        $eg = Country::create(['name' => 'Egypt', 'slug' => 'egypt', 'iso2' => 'EG', 'iso3' => 'EGY']);
        $ae = Country::create(['name' => 'United Arab Emirates', 'slug' => 'uae', 'iso2' => 'AE', 'iso3' => 'ARE']);

        City::create(['country_id' => $eg->id, 'name' => 'Cairo', 'slug' => 'cairo']);
        City::create(['country_id' => $ae->id, 'name' => 'Dubai', 'slug' => 'dubai']);

        $resp = $this->withHeader('X-Tenant-ID', 'test-tenant')
            ->getJson('/api/v1/cities/search?country_name=Egypt&query=a');

        $resp->assertOk()->assertJson(['success' => true]);
        $data = $resp->json('data');
        $names = array_map(fn($c) => $c['name'], $data);
        $this->assertContains('Cairo', $names);
        $this->assertNotContains('Dubai', $names);
    }

    public function test_search_with_country_code_filters_results()
    {
        $this->actingAsTenantUser();

        $eg = Country::create(['name' => 'Egypt', 'slug' => 'egypt', 'iso2' => 'EG', 'iso3' => 'EGY']);
        $ae = Country::create(['name' => 'United Arab Emirates', 'slug' => 'uae', 'iso2' => 'AE', 'iso3' => 'ARE']);

        City::create(['country_id' => $eg->id, 'name' => 'Cairo', 'slug' => 'cairo']);
        City::create(['country_id' => $ae->id, 'name' => 'Dubai', 'slug' => 'dubai']);

        $resp = $this->withHeader('X-Tenant-ID', 'test-tenant')
            ->getJson('/api/v1/cities/search?country_code=EG&query=a');

        $resp->assertOk()->assertJson(['success' => true]);
        $data = $resp->json('data');
        $names = array_map(fn($c) => $c['name'], $data);
        $this->assertContains('Cairo', $names);
        $this->assertNotContains('Dubai', $names);
    }

    public function test_search_with_empty_country_name_still_returns_results()
    {
        $this->actingAsTenantUser();

        $eg = Country::create(['name' => 'Egypt', 'slug' => 'egypt', 'iso2' => 'EG', 'iso3' => 'EGY']);
        $ae = Country::create(['name' => 'United Arab Emirates', 'slug' => 'uae', 'iso2' => 'AE', 'iso3' => 'ARE']);

        City::create(['country_id' => $eg->id, 'name' => 'Aswan', 'slug' => 'aswan']);
        City::create(['country_id' => $ae->id, 'name' => 'Al Ain', 'slug' => 'al-ain']);

        $resp = $this->withHeader('X-Tenant-ID', 'test-tenant')
            ->getJson('/api/v1/cities/search?country_name=&query=a');

        $resp->assertOk()->assertJson(['success' => true]);
        $data = $resp->json('data');
        $this->assertNotEmpty($data);
        $names = array_map(fn($c) => $c['name'], $data);
        $this->assertContains('Aswan', $names);
        $this->assertContains('Al Ain', $names);
    }
}
