<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Lấy danh sách user
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Thêm user mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = user::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json($user);
    }

    // Sửa user
    public function update(Request $request, $id)
    {
        $user = user::findOrFail($id);
        $user->update($request->only(['name', 'email']));

        return response()->json($user);
    }

    // Xóa user
    public function destroy($id)
    {
        user::destroy($id);
        return response()->json(['message' => 'User deleted']);
    }
}
