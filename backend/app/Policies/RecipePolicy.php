<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Recipe;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * Authorization policy for Recipe-related actions.
 *
 * This policy determines whether a user can perform specific actions on Recipe instances.
 * It follows the principle of single responsibility by focusing solely on authorization rules.
 */
final class RecipePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the recipe.
     *
     * @param User $user The user attempting the action
     * @param Recipe $recipe The recipe being updated
     * @return Response Authorization response
     */
    public function update(User $user, Recipe $recipe): Response
    {
        return $this->checkOwnershipOrAdmin($user, $recipe);
    }

    /**
     * Determine whether the user can delete the recipe.
     *
     * @param User $user The user attempting the action
     * @param Recipe $recipe The recipe being deleted
     * @return Response Authorization response
     */
    public function delete(User $user, Recipe $recipe): Response
    {
        return $this->checkOwnershipOrAdmin($user, $recipe);
    }

    /**
     * Check if user is admin or recipe owner.
     *
     * @param User $user The user to check
     * @param Recipe $recipe The recipe to check against
     * @return Response Authorization response
     */
    private function checkOwnershipOrAdmin(User $user, Recipe $recipe): Response
    {
        return ($user->role === 'Admin' || $recipe->created_by === $user->id)
            ? Response::allow()
            : Response::deny('You do not have permission to modify this recipe.');
    }
}
