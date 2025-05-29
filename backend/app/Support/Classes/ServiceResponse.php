<?php

namespace App\Support\Classes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class ServiceResponse
{
    /**
     * @var bool
     */
    private $success;

    /**
     * @var Model|Collection|null
     */
    private $model;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @param bool $success
     * @param Model|Collection|null $model
     * @param string|null $message
     */
    public function __construct(bool $success, $model = null, string $message = null)
    {
        $this->success = $success;
        $this->model = $model;
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return $this->success;
    }

    /**
     * @return Model|Collection|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'success' => $this->success,
            'model' => $this->model
        ];
    }
}
