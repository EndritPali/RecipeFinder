<?php

namespace App\Http\Services\Auth;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeIngredientService
{
    public function attachIngredient($recipeId, Request $request)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $recipe->ingredients()->attach($request->ingredient_id, [
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient attached successfully',
        ]);
    }

    public function updateQuantity($recipeId, $ingredientId, Request $request)
    {
        $recipe = Recipe::findOrFail($recipeId);

        if (!$recipe->ingredients()->where('ingredients.id', $ingredientId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ingredient not found in recipe',
            ], 404);
        }

        $recipe->ingredients()->updateExistingPivot($ingredientId, [
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient quantity updated successfully',
        ]);
    }

    public function detachIngredient($recipeId, $ingredientId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $recipe->ingredients()->detach($ingredientId);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient detached from recipe',
        ]);
    }
}
