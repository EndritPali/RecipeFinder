<?php

namespace App\Repositories\Recipes;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;

interface RecipeRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Recipe>
     */
    public function all(): Collection;

    /**
     * @param string $id
     * @return Recipe
     */
    public function find(string $id): Recipe;

    /**
     * @param array $data
     * @return Recipe
     */
    public function create(array $data): Recipe;

    /**
     * @param \App\Models\Recipe $recipe
     * @param array $data
     * @return bool
     */
    public function update(Recipe $recipe, array $data): bool;

    /**
     * @param \App\Models\Recipe $recipe
     * @return bool|null
     */
    public function delete(Recipe $recipe): ?bool;

    /**
     * @param \App\Models\Recipe $recipe
     * @param array $ingredientIds
     * @return void
     */
    public function attachIngredients(Recipe $recipe, array $ingredientIds): void;

    /**
     * @param \App\Models\Recipe $recipe
     * @param array $categoryIds
     * @return void
     */
    public function attachCategories(Recipe $recipe, array $categoryIds): void;
}
