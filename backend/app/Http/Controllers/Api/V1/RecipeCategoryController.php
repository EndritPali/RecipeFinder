<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AttachIngredientRequest;
use App\Http\Services\Auth\RecipeCategoryService;

class RecipeCategoryController extends Controller
{
    /**
     * @var 
     */
    protected $service;

    /**
     * @param \App\Http\Services\Auth\RecipeCategoryService $service
     */
    public function __construct(RecipeCategoryService $service)
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
        return $this->service->attachCategory($recipeId, $request->validated()['category_id']);
    }

    /**
     * @param mixed $recipeId
     * @param mixed $categoryId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy($recipeId, $categoryId)
    {
        return $this->service->detachCategory($recipeId, $categoryId);
    }
}
