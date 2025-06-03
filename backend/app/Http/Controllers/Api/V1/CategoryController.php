<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Services\CategoryService;
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
    private CategoryService $service;

    /**
     * Create a new controller instance.
     *
     * @param CategoryService $service
     */
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of categories.
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
     * Store a newly created category.
     *
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $response = $this->service->create($request);

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
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $response = $this->service->update($request, $id);

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
    public function destroy(Request $request, string $id): JsonResponse
    {
        $response = $this->service->delete($request, $id);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }
}
