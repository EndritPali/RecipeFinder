<?php

namespace App\Repositories\Recipes;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Category>
     */
    public function all()
    {
        return Category::all();
    }

    /**
     * @param string $id
     * @return Category
     */
    public function find(string $id)
    {
        return Category::findOrFail($id);
    }

    /**
     * @param array $data
     * @return Category
     */
    public function create(array $data)
    {
        return Category::create($data);
    }

    /**
     * @param \App\Models\Category $category
     * @param array $data
     * @return bool
     */
    public function update(Category $category, array $data)
    {
        return $category->update($data);
    }

    /**
     * @param \App\Models\Category $category
     * @return bool|null
     */
    public function delete(Category $category)
    {
        return $category->delete();
    }
}
