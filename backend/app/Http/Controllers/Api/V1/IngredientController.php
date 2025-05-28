<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreIngredientRequest;
use App\Http\Requests\Api\V1\UpdateIngredientRequest;
use App\Http\Services\IngredientService;

class IngredientController extends Controller
{
    /**
     * @var IngredientService
     */
    protected $service;

    /**
     * @param \App\Http\Services\IngredientService $service
     */
    public function __construct(IngredientService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->service->getAll();
    }

    /**
     * @param \App\Http\Requests\Api\V1\StoreIngredientRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreIngredientRequest $request)
    {
        return $this->service->create($request);
    }

    /**
     * @param \App\Http\Requests\Api\V1\UpdateIngredientRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateIngredientRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    /**
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        return $this->service->delete($id);
    }
}
