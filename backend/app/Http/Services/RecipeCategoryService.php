<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Recipe;
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
    private static $recipeRepository;
    private static $catagoryRepository;
    /**
     * Create a new RecipeCategoryService instance.
     */
    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
        CategoryRepositoryInterface $catagoryRepository,
    ) {
        self::$catagoryRepository = $catagoryRepository;
        self::$recipeRepository = $recipeRepository;
    }

    /**
     * Attach a category to a recipe.
     *
     * @param Recipe $recipe The recipe model instance
     * @param string $categoryId The category ID to attach
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When attachment fails
     */
    public static function attachCategory(Recipe $recipe, string $categoryId): ServiceResponse
    {
        try {
            self::$catagoryRepository->find($categoryId);

            $recipe->categories()->syncWithoutDetaching([$categoryId]);

            return new ServiceResponse(
                true,
                null,
                'Category attached to recipe successfully'
            );
        } catch (Exception $e) {
            Log::channel('recipeslog')->error('Failed to attach category to recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipe->id,
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
     * @param Recipe $recipe The recipe model instance
     * @param Category $category The category model instance
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When detachment fails
     */
    public static function detachCategory(Recipe $recipe, Category $category): ServiceResponse
    {
        try {
            $recipe->categories()->detach($category->id);

            return new ServiceResponse(
                true,
                null,
                'Category detached from recipe successfully'
            );
        } catch (Exception $e) {
            Log::channel('recipeslog')->error('Failed to detach category from recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipe->id,
                'category_id' => $category->id
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to detach category from recipe'
            );
        }
    }
}
