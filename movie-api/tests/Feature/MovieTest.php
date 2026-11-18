<?php

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a single movie on show', function () {
    // movie::factory()->count(3)->create();
    // $response = $this->get('/');
    $movie = Movie::factory()->create();
    $response = $this->get("api/movies/{$movie->id}");

    $response->assertStatus(200)
    ->assertJsonPath('data.id',$movie->id);
});
it('returns all movies on index', function (): void {
    Movie::factory()->count(3)->create();

    $response = $this->getJson('api/movies');
        $response->assertStatus(200)
    ->assertJsonCount(3,'data');
});
it('creates a movie with auto slug on store', function (): void {
    $payload = ['title' => 'test', 'year' => 1988, 'rating' =>8];

    $response = $this->postJson('api/movies', $payload);
            $response->assertStatus(201)
    ->assertJsonPath('data.title', 'test');
    $this->assertDatabaseHas( 'movies', ['slug' => 'test']);
})/* ->skip() */;
it('modifies a movie with auto slug on update', function (): void {
    $movie = Movie::factory()->create(['title' => 'test', 'year' => 1988, 'rating' =>8]);

    $response = $this->putJson("api/movies/{$movie->id}", ["title" => 'Child\'s Play']);
            $response->assertStatus(200)
    ->assertJsonPath('data.title', 'Child\'s Play');
    // $this->assertDatabaseHas('movies', ['slug' => 'comedia']);
})/* ->skip() */;
it('soft deletes a movie on destroy', function (): void {
    $movie = Movie::factory()->create();

    $this->deleteJson("api/movies/{$movie->id}");

    $this->assertSoftDeleted('movies', ['id' => $movie->id]);
})/* ->skip() */;
// it('creates a movie with auto slug on store', function () {
//     // movie::factory()->count(3)->create();
//     // $response = $this->get('/');
//     $movie = movie::factory()->create();
//     $response = $this->get("api/movies/{$movie->id}");

//     $response->assertStatus(200)
//     ->assertJsonPath('data.id',$movie->id);
// });
it('syncs genres when provided on store', function (): void {
    $genres = Genre::factory()->count(3)->create();
    $genre_ids = $genres->pluck('id')->toArray();

    $payload = [
        'title' => 'Test Movie',
        'year' => 1988,
        'rating' => 5,
        'genre_ids' => $genre_ids,
    ];

    $response = $this->postJson('api/movies', $payload);
    $response->assertCreated();

    $movie = Movie::latest()->first();
    $this->assertCount(3, $movie->genres);
    $this->assertEquals(
        $genres->pluck('id')->sort()->values(),
        $movie->genres->pluck('id')->sort()->values()
    );
});

it('validates genre_ids exist on store', function (): void {
    $payload = [
        'title' => 'Test Movie',
        'year' => 1988,
        'rating' => 5,
        'genre_ids' => [999],
    ];

    $response = $this->postJson('api/movies', $payload);
    $response->assertUnprocessable()
        ->assertJsonPath('message', 'El campo genre_ids.0 no existe.')
        ->assertJsonValidationErrors(['genre_ids.0']);
});
