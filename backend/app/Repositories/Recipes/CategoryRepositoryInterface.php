<?php

declare(strict_types=1);

namespace App\Repositories\Recipes;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CategoryRepositoryInterface
 *
 * Defines the contract for category data access operations.
 *
 * @package App\Repositories\Recipes
 */
interface CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return Collection<int, Category>
     * @throws \Exception When database operation fails
     */
    public function all(): Collection;

    /**
     * Find a category by ID.
     *
     * @param string $id
     * @return Category
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When category not found
     * @throws \Exception When database operation fails
     */
    public function find(string $id): Category;

    /**
     * Create a new category.
     *
     * @param array<string, mixed> $data
     * @return Category
     * @throws \Exception When database operation fails
     */
    public function create(array $data): Category;

    /**
     * Update an existing category.
     *
     * @param Category $category
     * @param array<string, mixed> $data
     * @return bool
     * @throws \Exception When database operation fails
     */
    public function update(Category $category, array $data): bool;

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool
     * @throws \Exception When database operation fails
     */
    public function delete(Category $category): bool;
}
