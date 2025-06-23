<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Policy for comment authorization.
 *
 * Determines whether a user can update or delete a comment, based on ownership or admin role.
 *
 * @package App\Policies
 */
class CommentPolicy
{
    /**
     * Determine whether the user can update the comment.
     *
     * @param User $user The user attempting the action
     * @param Comment $comment The comment being updated
     * @return Response Authorization response
     */
    public function update(User $user, Comment $comment): Response
    {
        return  $comment->user_id === $user->id
            ? Response::allow()
            : Response::deny('You do not have permission to edit this comment');
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param User $user The user attempting the action
     * @param Comment $comment The comment being deleted
     * @return Response Authorization response
     */
    public function delete(User $user, Comment $comment): Response
    {
        return $this->checkOwnershipOrAdmin($user, $comment);
    }

    /**
     * Check if user is admin or comment owner.
     *
     * @param User $user The user to check
     * @param Comment $comment The comment to check against
     * @return Response Authorization response
     */
    private function checkOwnershipOrAdmin(User $user, Comment $comment): Response
    {
        return ($user->role === 'Admin' || $comment->user_id === $user->id)
            ? Response::allow()
            : Response::deny('You do not have permission to modify this comment.');
    }
}
