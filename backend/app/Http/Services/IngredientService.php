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
    private IngredientRepositoryInterface $ingredients;

    /**
     * IngredientService constructor.
     *
     * @param IngredientRepositoryInterface $ingredients
     */
    public function __construct(IngredientRepositoryInterface $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * Retrieve all ingredients.
     *
     * @return ServiceResponse
     */
    public function getAll(): ServiceResponse
    {
        try {
            $ingredients = $this->ingredients->all();
            return new ServiceResponse(true, $ingredients);
        } catch (Exception $e) {
            Log::error('IngredientService::getAll Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Store a new ingredient in the database.
     *
     * @param Request $request
     * @return ServiceResponse
     */
    public function create(Request $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $ingredient = $this->ingredients->create($validated);

            DB::commit();
            return new ServiceResponse(true, $ingredient, 'Ingredient created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('IngredientService::create Exception: ' . $e->getMessage());
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
    public function update(Request $request, string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $ingredient = $this->ingredients->update($id, $validated);

            DB::commit();
            return new ServiceResponse(true, $ingredient, 'Ingredient updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('IngredientService::update Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Delete an ingredient by its ID.
     *
     * @param string $id
     * @return ServiceResponse
     */
    public function delete(string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $this->ingredients->delete($id);

            DB::commit();
            return new ServiceResponse(true, null, 'Ingredient deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('IngredientService::delete Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}
