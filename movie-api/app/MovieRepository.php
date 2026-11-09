<?php

namespace App;

use App\Models\Movie;
use App\Repositories\Contracts\MovieRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class MovieRepository extends BaseRepository implements MovieRepositoryInterface
{
    private const SORTABLE = ['title', 'rating', 'crated_at'];
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters, string $sortBy = 'name', string $order = 'asc'): Collection
    {
        $query = Movie::with(relations: 'genres');

        $query->when($filters['search'] ?? null, function (Builder $q, $search) {
            return $q->whereRaw(sql: 'LOWER(title) LIKE ?', bindings: ['%' . strtolower(string: $search) . '%']);
        });

        $query->when($filters['year'] ?? null, function (Builder $q, $year) {
            return $q->where(column: 'year', operator: $year);
        });

        $query->when($filters['min_rating'] ?? null, function (Builder $q, $minRating) {
            return $q->where(column: 'rating', operator: '>=', value: $minRating);
        });

        // equivalencia
        /*         
        $query->when($filters['genre_id'] ?? null, function (Builder $q, $genreId) {
            return $q->select('movies.*')
                ->join('genre_movie', 'genre_movie.movie_id', '=', 'movies.id')
                ->where('genre_movie.genre_id', $genreId);
        }); 
        */

        /**
        * SELECT * 
        * FROM `movies` 
        * WHERE EXISTS (
        * -- ==========================================
        * -- INICIO: Todo esto equivale al bloque que 
        * -- recibe la función callback como $subQuery
        * -- ==========================================
        * SELECT * 
        * FROM `genres` 
        * INNER JOIN `genre_movie` ON `genres`.`id` = `genre_movie`.`genre_id` 
        * WHERE `genre_movie`.`movie_id` = `movies`.`id` -- (Generado automáticamente por Eloquent para unir la relación)
        * AND `genres`.`id` = ?                       -- <-- Esto es exactamente tu: $subQuery->where('genres.id', $genreId)
        * -- ==========================================
        * -- FIN DEL $subQuery
        * -- ==========================================
        * );
         */
        $query->when(value: $filters['genre_id'] ?? null, callback: function (Builder $q, $genreId) {
            // Le indica a la consulta principal de `Movie` que filtre únicamente las películas que tengan una relación llamada `genres` que cumpla con la condición
            return $q->whereHas('genres', function (Builder $subQuery) use ($genreId) {
                $subQuery->where('genres.id', $genreId);
            });
        });

        $sortColumn = in_array($sortBy, self::SORTABLE) ? $sortBy : 'title';
        $order = $order === 'desc' ? 'desc':'asc';

        return $query->orderBy($sortColumn, $order)->get();
        //     $sortBy = in_array($sortBy, self::SORTABLE) ? $sortBy : 'name';
        //     $order = strtolower($order) === 'desc'? 'desc':'asc';
        //     return Genre::query()
        //     ->when(isset($filters['search']),
        //         fn($q) => $q->whereRaw('LOWER(name) LIKE ?',['%'.strtolower($filters['search']).'%'])
        //     )
        //      ->when(isset($filters['is_active']),
        //         fn($q) => $q->where('is_active',filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN))
        //     )
        //     ->orderBy($sortBy,$order)
        //     ->get();
        // }

        //     public function findBySlugOrFail(string $slug): Model
        // {
        //     return Genre::where('slug', $slug)->firstOrFail();
        // }
        //     public function restore(int $id): Model
        // {
        //     $genre = Genre::withTrashed()->findOrFail($id);
        //     $genre->restore();

        return $genre->refresh();
    }

    public function syncGenres(Movie $movie, array $genreIds): void {}
}
