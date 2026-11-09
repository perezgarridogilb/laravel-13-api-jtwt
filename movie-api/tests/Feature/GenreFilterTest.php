<?php

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('filters genres by search text', function (): void {
    Genre::factory()->create(['name' => 'Acción']);
    Genre::factory()->create(['name' => 'Drama']);

    $response = $this->getJson(uri: 'api/genres?search=acc');
    $response->assertStatus(status: 200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath(path: 'data.0.name', expect: 'Acción');
});


it('filters genres by is_active false', function (): void {
    Genre::factory()->create(['name' => 'Acción', 'is_active' => true]);
    Genre::factory()->create(['name' => 'Drama', 'is_active' => false]);

    $response = $this->getJson(uri: 'api/genres?is_active=false');
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath(path: 'data.0.name', expect: 'Drama');
});

it('orders genres by name in descending order', function (): void {
    Genre::factory()->create(['name' => 'Acción', 'is_active' => true]);
    Genre::factory()->create(['name' => 'Drama', 'is_active' => true]);
    $response = $this->getJson(uri: 'api/genres?sort_by=name&order=desc');
    $names = collect(value: $response->json(key: 'data'))->pluck(value: 'name')->values()->all();
    expect(value: $names)->toBe(expected: ['Drama', 'Acción']);

    $response->assertStatus(200)
        ->assertJsonCount(count: 2, key: 'data');
});

it(description: 'ignores an invalid sort column', closure: function (): void {
    Genre::factory()->create(['name' => 'Acción']);
    Genre::factory()->create(['name' => 'Drama']);

    $response = $this->getJson(uri: 'api/genres?sort_by=password');

    $response->assertStatus(status: 200)
        ->assertJsonCount(count: 2, key: 'data');
});

it(description: 'returns the correct genre by slug', closure: function (): void {
    $genre = Genre::factory()->create();
    $genre->delete();

    $this->assertSoftDeleted(table: 'genres', data: ['id' => $genre->id]);

    $this->patchJson(uri: 'api/genres/'.$genre->id.'/restore')
    ->assertStatus(status: 200);

    $this->assertDatabaseHas(table: 'genres', data: ['id' => $genre->id, 'deleted_at' => null]);
});
