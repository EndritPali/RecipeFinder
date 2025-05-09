<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class RecipeIngredientController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $recipeId)
    {
        $validated = $request->validate([
            'ingredient_id' => 'required|exists|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $recipe = Recipe::findOrFail($recipeId);
        $recipe->ingredients()->attach($validated['ingredient_id'], [
            'quantity' => $validated['quantity']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient attached successfuly',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $recipeId, $ingredientId)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $recipe = Recipe::findOrFail($recipeId);

        if (!$recipe->ingredients()->where('ingredient_id')->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ingredient not found'
            ], 404);
        }

        $recipe->ingredients()->updateExistingPivot($ingredientId, [
            'quantity' => $validated['quantity']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient quantity updated successfully',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($recipeId, $ingredientId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        $recipe->ingredients()->detach($ingredientId);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient detached from recipe'
        ]);
    }
}
