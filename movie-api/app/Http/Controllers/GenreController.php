<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $genres = Genre::all();
        return $this->successResponse(GenreResource::collection(resource: $genres));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenreRequest $request): JsonResponse
    {
        $genre = Genre::create($request->validated());
        return $this->successResponse(new GenreResource($genre), "Género creado exitosamente", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre): JsonResponse
    {
        return $this->successResponse(new GenreResource($genre), "Genero encontrado");
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Genre $genre)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenreRequest $request, Genre $genre): JsonResponse
    {
        $genre->update($request->validated());
        return $this->successResponse(new GenreResource($genre), "Genero actualizado");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return $this->successResponse(null, "Género eliminado exitosamente");
    }
}
