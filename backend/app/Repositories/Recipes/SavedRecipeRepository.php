<?php

namespace App\Repositories\Recipes;

use App\Models\SavedRecipe;

class SavedRecipeRepository
{
    /**
     * @param string $userId
     * @return \Illuminate\Database\Eloquent\Collection<int, SavedRecipe>
     */
    public function getByUserId(string $userId)
    {
        return SavedRecipe::with('recipe')
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * @param string $userId
     * @param string $recipeId
     * @return bool
     */
    public function exists(string $userId, string $recipeId): bool
    {
        return SavedRecipe::where('user_id', $userId)
            ->where('recipe_id', $recipeId)
            ->exists();
    }

    /**
     * @param string $userId
     * @param string $recipeId
     * @return SavedRecipe
     */
    public function create(string $userId, string $recipeId)
    {
        return SavedRecipe::create([
            'user_id' => $userId,
            'recipe_id' => $recipeId,
        ]);
    }

    /**
     * @param string $userId
     * @param string $recipeId
     * @return SavedRecipe|null
     */
    public function get(string $userId, string $recipeId): ?SavedRecipe
    {
        return SavedRecipe::where('user_id', $userId)
            ->where('recipe_id', $recipeId)
            ->first();
    }

    /**
     * @param \App\Models\SavedRecipe $savedRecipe
     * @return void
     */
    public function delete(SavedRecipe $savedRecipe): void
    {
        $savedRecipe->delete();
    }
}
