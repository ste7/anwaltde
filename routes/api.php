<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TodoController;

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{post}', [PostController::class, 'show']);
Route::post('posts', [PostController::class, 'store']);
Route::put('posts/{post}', [PostController::class, 'update']);

Route::get('todos', [TodoController::class, 'index']);
Route::get('todos/{todo}', [TodoController::class, 'show']);
Route::post('todos', [TodoController::class, 'store']);
Route::put('todos/{todo}', [TodoController::class, 'update']);
