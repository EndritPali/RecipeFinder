<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\IngredientController;
use Illuminate\Http\Request;
use App\Http\Resources\RecipeResource;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recipes = Recipe::with(['creator', 'ingredients', 'categories'])->get();

        return response()->json([
            'status' => 'success',
            'data' => RecipeResource::collection($recipes)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:100',
            'short_description' => 'required|string|min:5',
            'rating'            => 'required|integer|min:1|max:5',
            'image_url'         => 'required|url',
            'instructions'      => 'required|string',
            'preparation_time'  => 'required|integer|min:1',
            'cooking_time'      => 'required|integer|min:1',
            'servings'          => 'required|integer|min:1',
        ]);

        // Use the auth user directly (this is secure since it comes from the server)
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $validated['created_by'] = $user->id;

        $recipe = Recipe::create($validated);

        $ingredientNames = explode(',', $request->input('ingredients'));
        $ingredientIds = [];

        foreach ($ingredientNames as $name) {
            $ingredient = Ingredient::firstOrCreate(
                ['name' => trim($name)],
                ['unit' => 'pesos']
            );
            $ingredientIds[] = $ingredient->id;
        }

        $recipe->ingredients()->attach($ingredientIds);

        $categoryNames = explode(',', $request->input('category'));
        $categoryIds = [];

        foreach ($categoryNames as $name) {
            $category = Category::firstOrCreate(['name' => trim($name)]);
            $categoryIds[] = $category->id;
        }

        $recipe->categories()->attach($categoryIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe created successfully',
            'data' => new RecipeResource($recipe)
        ], 201);
    }

    /**
     * Grab recipes by user_id function - uses server-side user data for security
     */
    public function myRecipes(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $recipes = Recipe::with(['ingredients', 'categories'])
            ->where('created_by', $user->id)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => RecipeResource::collection($recipes)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = $request->user();

        // Only allow the owner or an admin to update the recipe
        if ($user->role !== 'Admin' && $recipe->created_by !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to update this recipe'
            ], 403);
        }

        $validated = $request->validate([
            'title'              => 'required',
            'short_description'  => 'required|min:5',
            'rating'             => 'required',
            'image_url'          => 'required|string',
            'instructions'       => 'required',
            'preparation_time'   => 'required',
            'cooking_time'       => 'required',
            'servings'           => 'required',
            'ingredients'        => 'required|string',
            'category'           => 'required|string',
        ]);

        $recipe->update($validated);

        $ingredientNames = explode(',', $request->input('ingredients'));
        $ingredientIds = [];

        foreach ($ingredientNames as $name) {
            $ingredient = Ingredient::firstOrCreate(
                ['name' => trim($name)],
                ['unit' => 'pesos']
            );
            $ingredientIds[] = $ingredient->id;
        }

        $recipe->ingredients()->sync($ingredientIds);

        $categoryNames = explode(',', $request->input('category'));
        $categoryIds = [];

        foreach ($categoryNames as $name) {
            $category = Category::firstOrCreate(['name' => trim($name)]);
            $categoryIds[] = $category->id;
        }

        $recipe->categories()->sync($categoryIds);

        return response()->json([
            'status'  => 'success',
            'message' => 'Recipe updated successfully',
            'data'    => new RecipeResource($recipe)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = $request->user();

        // Only allow the owner or an admin to delete the recipe
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
}
