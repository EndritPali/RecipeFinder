<?php

namespace App\Http\Services;

use App\Models\Recipe;
use App\Models\User;
use App\Repositories\Recipes\RecipeRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class RecipeService
 *
 * Handles all business logic related to recipe operations such as
 * creating, retrieving, updating, and deleting recipes.
 *
 * @package App\Http\Services
 */
final class RecipeService
{
    /**
     * Recipe repository for data access.
     */
    private static RecipeRepositoryInterface $recipeRepository;
    private static RecipeRelationService $relationService;

    /**
     * RecipeService constructor.
     *
     * @param RecipeRepositoryInterface $recipeRepository
     */
    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
        RecipeRelationService $recipeRelation
    ) {
        self::$recipeRepository = $recipeRepository;
        self::$relationService = $recipeRelation;
    }

    /**
     * Retrieve all recipes.
     *
     * @return ServiceResponse
     */
    public static function getAll(): ServiceResponse
    {
        try {
            $recipes = self::$recipeRepository->all();
            return new ServiceResponse(true, $recipes);
        } catch (Exception $e) {
            Log::error('RecipeService::getAll Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Store a new recipe in the database.
     *
     * @param Request $request
     * @return ServiceResponse
     */
    public static function store(Request $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            if (!$user) {
                return new ServiceResponse(false, null, 'Unauthorized');
            }

            $validated = $request->validated();
            $validated['created_by'] = $user->id;

            $recipe = self::$recipeRepository->create($validated);

            self::$relationService->processRelations($request, $recipe);

            DB::commit();
            return new ServiceResponse(true, $recipe, 'Recipe created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('RecipeService::store Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Retrieve a recipe by its ID.
     *
     * @param string $id
     * @return ServiceResponse
     */
    public static function getById(int|string $id): ServiceResponse
    {
        try {
            $recipe = self::$recipeRepository->find($id);
            return new ServiceResponse(true, $recipe);
        } catch (Exception $e) {
            Log::error('RecipeService::getById Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Update a recipe by its ID.
     *
     * @param Request $request
     * @param string $id
     * @return ServiceResponse
     */
    public static function update(Request $request, int|string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe = self::$recipeRepository->find($id);

            $validated = $request->validated();
            self::$recipeRepository->update($recipe, $validated);

            self::$relationService->processRelations($request, $recipe);

            DB::commit();

            $updatedRecipe = self::$recipeRepository->find($id);
            return new ServiceResponse(true, $updatedRecipe, 'Recipe updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('RecipeService::update Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Delete a recipe by its ID.
     *
     * @param Request $request
     * @param string $id
     * @return ServiceResponse
     */
    public static function destroy(Request $request, string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe = self::$recipeRepository->find($id);

            self::$relationService->detachRelations($recipe);
            $deleted = self::$recipeRepository->delete($recipe);

            DB::commit();
            return new ServiceResponse($deleted, null, $deleted ? 'Recipe deleted successfully' : 'Deletion failed');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('RecipeService::destroy Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}
