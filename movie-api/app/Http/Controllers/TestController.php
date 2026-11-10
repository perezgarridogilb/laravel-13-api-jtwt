<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends Controller
{
    use ApiResponse;

    /**
     * Muestra la vista de prueba.
     */
    public function __invoke(): View
    {
        return view('test', [
            // Título de la vista (en blanco por ahora).
            'titulo' => '',
            // Nombre del JS que vive en public/.
            'js' => 'test.js',
        ]);
    }

    /**
     * Recibe un valor y dice si es número, texto o combinado.
     */
    public function transform(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'value' => ['required', 'string'],
        ], [
            'value.required' => 'El valor no puede estar vacío',
        ]);

        $value = trim($validated['value']);

        $resultado = match (true) {
            // Solo números, con signo y decimal opcional: 123, -45, 3.14
            preg_match('/^[+-]?\d+([.,]\d+)?$/', $value) === 1 => 'Es número',
            // Solo letras y espacios: "hola", "Ciencia Ficción"
            preg_match('/^[\p{L}\s]+$/u', $value) === 1 => 'Es texto',
            // Mezcla de letras, números o símbolos: "abc123", "hola!"
            default => 'Es ambos (combinado)',
        };

        return $this->successResponse($resultado, 'Éxito');
    }
}
