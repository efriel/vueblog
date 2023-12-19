<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('user')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('introspect', 'introspect');
    });
});

Route::resource('/post', PostsController::class);
Route::resource('/comment', CommentsController::class);

Route::controller(CommentsController::class)->group(function () {
    Route::get('comment/list/{post_id}', 'list');
    Route::post('comment/islike', 'isLike');
    Route::post('comment/like', 'like');
    Route::post('comment/unlike', 'unlike');
});

Route::controller(PostsController::class)->group(function () {
    Route::post('post/like', 'like');
    Route::post('post/unlike', 'unlike');
    Route::post('post/islike', 'isLike');
    Route::post('post/search', 'search');
});



