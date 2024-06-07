<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_category()
    {
        $response = $this->post('/api/categories', [
            'name' => 'Test Category',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['name' => 'Test Category']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->put("/api/categories/{$category->id}", [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['name' => 'Updated Category']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_categories()
    {
        $category = Category::factory()->create();

        $response = $this->get('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $category->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_show_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->get("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertJson(['name' => $category->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_not_found_for_nonexistent_category()
    {
        $response = $this->get('/api/categories/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Category not found']);
    }
}
