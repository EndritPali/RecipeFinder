<?php

namespace App\Http\Services\Auth;

use App\Models\Comment;
use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function getAllComments()
    {
        $comments = Comment::with('user')->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => CommentResource::collection($comments)
        ]);
    }

    public function createComment(StoreCommentRequest $request)
    {
        $user = $request->user();

        $comment = Comment::create([
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

    public function getComment(string $id)
    {
        $comment = Comment::with('user')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

    public function updateComment(UpdateCommentRequest $request, string $id)
    {
        $comment = Comment::findOrFail($id);
        $user = $request->user();

        if ($user->id !== $comment->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $comment->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    public function deleteComment(string $id)
    {
        $comment = Comment::findOrFail($id);
        $user = Auth::user();

        if ($user->id !== $comment->user_id && $user->role !== 'Admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully'
        ]);
    }
}
