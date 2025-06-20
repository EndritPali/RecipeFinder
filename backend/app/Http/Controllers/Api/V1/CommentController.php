<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use App\Http\Services\CommentService;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Repositories\Comments\CommentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class CommentController
 *
 * Handles HTTP requests related to comment management.
 * 
 * This controller follows REST principles and Single Responsibility Pattern.
 *
 * @group Comments
 */
final class CommentController extends ApiController
{
    private $commentRepository;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        CommentRepositoryInterface $commentRepository,
    ) {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a paginated listing of comments.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page') ? (int) $request->get('per_page') : null;
        $comments = $this->commentRepository->getPaginated($perPage);
        
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created comment.
     *
     * @param StoreCommentRequest $request
     * @return CommentResource|JsonResponse
     */
    public function store(StoreCommentRequest $request): CommentResource|JsonResponse
    {
        $response = CommentService::create($request);

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 400);
        }

        return new CommentResource($response->getModel());
    }

    /**
     * Display the specified comment.
     *
     * @param string $id
     * @return CommentResource|JsonResponse
     */
    public function show(string $id): CommentResource|JsonResponse
    {
        try {
            $comment = $this->commentRepository->find($id);
            return new CommentResource($comment);
        } catch (\Exception $e) {
            return $this->errorResponse('Comment not found', 404);
        }
    }

    /**
     * Update the specified comment.
     *
     * @param UpdateCommentRequest $request
     * @param string $id
     * @return CommentResource|JsonResponse
     */
    public function update(UpdateCommentRequest $request, Comment $comment): CommentResource|JsonResponse
    {
        $this->authorize('update', $comment);
        $response = CommentService::update($request, $comment->id);

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 403);
        }

        return new CommentResource($response->getModel());
    }

    /**
     * Remove the specified comment.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);
        $response = CommentService::destroy($comment->id);

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 403);
        }

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
