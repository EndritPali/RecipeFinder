<?php

declare(strict_types=1);

namespace App\Repositories\Recipes;

use App\Models\Ingredient;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

/**
 * Class IngredientRepository
 *
 * Implements data access operations for ingredients.
 *
 * @package App\Repositories\Recipes
 */
final class IngredientRepository implements IngredientRepositoryInterface
{
    /**
     * @param Ingredient $model The User Eloquent model
     */
    public function __construct(
        private readonly Ingredient $model
    ) {}

    /**
     * Get all ingredients.
     *
     * @return Collection<int, Ingredient>
     * @throws Exception When database operation fails
     */
    public function all(): Collection
    {
        try {
            return Ingredient::all();
        } catch (Exception $e) {
            Log::error('IngredientRepository::all Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new ingredient.
     *
     * @param array<string, mixed> $data
     * @return Ingredient
     * @throws Exception When database operation fails
     */
    public function create(array $data): Ingredient
    {
        try {
            return Ingredient::create($data);
        } catch (Exception $e) {
            Log::error('IngredientRepository::create Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Find an ingredient by ID.
     *
     * @param string $id
     * @return Ingredient
     * @throws ModelNotFoundException When ingredient not found
     * @throws Exception When database operation fails
     */
    public function find(string $id): Ingredient
    {
        try {
            return Ingredient::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error('IngredientRepository::find ModelNotFoundException: ' . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            Log::error('IngredientRepository::find Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing ingredient.
     *
     * @param string $id
     * @param array<string, mixed> $data
     * @return Ingredient
     * @throws Exception When database operation fails
     */
    public function update(string $id, array $data): Ingredient
    {
        try {
            $ingredient = $this->find($id);
            $ingredient->update($data);
            return $ingredient;
        } catch (Exception $e) {
            Log::error('IngredientRepository::update Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an ingredient.
     *
     * @param string $id
     * @return void
     * @throws Exception When database operation fails
     */
    public function delete(string $id): void
    {
        try {
            $ingredient = $this->find($id);
            $ingredient->delete();
        } catch (Exception $e) {
            Log::error('IngredientRepository::delete Exception: ' . $e->getMessage());
            throw $e;
        }
    }
}
