<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
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

Route::middleware('auth:sanctum')->group(function () {

    //Đăng xuất

    Route::post('/logout', [AuthController::class, 'logout']);

    // Lấy thông tin người dùng
    Route::get('user',[AuthController::class,'getUsers']);

    // Add to cart
    Route::prefix('carts')->middleware('CheckAuthCart')->group(function () {
        Route::get('/',         [CartController::class, 'index']);
        Route::post('/',        [CartController::class, 'store']);
        Route::put('/{id}',     [CartController::class, 'update']);
        Route::delete('/{id}',  [CartController::class, 'destroy']);
    });
    // Order
    Route::prefix('orders')->middleware('CheckAuthCart')->group(function () {
        
        Route::post('/',        [OrderController::class, 'store']);

    });
});

Route::get('/products', [ProductController::class, 'index']);

Route::get('/checkout', [OrderController::class, 'checkout']);


// Tạo tài khoản 
Route::post('/signUp', [AuthController::class, 'signUp']);

// Đăng nhập
Route::post('/signIn', [AuthController::class, 'signIn']);

// Sản phẩm nổi bật
// Route::get('/getTopProducts', [ProductController::class, 'getTopProducts']);

// Danh sách sản phẩm
Route::get('/getAllProducts', [ProductController::class, 'getAllProducts']);

// Chi tiết sản phẩm
Route::get('{slug}/productDetail', [ProductController::class, 'productDetail']);
