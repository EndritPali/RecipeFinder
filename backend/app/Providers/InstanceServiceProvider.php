<?php

namespace App\Providers;


use App\Http\Services\Auth\UserService;
use App\Http\Services\RecipeRelationService;
use App\Http\Services\RecipeService;
use App\Models\Recipe;
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
    }
}
