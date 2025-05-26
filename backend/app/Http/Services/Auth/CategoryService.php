<?php

namespace App\Http\Services\Auth;

use App\Repositories\Recipes\CategoryRepository;
use Illuminate\Http\Request;

class CategoryService
{
    /**
     * @var 
     */
    protected $categories;

    /**
     * @param \App\Repositories\Recipes\CategoryRepository $categories
     */
    public function __construct(CategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->categories->all(),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $category = $this->categories->create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $category = $this->categories->find($id);
        $this->categories->update($category, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, string $id)
    {
        $category = $this->categories->find($id);
        $this->categories->delete($category);

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
        ]);
    }
}
