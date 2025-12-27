<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthCheckEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_email_returns_exists_true_for_existing_user()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $resp = $this->postJson('/api/v1/auth/check-email', [
            'email' => 'test@example.com',
        ]);

        $resp->assertStatus(200)
             ->assertJson([
                 'success' => true,
                 'data' => ['exists' => true],
             ]);
    }

    public function test_check_email_returns_exists_false_for_non_existing_user()
    {
        $resp = $this->postJson('/api/v1/auth/check-email', [
            'email' => 'absent@example.com',
        ]);

        $resp->assertStatus(200)
             ->assertJson([
                 'success' => true,
                 'data' => ['exists' => false],
             ]);
    }

    public function test_check_email_validates_invalid_email()
    {
        $resp = $this->postJson('/api/v1/auth/check-email', [
            'email' => 'not-an-email',
        ]);

        $resp->assertStatus(422);
    }
}
