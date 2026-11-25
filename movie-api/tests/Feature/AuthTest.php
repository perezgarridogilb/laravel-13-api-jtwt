<?php

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
test('creates user and returns token on register', function () {
$payload = [
        'name' => 'Teddy Test',
        'email' => 'teddy@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post(uri: '/api/auth/register', data: $payload);

    $response->assertStatus(status: 201)
        ->assertJsonStructure(structure: ['data' => ['access_token', 'token_type', 'expires_in', 'user']])
->assertJsonStructure([
    'data' => [
        'access_token',
        'token_type',
        'expires_in',
        'user' => ['email'],
    ],
]);
});

it('creates user and returns token on register', function (): void {
    $payload = [
        'name' => 'Teddy Test',
        'email' => 'teddy@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post('/api/auth/register', $payload);

    expect($response->json('data.user'))->not->toHaveKey('password');
});

it('requires all fields on register', function (): void {
    $response = $this->post('/api/auth/register', []);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('returns token for valid credentials on login', function (): void {
    User::factory()->create([
        'email' => 'test@mail.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/api/auth/login', [
        'email' => 'test@mail.com',
        'password' => 'password123',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['access_token', 'token_type', 'expires_in', 'user'],
        ]);
});

it(description: 'returns user with valid token on me', closure: function (): void {
    $user = User::factory()->create();

    $this
        ->actingAs(user: $user, guard: 'api')
        ->getJson(uri: '/api/auth/me')
        ->assertStatus(status: 200)
        ->assertJsonPath(path: 'data.email', expect: $user->email);
});

it(description: 'succeeds with valid token on logout', closure: function (): void {
    $user = User::factory()->create();

    $this
        ->actingAs(user: $user, guard: 'api')
        ->postJson(uri: '/api/auth/logout')
        ->assertStatus(status: 200);
});


