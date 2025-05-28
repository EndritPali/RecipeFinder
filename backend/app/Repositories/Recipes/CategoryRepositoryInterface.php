<?php

namespace App\Repositories\Recipes;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Category>
     */
    public function all();

    /**
     * @param string $id
     * @return Category
     */
    public function find(string $id);

    /**
     * @param array $data
     * @return Category
     */
    public function create(array $data);

    /**
     * @param \App\Models\Category $category
     * @param array $data
     * @return bool
     */
    public function update(Category $category, array $data);

    /**
     * @param \App\Models\Category $category
     * @return bool|null
     */
    public function delete(Category $category);
}
