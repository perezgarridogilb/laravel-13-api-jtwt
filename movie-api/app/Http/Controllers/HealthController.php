<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class HealthController extends Controller
{
    use ApiResponse;

    public function __invoke(): JsonResponse
    {
        return $this->successResponse(data: ['status' => 'OK', 'app' => 'MovieAPI'], message: 'Health check');
    }
}
