<?php

namespace App\Support\Classes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceResponse
{
    /**
     * @var bool
     */
    private $success;

    /**
     * @var Model|Collection|JsonResource|ResourceCollection|LengthAwarePaginator|null
     */
    private $model;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @param bool $success
     * @param Model|Collection|JsonResource|ResourceCollection|LengthAwarePaginator|null $model
     * @param string|null $message
     */
    public function __construct(bool $success, $model = null, ?string $message = null)
    {
        $this->success = $success;
        $this->model = $model;
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function success(): bool
    {
        return $this->success;
    }

    /**
     * @return Model|Collection|JsonResource|ResourceCollection|LengthAwarePaginator|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'model' => $this->model
        ];
    }
}
