<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Http\Requests\V1\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
        auth('sanctum')->user()->currentAccessToken()->delete();

        // auth('sanctum')->user()->tokens()->delete();

        return $this->ok('Đăng xuất thành công');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
                'current_password'      => 'required|current_password',
                'password'              => 'required|confirmed|min:8',
                'password_confirmation' => 'required',
            ],
            [
                'current_password.required'         => 'Vui lòng nhập mật khẩu hiện tại',
                'current_password.current_password' => 'Mật khẩu hiện tại không đúng',

                'password.required'   => 'Vui lòng nhập mật khẩu mới',
                'password.confirmed'  => 'Mật khẩu xác nhận không khớp',
                'password.min'        => 'Mật khẩu mới phải chứa ít nhất :min ký tự',
                'password_confirmation.required' => 'Vui lòng nhập xác nhận mật khẩu',
            ]
        );

        $request->user()->update(['password' => $request->password]);

        return $this->ok('Đổi mật khẩu thành công');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ], [
            'email.exists' => 'Email không tồn tại',
            'email.*'      => 'Email không hợp lệ',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->ok('Check your email')
            : $this->error('Something gone wrong');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->update([
                    'password' => $password,
                    'remember_token' => Str::random(60)
                ]);

                event(new PasswordReset($user));
            }
        );

        return match ($status) {
            Password::PASSWORD_RESET => $this->ok('Mật khẩu đã được đặt lại thành công'),
            Password::INVALID_TOKEN  => $this->error('Token không hợp lệ hoặc đã hết hạn'),
            default                  => $this->error('Có lỗi xảy ra khi đặt lại mật khẩu'),
        };
    }
}
