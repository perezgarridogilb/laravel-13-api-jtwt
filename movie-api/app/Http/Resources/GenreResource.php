<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GenreResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
    'id' => $this->id,
    'name' => $this->name,
    'slug' => $this->slug,
    'description' => $this->description,
    'is_active' => $this->is_active,
    'created_at' => $this->created_at?->toISOString(),
    'updated_at' => $this->updated_at?->toISOString(),
];
    }
}
