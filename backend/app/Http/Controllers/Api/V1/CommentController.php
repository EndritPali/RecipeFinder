<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Services\Auth\CommentService;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @var 
     */
    protected $service;

    /**
     * @param \App\Http\Services\Auth\CommentService $service
     */
    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->service->getAllComments();
    }

    /**
     * @param \App\Http\Requests\Api\V1\StoreCommentRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreCommentRequest $request)
    {
        return $this->service->createComment($request);
    }

    /**
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->service->getComment($id);
    }

    /**
     * @param \App\Http\Requests\Api\V1\UpdateCommentRequest $request
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateCommentRequest $request, $id)
    {
        return $this->service->updateComment($request, $id);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        return $this->service->deleteComment($id);
    }
}
