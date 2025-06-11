<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Repositories\Recipes\RecipeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class MyRecipesController
 *
 * Handles HTTP requests related to user's own recipes.
 */
final class MyRecipesController extends ApiController
{
    /**
     * Create a new MyRecipesController instance.
     */
    public function __construct(
        private readonly RecipeRepositoryInterface $recipes,
    ) {}

    /**
     * Get all recipes created by the authenticated user.
     *
     * @param Request $request The HTTP request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function myRecipes(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            $perPage = (int) $request->get('per_page', 15);
            if ($perPage < 1 || $perPage > 100) {
                $perPage = 15;
            }

            $recipes = $this->recipes->getByUserPaginated((string) $user->id, $perPage);
            return RecipeResource::collection($recipes);
        } catch (\Exception $e) {
            Log::error('Failed to fetch user recipes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user?->id ?? null
            ]);

            return $this->errorResponse('Failed to fetch recipes', 500);
        }
    }
}
