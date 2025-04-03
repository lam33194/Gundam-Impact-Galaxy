<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\CartItemController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::controller(CategoryController::class)->group(function () {
        // Lấy tất cả category
        Route::get('categories',       'index');
        // Category detail
        Route::get('categories/{slug}', 'show');
    });

    Route::controller(ProductController::class)->group(function () {
        // Lấy tất cả product
        Route::get('products',       'index');
        // Product detail
        Route::get('products/{slug}', 'show');
        // Lấy data colors và sizes 
        Route::get('variant-attributes', 'getVariantAttributes');
        // Lấy danh sách product theo category
        Route::get('categories/{slug}/products', 'getByCategory');
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

    Route::controller(OrderController::class)->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            // Đặt hàng
            Route::post('orders', 'store');
            // List đơn hàng của user
            Route::get ('orders', 'index');
            // Hủy đặt hàng
            Route::put ('orders/{id}', 'update');
        });
    });

    Route::prefix('auth')->group(function () {
        // Đăng ký
        Route::post('register', [AuthController::class, 'register']);   
    });
});
