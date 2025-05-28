<?php

namespace App\Repositories\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Comment>
     */
    public function getAllWithUser(): Collection;

    /**
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment;

    /**
     * @param string $id
     * @return Comment
     */
    public function findWithUser(string $id): Comment;

    /**
     * @param string $id
     * @return Comment
     */
    public function find(string $id): Comment;

    /**
     * @param \App\Models\Comment $comment
     * @param array $data
     * @return Comment
     */
    public function update(Comment $comment, array $data): Comment;

    /**
     * @param \App\Models\Comment $comment
     * @return void
     */
    public function delete(Comment $comment): void;
}
