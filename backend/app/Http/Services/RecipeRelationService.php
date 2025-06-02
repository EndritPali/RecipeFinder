<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\Request;

/**
 * Handles processing and management of recipe relations
 */
class RecipeRelationService
{
    /**
     * Process and attach all recipe relations
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return void
     */
    public function processRelations(Request $request, Recipe $recipe): void
    {
        if ($request->has('ingredients')) {
            $ingredientIds = $this->processIngredients($request->input('ingredients'));
            $recipe->ingredients()->sync($ingredientIds);
        }

        if ($request->has('category')) {
            $categoryIds = $this->processCategories($request->input('category'));
            $recipe->categories()->sync($categoryIds);
        }
    }

    /**
     * Detach all recipe relations
     *
     * @param Recipe $recipe
     * @return void
     */
    public function detachRelations(Recipe $recipe): void
    {
        $recipe->ingredients()->detach();
        $recipe->categories()->detach();
    }

    /**
     * Process comma-separated ingredient names
     *
     * @param string $ingredientsString
     * @return array<int>
     */
    private function processIngredients(string $ingredientsString): array
    {
        $names = preg_split('/,\s*/', trim($ingredientsString));
        $ids = [];

        foreach ($names as $name) {
            if (!empty($name)) {
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => trim($name)],
                    ['unit' => 'pesos']
                );
                $ids[] = $ingredient->id;
            }
        }

        return $ids;
    }

    /**
     * Process comma-separated category names
     *
     * @param string $categoryString
     * @return array<int>
     */
    private function processCategories(string $categoryString): array
    {
        $names = preg_split('/,\s*/', trim($categoryString));
        $ids = [];

        foreach ($names as $name) {
            if (!empty($name)) {
                $category = Category::firstOrCreate(['name' => trim($name)]);
                $ids[] = $category->id;
            }
        }

        return $ids;
    }
}
