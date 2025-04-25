<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\CartItemController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\VoucherController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\Api\V1\SocialAuthController;
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
        // Lấy danh sách product theo category
        Route::get('categories/{slug}/products', 'getByCategory');
        // Sản phẩm top doanh thu
        Route::get('getTopRevenueProducts', 'getTopRevenueProducts');
        // Sản phẩm bán chạy
        Route::get('getTopSellingProducts', 'getTopSellingProducts');
    });

    Route::controller(CommentController::class)->group(function () {
        // Danh sách bình luận của products
        Route::get('products/{slug}/comments', 'index');

        Route::middleware(['auth:sanctum'])->group(function() {
            // Danh sách comment của user
            Route::get('getUserComments', 'getUserComments');
            // Thêm bình luận
            Route::post('products/{slug}/comments', 'store');
            // Sửa bình luận
            Route::put('products/{slug}/comments/{id}', 'update');
            // Xóa bình luận
            Route::delete('comments/{id}', 'destroy');
        });
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
            // Chi tiết đơn hàng
            Route::get ('orders/{id}', 'show');
        });
    });

    Route::controller(UserController::class)->group(function () {
        // Lấy tất cả user
        Route::get('users',      'index');
        // Chi tiết user
        Route::get('users/{id}', 'show');
        // Cập nhật thông tin user
        Route::put('users',      'update')->middleware('auth:sanctum');
    });

    Route::controller(UserAddressController::class)->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            // List địa chỉ
            Route::get('addresses', 'index');
            // Get địa chỉ
            Route::get('addresses/{id}', 'show');
            // Thêm địa chỉ
            Route::post('addresses', 'store');
            // Sửa địa chỉ
            Route::put('addresses/{id}', 'update');
            // Xóa địa chỉ
            Route::delete('addresses/{id}', 'destroy');
        });
    });

    Route::controller(VoucherController::class)->group(function () {
        // Lấy tất cả user
        Route::get('vouchers', 'index');
    });

    Route::controller(PaymentController::class)->group(function(){
        // Tạo đường dẫn thanh toán online
        Route::get('orders/{id}/payment', 'createPayment')->middleware('auth:sanctum');
        Route::get('vnpay/return', 'vnpayReturn');
    });

    Route::controller(PostController::class)->group(function () {
        // Lấy tất cả post
        Route::get('posts', 'index');
        // Chi tiết post
        Route::get('posts/{id}', 'show');
    });

    Route::prefix('auth')->group(function () {
        // Đăng ký
        Route::post('register', [AuthController::class, 'register']);   
        Route::post('login',    [AuthController::class, 'login']);   

        Route::middleware('auth:sanctum')->group(function () {
            // Đăng xuất
            Route::post('logout', [AuthController::class, 'logout']);
            // Đổi mật khẩu
            Route::post('change-password', [AuthController::class, 'changePassword']);
        });

        // Đăng nhập bên thứ 3
        Route::get('google-login',    [SocialAuthController::class, 'googleLogin']);
        Route::get('google-callback', [SocialAuthController::class, 'googleCallback']);

        // Quên mật khẩu
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password',  [AuthController::class, 'resetPassword'])->name('password.reset');
    });
});
