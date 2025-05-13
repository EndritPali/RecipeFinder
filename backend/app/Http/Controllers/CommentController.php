<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Get all comments with users
    public function index()
    {
        $comments = Comment::with('user')->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => CommentResource::collection($comments)
        ]);
    }

    // Store a new comment
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id'    => $user->id,
            'description' => $validated['description'],
            'posted_at'   => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment posted successfully',
            'data' => $comment->load('user')
        ], 201);
    }

    // Show single comment
    public function show($id)
    {
        $comment = Comment::with('user')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

    // Update comment (only owner)
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $user = $request->user();

        if ($user->id !== $comment->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    // Delete comment (only owner)
    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $user = $request->user();

        if ($user->id !== $comment->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully'
        ]);
    }
}
