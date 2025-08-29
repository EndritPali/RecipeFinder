<?php

declare(strict_types=1);

namespace App\Repositories\Recipes;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\SavedRecipe;

/**
 * Interface for Recipe data persistence operations.
 *
 * This interface defines the contract for recipe repository implementations,
 * following the Repository pattern and Single Responsibility Principle.
 */
interface RecipeRepositoryInterface
{
    /**
     * Retrieve all recipes with their relationships.
     *
     * @return Collection<int, Recipe> Collection of recipes with eager loaded relationships
     */
    public function all(): Collection;

    /**
     * Retrieve paginated recipes.
     *
     * @param int $perPage Number of recipes per page
     * @return LengthAwarePaginator
     */
    public function getPaginated(?int $perPage = null): LengthAwarePaginator;


    /**
     * Find a recipe by its ID.
     *
     * @param string $id The unique identifier of the recipe
     * @throws ModelNotFoundException When recipe is not found
     * @return Recipe The found recipe instance with relationships
     */
    public function find(string $id): Recipe;

    /**
     * Create a new recipe with the given data.
     *
     * @param array<string, mixed> $data The recipe data for creation
     * @throws \InvalidArgumentException When required fields are missing
     * @return Recipe The newly created recipe instance
     */
    public function create(array $data): Recipe;

    /**
     * Update an existing recipe with new data.
     *
     * @param Recipe $recipe The recipe instance to update
     * @param array<string, mixed> $data The data to update the recipe with
     * @throws \InvalidArgumentException When invalid data is provided
     * @return bool True if update was successful
     */
    public function update(Recipe $recipe, array $data): bool;

    /**
     * Delete a recipe from the system.
     *
     * @param Recipe $recipe The recipe instance to delete
     * @throws \RuntimeException When deletion fails
     * @return bool True if deletion was successful
     */
    public function delete(Recipe $recipe): bool;

    /**
     * Attach ingredients to a recipe.
     *
     * @param Recipe $recipe The recipe to attach ingredients to
     * @param array<int> $ingredientIds Array of ingredient IDs
     * @throws \InvalidArgumentException When invalid ingredient IDs are provided
     */
    public function attachIngredients(Recipe $recipe, array $ingredientIds): void;

    /**
     * Attach categories to a recipe.
     *
     * @param Recipe $recipe The recipe to attach categories to
     * @param array<int> $categoryIds Array of category IDs
     * @throws \InvalidArgumentException When invalid category IDs are provided
     */
    public function attachCategories(Recipe $recipe, array $categoryIds): void;

    /**
     * Get all recipes created by a specific user.
     *
     * @param string $userId The user ID
     * @return Collection The collection of recipes
     */
    public function getByUser(string $userId): Collection;

    /**
     * Get paginated recipes created by a specific user.
     *
     * @param string $userId The user ID
     * @param int $perPage Number of recipes per page
     * @return LengthAwarePaginator
     */
    public function getByUserPaginated(string $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Retrieve all saved recipes for a given user as Recipe collection.
     *
     * @param string $userId
     * @return Collection<int, Recipe>
     */
    public function getSavedRecipesByUserId(string $userId): Collection;

    /**
     * Check if a recipe is saved by a specific user.
     *
     * @param string|int $userId
     * @param string|int $recipeId
     * @return bool
     */
    public function savedExists(string|int $userId, string|int $recipeId): bool;

    /**
     * Create a new saved recipe entry.
     *
     * @param string|int $userId
     * @param string|int $recipeId
     * @return SavedRecipe
     */
    public function saveRecipeForUser(string|int $userId, string|int $recipeId): SavedRecipe;

    /**
     * Get a specific saved recipe entry.
     *
     * @param string $userId
     * @param string $recipeId
     * @return SavedRecipe|null
     */
    public function getSavedEntry(string $userId, string $recipeId): ?SavedRecipe;

    /**
     * Delete a saved recipe entry.
     *
     * @param SavedRecipe $savedRecipe
     * @return void
     */
    public function deleteSavedEntry(SavedRecipe $savedRecipe): void;
}
