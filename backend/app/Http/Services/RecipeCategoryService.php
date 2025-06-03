<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Repositories\Recipes\RecipeRepositoryInterface;
use App\Repositories\Recipes\CategoryRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class RecipeCategoryService
 *
 * Handles the business logic for recipe-category relationships.
 */
final class RecipeCategoryService
{
    /**
     * Create a new RecipeCategoryService instance.
     */
    public function __construct(
        private readonly RecipeRepositoryInterface $recipes,
        private readonly CategoryRepositoryInterface $categories,
    ) {}

    /**
     * Attach a category to a recipe.
     *
     * @param string $recipeId The recipe ID
     * @param string $categoryId The category ID to attach
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When attachment fails
     */
    public function attachCategory(string $recipeId, string $categoryId): ServiceResponse
    {
        try {
            $recipe = $this->recipes->find($recipeId);
            $this->categories->find($categoryId);

            $recipe->categories()->syncWithoutDetaching([$categoryId]);

            return new ServiceResponse(
                true,
                null,
                'Category attached to recipe successfully'
            );
        } catch (Exception $e) {
            Log::error('Failed to attach category to recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipeId,
                'category_id' => $categoryId
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to attach category to recipe'
            );
        }
    }

    /**
     * Detach a category from a recipe.
     *
     * @param string $recipeId The recipe ID
     * @param string $categoryId The category ID to detach
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When detachment fails
     */
    public function detachCategory(string $recipeId, string $categoryId): ServiceResponse
    {
        try {
            $recipe = $this->recipes->find($recipeId);
            $recipe->categories()->detach($categoryId);

            return new ServiceResponse(
                true,
                null,
                'Category detached from recipe successfully'
            );
        } catch (Exception $e) {
            Log::error('Failed to detach category from recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipeId,
                'category_id' => $categoryId
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to detach category from recipe'
            );
        }
    }
}
