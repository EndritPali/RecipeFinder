<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'CATEGORIES';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'RECIPE_CATEGORIES', 'category_id', 'recipe_id');
    }
}

