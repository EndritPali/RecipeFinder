<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreSavedRecipesRequest;
use App\Http\Resources\RecipeResource;
use App\Http\Services\SavedRecipeService;
use App\Models\SavedRecipe;
use App\Repositories\Recipes\SavedRecipeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller for managing saved recipe operations.
 *
 * This controller handles HTTP requests for saving, listing, and removing saved recipes,
 * following REST principles and Single Responsibility Pattern.
 *
 * @group SavedRecipes
 */
final class SavedRecipeController extends ApiController
{
    /**
     * @var SavedRecipeRepositoryInterface
     */
    private $savedRecipeRepository;

    /**
     * Create a new controller instance.
     *
     * @param SavedRecipeRepositoryInterface $savedRecipeRepository
     */
    public function __construct(
        SavedRecipeRepositoryInterface $savedRecipeRepository
    ) {
        $this->savedRecipeRepository = $savedRecipeRepository;
    }

    /**
     * Display a listing of saved recipes for the authenticated user.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $savedRecipes = $this->savedRecipeRepository->getByUserId((string)$user->id);

        return RecipeResource::collection($savedRecipes->map(fn($saved) => $saved->recipe));
    }

    /**
     * Save a recipe for the authenticated user.
     *
     * @param StoreSavedRecipesRequest $request
     * @return RecipeResource|JsonResponse
     */
    public function store(StoreSavedRecipesRequest $request): RecipeResource|JsonResponse
    {
        $this->authorize('create', SavedRecipe::class);

        $response = SavedRecipeService::store($request);

        if ($response->success()) {
            return new RecipeResource($response->getModel()->recipe);
        }

        $statusCode = $response->getMessage() === 'Unauthorized' ? 401 : 400;
        return response()->json(['message' => $response->getMessage()], $statusCode);
    }

    /**
     * Display a specific saved recipe for the authenticated user.
     *
     * @param Request $request
     * @param string $recipeId
     * @return RecipeResource|JsonResponse
     */
    public function show(Request $request, string $recipeId): RecipeResource|JsonResponse
    {
        $response = SavedRecipeService::getById($request, $recipeId);

        if ($response->success()) {
            return new RecipeResource($response->getModel()->recipe);
        }

        return response()->json(['message' => $response->getMessage()], 404);
    }

    /**
     * Remove a saved recipe for the authenticated user.
     *
     * @param Request $request
     * @param string $recipeId
     * @return JsonResponse
     */
    public function destroy(Request $request, string $recipeId): JsonResponse
    {
        $this->authorize('delete', SavedRecipe::class);

        $response = SavedRecipeService::destroy($request, $recipeId);

        if ($response->success()) {
            return response()->json(['message' => 'Recipe removed from saved list.'], 200);
        }

        $statusCode = $response->getMessage() === 'Unauthorized' ? 401 : 400;
        return response()->json(['message' => $response->getMessage()], $statusCode);
    }
}