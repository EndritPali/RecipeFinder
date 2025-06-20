<?php

declare(strict_types=1);

namespace App\Repositories\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class CommentRepository
 *
 * Implements the persistence operations for comments.
 */
final class CommentRepository implements CommentRepositoryInterface
{
    public function __construct(
        private readonly Comment $model
    ) {}

    /**
     * Get all comments with their associated users.
     *
     * @return Collection<int, Comment> Returns a collection of comments with eager loaded users
     */
    public function getAllWithUser(): Collection
    {
        return $this->model->newQuery()->with('user')->get();
    }

    /**
     * Get paginated comments with their associated users.
     *
     * @param int|null $perPage Number of items per page (default: 10)
     * @return LengthAwarePaginator Returns a paginator instance of comments with eager loaded users
     */
    public function getPaginated(?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? 10;
        $perPage = min(max($perPage, 1), 100);

        return $this->model->newQuery()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    /**
     * Find a comment by ID with its associated user.
     *
     * @param string $id The comment ID
     * @return Comment The comment with eager loaded user
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When comment is not found
     */
    public function findWithUser(int|string $id): Comment
    {
        try {
            return $this->model->newQuery()->with('user')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(
                "Comment with ID {$id} not found.",
                previous: $e
            );
        }
    }

    /**
     * Find a comment by ID.
     *
     * @param string $id The comment ID
     * @return Comment The comment instance
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When comment is not found
     */
    public function find(int|string $id): Comment
    {
        try {
            return $this->model->newQuery()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(
                "Comment with ID {$id} not found.",
                previous: $e
            );
        }
    }

    /**
     * Create a new comment.
     *
     * @param array<string, mixed> $data The comment data
     * @return Comment The created comment instance
     */
    public function create(array $data): Comment
    {
        $requiredFields = ['user_id', 'description'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new InvalidArgumentException("Missing required field: {$field}");
            }
        }

        try {
            return $this->model->newQuery()->create($data);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to create comment: {$e->getMessage()}",
                previous: $e
            );
        }
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
        if (empty($data)) {
            throw new InvalidArgumentException('No data provided for update');
        }

        try {
            $comment->update($data);
            return $comment;
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to update comment {$comment->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * Delete a comment.
     *
     * @param Comment $comment The comment to delete
     * @return void
     */
    public function delete(Comment $comment): void
    {
        try {
            $comment->delete();
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to delete comment {$comment->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }
}
