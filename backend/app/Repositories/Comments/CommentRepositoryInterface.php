<?php

declare(strict_types=1);

namespace App\Repositories\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface CommentRepositoryInterface
 *
 * Defines the contract for comment data persistence operations.
 */
interface CommentRepositoryInterface
{
    /**
     * Get all comments with their associated users.
     *
     * @return Collection<int, Comment> Returns a collection of comments with eager loaded users
     */
    public function getAllWithUser(): Collection;

    /**
     * Get paginated comments with their associated users.
     *
     * @param int|null $perPage Number of items per page (default: 10)
     * @return LengthAwarePaginator Returns a paginator instance of comments with eager loaded users
     */
    public function getPaginated(?int $perPage = null): LengthAwarePaginator;

    /**
     * Create a new comment.
     *
     * @param array<string, mixed> $data The comment data
     * @return Comment The created comment instance
     */
    public function create(array $data): Comment;

    /**
     * Find a comment by ID with its associated user.
     *
     * @param string $id The comment ID
     * @return Comment The comment with eager loaded user
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When comment is not found
     */
    public function findWithUser(string $id): Comment;

    /**
     * Find a comment by ID.
     *
     * @param string $id The comment ID
     * @return Comment The comment instance
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When comment is not found
     */
    public function find(int|string $id): Comment;

    /**
     * Update an existing comment.
     *
     * @param Comment $comment The comment to update
     * @param array<string, mixed> $data The updated comment data
     * @return Comment The updated comment instance
     */
    public function update(Comment $comment, array $data): Comment;

    /**
     * Delete a comment.
     *
     * @param Comment $comment The comment to delete
     */
    public function delete(Comment $comment): void;
}
