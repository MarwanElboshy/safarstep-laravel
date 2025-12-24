<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_me_flow(): void
    {
        $register = $this->postJson('/api/v1/auth/register', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'secret1234',
        ]);
        $register->assertCreated();
        $token = $register->json('data.access_token');

        $me = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/auth/me');
        $me->assertOk();
    }

    public function test_login_and_logout_flow(): void
    {
        // Reuse register to create a user
        $this->postJson('/api/v1/auth/register', [
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => 'secret1234',
        ])->assertCreated();

        $login = $this->postJson('/api/v1/auth/login', [
            'email' => 'bob@example.com',
            'password' => 'secret1234',
            'device_name' => 'test-suite',
        ]);
        $login->assertOk();
        $token = $login->json('data.access_token');

        $logout = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/logout');
        $logout->assertOk();
    }
}
