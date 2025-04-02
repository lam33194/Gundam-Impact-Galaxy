<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->latest('id')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $colors     = ProductColor::all();
        $sizes      = ProductSize::all();
        $tags       = Tag::all();
        return view('admin.products.create', compact(['categories', 'tags', 'colors', 'sizes', 'tags']));        
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
