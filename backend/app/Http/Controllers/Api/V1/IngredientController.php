<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreIngredientRequest;
use App\Http\Requests\Api\V1\UpdateIngredientRequest;
use App\Http\Services\IngredientService;
use App\Models\Ingredient;
use App\Repositories\Recipes\IngredientRepositoryInterface;
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
     * Display a listing of ingredients.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $response = IngredientService::getAll();

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
        $response = IngredientService::create($request);

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
     * @param Ingredient $ingredient
     * @return JsonResponse
     */
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient): JsonResponse
    {
        $response = IngredientService::update($request, $ingredient);

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
     * @param Ingredient $ingredient
     * @return JsonResponse
     */
    public function destroy(Ingredient $ingredient): JsonResponse
    {
        $response = IngredientService::delete($ingredient);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Ingredient deleted successfully'
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }
}
