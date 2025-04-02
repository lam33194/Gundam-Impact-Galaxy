<?php

use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\V1\CartItemController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::controller(ProductController::class)->group(function () {
        // Lấy tất cả product
        Route::get('products',       'index');
        // Product detail
        Route::get('products/{slug}', 'show');
        // Lấy data colors và sizes 
        Route::get('variant-attributes', 'getVariantAttributes');
    });

    Route::controller(CartItemController::class)->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            // Add to Cart
            Route::post  ('carts', 'store');
            // Show cart items
            Route::get   ('carts', 'index');
            // Update cart items
            Route::put   ('carts/{id}', 'update');
            // Xóa toàn bộ giỏ hàng
            Route::delete('carts', 'destroy');
        });
    });
});
Route::apiResource('users', controller: UserController::class);

Route::get('/users', [UserController::class, 'index']);    // Lấy danh sách users
Route::post('/users', [UserController::class, 'store']);   // Thêm user mới
Route::get('/users/{id}', [UserController::class, 'show']); // Lấy user theo ID
Route::put('/users/{id}', [UserController::class, 'update']); // Cập nhật user
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Xóa user

Route::apiResource('tags', controller: TagController::class);

Route::get('/tags', [TagController::class, 'index']);    // Lấy danh sách tags
Route::post('/tags', [TagController::class, 'store']);   // Thêm tag mới
Route::get('/tags/{id}', [TagController::class, 'show']); // Lấy tag theo ID
Route::put('/tags/{id}', [TagController::class, 'update']); // Cập nhật tag
Route::delete('/tags/{id}', [TagController::class, 'destroy']); // Xóa tag

Route::apiResource('tags', controller: ColorController::class);

Route::get('/colors', [ColorController::class, 'index']);    // Lấy danh sách colors
Route::post('/colors', [ColorController::class, 'store']);   // Thêm color mới
Route::get('/colors/{id}', [ColorController::class, 'show']); // Lấy color theo ID
Route::put('/colors/{id}', [ColorController::class, 'update']); // Cập nhật color
Route::delete('/colors/{id}', [ColorController::class, 'destroy']); // Xóa color
