<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Recipe;

class RecipePolicy
{
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->role === 'Admin' || $recipe->created_by === $user->id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->role === 'Admin' || $recipe->created_by === $user->id;
    }
}
