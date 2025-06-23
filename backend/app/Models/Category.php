<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * Represents a recipe category.
 *
 * @property int $id
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
class Category extends Model
{
    protected $table = 'CATEGORIES';
    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * Get the recipes that belong to this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'RECIPE_CATEGORIES', 'category_id', 'recipe_id');
    }
}
