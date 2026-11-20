<?php
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
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});
