<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreRecipeRequest;
use App\Http\Requests\Api\V1\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Http\Services\Auth\RecipeService;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * @var 
     */
    protected $service;

    /**
     * @param \App\Http\Services\Auth\RecipeService $service
     */
    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->service->getAllRecipes();
    }

    /**
     * @param \App\Http\Requests\Api\V1\StoreRecipeRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRecipeRequest $request)
    {
        return $this->service->createRecipe($request);
    }

    /**
     * @param \App\Http\Requests\Api\V1\UpdateRecipeRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRecipeRequest $request, string $id)
    {
        return $this->service->updateRecipe($request, $id);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, string $id)
    {
        return $this->service->deleteRecipe($request, $id);
    }
}
