<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Repositories\Contracts\GenreRepositoryInterface;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request as HttpRequest;

class GenreController extends Controller
{
    use ApiResponse;
    public function __construct(private GenreRepositoryInterface $genres) {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(HttpRequest  $request): JsonResponse
    {
        $genres = $this->genres->filter(
            filters: $request->only(['search','is_active']),
            sortBy: $request->input('sort_by','name'),
            order: $request->input('order','asc'),
        );
        return $this->successResponse(GenreResource::collection($genres));
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
        $genre =$this->genres->create($request->validated());
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
        $this->genres->update($genre, $request->validated());
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
    public function showBySlug(string $slug): JsonResponse
{
    $genre = $this->genres->findBySlugOrFail($slug);
    return $this->successResponse(new GenreResource($genre));
}
    public function restore(int $id): JsonResponse
    {
        $this->genres->restore($id);

        return $this->successResponse(null, 'Género restaurado exitosamente.');
    }
}
