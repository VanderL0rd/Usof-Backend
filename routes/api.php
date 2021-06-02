<?php

use Illuminate\Routing\Route;
use App\Http\Controllers\AuthControl;
use App\Http\Controllers\UserControl;
use App\Http\Controllers\CatControl;
use App\Http\Controllers\PostControl;
use App\Http\Controllers\CommentControl;
use App\Http\Controllers\LikeControl;

Route::post('/auth/register', [AuthControl::class, 'register']);
Route::post('/auth/login', [AuthControl::class, 'login']);
Route::get('/posts', [PostControl::class, 'index']);
Route::get('/posts/{id}', [PostControl::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [UserControl::class, 'index']);
    Route::post('/auth/logout', [AuthControl::class, 'logout']);
    //!------------------------------------------------------!//
    Route::post('/users/avatar', [UserControl::class, 'update_picture']);
    Route::get('/users/{login}', [UserControl::class, 'search']);
    Route::patch('/users/{id}', [UserControl::class, 'update']);
    Route::delete('/users/{id}', [UserControl::class, 'destroy']);
    //!------------------------------------------------------!//
    Route::post('/categories', [CatControl::class, 'store']);
    Route::patch('/categories/{id}', [CatControl::class, 'update']);
    Route::delete('/categories/{id}', [CatControl::class, 'destroy']);
    Route::get('/categories', [CatControl::class, 'index']);
    Route::get('/categories/{id}', [CatControl::class, 'show']);
    Route::get('/categories/{id}/posts', [CatControl::class, 'show_posts']);
    //!------------------------------------------------------!//
    Route::post('/posts', [PostControl::class, 'store']);
    Route::patch('/posts/{id}', [PostControl::class, 'update']);
    Route::delete('/posts/{id}', [PostControl::class, 'destroy']);
    Route::get('/posts/{id}/categories', [PostControl::class, 'show_cats']);
    Route::post('/posts/{id}/comments', [CommentControl::class, 'store']);
    Route::get('/posts/{id}/comments', [CommentControl::class, 'show_spec']);
    Route::post('/posts/{id}/like', [LikeControl::class, 'store']);
    Route::get('/posts/{id}/like', [LikeControl::class, 'show_post']);
    Route::delete('/posts/{id}/like', [LikeControl::class, 'destroy_post']);
    //!------------------------------------------------------!//
    Route::get('/comments/{id}', [CommentControl::class, 'show']);
    Route::patch('/comments/{id}', [CommentControl::class, 'update']);
    Route::delete('/comments/{id}', [CommentControl::class, 'destroy']);
    Route::post('/comments/{id}/like', [LikeControl::class, 'store_com']);
    Route::get('/comments/{id}/like', [LikeControl::class, 'show_comment']);
    Route::delete('/comments/{id}/like', [LikeControl::class, 'destroy_com']);
});
