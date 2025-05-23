<?php

namespace App\Providers;

use App\Http\Services\Auth\SessionAuthenticationInterface;
use App\Http\Services\Auth\SessionAuthenticationService;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
