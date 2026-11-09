<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'synopsis',
        'year',
        'rating',
        'director',
        'duration',
        'poster',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'rating' => 'decimal:1',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Movie $movie): void {
            if (empty($movie->slug)) {
                $movie->slug = Str::slug($movie->title);
            }
        });

        static::updating(function (Movie $movie): void {
            if ($movie->isDirty('title')) {
                $movie->slug = Str::slug($movie->title);
            }
        });
    }

    public function genres() : BelongsToMany {
        return $this->belongsToMany(Genre::class);
    }
}
