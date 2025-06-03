<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Repositories\Recipes\CategoryRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CategoryService
 *
 * Handles all business logic related to category operations such as
 * creating, retrieving, updating, and deleting categories.
 *
 * @package App\Http\Services
 */
final class CategoryService
{
    /**
     * Category repository for data access.
     */
    private CategoryRepositoryInterface $categories;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepositoryInterface $categories
     */
    public function __construct(CategoryRepositoryInterface $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Retrieve all categories.
     *
     * @return ServiceResponse
     */
    public function getAll(): ServiceResponse
    {
        try {
            $categories = $this->categories->all();
            return new ServiceResponse(true, $categories);
        } catch (Exception $e) {
            Log::error('CategoryService::getAll Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Store a new category in the database.
     *
     * @param Request $request
     * @return ServiceResponse
     */
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $category = $this->categories->create($validated);

            DB::commit();
            return new ServiceResponse(true, $category, 'Category created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CategoryService::create Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Update a category by its ID.
     *
     * @param Request $request
     * @param string $id
     * @return ServiceResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = $this->categories->find($id);
            $validated = $request->validated();
            $this->categories->update($category, $request->validated());

            DB::commit();

            $updatedCategory = $this->categories->find($id);
            return new ServiceResponse(true, $updatedCategory, 'Category updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CategoryService::update Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Delete a category by its ID.
     *
     * @param Request $request
     * @param string $id
     * @return ServiceResponse
     */
    public function delete(Request $request, string $id)
    {

        try {
            DB::beginTransaction();

            $category = $this->categories->find($id);
            $deleted = $this->categories->delete($category);

            DB::commit();
            return new ServiceResponse($deleted, null, $deleted ? 'Category deleted successfully' : 'Deletion failed');
        } catch (Exception $e) {
            DB::rollback();
            Log::error('CategoryService::delete Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}
