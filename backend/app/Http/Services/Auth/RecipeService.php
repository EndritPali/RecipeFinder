<?php

namespace App\Http\Services\Auth;

use App\Http\Resources\RecipeResource;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecipeService
{
    public function getAllRecipes()
    {
        $recipes = Recipe::with(['creator', 'ingredients', 'categories'])->get();

        return response()->json([
            'status' => 'success',
            'data' => RecipeResource::collection($recipes)
        ]);
    }

    public function createRecipe(Request $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $validated['created_by'] = $user->id;
        $recipe = Recipe::create($validated);

        $ingredientIds = $this->attachIngredients($request->input('ingredients'));
        $recipe->ingredients()->attach($ingredientIds);

        $categoryIds = $this->attachCategories($request->input('category'));
        $recipe->categories()->attach($categoryIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe created successfully',
            'data' => new RecipeResource($recipe)
        ], 201);
    }

    public function updateRecipe(Request $request, string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = $request->user();

        if ($user->role !== 'Admin' && $recipe->created_by !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to update this recipe'
            ], 403);
        }

        $validated = $request->validated();
        $recipe->update($validated);

        $ingredientIds = $this->attachIngredients($request->input('ingredients'));
        $recipe->ingredients()->sync($ingredientIds);

        $categoryIds = $this->attachCategories($request->input('category'));
        $recipe->categories()->sync($categoryIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe updated successfully',
            'data' => new RecipeResource($recipe)
        ]);
    }

    public function deleteRecipe(Request $request, string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = $request->user();

        if ($user->role !== 'Admin' && $recipe->created_by !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to delete this recipe'
            ], 403);
        }

        $recipe->ingredients()->detach();
        $recipe->categories()->detach();
        $recipe->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe deleted successfully'
        ]);
    }

    private function attachIngredients(string $ingredientsString): array
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

    private function attachCategories(string $categoryString): array
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
