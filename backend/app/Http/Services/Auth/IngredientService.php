<?php

namespace App\Http\Services\Auth;

use App\Models\Ingredient;
use App\Http\Requests\Api\V1\StoreIngredientRequest;
use App\Http\Requests\Api\V1\UpdateIngredientRequest;

class IngredientService
{
    public function getAll()
    {
        $ingredients = Ingredient::all();

        return response()->json([
            'status' => 'success',
            'data' => $ingredients,
        ]);
    }

    public function create(StoreIngredientRequest $request)
    {
        $ingredient = Ingredient::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient created successfully',
            'data' => $ingredient
        ], 201);
    }

    public function update(UpdateIngredientRequest $request, string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient updated successfully',
            'data' => $ingredient
        ]);
    }

    public function delete(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient deleted successfully',
        ]);
    }
}
