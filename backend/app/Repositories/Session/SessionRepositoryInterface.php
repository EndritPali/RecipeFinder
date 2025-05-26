<?php

namespace App\Repositories\Session;

use App\Models\Session;

interface SessionRepositoryInterface
{
    /**
     * @param int $userId
     * @param string $token
     * @return void
     */
    public function create(int $userId, string $token): Session;

    /**
     * @param string $token
     * @return void
     */
    public function deleteByToken(string $token): int;
}
