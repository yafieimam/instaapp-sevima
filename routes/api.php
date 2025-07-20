<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\CommentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    Route::post('/posts/{id}/like', [LikeController::class, 'toggleLike']);
    Route::get('/posts/{id}/likes', [LikeController::class, 'showLikes']);

    Route::post('/posts/{id}/comments', [CommentController::class, 'store']);
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});