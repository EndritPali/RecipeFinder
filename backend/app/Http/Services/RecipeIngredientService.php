<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Requests\Api\V1\AttachIngredientRequest;
use App\Http\Requests\Api\V1\UpdateRecipeIngredientRequest;
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
    /**
     * Create a new RecipeIngredientService instance.
     */
    public function __construct(
        private readonly RecipeRepositoryInterface $recipes,
    ) {}

    /**
     * Attach an ingredient to a recipe.
     *
     * @param string $recipeId The recipe ID
     * @param AttachIngredientRequest $request The validated request containing ingredient data
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When attachment fails
     */
    public function attachIngredient(string $recipeId, AttachIngredientRequest $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe = $this->recipes->find($recipeId);
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
            Log::error('Failed to attach ingredient to recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipeId,
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
     * @param string $recipeId The recipe ID
     * @param string $ingredientId The ingredient ID
     * @param UpdateRecipeIngredientRequest $request The validated request containing quantity data
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When update fails
     */
    public function updateQuantity(string $recipeId, string $ingredientId, UpdateRecipeIngredientRequest $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe = $this->recipes->find($recipeId);

            if (!$recipe->ingredients()->where('ingredients.id', $ingredientId)->exists()) {
                return new ServiceResponse(
                    false,
                    null,
                    'Ingredient not found in recipe'
                );
            }

            $recipe->ingredients()->updateExistingPivot($ingredientId, [
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
            Log::error('Failed to update ingredient quantity', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipeId,
                'ingredient_id' => $ingredientId,
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
     * @param string $recipeId The recipe ID
     * @param string $ingredientId The ingredient ID to detach
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When detachment fails
     */
    public function detachIngredient(string $recipeId, string $ingredientId): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe = $this->recipes->find($recipeId);
            $recipe->ingredients()->detach($ingredientId);

            DB::commit();

            return new ServiceResponse(
                true,
                null,
                'Ingredient detached from recipe successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to detach ingredient from recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_id' => $recipeId,
                'ingredient_id' => $ingredientId
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to detach ingredient from recipe'
            );
        }
    }
}
