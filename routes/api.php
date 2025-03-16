<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SocialAuthController;
use App\Http\Controllers\Api\V1\VariantAttributeController;
use App\Http\Controllers\Api\V1\VariantController;
use App\Http\Controllers\Api\V1\VariantValueController;
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

    // Product API
    Route::controller(ProductController::class)->group(function () {
        Route::get   ('products',        'index');
        Route::get   ('products/{slug}', 'show');
        Route::post  ('products',        'store');
        Route::put   ('products/{slug}', 'update');
        Route::delete('products/{slug}', 'destroy');
        Route::put   ('products/{slug}/image/{id}', 'update_single_product_image');
    });

    // Variant Attributes Api
    Route::controller(VariantAttributeController::class)->group(function () {
        Route::get   ('variant-attributes',      'index');
        Route::get   ('variant-attributes/{id}', 'show');
        Route::post  ('variant-attributes',      'store');
        Route::put   ('variant-attributes/{id}', 'update');
        Route::delete('variant-attributes/{id}', 'destroy');
    });

    // Variant Value Api
    Route::controller(VariantValueController::class)->group(function () {
        Route::get   ('variant-attributes/{attribute_id}/values',            'index');
        Route::get   ('variant-attributes/{attribute_id}/values/{value_id}', 'show');
        Route::post  ('variant-attributes/{attribute_id}/values',            'store');
        Route::put   ('variant-attributes/{attribute_id}/values/{value_id}', 'update');
        Route::delete('variant-attributes/{attribute_id}/values/{value_id}', 'destroy');
    });
        
    // Variant API
    Route::controller(VariantController::class)->group(function () {
        Route::get   ('products/{slug}/variants',       'index');
        Route::get   ('products/{slug}/variants/{sku}', 'show');
        Route::post  ('products/{slug}/variants',       'store');
        Route::put   ('products/{slug}/variants/{sku}', 'update');
        Route::delete('products/{slug}/variants/{sku}', 'destroy');
    });

    Route::prefix('auth')->group(function () {
        // Đăng ký, đăng nhập
        Route::post('login',    [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        
        // Đăng nhập bên thứ 3
        Route::get('google-login',    [SocialAuthController::class, 'googleLogin']) ;
        Route::get('google-callback', [SocialAuthController::class, 'googleCallback']) ;
    
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