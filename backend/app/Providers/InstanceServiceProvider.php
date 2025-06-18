<?php

namespace App\Providers;


use App\Http\Services\Auth\UserService;
use App\Repositories\Users\UserRepository;
use App\Models\User;
use Illuminate\Support\ServiceProvider;


class InstanceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->instance(
            UserService::class,
            new UserService(
                new UserRepository(new User())
            )
        );
    }
}
