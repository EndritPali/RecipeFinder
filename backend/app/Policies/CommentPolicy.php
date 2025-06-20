<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): Response
    {
        return  $comment->user_id === $user->id
            ? Response::allow()
            : Response::deny('You do not have permission to edit this comment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): Response
    {
        return $this->checkOwnershipOrAdmin($user, $comment);
    }

    private function checkOwnershipOrAdmin(User $user, Comment $comment): Response
    {
        return ($user->role === 'Admin' || $comment->user_id === $user->id)
            ? Response::allow()
            : Response::deny('You do not have permission to modify this comment.');
    }
}
