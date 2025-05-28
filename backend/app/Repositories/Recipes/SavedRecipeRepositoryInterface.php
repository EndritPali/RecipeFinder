<?php

namespace App\Repositories\Recipes;

use App\Models\SavedRecipe;
use Illuminate\Database\Eloquent\Collection;

interface SavedRecipeRepositoryInterface
{
    /**
     * @param string $userId
     * @return \Illuminate\Database\Eloquent\Collection<int, SavedRecipe>
     */
    public function getByUserId(string $userId): Collection;

    /**
     * @param string $userId
     * @param string $recipeId
     * @return bool
     */
    public function exists(string $userId, string $recipeId): bool;

    /**
     * @param string $userId
     * @param string $recipeId
     * @return SavedRecipe
     */
    public function create(string $userId, string $recipeId): SavedRecipe;

    /**
     * @param string $userId
     * @param string $recipeId
     * @return SavedRecipe|null
     */
    public function get(string $userId, string $recipeId): ?SavedRecipe;

    /**
     * @param \App\Models\SavedRecipe $savedRecipe
     * @return void
     */
    public function delete(SavedRecipe $savedRecipe): void;
}
