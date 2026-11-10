<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Repositories\Contracts\MovieRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MovieController extends Controller
{
    use ApiResponse;

    public function __construct(private MovieRepositoryInterface $movieRepository) {

    }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
