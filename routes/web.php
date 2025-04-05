<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\OrderController;
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
    Route::view('/', 'admin.dashboard')->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('vouchers', VoucherController::class);
    Route::post('/vouchers/{id}/toggle', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle');

    Route::get('/login', [LoginController::class, 'showFormLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
});