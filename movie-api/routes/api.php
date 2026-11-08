<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get(uri: '/health', action: HealthController::class);

Route::get('genres/slug/{slug}', [GenreController::class, 'showBySlug'])->name('genres.showBySlug');

Route::apiResource('genres', GenreController::class);