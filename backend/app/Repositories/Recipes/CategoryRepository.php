<?php

declare(strict_types=1);

namespace App\Repositories\Recipes;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

/**
 * Class CategoryRepository
 *
 * Implements data access operations for categories.
 *
 * @package App\Repositories\Recipes
 */
final class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return Collection<int, Category>
     * @throws Exception When database operation fails
     */
    public function all(): Collection
    {
        try {
            return Category::all();
        } catch (Exception $e) {
            Log::error('CategoryRepository::all Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Find a category by ID.
     *
     * @param string $id
     * @return Category
     * @throws ModelNotFoundException When category not found
     * @throws Exception When database operation fails
     */
    public function find(string $id): Category
    {
        try {
            return Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error('CategoryRepository::find ModelNotFoundException: ' . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            Log::error('CategoryRepository::find Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new category.
     *
     * @param array<string, mixed> $data
     * @return Category
     * @throws Exception When database operation fails
     */
    public function create(array $data): Category
    {
        try {
            return Category::create($data);
        } catch (Exception $e) {
            Log::error('CategoryRepository::create Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing category.
     *
     * @param Category $category
     * @param array<string, mixed> $data
     * @return bool
     * @throws Exception When database operation fails
     */
    public function update(Category $category, array $data): bool
    {
        try {
            return $category->update($data);
        } catch (Exception $e) {
            Log::error('CategoryRepository::update Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool
     * @throws Exception When database operation fails
     */
    public function delete(Category $category): bool
    {
        try {
            return (bool) $category->delete();
        } catch (Exception $e) {
            Log::error('CategoryRepository::delete Exception: ' . $e->getMessage());
            throw $e;
        }
    }
}
