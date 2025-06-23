<?php

namespace App\Policies;

use App\Models\SavedRecipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SavedRecipePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SavedRecipe $savedRecipe): Response
    {
        return $this->isAuthenticated($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->isAuthenticated($user);
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        return $this->isAuthenticated($user);
    }

    private function isAuthenticated(User $user): Response
    {
        return $user
            ? Response::allow()
            : Response::deny('You must be logged in to save recipes');
    }
}
