<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Services\Auth\CategoryService;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * @var 
     */
    protected $service;

    /**
     * @param \App\Http\Services\Auth\CategoryService $service
     */
    public function __construct(CategoryService $service)
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
     * @param \App\Http\Requests\Api\V1\StoreCategoryRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        return $this->service->create($request);
    }

    /**
     * @param \App\Http\Requests\Api\V1\UpdateCategoryRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, string $id)
    {
        return $this->service->delete($request, $id);
    }
}
