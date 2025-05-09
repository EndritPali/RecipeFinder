<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'instructions' => $this->instructions,
            'category' => $this->categories->first(),
            'rating' => $this->rating,
            'created_by' => ($this->creator->username ?? 'Unknown')
                . ' (' . ($this->creator->role ?? 'No Role') . ')',
            'ingredients' => $this->ingredients->pluck('name'),
            'image_url' => $this->image_url,
            'preparation_time' => $this->preparation_time,
            'servings' => $this->servings,
            'cooking_time' => $this->cooking_time,
            'created_at' => $this->created_at,
        ];
    }
}
