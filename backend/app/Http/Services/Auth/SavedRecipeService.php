<?php

namespace App\Http\Services\Auth;

use App\Http\Requests\Api\V1\StoreSavedRecipesRequest;
use App\Models\SavedRecipe;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSavedRecipeRequest;

class SavedRecipeService
{
    public function getSavedRecipes()
    {
        $user = Auth::user();

        $savedRecipes = SavedRecipe::with('recipe')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($savedRecipes);
    }

    public function saveRecipe(StoreSavedRecipesRequest $request)
    {
        $user = Auth::user();

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

    public function isRecipeSaved(string $recipeId)
    {
        $user = Auth::user();

        $exists = SavedRecipe::where('user_id', $user->id)
            ->where('recipe_id', $recipeId)
            ->exists();

        return response()->json(['saved' => $exists]);
    }

    public function removeSavedRecipe(string $recipeId)
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