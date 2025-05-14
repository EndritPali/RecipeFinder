<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedRecipe;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class SavedRecipeController extends Controller
{
    // GET /api/saved-recipes
    public function index()
    {
        $user = Auth::user();

        $savedRecipes = SavedRecipe::with('recipe') 
            ->where('user_id', $user->id)
            ->get();

        return response()->json($savedRecipes);
    }

    // POST /api/saved-recipes
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
        ]);

        $exists = SavedRecipe::where('user_id', $user->id)
            ->where('recipe_id', $request->recipe_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Recipe already saved!'], 409);
        }

        $saved = SavedRecipe::create([
            'user_id' => $user->id,
            'recipe_id' => $request->recipe_id,
        ]);

        return response()->json($saved, 201);
    }

    // GET /api/saved-recipes/{recipeId}
    public function show($recipeId)
    {
        $user = Auth::user();

        $exists = SavedRecipe::where('user_id', $user->id)
            ->where('recipe_id', $recipeId)
            ->exists();

        return response()->json(['saved' => $exists]);
    }

    // DELETE /api/saved-recipes/{recipeId}
    public function destroy($recipeId)
    {
        $user = Auth::user();

        $saved = SavedRecipe::where('user_id', $user->id)
            ->where('recipe_id', $recipeId)
            ->first();

        if (!$saved) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $saved->delete();

        return response()->json(['message' => 'Recipe removed from saved list']);
    }
}
