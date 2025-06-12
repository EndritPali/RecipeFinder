<?php

declare(strict_types=1);

namespace App\Repositories\Recipes;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RuntimeException;

/**
 * Implementation of the RecipeRepositoryInterface for Eloquent ORM.
 *
 * This repository handles all database operations for the Recipe model,
 * following the Repository pattern and Single Responsibility Principle.
 */
final class RecipeRepository implements RecipeRepositoryInterface
{
    /**
     * @param Recipe $model The Recipe Eloquent model
     */
    public function __construct(
        private readonly Recipe $model
    ) {}

    /**
     * {@inheritDoc}
     */
    public function all(): Collection
    {
        return $this->model->newQuery()
            ->with(['creator', 'ingredients', 'categories'])
            ->orderBy('id')
            ->get();
    }


    /**
     * {@inheritDoc}
     */
    public function getPaginated(?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? 15;
        $perPage = min(max($perPage, 1), 100);

        return $this->model->newQuery()
            ->paginate($perPage);
    }


    /**
     * {@inheritDoc}
     */
    public function find(string $id): Recipe
    {
        try {
            return $this->model->newQuery()
                ->with(['creator', 'ingredients', 'categories'])
                ->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(
                "Recipe with ID {$id} not found.",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): Recipe
    {
        $requiredFields = ['title', 'short_description', 'created_by'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new InvalidArgumentException("Missing required field: {$field}");
            }
        }

        try {
            return $this->model->newQuery()->create($data);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to create recipe: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(Recipe $recipe, array $data): bool
    {
        if (empty($data)) {
            throw new InvalidArgumentException('No data provided for update');
        }

        try {
            return $recipe->update($data);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to update recipe {$recipe->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Recipe $recipe): bool
    {
        try {
            return (bool) $recipe->delete();
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to delete recipe {$recipe->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function attachIngredients(Recipe $recipe, array $ingredientIds): void
    {
        if (empty($ingredientIds)) {
            throw new InvalidArgumentException('No ingredient IDs provided');
        }

        try {
            $recipe->ingredients()->sync($ingredientIds);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to attach ingredients to recipe {$recipe->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function attachCategories(Recipe $recipe, array $categoryIds): void
    {
        if (empty($categoryIds)) {
            throw new InvalidArgumentException('No category IDs provided');
        }

        try {
            $recipe->categories()->sync($categoryIds);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to attach categories to recipe {$recipe->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * Get all recipes created by a specific user.
     *
     * @param string $userId The user ID
     * @return Collection The collection of recipes
     */
    public function getByUser(string $userId): Collection
    {
        return $this->model->newQuery()
            ->with(['ingredients', 'categories'])
            ->where('created_by', $userId)
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getByUserPaginated(string $userId, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? 15;
        $perPage = min(max($perPage, 1), 100);

        return $this->model->newQuery()
            ->with(['ingredients', 'categories'])
            ->where('created_by', $userId)
            ->paginate($perPage);
    }
}
