<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RecipeIngredient
 *
 * Represents the pivot between recipes and ingredients.
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $ingredient_id
 * @property string $quantity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
class RecipeIngredient extends Model
{
    protected $table = 'RECIPE_INGREDIENTS';
    public $timestamps = false;

    protected $fillable = ['recipe_id', 'ingredient_id', 'quantity'];

    /**
     * Get the recipe for this pivot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id');
    }

    /**
     * Get the ingredient for this pivot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}
