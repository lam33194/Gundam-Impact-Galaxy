<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Alert;
use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductColorRequest;
use App\Http\Requests\UpdateProductColorRequest;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductColorController extends Controller
{
    private const VIEW_PATH = "admin.product_color.";

    public function index()
    {
        $productColors = ProductColor::query()->latest('id')->paginate(10);
        return view(self::VIEW_PATH . __FUNCTION__, compact('productColors'));
    }

    public function create()
    {
        return view(self::VIEW_PATH . __FUNCTION__);
    }

    public function store(StoreProductColorRequest $request)
    {
        try {
            ProductColor::create($request->validated());
            Toastr::success(null, 'Thêm màu thành công');
            return redirect()->route('admin.product-colors.index');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Toastr::error(null, 'Thêm màu không thành công');
            return back();
        }
    }

    public function edit(ProductColor $productColor)
    {
        return view(self::VIEW_PATH . __FUNCTION__, compact('productColor'));
    }

    public function update(UpdateProductColorRequest $request, ProductColor $productColor)
    {
        try {
            $productColor->update($request->validated());
            Toastr::success(null, 'Sửa màu thành công');
            return back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Alert::error('Lỗi khi sửa', "Luxchill Thông Báo");
            return back();
        }
    }
}
