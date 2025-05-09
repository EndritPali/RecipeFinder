<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::all();

        return response()->json([
            'status' => 'success',
            'data' => $ingredients,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
        ]);


        $ingredients = Ingredient::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient created successfully',
            'data' => $ingredients
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ingredients = Ingredient::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
        ]);

        $ingredients->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient updated successfully',
            'data' => $ingredients
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ingredient deleted successfully',
        ]);
    }
}
