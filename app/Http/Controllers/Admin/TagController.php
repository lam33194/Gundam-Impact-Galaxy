<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    private const VIEW_PATH = 'admin.tags.';

    public function index()
    {
        $tags = Tag::query()->withCount('products')->latest('id')->paginate(20);

        return view(self::VIEW_PATH . __FUNCTION__, compact('tags'));
    }

    public function create()
    {
        return view(self::VIEW_PATH . __FUNCTION__);
    }

    public function store(StoreTagRequest $request)
    {
        try {
            Tag::create($request->validated());
            Toastr::success(null, 'Thêm tag thành công');
            return redirect()->route('admin.tags.index');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Toastr::error(null, 'Thêm tag không thành công');
            return back();
        }
    }

    public function edit(Tag $tag)
    {
        return view(self::VIEW_PATH . __FUNCTION__, compact('tag'));
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try {
            $tag->update($request->validated());
            Toastr::success(message: null, title: 'Sửa size thành công');
            return back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Toastr::error(null, 'Sửa size không thành công');
            return back();
        }
    }

    public function destroy($id){
        try {
            $product = Tag::query()->findOrFail($id);
            $product->delete();
            return back()->with('success','Xoa thanh cong');
            
            
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Khong thay',
            ]);
            
        }
    }
}
