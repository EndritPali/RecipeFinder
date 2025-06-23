<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Repositories\Recipes\SavedRecipeRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Service for handling saved recipe business logic.
 *
 * This service implements operations for saving, retrieving, and removing saved recipes,
 * maintaining separation of concerns and following SOLID principles.
 */
final class SavedRecipeService
{
    /**
     * Saved recipe repository for data access.
     */
    private static SavedRecipeRepositoryInterface $savedRecipeRepository;

    /**
     * SavedRecipeService constructor.
     *
     * @param SavedRecipeRepositoryInterface $savedRecipeRepository
     */
    public function __construct(SavedRecipeRepositoryInterface $savedRecipeRepository)
    {
        self::$savedRecipeRepository = $savedRecipeRepository;
    }

    /**
     * Store a new saved recipe for the authenticated user.
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
            $userId = (string)$user->id;
            $recipeId = $validated['recipe_id'];

            if (self::$savedRecipeRepository->exists($userId, $recipeId)) {
                return new ServiceResponse(false, null, 'Recipe already saved!');
            }

            $saved = self::$savedRecipeRepository->create($userId, $recipeId);

            DB::commit();
            return new ServiceResponse(true, $saved, 'Recipe saved successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('recipeslog')->error('SavedRecipeService::store Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Retrieve a specific saved recipe for the authenticated user.
     *
     * @param Request $request
     * @param string $recipeId
     * @return ServiceResponse
     */
    public static function getById(Request $request, string $recipeId): ServiceResponse
    {
        try {
            $user = $request->user();
            if (!$user) {
                return new ServiceResponse(false, null, 'Unauthorized');
            }

            $saved = self::$savedRecipeRepository->get((string)$user->id, $recipeId);
            if (!$saved) {
                return new ServiceResponse(false, null, 'Not found');
            }

            return new ServiceResponse(true, $saved);
        } catch (Exception $e) {
            Log::channel('recipeslog')->error('SavedRecipeService::getById Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Remove a saved recipe for the authenticated user.
     *
     * @param Request $request
     * @param string $recipeId
     * @return ServiceResponse
     */
    public static function destroy(Request $request, string $recipeId): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            if (!$user) {
                return new ServiceResponse(false, null, 'Unauthorized');
            }

            $saved = self::$savedRecipeRepository->get((string)$user->id, $recipeId);
            if (!$saved) {
                return new ServiceResponse(false, null, 'Not found');
            }

            self::$savedRecipeRepository->delete($saved);

            DB::commit();
            return new ServiceResponse(true, null, 'Recipe removed from saved list');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('recipeslog')->error('SavedRecipeService::destroy Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}