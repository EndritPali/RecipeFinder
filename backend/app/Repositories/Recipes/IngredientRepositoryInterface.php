<?php

declare(strict_types=1);

namespace App\Repositories\Recipes;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface IngredientRepositoryInterface
 *
 * Defines the contract for ingredient data access operations.
 *
 * @package App\Repositories\Recipes
 */
interface IngredientRepositoryInterface
{
    /**
     * Get all ingredients.
     *
     * @return Collection<int, Ingredient>
     * @throws \Exception When database operation fails
     */
    public function all(): Collection;

    /**
     * Create a new ingredient.
     *
     * @param array<string, mixed> $data
     * @return Ingredient
     * @throws \Exception When database operation fails
     */
    public function create(array $data): Ingredient;

    /**
     * Find an ingredient by ID.
     *
     * @param string $id
     * @return Ingredient
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When ingredient not found
     * @throws \Exception When database operation fails
     */
    public function find(string $id): Ingredient;

    /**
     * Update an existing ingredient.
     *
     * @param Ingredient $ingredient
     * @param array<string, mixed> $data
     * @return bool
     * @throws \Exception When database operation fails
     */
    public function update(Ingredient $ingredient, array $data): bool;

    /**
     * Delete an ingredient.
     *
     * @param Ingredient $ingredient
     * @return bool
     * @throws \Exception When database operation fails
     */
    public function delete(Ingredient $ingredient): bool;
}
