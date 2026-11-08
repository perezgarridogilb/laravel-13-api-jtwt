<?php

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a single genre', function () {
    // Genre::factory()->count(3)->create();
    // $response = $this->get('/');
    $genre = Genre::factory()->create();
    $response = $this->get("api/genres/{$genre->id}");

    $response->assertStatus(200)
    ->assertJsonPath('data.id',$genre->id);
});
it('returns all genres on index', function (): void {
    Genre::factory()->count(3)->create();

    $response = $this->getJson('api/genres');
        $response->assertStatus(200)
    ->assertJsonCount(3,'data');
});
it('creates a genre with auto slug on store', function (): void {
    $payload = ['name' => 'Comedia', 'description' => 'Son para reir','is_active' => '1'];

    $response = $this->postJson('api/genres', $payload);
            $response->assertStatus(201)
    ->assertJsonPath('data.name', 'Comedia');
    $this->assertDatabaseHas( 'genres', ['slug' => 'comedia']);
});
it('modifies a genre with auto slug on update', function (): void {
    $genre = Genre::factory()->create(['name' => 'Terror', 'description' => 'Son para reir','is_active' => '1']);

    $response = $this->putJson("api/genres/{$genre->id}", ['name' => 'Horror','is_active' => '1']);
            $response->assertStatus(200)
    ->assertJsonPath('data.name', 'Horror');
    // $this->assertDatabaseHas('genres', ['slug' => 'comedia']);
});
it('soft deletes a genre on destroy', function (): void {
    $genre = Genre::factory()->create();

    $this->deleteJson("api/genres/{$genre->id}");

    $this->assertSoftDeleted('genres', ['id' => $genre->id]);
});
// it('creates a genre with auto slug on store', function () {
//     // Genre::factory()->count(3)->create();
//     // $response = $this->get('/');
//     $genre = Genre::factory()->create();
//     $response = $this->get("api/genres/{$genre->id}");

//     $response->assertStatus(200)
//     ->assertJsonPath('data.id',$genre->id);
// });

