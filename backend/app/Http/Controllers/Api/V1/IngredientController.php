<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreIngredientRequest;
use App\Http\Requests\Api\V1\UpdateIngredientRequest;
use App\Http\Services\Auth\IngredientService;

class IngredientController extends Controller
{
    protected $service;

    public function __construct(IngredientService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAll();
    }

    public function store(StoreIngredientRequest $request)
    {
        return $this->service->create($request);
    }

    public function update(UpdateIngredientRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->service->delete($id);
    }
}
