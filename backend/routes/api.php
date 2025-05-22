<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\Auth\SessionController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\UserController;

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecipeIngredientController;
use App\Http\Controllers\RecipeCategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SavedRecipeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MyRecipesController;


Route::apiResource('recipes', RecipeController::class)->only(['index']);
Route::apiResource('ingredients', IngredientController::class);
Route::apiResource('category', CategoryController::class);
Route::apiResource('recipe-ingredients', RecipeIngredientController::class)->only(['store', 'destroy']);
Route::apiResource('recipe-category', RecipeCategoryController::class)->only(['store', 'destroy']);
Route::apiResource('comments', CommentController::class)->only(['index', 'show']);

Route::post('/upload-image', function (Request $request) {
    $request->validate([
        'image' => 'required|image|max:2048',
    ]);

    $filename = time() . '_' . rand(1000, 9999) . '.' . $request->file('image')->getClientOriginalExtension();
    $request->file('image')->move(public_path('images'), $filename);

    return response()->json(['url' => url('images/' . $filename)]);
});

Route::prefix('api/v1')->group(function () {
    Route::apiResource('user', UserController::class);

    Route::prefix('auth')->group(function () {
        Route::post('login', [SessionController::class, 'login']);
        Route::post('logout', [SessionController::class, 'logout']);
        Route::post('password-reset/request', [PasswordResetController::class, 'requestReset']);
        Route::post('password-reset/reset', [PasswordResetController::class, 'resetPassword']);
        Route::post('password-reset/submit', [PasswordResetController::class, 'submitResetRequest']);
        Route::post('/register', [RegisterController::class, 'register']);
        Route::middleware('auth.token')->get('/me', fn(Request $request) => response()->json($request->user()));
    });
});

Route::middleware(['auth.token', 'role:User,Admin'])->group(function () {
    Route::get('/admin', fn() => response()->json(['message' => 'Dashboard']));
    Route::get('/my-recipes', [MyRecipesController::class, 'myRecipes']);

    Route::apiResource('recipes', RecipeController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('comments', CommentController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('saved-recipes', SavedRecipeController::class);

    Route::post('/comments/{id}/like', [LikeController::class, 'toggleLike']);

    Route::apiResource('user', UserController::class)->only(['update']);
});

Route::middleware(['auth.token', 'role:Admin'])->group(function () {
    Route::get('/auth/password-reset/pending', [PasswordResetController::class, 'getPendingRequests']);
    Route::post('/auth/password-reset/process', [PasswordResetController::class, 'processResetRequest']);

    Route::apiResource('user', UserController::class)->except(['update']);
});
