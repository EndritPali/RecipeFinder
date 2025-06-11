<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource class for transforming comment models into JSON responses.
 */
final class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The incoming HTTP request
     * @return array<string, mixed> The transformed resource
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

    /**
     * Customize the pagination information for the resource.
     *
     * @param Request $request The incoming HTTP request
     * @param array<string, mixed> $paginated The paginated array
     * @param array<string, mixed> $default The default array
     * @return array<string, mixed> The customized pagination information
     */
    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return [
            'meta' => [
                'current_page' => $paginated['current_page'],
                'last_page' => $paginated['last_page'],
                'per_page' => $paginated['per_page'],
                'total' => $paginated['total']
            ]
        ];
    }
}
