<?php

namespace App\Repositories\Recipes;

use App\Models\Ingredient;

interface IngredientRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Ingredient>
     */
    public function all();

    /**
     * @param array $data
     * @return Ingredient
     */
    public function create(array $data);

    /**
     * @param string $id
     * @return Ingredient
     */
    public function find(string $id): Ingredient;

    /**
     * @param string $id
     * @param array $data
     * @return Ingredient
     */
    public function update(string $id, array $data);

    /**
     * @param string $id
     * @return void
     */
    public function delete(string $id): void;
}
