<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a tenant for tests
        $this->tenant = Tenant::create([
            'id' => '829a874b-bd67-48df-985a-91bd28f56e6e',
            'name' => 'Test Organization',
            'slug' => 'test-org',
            'primary_color' => '#2A50BC',
            'secondary_color' => '#10B981',
        ]);
    }

    public function test_check_email_endpoint_reports_existence(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'tenant_id' => $this->tenant->id
        ]);

        $this->postJson('/api/v1/auth/check-email', ['email' => 'user@example.com'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.exists', true);

        $this->postJson('/api/v1/auth/check-email', ['email' => 'missing@example.com'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.exists', false);
    }

    public function test_validate_credentials_returns_tenants_on_success(): void
    {
        $password = 'secret1234';
        $user = User::factory()->create([
            'email' => 'user2@example.com',
            'password' => Hash::make($password),
            'tenant_id' => $this->tenant->id,
        ]);

        $this->postJson('/api/v1/auth/validate-credentials', [
            'email' => 'user2@example.com',
            'password' => $password,
        ])->assertOk()
          ->assertJsonPath('success', true)
          ->assertJsonStructure(['data' => ['tenants' => [['id','name']]]]);

        $this->postJson('/api/v1/auth/validate-credentials', [
            'email' => 'user2@example.com',
            'password' => 'wrong',
        ])->assertStatus(422)
          ->assertJsonPath('success', false);
    }

    public function test_login_returns_access_token_and_user(): void
    {
        $password = 'secret1234';
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make($password),
            'tenant_id' => $this->tenant->id,
        ]);

        $this->postJson('/api/v1/auth/login', [
            'email' => 'login@example.com',
            'password' => $password,
            'tenant_id' => $this->tenant->id,
        ])->assertOk()
          ->assertJsonPath('success', true)
          ->assertJsonStructure(['data' => ['access_token','token_type','user' => ['id','email']]]);
    }

    public function test_me_requires_authentication_and_returns_user(): void
    {
        $password = 'secret1234';
        $user = User::factory()->create([
            'email' => 'me@example.com',
            'password' => Hash::make($password),
            'tenant_id' => $this->tenant->id,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
             ->getJson('/api/v1/auth/me')
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.user.email', 'me@example.com');
    }
}
