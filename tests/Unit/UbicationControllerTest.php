<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ubication;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UbicationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_ubication()
    {
        $response = $this->post('/api/ubications', [
            'name' => 'Test Ubication',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['name' => 'Test Ubication']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_ubication()
    {
        $ubication = Ubication::factory()->create();

        $response = $this->put("/api/ubications/{$ubication->id}", [
            'name' => 'Updated Ubication',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['name' => 'Updated Ubication']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_ubications()
    {
        $ubication = Ubication::factory()->create();

        $response = $this->get('/api/ubications');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $ubication->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_show_a_ubication()
    {
        $ubication = Ubication::factory()->create();

        $response = $this->get("/api/ubications/{$ubication->id}");

        $response->assertStatus(200);
        $response->assertJson(['name' => $ubication->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_not_found_for_nonexistent_ubication()
    {
        $response = $this->get('/api/ubications/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Ubication not found']);
    }
}
