<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductColorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSizeController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserVoucherController;
use App\Http\Controllers\Admin\VoucherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashBoardController::class, 'index'])->name('dashboard');
Route::resource('categories', CategoryController::class);
Route::resource('users', UserController::class);

Route::get('comments', [CommentController::class, 'index'])->name('comments.index');

Route::resource('products', ProductController::class);
Route::resource('product-sizes', ProductSizeController::class);
Route::resource('product-colors', ProductColorController::class);

Route::resource('tags', TagController::class);

Route::resource('vouchers', VoucherController::class);
Route::post('/vouchers/{id}/toggle', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle');

Route::resource('user-vouchers', UserVoucherController::class);

Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [OrderController::class, 'edit'])->name('orders.edit');
Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
