<?php

namespace App\Providers;

use App\Http\Services\Auth\SessionAuthenticationInterface;
use App\Http\Services\Auth\SessionAuthenticationService;
use App\Repositories\Session\SessionRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Session\SessionRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(SessionAuthenticationInterface::class, SessionAuthenticationService::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
