<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AttachCategoryRequest;
use App\Http\Services\RecipeCategoryService;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;

/**
 * Class RecipeCategoryController
 *
 * Handles HTTP requests related to recipe-category relationships.
 */
final class RecipeCategoryController extends ApiController
{

    /**
     * Attach a category to a recipe.
     *
     * @param AttachCategoryRequest $request The validated request
     * @param Recipe $recipe The recipe model instance
     * @return JsonResponse Response indicating success or failure
     */
    public function store(AttachCategoryRequest $request, Recipe $recipe): JsonResponse
    {
        $response = RecipeCategoryService::attachCategory($recipe, $request->validated('category_id'));

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
     * @param Recipe $recipe The recipe model instance
     * @param Category $category The category model instance
     * @return JsonResponse Response indicating success or failure
     */
    public function destroy(Recipe $recipe, Category $category): JsonResponse
    {
        $response = RecipeCategoryService::detachCategory($recipe, $category);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => $response->getMessage()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }
}
