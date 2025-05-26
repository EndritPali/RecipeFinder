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
    /**
     * @var 
     */
    protected $service;

    /**
     * @param \App\Http\Services\Auth\SavedRecipeService $service
     */
    public function __construct(SavedRecipeService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->service->getSavedRecipes();
    }

    /**
     * @param \App\Http\Requests\Api\V1\StoreSavedRecipesRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreSavedRecipesRequest $request)
    {
        return $this->service->saveRecipe($request);
    }

    /**
     * @param string $recipeId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $recipeId)
    {
        return $this->service->isRecipeSaved($recipeId);
    }

    /**
     * @param string $recipeId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $recipeId)
    {
        return $this->service->removeSavedRecipe($recipeId);
    }
}
