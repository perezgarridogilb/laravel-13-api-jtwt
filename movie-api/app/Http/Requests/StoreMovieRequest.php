<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
{
    return [
        'title' => ['required', 'string', 'max:255', 'unique:movies,title'],
        'year' => ['required', 'integer', 'between:1888,2100'],
        'synopsis' => ['nullable', 'string'],
        'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
        'director' => ['nullable', 'string', 'max:255'],
        'duration' => ['nullable', 'integer', 'min:1'],
        // 7. poster - opcional, texto (referencia/ruta de la imagen)
        'poster' => ['nullable', 'string'],
        // 8. is_active - opcional, booleano
        'is_active' => ['nullable', 'boolean'],
        // 9. genre_ids - opcional, debe ser un array
        'genre_ids' => ['nullable', 'array'],
        // 10. genre_ids.* - cada elemento del array debe ser un entero que exista en la tabla genres
        'genre_ids.*' => ['integer', 'exists:genres,id'],
    ];
    }
}
