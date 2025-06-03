<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use App\Http\Services\CommentService;
use Illuminate\Http\JsonResponse;

/**
 * Class CommentController
 *
 * Handles HTTP requests related to comment management.
 */
final class CommentController extends ApiController
{
    /**
     * @param CommentService $service
     */
    public function __construct(
        private readonly CommentService $service,
    ) {}

    /**
     * Display a listing of comments.
     */
    public function index(): JsonResponse
    {
        $response = $this->service->getAllComments();

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->getModel()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Store a newly created comment.
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $response = $this->service->createComment($request);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Comment posted successfully',
                'data' => $response->getModel()
            ], 201);
        }

        return $this->errorResponse($response->getMessage(), 400);
    }

    /**
     * Display the specified comment.
     */
    public function show(string $id): JsonResponse
    {
        $response = $this->service->getComment($id);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->getModel()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 404);
    }

    /**
     * Update the specified comment.
     */
    public function update(UpdateCommentRequest $request, string $id): JsonResponse
    {
        $response = $this->service->updateComment($request, $id);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Comment updated successfully',
                'data' => $response->getModel()
            ]);
        }

        return $this->errorResponse($response->getMessage(), 403);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(string $id): JsonResponse
    {
        $response = $this->service->deleteComment($id);

        if ($response->success()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Comment deleted successfully'
            ]);
        }

        return $this->errorResponse($response->getMessage(), 403);
    }
}
