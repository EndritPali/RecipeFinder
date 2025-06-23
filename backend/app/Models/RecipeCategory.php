<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RecipeCategory
 *
 * Represents the pivot between recipes and categories.
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $category_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
class RecipeCategory extends Model
{
    protected $table = 'RECIPE_CATEGORIES';
    public $timestamps = false;

    protected $fillable = ['recipe_id', 'category_id'];

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
     * Get the category for this pivot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
