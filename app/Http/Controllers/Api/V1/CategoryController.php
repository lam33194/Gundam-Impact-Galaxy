<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CategoryStoreRequest;
use App\Http\Requests\V1\CategoryUpdateRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'products',
        'parent',
        'children'
    ];

    public function index(Request $request)
    {
        $categories = Category::query();

        $this->loadRelations($categories, $request);

        return $this->ok('Lấy danh sách danh mục thành công', [
            'categories' => CategoryResource::collection($categories->get()),
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::create($validatedData);

        $this->loadRelations($category, $request, true);
        
        return $this->created("Tạo danh mục thành công", [
            'category' => new CategoryResource($category),
        ]);
    }

    public function show(string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $this->loadRelations($category, request(), true);

        return $this->ok("Lấy thông tin danh mục thành công", [
            'category' => new CategoryResource($category),
        ]);
    }

    public function update(CategoryUpdateRequest $request, string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $data = $request->validated();

        $category->update($data);

        $this->loadRelations($category, $request, true);

        return $this->ok("Cập nhật thành công", [
            'category' => new CategoryResource($category),
        ]);
    }

    public function destroy(string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $category->delete();

        return $this->no_content();
    }
}
