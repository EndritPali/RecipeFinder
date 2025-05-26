<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AttachIngredientRequest;
use App\Http\Requests\Api\V1\UpdateRecipeIngredientRequest;
use App\Http\Services\Auth\RecipeIngredientService;

class RecipeIngredientController extends Controller
{
    /**
     * @var 
     */
    protected $service;

    /**
     * @param \App\Http\Services\Auth\RecipeIngredientService $service
     */
    public function __construct(RecipeIngredientService $service)
    {
        $this->service = $service;
    }

    /**
     * @param \App\Http\Requests\Api\V1\AttachIngredientRequest $request
     * @param mixed $recipeId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(AttachIngredientRequest $request, $recipeId)
    {
        return $this->service->attachIngredient($recipeId, $request);
    }

    /**
     * @param \App\Http\Requests\Api\V1\UpdateRecipeIngredientRequest $request
     * @param mixed $recipeId
     * @param mixed $ingredientId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRecipeIngredientRequest $request, $recipeId, $ingredientId)
    {
        return $this->service->updateQuantity($recipeId, $ingredientId, $request);
    }

    /**
     * @param mixed $recipeId
     * @param mixed $ingredientId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy($recipeId, $ingredientId)
    {
        return $this->service->detachIngredient($recipeId, $ingredientId);
    }
}
