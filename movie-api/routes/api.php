<?php

use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get(uri: '/health', action: HealthController::class);
