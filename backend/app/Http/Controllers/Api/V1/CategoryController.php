<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Services\CategoryService;
use App\Repositories\Recipes\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 *
 * Handles HTTP requests related to category management.
 *
 * @package App\Http\Controllers\Api\V1
 */
final class CategoryController extends ApiController
{
    /**
     * Category service instance.
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * Create a new controller instance.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categryRepository)
    {
        $this->categoryRepository = $categryRepository;
    }

    /**
     * Display a listing of categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $response = CategoryService::getAll();

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->getModel()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Store a newly created category.
     *
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $response = CategoryService::create($request->validated());

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $response->getModel()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Update the specified category.
     *
     * @param UpdateCategoryRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $response = CategoryService::update($request->validated(), $category->id);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'data' => $response->getModel()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Remove the specified category.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $response = CategoryService::delete( $category->id);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }
}
