<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CategoryStoreRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with(['parent','children'])->paginate(10);

        return $this->ok('Lấy danh sách danh mục thành công', [
            'categories' => CategoryResource::collection($categories)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::create($validatedData);

        return $this->created("Tạo danh mục thành công", [
            'category' => new CategoryResource($category),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $category = Category::whereSlug($slug)->with(['parent','children'])->first();

        return (!$category)
            ? $this->not_found("Danh mục không tồn tại")
            : $this->ok("Lấy thông tin danh mục thành công", [
                'category' => new CategoryResource($category),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $category->delete();

        return $this->no_content();
    }
}
