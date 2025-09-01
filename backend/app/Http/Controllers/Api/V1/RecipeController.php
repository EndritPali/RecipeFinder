<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreRecipeRequest;
use App\Http\Requests\Api\V1\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Http\Services\RecipeService;
use App\Models\Recipe;
use App\Repositories\Recipes\RecipeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Api\V1\StoreSavedRecipesRequest;
use App\Models\SavedRecipe;

/**
 * @group Recipes
 *
 * Endpoints for managing recipes.
 */
final class RecipeController extends ApiController
{

    /**
     * Summary of recipeRepository

     * @var RecipeRepositoryInterface 
     */
    private $recipeRepository;

    /**
     * Create a new controller instance.
     *
     * @param RecipeService $recipeService
     * @param RecipeRepositoryInterface $recipeRepository
     */
    public function __construct(
        RecipeRepositoryInterface $recipeRepository
    ) {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Get a list of all recipes.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $perPage = $request->get('per_page') ? (int) $request->get('per_page') : null;

        if ($request->boolean('mine')) {
            $user = $request->user();
            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            $response = RecipeService::getByUserPaginated($user, $perPage);
            if ($response->success()) {
                return RecipeResource::collection($response->getModel());
            }
            return $this->errorResponse($response->getMessage() ?? 'Failed to fetch recipes', 500);
        }

        $paginatedRecipes = $this->recipeRepository->getPaginated($perPage);
        return RecipeResource::collection($paginatedRecipes);
    }

    /**
     * Store a newly created recipe.
     *
     * @param StoreRecipeRequest $request
     * @return RecipeResource|JsonResponse
     */
    public function store(StoreRecipeRequest $request): RecipeResource|JsonResponse
    {
        $response = RecipeService::store($request);

        if ($response->success()) {
            return new RecipeResource($response->getModel());
        }

        $statusCode = $response->getMessage() === 'Unauthorized' ? 401 : 400;
        return response()->json(['message' => $response->getMessage()], $statusCode);
    }

    /**
     * Display the specified recipe.
     *
     * @param Recipe $recipe
     * @return RecipeResource
     */
    public function show(Recipe $recipe): RecipeResource
    {
        return new RecipeResource($recipe);
    }

    /**
     * Update the specified recipe.
     *
     * @param UpdateRecipeRequest $request
     * @param Recipe $recipe
     * @return RecipeResource|JsonResponse
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe): RecipeResource|JsonResponse
    {
        $this->authorize('update', $recipe);
        $response = RecipeService::update($request, $recipe->id);

        if ($response->success()) {
            return new RecipeResource($response->getModel());
        }

        $statusCode = $response->getMessage() === 'Permission denied' ? 403 : 400;
        return response()->json(['message' => $response->getMessage()], $statusCode);
    }

    /**
     * Delete the specified recipe.
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return JsonResponse
     */
    public function destroy(Request $request, Recipe $recipe): JsonResponse
    {
        $this->authorize('delete', $recipe);
        $response = RecipeService::destroy($request, $recipe->id);

        if ($response->success()) {
            return response()->json(['message' => 'Recipe deleted successfully.'], 200);
        }

        $statusCode = $response->getMessage() === 'Permission denied' ? 403 : 400;
        return response()->json(['message' => $response->getMessage()], $statusCode);
    }

    /**
     * Display a listing of saved recipes for the authenticated user.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function savedIndex(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $response = RecipeService::getSavedByUser($user);

        return RecipeResource::collection($response->getModel());
    }

    /**
     * Save a recipe for the authenticated user.
     *
     * @param StoreSavedRecipesRequest $request
     * @return RecipeResource|JsonResponse
     */
    public function saveRecipe(StoreSavedRecipesRequest $request): RecipeResource|JsonResponse
    {
        $this->authorize('create', SavedRecipe::class);

        $response = RecipeService::saveForUser($request);

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
     * @param Recipe $recipe
     * @return RecipeResource|JsonResponse
     */
    public function savedShow(Request $request, Recipe $recipe): RecipeResource|JsonResponse
    {
        $response = RecipeService::getSavedEntry($request->user(), $recipe);

        if ($response->success()) {
            return new RecipeResource($response->getModel()->recipe);
        }

        return response()->json(['message' => $response->getMessage()], 404);
    }

    /**
     * Remove a saved recipe for the authenticated user.
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return JsonResponse
     */
    public function savedDestroy(Request $request, Recipe $recipe): JsonResponse
    {
        $this->authorize('delete', SavedRecipe::class);

        $response = RecipeService::removeSaved($request->user(), $recipe);

        if ($response->success()) {
            return response()->json(['message' => 'Recipe removed from saved list.'], 200);
        }

        $statusCode = $response->getMessage() === 'Unauthorized' ? 401 : 400;
        return response()->json(['message' => $response->getMessage()], $statusCode);
    }
}
