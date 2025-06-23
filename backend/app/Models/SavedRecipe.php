<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SavedRecipe
 *
 * Represents a recipe saved by a user.
 *
 * @property int $id
 * @property int $user_id
 * @property int $recipe_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
class SavedRecipe extends Model
{
    protected $table = 'saved_recipes';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'recipe_id',
    ];

    /**
     * Get the user who saved the recipe.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recipe that was saved.
     *
     * @return BelongsTo
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
