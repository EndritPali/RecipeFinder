<?php

namespace App\Repositories\Recipes;

use App\Models\Ingredient;

class IngredientRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Ingredient>
     */
    public function all()
    {
        return Ingredient::all();
    }

    /**
     * @param array $data
     * @return Ingredient
     */
    public function create(array $data)
    {
        return Ingredient::create($data);
    }

    /**
     * @param string $id
     * @return Ingredient
     */
    public function find(string $id): Ingredient
    {
        return Ingredient::findOrFail($id);
    }

    /**
     * @param string $id
     * @param array $data
     * @return Ingredient
     */
    public function update(string $id, array $data)
    {
        $ingredient = $this->find($id);
        $ingredient->update($data);
        return $ingredient;
    }

    /**
     * @param string $id
     * @return void
     */
    public function delete(string $id): void
    {
        $ingredient = $this->find($id);
        $ingredient->delete();
    }
}
