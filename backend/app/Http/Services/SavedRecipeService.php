<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Requests\Api\V1\StoreSavedRecipesRequest;
use App\Http\Resources\RecipeResource;
use App\Models\SavedRecipe;
use App\Repositories\Recipes\SavedRecipeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

/**
 * Service class for managing saved recipe operations.
 */
final class SavedRecipeService
{
    /**
     * @var SavedRecipeRepositoryInterface
     */
    private SavedRecipeRepositoryInterface $savedRecipeRepo;

    /**
     * @param SavedRecipeRepositoryInterface $savedRecipeRepo
     */
    public function __construct(SavedRecipeRepositoryInterface $savedRecipeRepo)
    {
        $this->savedRecipeRepo = $savedRecipeRepo;
    }

    /**
     * Get all saved recipes for the authenticated user.
     */
    public function getSavedRecipes(): AnonymousResourceCollection
    {
        $userId = (string) Auth::id();
        $savedRecipes = $this->savedRecipeRepo->getByUserId($userId);

        return RecipeResource::collection($savedRecipes->map(function ($savedRecipe) {
            $recipe = $savedRecipe->recipe;
            $recipe->saved_id = $savedRecipe->id;
            return $recipe;
        }));
    }

    /**
     * Save a recipe for the authenticated user.
     *
     * @param StoreSavedRecipesRequest $request
     */
    public function saveRecipe(StoreSavedRecipesRequest $request): JsonResponse
    {
        $userId = (string) Auth::id();
        $recipeId = (string) $request->recipe_id;

        if ($this->savedRecipeRepo->exists($userId, $recipeId)) {
            return response()->json(['message' => 'Recipe already saved!'], 409);
        }

        $saved = $this->savedRecipeRepo->create($userId, $recipeId);

        return response()->json(new RecipeResource($saved->recipe), 201);
    }

    /**
     * Check if a recipe is saved by the authenticated user.
     *
     * @param string $recipeId
     */
    public function isRecipeSaved(string $recipeId): JsonResponse
    {
        $userId = (string) Auth::id();
        $exists = $this->savedRecipeRepo->exists($userId, $recipeId);

        return response()->json(['saved' => $exists]);
    }

    /**
     * Remove a saved recipe for the authenticated user.
     *
     * @param string $recipeId
     */
    public function removeSavedRecipe(string $recipeId): JsonResponse
    {
        $userId = (string) Auth::id();
        $saved = $this->savedRecipeRepo->get($userId, $recipeId);

        if (!$saved) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $this->savedRecipeRepo->delete($saved);

        return response()->json(['message' => 'Recipe removed from saved list']);
    }
}
