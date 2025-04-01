<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {

    Route::controller(ProductController::class)->group(function(){
        // Lấy tất cả product
        Route::get('products',        'index');
        // Product detail
        Route::get('products/{slug}', 'show');
    });
});

Route::apiResource('tags', TagController::class);
