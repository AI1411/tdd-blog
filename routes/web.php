<?php

use App\Http\Controllers\BlogViewController;
use App\Http\Controllers\Mypage\PostMypageController;
use App\Http\Controllers\Mypage\UserLoginController;
use App\Http\Controllers\SignUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogViewController::class, 'index']);
Route::get('posts/{post}', [BlogViewController::class, 'show'])->name('post.show');

Route::get('signup', [SignUpController::class, 'index']);
Route::post('signup', [SignUpController::class, 'store']);

Route::get('mypage/login', [UserLoginController::class, 'index'])->name('login');
Route::post('mypage/login', [UserLoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::post('mypage/logout', [UserLoginController::class, 'logout']);

    Route::get('mypage/posts', [PostMypageController::class, 'index']);
    Route::get('mypage/posts/create', [PostMypageController::class, 'create']);
    Route::post('mypage/posts/create', [PostMypageController::class, 'store']);
    Route::get('mypage/posts/edit/{post}', [PostMypageController::class, 'edit'])->name('mypage.posts.edit');
    Route::post('mypage/posts/edit/{post}', [PostMypageController::class, 'update'])->name('mypage.posts.update');
    Route::delete('mypage/posts/delete/{post}', [PostMypageController::class, 'destroy'])->name('mypage.posts.delete');
});
