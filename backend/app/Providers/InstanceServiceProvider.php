<?php

namespace App\Providers;

use App\Http\Services\Auth\PasswordResetService;
use App\Http\Services\Auth\UserService;
use App\Http\Services\CategoryService;
use App\Http\Services\CommentService;
use App\Http\Services\IngredientService;
use App\Http\Services\RecipeCategoryService;
use App\Http\Services\RecipeIngredientService;
use App\Http\Services\RecipeRelationService;
use App\Http\Services\RecipeService;
use App\Http\Services\RegisterService;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Repositories\Comments\CommentRepository;
use App\Repositories\PasswordReset\PasswordResetRepository;
use App\Repositories\Recipes\CategoryRepository;
use App\Repositories\Recipes\RecipeRepository;
use App\Repositories\Users\UserRepository;
use App\Models\User;
use App\Repositories\Recipes\IngredientRepository;
use App\Http\Services\Auth\SessionAuthenticationService;
use App\Repositories\Session\SessionRepository;
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

        $this->app->instance(
            RecipeService::class,
            new RecipeService(
                new RecipeRepository(new Recipe()),
                new RecipeRelationService()
            )
        );

        $this->app->instance(
            CommentService::class,
            new CommentService(
                new CommentRepository(new Comment())
            )
        );

        $this->app->instance(
            CategoryService::class,
            new CategoryService(
                new CategoryRepository(new Category())
            )
        );

        $this->app->instance(
            RegisterService::class,
            new RegisterService(
                new UserRepository(new User())
            )
        );

        $this->app->instance(
            PasswordResetService::class,
            new PasswordResetService(
                new PasswordResetRepository()
            )
        );

        $this->app->instance(
            IngredientService::class,
            new IngredientService(
                new IngredientRepository(new Ingredient())
            )
        );

        $this->app->instance(
            RecipeIngredientService::class,
            new RecipeIngredientService(
                new RecipeRepository(new Recipe())
            )
        );

        $this->app->instance(
            RecipeCategoryService::class,
            new RecipeCategoryService(
                new RecipeRepository(new Recipe()),
                new CategoryRepository(new Category())
            )
        );

        $this->app->instance(
            SessionAuthenticationService::class,
            new SessionAuthenticationService(
                new SessionRepository()
            )
        );
    }
}
