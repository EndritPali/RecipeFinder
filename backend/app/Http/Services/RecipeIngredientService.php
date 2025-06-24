<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Requests\Api\V1\AttachIngredientRequest;
use App\Http\Requests\Api\V1\UpdateRecipeIngredientRequest;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Repositories\Recipes\RecipeRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class RecipeIngredientService
 *
 * Handles the business logic for recipe-ingredient relationships.
 */
final class RecipeIngredientService
{
    private static $recipeRepository;

    /**
     * Create a new RecipeIngredientService instance.
     */
    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
    ) {
        self::$recipeRepository = $recipeRepository;
    }

    /**
     * Attach an ingredient to a recipe.
     *
     * @param Recipe $recipe The recipe model instance
     * @param AttachIngredientRequest $request The validated request containing ingredient data
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When attachment fails
     */
    public static function attachIngredient(Recipe $recipe, AttachIngredientRequest $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe->ingredients()->attach($request->validated('ingredient_id'), [
                'quantity' => $request->validated('quantity')
            ]);

            DB::commit();

            return new ServiceResponse(
                true,
                null,
                'Ingredient attached successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('recipeslog')->error('Failed to attach ingredient to recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipe->id,
                'request' => $request->validated()
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to attach ingredient to recipe'
            );
        }
    }

    /**
     * Update the quantity of an ingredient in a recipe.
     *
     * @param Recipe $recipe The recipe model instance
     * @param Ingredient $ingredient The ingredient model instance
     * @param UpdateRecipeIngredientRequest $request The validated request containing quantity data
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When update fails
     */
    public static function updateQuantity(Recipe $recipe, Ingredient $ingredient, UpdateRecipeIngredientRequest $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            if (!$recipe->ingredients()->where('ingredients.id', $ingredient->id)->exists()) {
                return new ServiceResponse(
                    false,
                    null,
                    'Ingredient not found in recipe'
                );
            }

            $recipe->ingredients()->updateExistingPivot($ingredient->id, [
                'quantity' => $request->validated('quantity')
            ]);

            DB::commit();

            return new ServiceResponse(
                true,
                null,
                'Ingredient quantity updated successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('recipeslog')->error('Failed to update ingredient quantity', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredient->id,
                'request' => $request->validated()
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to update ingredient quantity'
            );
        }
    }

    /**
     * Detach an ingredient from a recipe.
     *
     * @param Recipe $recipe The recipe model instance
     * @param Ingredient $ingredient The ingredient ID to detach
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When detachment fails
     */
    public static function detachIngredient(Recipe $recipe, Ingredient $ingredient): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe->ingredients()->detach($ingredient->id);

            DB::commit();

            return new ServiceResponse(
                true,
                null,
                'Ingredient detached from recipe successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('recipeslog')->error('Failed to detach ingredient from recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredient->id
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to detach ingredient from recipe'
            );
        }
    }
}
