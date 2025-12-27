<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_redirects_to_login_when_unauthenticated()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_dashboard_access_after_login_sets_tenant_and_succeeds()
    {
        // Create a tenant and user
        $tenantId = \Illuminate\Support\Str::uuid()->toString();
        \DB::table('tenants')->insert([
            'id' => $tenantId,
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'settings' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenantId,
            'password' => 'password', // factory usually hashes
        ]);

        // Log in via session
        $this->actingAs($user);
        // Simulate missing session tenant; middleware should derive from user
        session()->forget('tenant_id');

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }
}
