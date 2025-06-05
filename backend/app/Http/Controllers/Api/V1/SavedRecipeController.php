<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSavedRecipesRequest;
use App\Http\Services\SavedRecipeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller for managing saved recipe operations.
 */
final class SavedRecipeController extends Controller
{
    /**
     * @var SavedRecipeService
     */
    private SavedRecipeService $service;

    /**
     * @param SavedRecipeService $service
     */
    public function __construct(SavedRecipeService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all saved recipes for the authenticated user.
     */
    public function index(): AnonymousResourceCollection
    {
        return $this->service->getSavedRecipes();
    }

    /**
     * Save a recipe for the authenticated user.
     *
     * @param StoreSavedRecipesRequest $request
     */
    public function store(StoreSavedRecipesRequest $request): JsonResponse
    {
        return $this->service->saveRecipe($request);
    }

    /**
     * Check if a recipe is saved by the authenticated user.
     *
     * @param string $recipeId
     */
    public function show(string $recipeId): JsonResponse
    {
        return $this->service->isRecipeSaved($recipeId);
    }

    /**
     * Remove a saved recipe for the authenticated user.
     *
     * @param string $recipeId
     */
    public function destroy(string $recipeId): JsonResponse
    {
        return $this->service->removeSavedRecipe($recipeId);
    }
}
