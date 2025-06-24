<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AttachIngredientRequest;
use App\Http\Requests\Api\V1\UpdateRecipeIngredientRequest;
use App\Http\Services\RecipeIngredientService;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;

/**
 * Class RecipeIngredientController
 *
 * Handles HTTP requests related to recipe-ingredient relationships.
 */
final class RecipeIngredientController extends ApiController
{
    /**
     * Attach an ingredient to a recipe.
     *
     * @param AttachIngredientRequest $request The validated request
     * @param Recipe $recipe The recipe model instance
     * @return JsonResponse Response indicating success or failure
     */
    public function store(AttachIngredientRequest $request, Recipe $recipe): JsonResponse
    {
        $response = RecipeIngredientService::attachIngredient($recipe, $request);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => $response->getMessage()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Update the quantity of an ingredient in a recipe.
     *
     * @param UpdateRecipeIngredientRequest $request The validated request
     * @param Recipe $recipe The recipe model instance
     * @param Ingredient $ingredient The ingredient model instance
     * @return JsonResponse Response indicating success or failure
     */
    public function update(UpdateRecipeIngredientRequest $request, Recipe $recipe, Ingredient $ingredient): JsonResponse
    {
        $response = RecipeIngredientService::updateQuantity($recipe, $ingredient, $request);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => $response->getMessage()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Detach an ingredient from a recipe.
     *
     * @param Recipe $recipe The recipe model instance
     * @param Ingredient $ingredient The ingredient model instance
     * @return JsonResponse Response indicating success or failure
     */
    public function destroy(Recipe $recipe, Ingredient $ingredient): JsonResponse
    {
        $response = RecipeIngredientService::detachIngredient($recipe, $ingredient);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => $response->getMessage()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }
}
