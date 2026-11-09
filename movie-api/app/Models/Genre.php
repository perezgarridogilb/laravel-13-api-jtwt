<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Genre extends Model
{
    /** @use HasFactory<\Database\Factories\GenreFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Genre $genre): void {
            if (empty($genre->slug)) {
                $genre->slug = Str::slug($genre->name);
            }
        });
        static::updating(function (Genre $genre): void {
            if ($genre->isDirty('name')) {
                $genre->slug = Str::slug($genre->name);
            }
        });
    }
        public function movies() : BelongsToMany {
        return $this->belongsToMany(Movie::class);
    }
}
