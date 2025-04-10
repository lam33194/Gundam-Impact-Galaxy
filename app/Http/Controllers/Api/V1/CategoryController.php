<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'products',
        'products.tags',
        'products.galleries',
        'products.variants',
    ];

    public function index(Request $request)
    {
        $categories = Category::query()->withCount('products');

        $this->loadRelations($categories, $request);

        $perPage = request()->query('per_page', 10);

        return response()->json($categories->paginate($perPage));
        // return $this->ok('Lấy danh sách danh mục thành công', $categories->paginate(10));
    }

    public function show(string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $this->loadRelations($category, request(), true);

        return response()->json($category);
        
        // return $this->ok("Lấy thông tin danh mục thành công", $category);
    }
}
