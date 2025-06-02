<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreRecipeRequest;
use App\Http\Requests\Api\V1\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Http\Services\RecipeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Recipes
 *
 * Endpoints for managing recipes.
 */
class RecipeController extends ApiController
{
    /**
     * Recipe service instance.
     *
     * @var RecipeService
     */
    private RecipeService $recipeService;

    /**
     * Create a new controller instance.
     *
     * @param RecipeService $recipeService
     */
    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    /**
     * Get a list of all recipes.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $response = $this->recipeService->getAll();

        if ($response->success()) {
            return RecipeResource::collection($response->getModel());
        }

        return response()->json(['message' => $response->getMessage()], 400);
    }

    /**
     * Store a newly created recipe.
     *
     * @param StoreRecipeRequest $request
     * @return RecipeResource|JsonResponse
     */
    public function store(StoreRecipeRequest $request): RecipeResource|JsonResponse
    {
        $response = $this->recipeService->store($request);

        if ($response->success()) {
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
        $response = $this->recipeService->getById($id);

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
    public function update(UpdateRecipeRequest $request, string $id): RecipeResource|JsonResponse
    {
        $response = $this->recipeService->update($request, $id);

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
    public function destroy(Request $request, string $id): JsonResponse
    {
        $response = $this->recipeService->destroy($request, $id);

        if ($response->success()) {
            return response()->json(['message' => 'Recipe deleted successfully.'], 200);
        }

        $statusCode = $response->getMessage() === 'Permission denied' ? 403 : 400;
        return response()->json(['message' => $response->getMessage()], $statusCode);
    }
}
