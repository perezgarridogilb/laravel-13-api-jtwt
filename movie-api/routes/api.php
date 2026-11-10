<?php

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