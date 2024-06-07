<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_user()
    {
        $response = $this->post('/api/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['email' => 'test@example.com']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();

        $response = $this->put("/api/users/{$user->id}", [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'password' => 'newpassword',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['email' => 'updated@example.com']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_users()
    {
        $user = User::factory()->create();

        $response = $this->get('/api/users');

        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => $user->email]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_show_a_user()
    {
        $user = User::factory()->create();

        $response = $this->get("/api/users/{$user->id}");

        $response->assertStatus(200);
        $response->assertJson(['email' => $user->email]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_not_found_for_nonexistent_user()
    {
        $response = $this->get('/api/users/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'User not found']);
    }
}
