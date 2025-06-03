<?php

declare(strict_types=1);

namespace App\Repositories\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CommentRepository
 *
 * Implements the persistence operations for comments.
 */
final class CommentRepository implements CommentRepositoryInterface
{
    /**
     * Get all comments with their associated users.
     *
     * @return Collection<int, Comment> Returns a collection of comments with eager loaded users
     */
    public function getAllWithUser(): Collection
    {
        return Comment::with('user')->latest()->get();
    }

    /**
     * Create a new comment.
     *
     * @param array<string, mixed> $data The comment data
     * @return Comment The created comment instance
     */
    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    /**
     * Find a comment by ID with its associated user.
     *
     * @param string $id The comment ID
     * @return Comment The comment with eager loaded user
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When comment is not found
     */
    public function findWithUser(string $id): Comment
    {
        return Comment::with('user')->findOrFail($id);
    }

    /**
     * Find a comment by ID.
     *
     * @param string $id The comment ID
     * @return Comment The comment instance
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When comment is not found
     */
    public function find(string $id): Comment
    {
        return Comment::findOrFail($id);
    }

    /**
     * Update an existing comment.
     *
     * @param Comment $comment The comment to update
     * @param array<string, mixed> $data The updated comment data
     * @return Comment The updated comment instance
     */
    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment;
    }

    /**
     * Delete a comment.
     *
     * @param Comment $comment The comment to delete
     */
    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
