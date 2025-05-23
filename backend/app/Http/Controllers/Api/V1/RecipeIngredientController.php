<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AttachIngredientRequest;
use App\Http\Requests\Api\V1\UpdateRecipeIngredientRequest;
use App\Http\Services\Auth\RecipeIngredientService;

class RecipeIngredientController extends Controller
{
    protected $service;

    public function __construct(RecipeIngredientService $service)
    {
        $this->service = $service;
    }

    public function store(AttachIngredientRequest $request, $recipeId)
    {
        return $this->service->attachIngredient($recipeId, $request);
    }

    public function update(UpdateRecipeIngredientRequest $request, $recipeId, $ingredientId)
    {
        return $this->service->updateQuantity($recipeId, $ingredientId, $request);
    }

    public function destroy($recipeId, $ingredientId)
    {
        return $this->service->detachIngredient($recipeId, $ingredientId);
    }
}
