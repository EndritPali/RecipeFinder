<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\IngredientController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\PasswordResetController;
use App\Http\Controllers\Api\V1\RecipeCategoryController;
use App\Http\Controllers\Api\V1\RecipeController;
use App\Http\Controllers\Api\V1\RecipeIngredientController;
use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\Api\V1\SessionController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| V1 API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Image Upload (utility route)
Route::post('/upload-image', function (Request $request) {
    $request->validate(['image' => 'required|image|max:2048']);
    $filename = time() . '_' . rand(1000, 9999) . '.' . $request->file('image')->getClientOriginalExtension();
    $request->file('image')->move(public_path('images'), $filename);
    return response()->json(['url' => url('images/' . $filename)]);
});

Route::prefix('v1')->group(function () {

    // --- Public Routes ---
    Route::apiResource('recipes', RecipeController::class)->only(['index', 'show']);
    Route::apiResource('comments', CommentController::class)->only(['index', 'show']);
    Route::apiResource('ingredients', IngredientController::class)->only(['index', 'show']);
    Route::apiResource('category', CategoryController::class)->only(['index', 'show']);

    Route::prefix('auth')->controller(SessionController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth.token');
    });

    Route::post('auth/register', [RegisterController::class, 'register']);

    Route::prefix('auth/password-reset')->controller(PasswordResetController::class)->group(function () {
        Route::post('request', 'requestReset');
        Route::post('reset', 'resetPassword');
        Route::post('submit', 'submitResetRequest');
    });

    // --- Authenticated User Routes ---
    Route::middleware(['auth.token', 'role:User,Admin'])->group(function () {
        Route::get('auth/me', fn(Request $request) => response()->json($request->user()));
        Route::get('my-recipes', [RecipeController::class, 'myRecipes']);

        // Recipes & Comments
        Route::apiResource('recipes', RecipeController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('comments', CommentController::class)->only(['store', 'update', 'destroy']);

        // Saved Recipes
        Route::controller(RecipeController::class)->prefix('saved-recipes')->name('saved-recipes.')->group(function () {
            Route::get('/', 'savedIndex')->name('index');
            Route::post('/', 'saveRecipe')->name('store');
            Route::get('/{recipe}', 'savedShow')->name('show');
            Route::delete('/{recipe}', 'savedDestroy')->name('destroy');
        });

        // Likes
        Route::post('comments/{comment}/like', [LikeController::class, 'toggleLike']);

        // Recipe Relationships
        Route::controller(RecipeCategoryController::class)->group(function () {
            Route::post('recipes/{recipe}/category', 'store');
            Route::delete('recipes/{recipe}/category/{category}', 'destroy');
        });

        Route::controller(RecipeIngredientController::class)->group(function () {
            Route::post('recipes/{recipe}/ingredients', 'store');
            Route::put('recipes/{recipe}/ingredients/{ingredient}', 'update');
            Route::delete('recipes/{recipe}/ingredients/{ingredient}', 'destroy');
        });

        // User self-management
        Route::apiResource('users', UserController::class)->only(['update', 'destroy']);
    });

    // --- Admin-Only Routes ---
    Route::middleware(['auth.token', 'role:Admin'])->group(function () {
        Route::get('/dashboard', fn() => response()->json(['message' => 'Dashboard']));

        // Admin User Management
        Route::apiResource('users', UserController::class)->except(['update', 'destroy']);

        // Admin Resource Management
        Route::apiResource('ingredients', IngredientController::class)->except(['index', 'show']);
        Route::apiResource('category', CategoryController::class)->except(['index', 'show']);

        // Admin Password Reset Management
        Route::prefix('auth/password-reset')->controller(PasswordResetController::class)->group(function () {
            Route::get('pending', 'getPendingRequests');
            Route::post('process', 'processResetRequest');
        });
    });
});
