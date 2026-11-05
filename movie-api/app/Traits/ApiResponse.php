<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiResponse
{
    protected function successResponse(mixed $data, string $message = '', int $status = 200): JsonResponse
    {
        $payload = ['data' => $data];
        if ($message != '') {
            $payload['message'] = $message;
        }

        return response()->json(data: $payload, status: $status);
    }

    protected function errorResponse(string $message, int $status, mixed $errors = null): JsonResponse
    {
        $payload = ['message' => $message];
        if ($errors != null) {
            $payload['errors'] = $errors;
        }

        return response()->json(data: $payload, status: $status);
    }
}
