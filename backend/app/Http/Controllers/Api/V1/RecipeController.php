<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreRecipeRequest;
use App\Http\Requests\Api\V1\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\http\Services\Auth\RecipeService;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    protected $service;

    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAllRecipes();
    }

    public function store(StoreRecipeRequest $request)
    {
        return $this->service->createRecipe($request);
    }

    public function update(UpdateRecipeRequest $request, string $id)
    {
        return $this->service->updateRecipe($request, $id);
    }

    public function destroy(Request $request, string $id)
    {
        return $this->service->deleteRecipe($request, $id);
    }
}
