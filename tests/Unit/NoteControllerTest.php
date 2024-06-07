<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Note;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_note()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/api/notes', [
            'title' => 'Test Note',
            'description' => 'This is a test note.',
            'ubication' => 'Test Location',
            'status' => true,
            'category_id' => $category->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['title' => 'Test Note']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_note()
    {
        $note = Note::factory()->create();

        $response = $this->put("/api/notes/{$note->id}", [
            'title' => 'Updated Note',
            'description' => 'This is an updated note.',
            'ubication' => 'Updated Location',
            'status' => true,
            'category_id' => $note->category_id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['title' => 'Updated Note']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_notes()
    {
        $note = Note::factory()->create();

        $response = $this->get('/api/notes');

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => $note->title]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_show_a_note()
    {
        $note = Note::factory()->create();

        $response = $this->get("/api/notes/{$note->id}");

        $response->assertStatus(200);
        $response->assertJson(['title' => $note->title]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_not_found_for_nonexistent_note()
    {
        $response = $this->get('/api/notes/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Note not found']);
    }
}
