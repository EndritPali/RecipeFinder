<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Recipe
 *
 * Represents a recipe entity.
 *
 * @property int $id
 * @property string $title
 * @property string $short_description
 * @property float $rating
 * @property string|null $image_url
 * @property string $instructions
 * @property int $preparation_time
 * @property int $cooking_time
 * @property int $servings
 * @property int $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
class Recipe extends Model
{
    protected $table = 'RECIPES';
    // public $timestamps = false;

    protected $fillable = [
        'title',
        'short_description',
        'rating',
        'image_url',
        'instructions',
        'preparation_time',
        'cooking_time',
        'servings',
        'created_by',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user who created the recipe.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the ingredients for the recipe.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'RECIPE_INGREDIENTS', 'recipe_id', 'ingredient_id')
            ->withPivot('quantity');
    }

    /**
     * Get the categories for the recipe.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'RECIPE_CATEGORIES', 'recipe_id', 'category_id');
    }
}
