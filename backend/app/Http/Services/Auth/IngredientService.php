<?php

namespace App\Http\Services\Auth;

use App\Repositories\Recipes\IngredientRepositoryInterface;
use App\Http\Requests\Api\V1\StoreIngredientRequest;
use App\Http\Requests\Api\V1\UpdateIngredientRequest;

class IngredientService
{
    /**
     * @var IngredientRepositoryInterface
     */
    protected $ingredients;

    /**
     * @param \App\Repositories\Recipes\IngredientRepositoryInterface $ingredients
     */
    public function __construct(IngredientRepositoryInterface $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $ingredients = $this->ingredients->all();

        return response()->json([
            'status' => 'success',
            'data' => $ingredients,
        ]);
    }
    /**
     * @param \App\Http\Requests\Api\V1\StoreIngredientRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(StoreIngredientRequest $request)
    {
        $ingredient = $this->ingredients->create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient created successfully',
            'data' => $ingredient,
        ], 201);
    }

    /**
     * @param \App\Http\Requests\Api\V1\UpdateIngredientRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateIngredientRequest $request, string $id)
    {
        $ingredient = $this->ingredients->update($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient updated successfully',
            'data' => $ingredient,
        ]);
    }

    /**
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $this->ingredients->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient deleted successfully',
        ]);
    }
}
