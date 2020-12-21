<?php

use App\Http\Controllers\BlogViewController;
use App\Http\Controllers\SignUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogViewController::class, 'index']);
Route::get('posts/{post}', [BlogViewController::class, 'show'])->name('post.show');

Route::get('signup', [SignUpController::class, 'index']);
Route::post('signup', [SignUpController::class, 'store']);

Route::get('/mypage/login', [\App\Http\Controllers\Mypage\UserLoginController::class, 'index']);
Route::post('/mypage/login', [\App\Http\Controllers\Mypage\UserLoginController::class, 'login']);
