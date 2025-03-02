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

        return $this->created('Đăng ký thành công', [
            'user'  => $user,
            'token' => $user->createToken('token-register')->plainTextToken
        ]);
    }
    public function login(LoginRequest $request)
    {
        $credential = $request->only(['email', 'password']);

        if (!Auth::attempt($credential)) {
            return $this->unauthorize('Thông tin đăng nhập không chính xác');
        }

        $user = User::where('email', $request->email)->first();

        return $this->ok('Đăng nhập thành công', [
            'user'  => $user,
            'token' => $user->createToken('token-login')->plainTextToken
        ]);
    }
    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return $this->ok('Đăng xuất thành công');
    }
}
