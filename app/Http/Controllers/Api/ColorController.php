<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;



class ColorController extends Controller
{
    // Lấy danh sách colors
    public function index()
    {
        return response()->json(Color::all());
    }

    // Thêm color mới
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:colors']);
        $color = Color::create($request->all());
        return response()->json($color);
    }

    // Lấy thông tin color theo ID
    public function show($id)
    {
        return response()->json(Color::findOrFail($id));
    }

    // Cập nhật color
    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $color->update($request->all());
        return response()->json($color);
    }

    // Xóa color
    public function destroy($id)
    {
        Color::destroy($id);
        return response()->json(['message' => 'Color deleted']);
    }
}
