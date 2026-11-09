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
