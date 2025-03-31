<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use LoadRelations;

    protected $validRelations = [
        'variants',
        'galleries',
        'tags',
        'category',
    ];

    public function index()
    {
        $products = Product::query();

        $this->loadRelations($products, request());

        return response()->json($products->paginate(10));
    }

    public function show(string $slug)
    {
        $products = Product::whereSlug($slug)->first();

        if(!$products) return $this->not_found('Sản phẩm không tồn tại');

        $this->loadRelations($products, request(), true);

        return response()->json($products);
    }
}
