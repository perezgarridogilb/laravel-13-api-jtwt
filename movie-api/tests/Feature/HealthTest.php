<?php

test(description: 'returns ok on health endpoint', closure: function (): void {
    $response = $this->get(uri: '/api/health');

    $response->assertStatus(status: 200);
    $response->assertJson(value: ['data' => ['status' => 'OK', 'app' => 'MovieAPI'], 'message' => 'Health check']);
});
