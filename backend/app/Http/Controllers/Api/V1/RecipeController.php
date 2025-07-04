<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\RecipeCreated;
use App\Http\Requests\Api\V1\StoreRecipeRequest;
use App\Http\Requests\Api\V1\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Http\Services\RecipeService;
use App\Models\Recipe;
use App\Models\User;
use App\Repositories\Recipes\RecipeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
            // $recipe = $response->getModel();
            // broadcast(new RecipeCreated($recipe));
            return new RecipeResource($response->getModel());
        }

        $statusCode = $response->getMessage() === 'Unauthorized' ? 401 : 400;
        return response()->json(['message' => $response->getMessage()], $statusCode);
    }

    /**
     * Display the specified recipe.
     *
     * @param string $id
     * @return RecipeResource|JsonResponse
     */
    public function show(string $id): RecipeResource|JsonResponse
    {
        $response = RecipeService::getById($id);

        if ($response->success()) {
            return new RecipeResource($response->getModel());
        }

        return response()->json(['message' => $response->getMessage()], 404);
    }

    /**
     * Update the specified recipe.
     *
     * @param UpdateRecipeRequest $request
     * @param string $id
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
     * @param string $id
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
}
