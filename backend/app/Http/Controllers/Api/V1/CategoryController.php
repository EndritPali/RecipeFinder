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
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAll();
    }

    public function store(StoreCategoryRequest $request)
    {
        return $this->service->create($request);
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(Request $request, string $id)
    {
        return $this->service->delete($request, $id);
    }
}
