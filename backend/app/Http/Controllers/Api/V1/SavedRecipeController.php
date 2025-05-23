<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSavedRecipesRequest;
use App\Http\Services\Auth\SavedRecipeService;
use Illuminate\Http\Request;
use App\Models\SavedRecipe;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class SavedRecipeController extends Controller
{
    protected $service;

    public function __construct(SavedRecipeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getSavedRecipes();
    }

    public function store(StoreSavedRecipesRequest $request)
    {
        return $this->service->saveRecipe($request);
    }

    public function show(string $recipeId)
    {
        return $this->service->isRecipeSaved($recipeId);
    }

    public function destroy(string $recipeId)
    {
        return $this->service->removeSavedRecipe($recipeId);
    }
}
