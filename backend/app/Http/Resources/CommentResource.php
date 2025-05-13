<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->username,
            'creator_id' => $this->user->id,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'likes' => $this->likes
        ];
    }
}
