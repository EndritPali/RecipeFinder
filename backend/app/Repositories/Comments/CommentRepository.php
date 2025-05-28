<?php

namespace  App\Repositories\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Comment>
     */
    public function getAllWithUser(): Collection
    {
        return Comment::with('user')->latest()->get();
    }

    /**
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    /**
     * @param string $id
     * @return Comment
     */
    public function findWithUser(string $id): Comment
    {
        return Comment::with('user')->findOrFail($id);
    }

    /**
     * @param string $id
     * @return Comment
     */
    public function find(string $id): Comment
    {
        return Comment::findOrFail($id);
    }

    /**
     * @param \App\Models\Comment $comment
     * @param array $data
     * @return Comment
     */
    public function update(Comment $comment, array $data): Comment
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
