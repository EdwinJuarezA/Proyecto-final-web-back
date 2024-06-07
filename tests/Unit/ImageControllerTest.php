<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_an_image()
    {
        $response = $this->post('/api/images', [
            'url' => 'http://example.com/image.jpg',
            'note_id' => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['url' => 'http://example.com/image.jpg']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_delete_an_image()
    {
        $image = Image::factory()->create();

        $response = $this->delete("/api/images/{$image->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Se ha eliminado la imagen correctamente']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_images()
    {
        $image = Image::factory()->create();

        $response = $this->get('/api/images');

        $response->assertStatus(200);
        $response->assertJsonFragment(['url' => $image->url]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_show_an_image()
    {
        $image = Image::factory()->create();

        $response = $this->get("/api/images/{$image->id}");

        $response->assertStatus(200);
        $response->assertJson(['url' => $image->url]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_not_found_for_nonexistent_image()
    {
        $response = $this->get('/api/images/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Image not found']);
    }
}
