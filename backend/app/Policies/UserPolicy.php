<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy for user authorization.
 *
 * Determines whether a user can view, create, update, or delete another user, based on admin role or ownership.
 *
 * @package App\Policies
 */
final class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the target user.
     *
     * @param User $user The user attempting the action
     * @param User $target The user being viewed
     * @return Response Authorization response
     */
    public function view(User $user, User $target): Response
    {
        return $this->UserOrAdmin($user, $target);
    }

    /**
     * Determine whether the user can create users.
     *
     * @param User $user The user attempting the action
     * @return Response Authorization response
     */
    public function create(User $user): Response
    {
        return $user->role === 'Admin'
            ? Response::allow()
            : Response::deny('User is not allowed to create other users');
    }

    /**
     * Determine whether the user can update the target user.
     *
     * @param User $user The user attempting the action
     * @param User $target The user being updated
     * @return Response Authorization response
     */
    public function update(User $user, User $target): Response
    {
        return $this->UserOrAdmin($user, $target);
    }

    /**
     * Determine whether the user can delete the target user.
     *
     * @param User $user The user attempting the action
     * @param User $target The user being deleted
     * @return Response Authorization response
     */
    public function delete(User $user, User $target): Response
    {
        return $this->UserOrAdmin($user, $target);
    }

    /**
     * Check if user is admin or the target user.
     *
     * @param User $user The user to check
     * @param User $target The user to check against
     * @return Response Authorization response
     */
    private function UserOrAdmin(User $user, User $target)
    {
        return ($user->role === 'Admin' ||  $user->id === $target->id)
            ? Response::allow()
            : Response::deny('You do not have permission to modify other users');
    }
}
