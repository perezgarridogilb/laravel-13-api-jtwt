<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Repositories\Contracts\MovieRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MovieController extends Controller
{
    use ApiResponse;

    public function __construct(private MovieRepositoryInterface $movieRepository) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(keys: ['search', 'year', 'min_rating', 'genre_id']);
        $sortBy = $request->input(key: 'sort_by', default: 'title');
        $order = $request->input(key: 'order', default: 'asc');
        $movies = $this->movieRepository->filter(filters: $filters, sortBy: $sortBy, order: $order);
        return $this->successResponse(data: MovieResource::collection($movies));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request)
    {
        // El objeto $request->safe() descarta por completo los campos extra (campo_basura_hack y _token) y 
        // te deja únicamente los campos que pasaron por la validación
        $movie = $this->movieRepository->create(data: $request->safe()->except(keys: 'genre_ids'));
        if ($request->has(key: 'genre_ids')) {
            $this->movieRepository->syncGenres(movie: $movie, genreIds: $request->genre_ids);
        }

        $movie->load('genres');
        return $this->successResponse(new MovieResource($movie), "Película creada exitosamente");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movie = $this->movieRepository->findOrFail($id);
        $movie->load('genres');
        return $this->successResponse(new MovieResource($movie), "Película consultada exitosamente");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $movie = $this->movieRepository->update(model: $movie, data: $request->safe()->except(keys: 'genre_ids'));
        if ($request->has(key: 'genre_ids')) {
            $this->movieRepository->syncGenres(movie: $movie, genreIds: $request->genre_ids);
        }
        $movie->load('genres');
        return $this->successResponse(data: new MovieResource(resource: $movie), message: "Película actualizada exitosamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
public function destroy(Movie $movie)
{
    $this->movieRepository->delete(model: $movie);
    return response()->noContent();
}
}
