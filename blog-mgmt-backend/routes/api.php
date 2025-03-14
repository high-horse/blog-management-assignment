<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('profile', [AuthController::class, 'profile']);


    Route::get('users', [UserController::class, 'getUsers']);
    Route::post('users', [UserController::class, 'createUsers']);
    Route::get('users/{id}', [UserController::class, 'getUserDetail']);
    Route::post('users/{id}', [UserController::class, 'updateUserDetail']);
    Route::delete('users/{id}', [UserController::class, 'deleteUsers']);

    
    Route::get('blogs', [BlogController::class, 'getBlogs']);
    Route::post('blog', [BlogController::class, 'createBlog']);
    Route::get('blog/{id}', [BlogController::class, 'getBlogDetail']);
    Route::put('blog/{id}', [BlogController::class, 'updateBlogDetail']);
    Route::delete('blog/{id}', [BlogController::class, 'deleteBlog']);
    
    Route::get('resource/roles', [UserController::class, 'getResourceRoles']);
});