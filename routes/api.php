<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // User API
    Route::controller(UserController::class)->group(function () {
        Route::get   ('users',      'index');
        Route::get   ('users/{id}', 'show');
        Route::post  ('users',      'store');
        Route::put   ('users/{id}', 'update');
        Route::delete('users/{id}', 'destroy');
    });

    // Category API
    Route::controller(CategoryController::class)->group(function () {
        Route::get   ('categories',        'index');
        Route::get   ('categories/{slug}', 'show');
        Route::post  ('categories',        'store');
        Route::put   ('categories/{slug}', 'update');
        Route::delete('categories/{slug}', 'destroy');
    });

    Route::prefix('auth')->group(function () {
        // Đăng ký, đăng nhập
        Route::post('login',    [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    
        Route::middleware('auth:sanctum')->group(function () {
            // Đăng xuất
            Route::post('logout', [AuthController::class, 'logout']);
            // Đổi mật khẩu
            Route::post('change-password', [AuthController::class, 'changePassword']);
        });

        // Quên mật khẩu
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password',  [AuthController::class, 'resetPassword'])->name('password.reset');    
    });
});