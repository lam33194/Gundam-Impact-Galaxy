<?php

use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
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
Route::apiResource('tags', TagController::class);

Route::get('/users', [UserController::class, 'index']);    // Lấy danh sách users
Route::post('/users', [UserController::class, 'store']);   // Thêm user mới
Route::get('/users/{id}', [UserController::class, 'show']); // Lấy user theo ID
Route::put('/users/{id}', [UserController::class, 'update']); // Cập nhật user
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Xóa user