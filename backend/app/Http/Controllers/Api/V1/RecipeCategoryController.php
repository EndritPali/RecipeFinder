<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;

class RecipeCategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $recipeId)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $recipe = Recipe::findOrFail($recipeId);
        $recipe->categories()->syncWithoutDetaching([$validated['category_id']]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category attached to recipe'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($recipeId, $categoryId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $recipe->categories()->detach($categoryId);

        return response()->json([
            'status' => 'success',
            'message' => 'Category detached from recipe'
        ]);
    }
}
