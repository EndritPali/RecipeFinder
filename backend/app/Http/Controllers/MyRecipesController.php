<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;

class MyRecipesController extends Controller
{
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
}
