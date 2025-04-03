<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    private const PATH_VIEW = 'admin.categories.';
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {

        $categories = $this->categoryService->all();

        if ($categories->currentPage() > $categories->lastPage()) {
            return redirect()->route('admin.categories.index', ['page' => $categories->lastPage()]);
        }

        return view(self::PATH_VIEW . __FUNCTION__, compact('categories'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }
    public function store(StoreCategoryRequest $request)
    {
        try {
            $this->categoryService->store($request->all());

            Toastr::success(null, 'Thao tác thành công');
            return redirect()->route('admin.categories.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function show(Category $category)
    {
        return view(self::PATH_VIEW . __FUNCTION__, compact('category'));
    }

    public function edit(Category $category)
    {
        return view(self::PATH_VIEW . __FUNCTION__, compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $this->categoryService->update($category, $request->all());

            Toastr::success(null, 'Thao tác thành công');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
  
}
