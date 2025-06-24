<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ToggleLikeRequest;
use App\Models\Comment;
use App\Support\Classes\ServiceResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Class LikeController
 *
 * Handles HTTP requests related to comment likes.
 */
final class LikeController extends ApiController
{
    /**
     * Toggle like status for a comment.
     *
     * @param ToggleLikeRequest $request The validated request
     * @param Comment $comment The comment model instance
     * @return JsonResponse Response indicating success or failure
     */
    public function toggleLike(ToggleLikeRequest $request, Comment $comment): JsonResponse
    {
        try {
            $user = $request->user();
            $action = $request->validated('action');

            if ($action === 'like') {
                $comment->increment('likes');
                $message = 'Comment liked successfully';
            } elseif ($action === 'unlike' && $comment->likes > 0) {
                $comment->decrement('likes');
                $message = 'Comment unliked successfully';
            } else {
                return $this->errorResponse('Invalid like action', 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'likes' => $comment->likes,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to toggle comment like', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'comment_id' => $comment->id,
                'user_id' => $user?->id,
                'action' => $action ?? null
            ]);

            return $this->errorResponse('Failed to process like action', 500);
        }
    }
}
