<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->only(['email', 'password', 'name']);

        $user = User::create($validatedData);

        return response()->json([
            $user, 
            $user->createToken('token-register')->plainTextToken
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credential = $request->only(['email', 'password']);

        if (!Auth::attempt($credential)) {
            return $this->unauthenticated('Thông tin đăng nhập không chính xác');
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            $user, 
            $user->createToken('token-register')->plainTextToken
        ]);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return $this->ok('Đăng xuất thành công');
    }

    public function changePassword(Request $request)
    {
        $data = $request->all();

        $request->validate([
                'current_password'      => 'required|current_password',
                'password'              => 'required|confirmed|min:8',
                'password_confirmation' => 'required',
            ],
            [
                'current_password.required'         => 'Vui lòng nhập mật khẩu hiện tại',
                'current_password.current_password' => 'Sai mật khẩu',

                'password.required'   => 'Vui lòng nhập mật khẩu mới',
                'password.confirmed'  => 'Mật khẩu xác nhận không khớp',
                'password.min'        => 'Mật khẩu mới phải chứa ít nhất :min ký tự',
                'password_confirmation.required' => 'Vui lòng nhập xác nhận mật khẩu',
            ]
        );

        $request->user()->update(['password' => $request->password]);

        return $this->ok('Đổi mật khẩu thành công');
    }
}
