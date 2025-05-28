<?php

namespace App\Repositories\Recipes;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;

class RecipeRepository implements RecipeRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Recipe>
     */
    public function all(): Collection
    {
        return Recipe::with(['creator', 'ingredients', 'categories'])->get();
    }

    /**
     * @param string $id
     * @return Recipe
     */
    public function find(string $id): Recipe
    {
        return Recipe::findOrFail($id);
    }

    /**
     * @param array $data
     * @return Recipe
     */
    public function create(array $data): Recipe
    {
        return Recipe::create($data);
    }

    /**
     * @param \App\Models\Recipe $recipe
     * @param array $data
     * @return bool
     */
    public function update(Recipe $recipe, array $data): bool
    {
        return $recipe->update($data);
    }

    /**
     * @param \App\Models\Recipe $recipe
     * @return bool|null
     */
    public function delete(Recipe $recipe): ?bool
    {
        return $recipe->delete();
    }

    /**
     * @param \App\Models\Recipe $recipe
     * @param array $ingredientIds
     * @return void
     */
    public function attachIngredients(Recipe $recipe, array $ingredientIds): void
    {
        $recipe->ingredients()->sync($ingredientIds);
    }

    /**
     * @param \App\Models\Recipe $recipe
     * @param array $categoryIds
     * @return void
     */
    public function attachCategories(Recipe $recipe, array $categoryIds): void
    {
        $recipe->categories()->sync($categoryIds);
    }
}
