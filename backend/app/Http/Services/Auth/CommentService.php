<?php

namespace App\Http\Services\Auth;

use App\Repositories\Comments\CommentRepositoryInterface;
use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * @var CommentRepositoryInterface
     */
    protected CommentRepositoryInterface $repository;

    /**
     * @param \App\Repositories\Comments\CommentRepositoryInterface $repository
     */
    public function __construct(CommentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllComments()
    {
        $comments = $this->repository->getAllWithUser();

        return response()->json([
            'status' => 'success',
            'data' => CommentResource::collection($comments)
        ]);
    }

    /**
     * @param \App\Http\Requests\Api\V1\StoreCommentRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createComment(StoreCommentRequest $request)
    {
        $user = $request->user();

        $comment = $this->repository->create([
            'user_id'    => $user->id,
            'description' => $request->description,
            'posted_at'   => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment posted successfully',
            'data' => $comment->load('user')
        ], 201);
    }

    /**
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getComment(string $id)
    {
        $comment = $this->repository->findWithUser($id);

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

    /**
     * @param \App\Http\Requests\Api\V1\UpdateCommentRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateComment(UpdateCommentRequest $request, string $id)
    {
        $comment = $this->repository->find($id);
        $user = $request->user();

        if ($user->id !== $comment->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $updated = $this->repository->update($comment, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully',
            'data' => $updated
        ]);
    }

    /**
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function deleteComment(string $id)
    {
        $comment = $this->repository->find($id);
        $user = Auth::user();

        if ($user->id !== $comment->user_id && $user->role !== 'Admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $this->repository->delete($comment);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully'
        ]);
    }
}
