<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RecipeCategoryController;
use App\Http\Controllers\SavedRecipeController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeIngredientController;
use Illuminate\Http\Request;

// Route::middleware('auth:sanctum')->post('/recipes', [RecipeController::class, 'store']);
Route::apiResource('recipes', RecipeController::class)->only(['index']);
Route::apiResource('ingredients', IngredientController::class);
Route::apiResource('category', CategoryController::class);
Route::apiResource('recipe-ingredients', RecipeIngredientController::class)->only(['store', 'destroy']);
Route::apiResource('recipe-category', RecipeCategoryController::class)->only(['store', 'destroy']);
Route::post('/register', [UserController::class, 'register']);



Route::post('/upload-image', function (Illuminate\Http\Request $request) {
    $request->validate([
        'image' => 'required|image|max:2048',
    ]);

    $filename = time() . '_' . rand(1000, 9999) . '.' . $request->file('image')->getClientOriginalExtension();
    $request->file('image')->move(public_path('images'), $filename);

    $url = url('images/' . $filename);

    return response()->json(['url' => $url]);
});

Route::prefix('auth')->group(function () {
    route::post('login', [SessionController::class, 'login']);
    Route::post('/logout', [SessionController::class, 'logout']);
    Route::post('/password-reset/request', [PasswordResetController::class, 'requestReset']);
    Route::post('/password-reset/reset', [PasswordResetController::class, 'resetPassword']);
});

Route::middleware('auth.token')->get('/me', fn(Request $request) => response()->json($request->user()));
Route::post('/auth/password-reset/submit', [PasswordResetController::class, 'submitResetRequest']);
Route::apiResource('comments', CommentController::class)->only(['index', 'show']);

Route::middleware(['auth.token', 'role:User,Admin'])->group(function () {
    Route::get('/admin', fn() => response()->json(['message' => 'Dashboard']));
    Route::get('/my-recipes', [RecipeController::class, 'myRecipes']);
    Route::apiResource('recipes', RecipeController::class)->only(['destroy', 'update', 'store']);
    Route::apiResource('comments', CommentController::class)->only(['destroy', 'update', 'store']);
    Route::apiResource('saved-recipes', SavedRecipeController::class);
    Route::post('/comments/{id}/like', [CommentController::class, 'toggleLike']);

    // Move this outside of the Admin-only nested group
    Route::apiResource('user', UserController::class)->only(['update']);
});

Route::middleware(['auth.token', 'role:Admin'])->group(function () {
    Route::apiResource('user', UserController::class)->except('update');
    Route::get('/auth/password-reset/pending', [PasswordResetController::class, 'getPendingRequests']);
    Route::post('/auth/password-reset/process', [PasswordResetController::class, 'processResetRequest']);
});
