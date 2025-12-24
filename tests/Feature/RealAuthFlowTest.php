<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RealAuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a tenant
        $this->tenant = Tenant::create([
            'id' => '829a874b-bd67-48df-985a-91bd28f56e6e',
            'name' => 'SafarStep Tourism',
            'slug' => 'safarstep',
            'primary_color' => '#2A50BC',
            'secondary_color' => '#10B981',
        ]);

        // Create user with tenant
        $this->user = User::create([
            'name' => 'SafarStep Admin',
            'email' => 'iosmarawan@gmail.com',
            'password' => Hash::make('23115520++'),
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_step_1_check_email(): void
    {
        $response = $this->postJson('/api/v1/auth/check-email', [
            'email' => 'iosmarawan@gmail.com'
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.exists', true);
    }

    public function test_step_2_validate_credentials_returns_tenant(): void
    {
        $response = $this->postJson('/api/v1/auth/validate-credentials', [
            'email' => 'iosmarawan@gmail.com',
            'password' => '23115520++',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.tenants.0.name', 'SafarStep Tourism')
            ->assertJsonPath('data.tenants.0.id', '829a874b-bd67-48df-985a-91bd28f56e6e');
    }

    public function test_step_3_login_returns_token_and_tenant_data(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'iosmarawan@gmail.com',
            'password' => '23115520++',
            'tenant_id' => '829a874b-bd67-48df-985a-91bd28f56e6e',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'user' => ['id', 'name', 'email', 'tenant_id'],
                    'tenant' => ['id', 'name', 'slug', 'primary_color', 'secondary_color'],
                    'permissions'
                ]
            ])
            ->assertJsonPath('data.user.email', 'iosmarawan@gmail.com')
            ->assertJsonPath('data.tenant.name', 'SafarStep Tourism')
            ->assertJsonPath('data.tenant.primary_color', '#2A50BC');
    }

    public function test_full_login_flow(): void
    {
        // Step 1: Check email
        $checkEmail = $this->postJson('/api/v1/auth/check-email', [
            'email' => 'iosmarawan@gmail.com'
        ]);
        $checkEmail->assertOk();

        // Step 2: Validate credentials
        $validate = $this->postJson('/api/v1/auth/validate-credentials', [
            'email' => 'iosmarawan@gmail.com',
            'password' => '23115520++',
        ]);
        $validate->assertOk();
        $tenantId = $validate->json('data.tenants.0.id');

        // Step 3: Login
        $login = $this->postJson('/api/v1/auth/login', [
            'email' => 'iosmarawan@gmail.com',
            'password' => '23115520++',
            'tenant_id' => $tenantId,
        ]);
        $login->assertOk();
        $token = $login->json('data.access_token');

        // Step 4: Verify token works with /me
        $me = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/me');
        
        $me->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'iosmarawan@gmail.com');
    }

    public function test_wrong_tenant_id_rejected(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'iosmarawan@gmail.com',
            'password' => '23115520++',
            'tenant_id' => 'wrong-tenant-id',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Invalid tenant selection.');
    }
}
