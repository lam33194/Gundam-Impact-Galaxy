<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->latest('id')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // 
    }
}
