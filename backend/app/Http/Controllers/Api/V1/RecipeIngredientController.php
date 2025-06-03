<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AttachIngredientRequest;
use App\Http\Requests\Api\V1\UpdateRecipeIngredientRequest;
use App\Http\Services\RecipeIngredientService;
use Illuminate\Http\JsonResponse;

/**
 * Class RecipeIngredientController
 *
 * Handles HTTP requests related to recipe-ingredient relationships.
 */
final class RecipeIngredientController extends ApiController
{
    /**
     * Create a new RecipeIngredientController instance.
     */
    public function __construct(
        private readonly RecipeIngredientService $service,
    ) {}

    /**
     * Attach an ingredient to a recipe.
     *
     * @param AttachIngredientRequest $request The validated request
     * @param string $recipeId The recipe ID
     * @return JsonResponse Response indicating success or failure
     */
    public function store(AttachIngredientRequest $request, string $recipeId): JsonResponse
    {
        $response = $this->service->attachIngredient($recipeId, $request);

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
     * @param string $recipeId The recipe ID
     * @param string $ingredientId The ingredient ID
     * @return JsonResponse Response indicating success or failure
     */
    public function update(UpdateRecipeIngredientRequest $request, string $recipeId, string $ingredientId): JsonResponse
    {
        $response = $this->service->updateQuantity($recipeId, $ingredientId, $request);

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
     * @param string $recipeId The recipe ID
     * @param string $ingredientId The ingredient ID
     * @return JsonResponse Response indicating success or failure
     */
    public function destroy(string $recipeId, string $ingredientId): JsonResponse
    {
        $response = $this->service->detachIngredient($recipeId, $ingredientId);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => $response->getMessage()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }
}
