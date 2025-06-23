<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ingredient
 *
 * Represents an ingredient used in recipes.
 *
 * @property int $id
 * @property string $name
 * @property string $unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
class Ingredient extends Model
{
    protected $table = 'INGREDIENTS';
    public $timestamps = false;

    protected $fillable = ['name', 'unit'];

    /**
     * Get the recipes that use this ingredient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'RECIPE_INGREDIENTS', 'ingredient_id', 'recipe_id')
            ->withPivot('quantity');
    }
}
