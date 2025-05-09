<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'INGREDIENTS';
    public $timestamps = false;

    protected $fillable = ['name', 'unit'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'RECIPE_INGREDIENTS', 'ingredient_id', 'recipe_id')
            ->withPivot('quantity');
    }
}
