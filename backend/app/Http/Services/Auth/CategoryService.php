<?php

namespace App\Http\Services\Auth;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function getAll()
    {
        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    public function create(Request $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    public function delete(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
        ]);
    }
}
