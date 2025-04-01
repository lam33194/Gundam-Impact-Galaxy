<?php

use App\Http\Controllers\Api\V1\CartItemController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::controller(ProductController::class)->group(function () {
        // Lấy tất cả product
        Route::get('products',       'index');
        // Product detail
        Route::get('products/{slug}', 'show');
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

Route::apiResource('tags', TagController::class);