<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiV1BasicTest extends TestCase
{
    public function test_auth_me_endpoint_returns_ok(): void
    {
        // Unauthenticated requests should be unauthorized
        $this->getJson('/api/v1/auth/me')->assertStatus(401);
    }

    public function test_tenants_index_endpoint_returns_ok(): void
    {
        // Tenants endpoint now requires auth:sanctum
        $this->getJson('/api/v1/tenants')->assertStatus(401);
    }
}
