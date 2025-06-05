<?php

declare(strict_types=1);

namespace App\Repositories\Recipes;

use App\Models\SavedRecipe;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for managing saved recipe persistence operations.
 */
interface SavedRecipeRepositoryInterface
{
    /**
     * Retrieve all saved recipes for a given user.
     *
     * @param string $userId The ID of the user
     * @return Collection<int, SavedRecipe>
     */
    public function getByUserId(string $userId): Collection;

    /**
     * Check if a recipe is saved by a specific user.
     *
     * @param string $userId The ID of the user
     * @param string $recipeId The ID of the recipe
     * @return bool
     */
    public function exists(string $userId, string $recipeId): bool;

    /**
     * Create a new saved recipe entry.
     *
     * @param string $userId The ID of the user
     * @param string $recipeId The ID of the recipe
     * @return SavedRecipe
     */
    public function create(string $userId, string $recipeId): SavedRecipe;

    /**
     * Get a specific saved recipe entry.
     *
     * @param string $userId The ID of the user
     * @param string $recipeId The ID of the recipe
     * @return SavedRecipe|null
     */
    public function get(string $userId, string $recipeId): ?SavedRecipe;

    /**
     * Delete a saved recipe entry.
     *
     * @param SavedRecipe $savedRecipe The saved recipe to delete
     */
    public function delete(SavedRecipe $savedRecipe): void;
}
