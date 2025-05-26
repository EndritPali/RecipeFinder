<?php

namespace App\Http\Services\Auth;

use App\Repositories\Recipes\RecipeRepository;
use App\Repositories\Recipes\CategoryRepository;

class RecipeCategoryService
{
    /**
     * @var 
     */
    protected $recipes;

    /**
     * @var 
     */
    protected $categories;

    /**
     * @param \App\Repositories\Recipes\RecipeRepository $recipes
     * @param \App\Repositories\Recipes\CategoryRepository $categories
     */
    public function __construct(
        RecipeRepository $recipes,
        CategoryRepository $categories
    ) {
        $this->recipes = $recipes;
        $this->categories = $categories;
    }

    /**
     * @param string $recipeId
     * @param string $categoryId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function attachCategory(string $recipeId, string $categoryId)
    {
        $recipe = $this->recipes->find($recipeId);
        $this->categories->find($categoryId);

        $recipe->categories()->syncWithoutDetaching([$categoryId]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category attached to recipe',
        ]);
    }

    /**
     * @param string $recipeId
     * @param string $categoryId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function detachCategory(string $recipeId, string $categoryId)
    {
        $recipe = $this->recipes->find($recipeId);
        $recipe->categories()->detach($categoryId);

        return response()->json([
            'status' => 'success',
            'message' => 'Category detached from recipe',
        ]);
    }
}
