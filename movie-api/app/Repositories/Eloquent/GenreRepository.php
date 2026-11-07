<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre;
use App\Repositories\Contracts\GenreRepositoryInterface;
/**
 * 1. Se extiende BaseRepository que a su vez implementa BaseRepositoryInterface
 * 2. Se implementa el contrato GenreRepositoryInterface, que extiende el "CRUD" de BaseRepositoryInterface (más lo específico de Genre) y lo cumple al extender de BaseRepository (el punto 1)
 */
class GenreRepository extends BaseRepository implements GenreRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Genre $model)
    {
        parent::__construct($model);
    }
}
