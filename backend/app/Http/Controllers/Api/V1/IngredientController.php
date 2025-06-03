<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreIngredientRequest;
use App\Http\Requests\Api\V1\UpdateIngredientRequest;
use App\Http\Services\IngredientService;
use Illuminate\Http\JsonResponse;

/**
 * Class IngredientController
 *
 * Handles HTTP requests related to ingredient management.
 *
 * @package App\Http\Controllers\Api\V1
 */
final class IngredientController extends ApiController
{
    /**
     * Ingredient service instance.
     */
    private IngredientService $service;

    /**
     * Create a new controller instance.
     *
     * @param IngredientService $service
     */
    public function __construct(IngredientService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of ingredients.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $response = $this->service->getAll();

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->getModel()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Store a newly created ingredient.
     *
     * @param StoreIngredientRequest $request
     * @return JsonResponse
     */
    public function store(StoreIngredientRequest $request): JsonResponse
    {
        $response = $this->service->create($request);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient created successfully',
                'data' => $response->getModel()
            ], 201);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Update the specified ingredient.
     *
     * @param UpdateIngredientRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateIngredientRequest $request, string $id): JsonResponse
    {
        $response = $this->service->update($request, $id);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient updated successfully',
                'data' => $response->getModel()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Remove the specified ingredient.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $response = $this->service->delete($id);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient deleted successfully'
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }
}
