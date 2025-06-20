<?php

namespace App\Providers;


use App\Http\Services\Auth\UserService;
use App\Http\Services\CategoryService;
use App\Http\Services\CommentService;
use App\Http\Services\RecipeRelationService;
use App\Http\Services\RecipeService;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Recipe;
use App\Repositories\Comments\CommentRepository;
use App\Repositories\Recipes\CategoryRepository;
use App\Repositories\Recipes\RecipeRepository;
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
    }
}
