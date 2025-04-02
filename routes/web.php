<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VoucherController;
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
});