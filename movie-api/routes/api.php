<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get(uri: '/health', action: HealthController::class);

// Públicas
Route::prefix('auth')->group(callback: function (): void {
    Route::post(uri: 'register', action: [AuthController::class, 'register']);
    Route::post(uri: 'login', action: [AuthController::class, 'login']);
});

Route::post('/transform', [TestController::class, 'transform']);

// Géneros
Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/slug/{slug}', [GenreController::class, 'showBySlug']);
Route::get('/genres/{genre}', [GenreController::class, 'show']);
// - Route::get('genres/slug/{slug}', [GenreController::class, 'showBySlug'])->name('genres.showBySlug');
// Movies
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show']);

// Route::patch('genres/{id}/restore', [GenreController::class, 'restore'])->name('genres.restore');

// Route::apiResource('genres', GenreController::class);
// Route::apiResource('movies', MovieController::class);

// Protegidas
// dos puntos pide guard jwt
// Route::prefix('auth:api')->middleware('auth:api')->group(callback: function (): void {
//     Route::get(uri: 'me', action: [AuthController::class, 'me']);
//     Route::post(uri: 'logout', action: [AuthController::class, 'logout']);
//     Route::post(uri: 'refresh', action: [AuthController::class, 'refresh']);
// });
// Protegidas
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
    // Géneros
    Route::middleware('role:admin,editor')->group(function () {
        Route::post('genres', [GenreController::class, 'store']);
        Route::put('genres/{genre}', [GenreController::class, 'update']);
        Route::patch('genres/{genre}', [GenreController::class, 'update']);
    });
    Route::middleware('role:admin')->group(function () {
        Route::delete('genres/{genre}', [GenreController::class, 'destroy']);
        Route::post('/genres/{id}/restore', [GenreController::class, 'restore']);
    });

    // Películas
    Route::middleware('role:admin,editor')->group(function () {
        Route::post('movies', [MovieController::class, 'store']);
        Route::put('movies/{movie}', [MovieController::class, 'update']);
        Route::patch('movies/{movie}', [MovieController::class, 'update']);
    });
    Route::middleware('role:admin')->group(function () {
        Route::delete('movies/{movie}', [MovieController::class, 'destroy']);
    });
});