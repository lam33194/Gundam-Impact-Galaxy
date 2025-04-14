<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserVoucherController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductColorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSizeController;
use App\Http\Controllers\Admin\ProductStatisticsController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/{any}', function () {
    return view('index');
})->where('any', '^(?!admin).*');

Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class);
    Route::resource('vouchers', VoucherController::class);

    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class);
    Route::resource('tags', TagController::class);
    Route::resource('product-colors', ProductColorController::class);
    Route::resource('product-sizes', ProductSizeController::class);

    

    Route::post('/vouchers/{id}/toggle', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle');


    
    Route::controller(StatController::class)->group(function() {
        Route::get('stats', 'index')->name('stats.index');
    });

    Route::controller(ProductStatisticsController::class)->group(function() {
        Route::get('product_statistics', 'index')->name('product_statistics.index');
    });

    Route::get('/login', [LoginController::class, 'showFormLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::resource('user_vouchers', UserVoucherController::class);
});

