<?php

namespace App\Http\Services;

use App\Repositories\Recipes\RecipeRepositoryInterface;
use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\RecipeResource;

class RecipeService
{
    protected $recipes;

    /**
     * @param \App\Repositories\Recipes\RecipeRepositoryInterface $recipes
     */
    public function __construct(RecipeRepositoryInterface $recipes)
    {
        $this->recipes = $recipes;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllRecipes()
    {
        return response()->json([
            'status' => 'success',
            'data' => RecipeResource::collection($this->recipes->all())
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createRecipe(Request $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $validated['created_by'] = $user->id;
        $recipe = $this->recipes->create($validated);

        $ingredientIds = $this->processIngredients($request->input('ingredients'));
        $categoryIds = $this->processCategories($request->input('category'));

        $this->recipes->attachIngredients($recipe, $ingredientIds);
        $this->recipes->attachCategories($recipe, $categoryIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe created successfully',
            'data' => new RecipeResource($recipe)
        ], 201);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateRecipe(Request $request, string $id)
    {
        $recipe = $this->recipes->find($id);
        $user = $request->user();

        if ($user->role !== 'Admin' && $recipe->created_by !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Permission denied'], 403);
        }

        $validated = $request->validated();
        $this->recipes->update($recipe, $validated);

        $ingredientIds = $this->processIngredients($request->input('ingredients'));
        $categoryIds = $this->processCategories($request->input('category'));

        $this->recipes->attachIngredients($recipe, $ingredientIds);
        $this->recipes->attachCategories($recipe, $categoryIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe updated successfully',
            'data' => new RecipeResource($recipe)
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function deleteRecipe(Request $request, string $id)
    {
        $recipe = $this->recipes->find($id);
        $user = $request->user();

        if ($user->role !== 'Admin' && $recipe->created_by !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Permission denied'], 403);
        }

        $recipe->ingredients()->detach();
        $recipe->categories()->detach();

        $this->recipes->delete($recipe);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe deleted successfully'
        ]);
    }

    /**
     * @param string $ingredientsString
     * @return array
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
     * @param string $categoryString
     * @return array
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
