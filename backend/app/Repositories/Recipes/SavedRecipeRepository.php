<?php

declare(strict_types=1);

namespace App\Repositories\Recipes;

use App\Models\SavedRecipe;
use App\Repositories\Recipes\SavedRecipeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Repository implementation for saved recipe persistence operations.
 */
final class SavedRecipeRepository implements SavedRecipeRepositoryInterface
{
    /**
     * @param string $userId
     * @return \Illuminate\Database\Eloquent\Collection<int, SavedRecipe>
     */
    public function getByUserId(string $userId): Collection
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
    public function exists(int|string $userId, int|string $recipeId): bool
    {
        return SavedRecipe::where('user_id', $userId)
            ->where('recipe_id', $recipeId)
            ->exists();
    }

    /**
     * @param string $userId
     * @param string $recipeId
     * @return SavedRecipe
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function create(int|string $userId, int|string $recipeId): SavedRecipe
    {
        $savedRecipe = SavedRecipe::create([
            'user_id' => $userId,
            'recipe_id' => $recipeId,
        ]);

        if (!$savedRecipe) {
            throw new ModelNotFoundException('Failed to create saved recipe');
        }

        return $savedRecipe;
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
