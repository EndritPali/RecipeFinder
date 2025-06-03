<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AttachCategoryRequest;
use App\Http\Services\RecipeCategoryService;
use Illuminate\Http\JsonResponse;

/**
 * Class RecipeCategoryController
 *
 * Handles HTTP requests related to recipe-category relationships.
 */
final class RecipeCategoryController extends ApiController
{
    /**
     * Create a new RecipeCategoryController instance.
     */
    public function __construct(
        private readonly RecipeCategoryService $service,
    ) {}

    /**
     * Attach a category to a recipe.
     *
     * @param AttachCategoryRequest $request The validated request
     * @param string $recipeId The recipe ID
     * @return JsonResponse Response indicating success or failure
     */
    public function store(AttachCategoryRequest $request, string $recipeId): JsonResponse
    {
        $response = $this->service->attachCategory($recipeId, $request->validated('category_id'));

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => $response->getMessage()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Detach a category from a recipe.
     *
     * @param string $recipeId The recipe ID
     * @param string $categoryId The category ID
     * @return JsonResponse Response indicating success or failure
     */
    public function destroy(string $recipeId, string $categoryId): JsonResponse
    {
        $response = $this->service->detachCategory($recipeId, $categoryId);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => $response->getMessage()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }
}
