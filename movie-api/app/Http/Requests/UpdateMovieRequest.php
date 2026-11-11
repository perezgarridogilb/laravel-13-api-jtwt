<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMovieRequest extends FormRequest
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
    return [
        // Revisa toda la tabla de películas para asegurarte de que el título no esté repetido... 
        // excepto en el registro con el ID que estamos modificando en este preciso momento
        'title' => ['sometimes', 'string', 'max:255', Rule::unique(table: 'movies', column: 'title')->ignore($this->route(param: 'movie'))],
        'year' => ['required', 'integer', 'between:1888,2100'],
        'synopsis' => ['sometimes', 'string'],
        'rating' => ['sometimes', 'numeric', 'min:0', 'max:10'],
        'director' => ['sometimes', 'string', 'max:255'],
        'duration' => ['sometimes', 'integer', 'min:1'],
        // 7. poster - opcional, texto (referencia/ruta de la imagen)
        'poster' => ['sometimes', 'string'],
        // 8. is_active - opcional, booleano
        'is_active' => ['sometimes', 'boolean'],
        // 9. genre_ids - opcional, debe ser un array
        'genre_ids' => ['sometimes', 'array'],
        // 10. genre_ids.* - cada elemento del array debe ser un entero que exista en la tabla genres
        'genre_ids.*' => ['integer', 'exists:genres,id'],
    ];
    }
}
