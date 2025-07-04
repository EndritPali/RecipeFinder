<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Events\CommentCreated;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Support\Classes\ServiceResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CommentService
 *
 * Handles all business logic related to comment operations.
 */
final class CommentService
{
    private static CommentRepositoryInterface $commentRepository;

    /**
     * Create a new CommentService instance.
     */
    public function __construct(
        CommentRepositoryInterface $commentRepository
    ) {
        self::$commentRepository = $commentRepository;
    }

    /**
     * Retrieve paginated comments with their users.
     *
     * @param int|null $perPage Number of items per page
     * @return LengthAwarePaginator Returns a paginated collection of comments with their associated users
     *
     * @throws Exception When database query fails
     */
    public static function getAllComments(?int $perPage = null): LengthAwarePaginator
    {
        try {
            return self::$commentRepository->getPaginated($perPage);
        } catch (Exception $e) {
            Log::channel('commentslog')->error('Failed to get paginated comments', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'per_page' => $perPage
            ]);

            throw $e;
        }
    }

    /**
     * Create a new comment.
     *
     * @param StoreCommentRequest $request The validated request containing comment data
     * @return ServiceResponse<JsonResource> Returns the created comment with its user
     *
     * @throws Exception When comment creation fails
     */
    public static function create(StoreCommentRequest $request): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $comment = self::$commentRepository->create([
                'user_id' => $request->user()->id,
                'description' => $request->validated('description'),
                'posted_at' => now(),
            ]);

            DB::commit();
            event(new CommentCreated($comment->load('user')));

            return new ServiceResponse(
                true,
                new CommentResource($comment->load('user'))
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('commentslog')->error('Failed to create comment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->validated()
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to create comment'
            );
        }
    }

    /**
     * Get a specific comment with its user.
     *
     * @param string $id The comment ID
     * @return ServiceResponse<JsonResource> Returns the comment with its user
     *
     * @throws Exception When comment retrieval fails
     */
    public static function getComment(int|string $id): ServiceResponse
    {
        try {
            $comment = self::$commentRepository->findWithUser($id);

            return new ServiceResponse(
                true,
                new CommentResource($comment)
            );
        } catch (Exception $e) {
            Log::channel('commentslog')->error('Failed to get comment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id
            ]);

            return new ServiceResponse(
                false,
                null,
                'Comment not found'
            );
        }
    }

    /**
     * Update an existing comment.
     *
     * @param UpdateCommentRequest $request The validated request containing updated comment data
     * @param string $id The comment ID
     * @return ServiceResponse<JsonResource> Returns the updated comment
     *
     * @throws Exception When comment update fails
     */
    public static function update(UpdateCommentRequest $request, int|string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $comment = self::$commentRepository->find($id);
            $updated = self::$commentRepository->update($comment, $request->validated());

            DB::commit();
            event(new CommentCreated($comment->load('user')));

            return new ServiceResponse(
                true,
                new CommentResource($updated)
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('commentslog')->error('Failed to update comment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id,
                'request' => $request->validated()
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to update comment'
            );
        }
    }

    /**
     * Delete a comment.
     *
     * @param string $id The comment ID
     * @return ServiceResponse<null> Returns success status with no data
     *
     * @throws Exception When comment deletion fails
     */
    public static function destroy(int|string $id): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $comment = self::$commentRepository->find($id);
            self::$commentRepository->delete($comment);

            DB::commit();

            return new ServiceResponse(
                true,
                null,
                'Comment deleted successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('commentslog')->error('Failed to delete comment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id
            ]);

            return new ServiceResponse(
                false,
                null,
                'Failed to delete comment'
            );
        }
    }
}
