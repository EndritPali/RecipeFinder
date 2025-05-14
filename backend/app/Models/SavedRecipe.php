<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recipe that was saved.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
