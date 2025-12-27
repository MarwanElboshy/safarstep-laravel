<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiLoginSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_login_establishes_session_and_allows_dashboard_access()
    {
        // Prepare tenant and user
        $tenantId = \Illuminate\Support\Str::uuid()->toString();
        \DB::table('tenants')->insert([
            'id' => $tenantId,
            'name' => 'Demo Tenant',
            'slug' => 'demo-tenant',
            'settings' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::factory()->create([
            'email' => 'demo@example.com',
            'tenant_id' => $tenantId,
        ]);

        // API login
        $resp = $this->postJson('/api/v1/auth/login', [
            'email' => 'demo@example.com',
            'password' => 'password',
            'tenant_id' => $tenantId,
            'device_name' => 'test-suite',
        ]);
        // Debug response during development (disabled)
        // fwrite(STDERR, json_encode($resp->json()) . "\n");
        $resp->assertStatus(200);

        // Now access dashboard via web session
        $dash = $this->get('/dashboard');
        $dash->assertStatus(200);
    }
}
