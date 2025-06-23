<?php

namespace App\Repositories\Session;

use App\Models\Session;
use App\Repositories\Session\SessionRepositoryInterface;
use Illuminate\Support\Carbon;

class SessionRepository implements SessionRepositoryInterface
{
    /**
     * @param int $userid
     * @param string $token
     * @return Session
     */
    public function create(int $userid, string $token): Session
    {
        return Session::create([
            'user_id' => $userid,
            'token' => $token,
            'expires_at' => Carbon::now()->addDays(7),
            'created_at' => now()
        ]);
    }

    /**
     * @param string $token
     * @return bool|null
     */
    public function deleteByToken(string $token): int
    {
        return Session::where('token', $token)->delete();
    }

    /**
     * Find a session by token.
     *
     * @param string $token
     * @return Session|null
     */
    public function findByToken(string $token): ?Session
    {
        return Session::with('user')->where('token', $token)->first();
    }
}
