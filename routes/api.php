<?php

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
});