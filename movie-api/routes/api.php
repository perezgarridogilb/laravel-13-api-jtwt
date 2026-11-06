<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get(uri: '/health', action: HealthController::class);
Route::apiResource('genres', GenreController::class);