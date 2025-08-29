<?php

namespace App\Providers;

use App\Http\Services\Auth\SessionAuthenticationInterface;
use App\Http\Services\Auth\SessionAuthenticationService;
use App\Repositories\Session\SessionRepositoryInterface;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\Contracts\UserRepositoryInterface;
use App\Repositories\Session\SessionRepository;
use App\Repositories\Recipes\IngredientRepository;
use App\Repositories\Recipes\IngredientRepositoryInterface;
use App\Repositories\PasswordReset\PasswordResetRepository;
use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;
use App\Repositories\Recipes\CategoryRepository;
use App\Repositories\Recipes\CategoryRepositoryInterface;
use App\Repositories\Recipes\RecipeRepository;
use App\Repositories\Recipes\RecipeRepositoryInterface;
use App\Repositories\Comments\CommentRepository;
use App\Repositories\Comments\CommentRepositoryInterface;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            SessionAuthenticationInterface::class,
            SessionAuthenticationService::class
        );

        $this->app->bind(
            SessionRepositoryInterface::class,
            SessionRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            IngredientRepositoryInterface::class,
            IngredientRepository::class
        );

        $this->app->bind(
            PasswordResetRepositoryInterface::class,
            PasswordResetRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            RecipeRepositoryInterface::class,
            RecipeRepository::class
        );

        $this->app->bind(
            CommentRepositoryInterface::class,
            CommentRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
