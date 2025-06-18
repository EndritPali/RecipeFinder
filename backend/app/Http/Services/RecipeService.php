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
    private RecipeRepositoryInterface $recipeRepository;
    private RecipeRelationService $relationService;

    /**
     * RecipeService constructor.
     *
     * @param RecipeRepositoryInterface $recipeRepository
     */
    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
        RecipeRelationService $recipeRelation
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->relationService = $recipeRelation;
    }

    /**
     * Retrieve all recipes.
     *
     * @return ServiceResponse
     */
    public function getAll(): ServiceResponse
    {
        try {
            $recipes = $this->recipeRepository->all();
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
    public function store(Request $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            if (!$user) {
                return new ServiceResponse(false, null, 'Unauthorized');
            }

            $validated = $request->validated();
            $validated['created_by'] = $user->id;

            $recipe = $this->recipeRepository->create($validated);

            $this->relationService->processRelations($request, $recipe);

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
    public function getById(string $id): ServiceResponse
    {
        try {
            $recipe = $this->recipeRepository->find($id);
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
    public function update(Request $request, string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe = $this->recipeRepository->find($id);
            $user = $request->user();

            $permission = $this->canUserModifyRecipe($user, $recipe);
            if (!$permission->success()) {
                return $permission;
            }

            $validated = $request->validated();
            $this->recipeRepository->update($recipe, $validated);

            $this->relationService->processRelations($request, $recipe);

            DB::commit();

            $updatedRecipe = $this->recipeRepository->find($id);
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
    public function destroy(Request $request, string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $recipe = $this->recipeRepository->find($id);
            $user = $request->user();

            $permission = $this->canUserModifyRecipe($user, $recipe);
            if (!$permission->success()) {
                return $permission;
            }

            $this->relationService->detachRelations($recipe);
            $deleted = $this->recipeRepository->delete($recipe);

            DB::commit();
            return new ServiceResponse($deleted, null, $deleted ? 'Recipe deleted successfully' : 'Deletion failed');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('RecipeService::destroy Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Check if user can modify the recipe.
     *
     * @param User $user
     * @param Recipe $recipe
     * @return ServiceResponse
     */
    private function canUserModifyRecipe(User $user, Recipe $recipe): ServiceResponse
    {
        if (!$user->can('update', $recipe)) {
            return new ServiceResponse(false, null, 'Permission denied');
        }

        return new ServiceResponse(true);
    }
}
