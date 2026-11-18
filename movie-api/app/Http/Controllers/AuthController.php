<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        try {
            $token = Auth::guard('api')->login($user);
            if (!$token) {
                return $this->errorResponse("No se pudo generar el token de acceso", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return $this->successResponse($this->tokenPayload($user, $token), "Usuario registrado exitosamente.", Response::HTTP_CREATED);
        } catch (JWTException $e) {
            return $this->errorResponse("Error al generar el token", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tokenPayload(User $user, string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => new UserResource($user)
        ];
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = Auth::guard('api')->attempt(credentials: $credentials)) {
            return $this->errorResponse(message: "Credenciales incorrectas.", status: Response::HTTP_UNAUTHORIZED);
        }

        return $this->successResponse(
            data: $this->tokenPayload(user: Auth::guard('api')->user(), token: $token)
        );
    }
    public function me(): JsonResponse
    {
        return $this->successResponse(data: new UserResource(resource: Auth::guard(name: 'api')->user()));
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return $this->successResponse(data: null, message: 'Sesión cerrada exitosamente.');
    }

    public function refresh(): JsonResponse
    {
        $token = Auth::guard('api')->refresh();

        return $this->successResponse(
            data: $this->tokenPayload(user: Auth::guard('api')->user(), token: $token)
        );
    }
}
