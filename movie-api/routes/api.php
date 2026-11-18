<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get(uri: '/health', action: HealthController::class);
Route::post('/transform', [TestController::class, 'transform']);

Route::get('genres/slug/{slug}', [GenreController::class, 'showBySlug'])->name('genres.showBySlug');
Route::patch('genres/{id}/restore', [GenreController::class, 'restore'])->name('genres.restore');

Route::apiResource('genres', GenreController::class);
Route::apiResource('movies', MovieController::class);

// Públicas
Route::prefix('auth')->group(callback: function (): void {
    Route::post(uri: 'register', action: [AuthController::class, 'register']);
    Route::post(uri: 'login', action: [AuthController::class, 'login']);
});

// Protegidas
Route::prefix('auth:api')->group(callback: function (): void {
    Route::get(uri: 'me', action: [AuthController::class, 'me']);
    Route::post(uri: 'logout', action: [AuthController::class, 'logout']);
    Route::post(uri: 'refresh', action: [AuthController::class, 'refresh']);
});