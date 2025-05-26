<?php

namespace App\Http\Services\Auth;

use App\Http\Requests\Api\V1\StoreSavedRecipesRequest;
use App\Repositories\Recipes\SavedRecipeRepository;
use Illuminate\Support\Facades\Auth;

class SavedRecipeService
{
    /**
     * @var 
     */
    protected $savedRecipeRepo;

    /**
     * @param \App\Repositories\Recipes\SavedRecipeRepository $savedRecipeRepo
     */
    public function __construct(SavedRecipeRepository $savedRecipeRepo)
    {
        $this->savedRecipeRepo = $savedRecipeRepo;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getSavedRecipes()
    {
        $userId = Auth::id();
        $savedRecipes = $this->savedRecipeRepo->getByUserId($userId);

        return response()->json($savedRecipes);
    }

    /**
     * @param \App\Http\Requests\Api\V1\StoreSavedRecipesRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function saveRecipe(StoreSavedRecipesRequest $request)
    {
        $userId = Auth::id();

        if ($this->savedRecipeRepo->exists($userId, $request->recipe_id)) {
            return response()->json(['message' => 'Recipe already saved!'], 409);
        }

        $saved = $this->savedRecipeRepo->create($userId, $request->recipe_id);

        return response()->json($saved, 201);
    }

    /**
     * @param string $recipeId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function isRecipeSaved(string $recipeId)
    {
        $userId = Auth::id();

        $exists = $this->savedRecipeRepo->exists($userId, $recipeId);

        return response()->json(['saved' => $exists]);
    }

    /**
     * @param string $recipeId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function removeSavedRecipe(string $recipeId)
    {
        $userId = Auth::id();

        $saved = $this->savedRecipeRepo->get($userId, $recipeId);

        if (!$saved) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $this->savedRecipeRepo->delete($saved);

        return response()->json(['message' => 'Recipe removed from saved list']);
    }
}
