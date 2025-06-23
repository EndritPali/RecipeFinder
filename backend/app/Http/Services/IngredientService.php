<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Repositories\Recipes\IngredientRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class IngredientService
 *
 * Handles all business logic related to ingredient operations such as
 * creating, retrieving, updating, and deleting ingredients.
 *
 * @package App\Http\Services
 */
final class IngredientService
{
    /**
     * Ingredient repository for data access.
     */
    private static IngredientRepositoryInterface $ingredientRepository;

    /**
     * IngredientService constructor.
     *
     * @param IngredientRepositoryInterface $ingredientRepository
     */
    public function __construct(IngredientRepositoryInterface $ingredientRepository)
    {
       self::$ingredientRepository = $ingredientRepository;
    }

    /**
     * Retrieve all ingredients.
     *
     * @return ServiceResponse
     */
    public static function getAll(): ServiceResponse
    {
        try {
            $ingredients = self::$ingredientRepository->all();
            return new ServiceResponse(true, $ingredients);
        } catch (Exception $e) {
            Log::channel('recipeslog')->error('IngredientService::getAll Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Store a new ingredient in the database.
     *
     * @param Request $request
     * @return ServiceResponse
     */
    public static function create(Request $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $ingredient = self::$ingredientRepository->create($validated);

            DB::commit();
            return new ServiceResponse(true, $ingredient, 'Ingredient created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('recipeslog')->error('IngredientService::create Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Update an ingredient by its ID.
     *
     * @param Request $request
     * @param string $id
     * @return ServiceResponse
     */
    public static function update(Request $request, string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $ingredient = self::$ingredientRepository->update($id, $validated);

            DB::commit();
            return new ServiceResponse(true, $ingredient, 'Ingredient updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('recipeslog')->error('IngredientService::update Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Delete an ingredient by its ID.
     *
     * @param string $id
     * @return ServiceResponse
     */
    public static function delete(string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            self::$ingredientRepository->delete($id);

            DB::commit();
            return new ServiceResponse(true, null, 'Ingredient deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('recipeslog')->error('IngredientService::delete Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}
