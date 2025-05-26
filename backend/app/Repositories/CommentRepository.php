<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Comment>
     */
    public function getAllWithUser()
    {
        return Comment::with('user')->latest()->get();
    }

    /**
     * @param array $data
     * @return Comment
     */
    public function create(array $data)
    {
        return Comment::create($data);
    }

    /**
     * @param string $id
     * @return Comment
     */
    public function findWithUser(string $id)
    {
        return Comment::with('user')->findOrFail($id);
    }

    /**
     * @param string $id
     * @return Comment
     */
    public function find(string $id)
    {
        return Comment::findOrFail($id);
    }

    /**
     * @param \App\Models\Comment $comment
     * @param array $data
     * @return Comment
     */
    public function update(Comment $comment, array $data)
    {
        $comment->update($data);
        return $comment;
    }

    /**
     * @param \App\Models\Comment $comment
     * @return void
     */
    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
