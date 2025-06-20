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
    private static $categoryRepository;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        self::$categoryRepository = $categoryRepository;
    }

    /**
     * Retrieve all categories.
     *
     * @return ServiceResponse
     */
    public static function getAll(): ServiceResponse
    {
        try {
            $categories = self::$categoryRepository->all();
            return new ServiceResponse(true, $categories);
        } catch (Exception $e) {
            Log::channel('categorieslog')->error('CategoryService::getAll Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Store a new category in the database.
     *
     * @param Request $request
     * @return ServiceResponse
     */
    public static function create(array $data)
    {
        try {
            DB::beginTransaction();

            $category = self::$categoryRepository->create($data);

            DB::commit();
            return new ServiceResponse(true, $category, 'Category created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('categorieslog')->error('CategoryService::create Exception: ' . $e->getMessage());
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
    public static function update(array $data, int|string $id)
    {
        try {
            $category = self::$categoryRepository->find($id);
            self::$categoryRepository->update($category, $data);

            DB::commit();

            $updatedCategory = self::$categoryRepository->find($id);
            return new ServiceResponse(true, $updatedCategory, 'Category updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('categorieslog')->error('CategoryService::update Exception: ' . $e->getMessage());
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
    public static function delete(int|string $id)
    {

        try {
            DB::beginTransaction();

            $category = self::$categoryRepository->find($id);
            $deleted = self::$categoryRepository->delete($category);

            DB::commit();
            return new ServiceResponse($deleted, null, $deleted ? 'Category deleted successfully' : 'Deletion failed');
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('categorieslog')->error('CategoryService::delete Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}
