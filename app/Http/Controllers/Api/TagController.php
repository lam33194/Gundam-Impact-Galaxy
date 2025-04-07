<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Lấy danh sách tất cả tags
    public function index()
    {
        return response()->json(Tag::all());
    }

    // Thêm tag mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tags'
        ]);

        $tag = Tag::create([
            'name' => $request->name
        ]);

        return response()->json($tag, 201);
    }

    // Lấy chi tiết tag theo ID
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json($tag);
    }

    // Cập nhật tag
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:tags,name,' . $id
        ]);

        $tag->update([
            'name' => $request->name
        ]);

        return response()->json($tag);
    }

    // Xóa tag
    public function destroy($id)
    {
        Tag::destroy($id);
        return response()->json(['message' => 'Tag deleted']);
    }
}