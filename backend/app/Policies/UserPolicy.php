<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $target): Response
    {
        return $this->UserOrAdmin($user, $target);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->role === 'Admin'
            ? Response::allow()
            : Response::deny('User is not allowed to create other users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $target): Response
    {
        return $this->UserOrAdmin($user, $target);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $target): Response
    {
        return $this->UserOrAdmin($user, $target);
    }

    private function UserOrAdmin(User $user, User $target)
    {
        return ($user->role === 'Admin' ||  $user->id === $target->id)
            ? Response::allow()
            : Response::deny('You do not have permission to modify other users');
    }
}
