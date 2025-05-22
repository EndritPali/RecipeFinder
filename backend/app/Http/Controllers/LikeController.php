<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike(Request $request, $id)
    {
        $user = $request->user();
        $comment = Comment::findOrFail($id);

        $action = $request->input('action'); // 'like' or 'unlike'

        if ($action === 'like') {
            $comment->increment('likes');
        } elseif ($action === 'unlike' && $comment->likes > 0) {
            $comment->decrement('likes');
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid like action'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Comment ' . $action . 'd successfully',
            'likes' => $comment->likes,
        ]);
    }
}
