<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'RECIPE_INGREDIENTS', 'recipe_id', 'ingredient_id')
            ->withPivot('quantity');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'RECIPE_CATEGORIES', 'recipe_id', 'category_id');
    }
}
