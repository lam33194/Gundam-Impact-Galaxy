<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->only(['email', 'password', 'name']);

        $user = User::create($validatedData);

        return $this->created('Đăng ký thành công', [
            'user'  => new UserResource($user),
            'token' => $user->createToken('token-register')->plainTextToken
        ]);
    }
    public function login(LoginRequest $request)
    {
        $credential = $request->only(['email', 'password']);

        if (!Auth::attempt($credential)) {
            return $this->unauthenticated('Thông tin đăng nhập không chính xác');
        }

        $user = User::where('email', $request->email)->first();

        return $this->ok('Đăng nhập thành công', [
            'user'  => new UserResource($user),
            'token' => $user->createToken('token-login')->plainTextToken
        ]);
    }
    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return $this->ok('Đăng xuất thành công');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email'   => ['required', 'email', 'exists:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        ], [
            'email.*' => 'Email không hợp lệ',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->ok('Check yout email')
            : $this->error('Something gone wrong', 400);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'passwordConfirmation', 'token'),
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
            Password::INVALID_TOKEN  => $this->error('Token không hợp lệ hoặc đã hết hạn', 400),
            default                  => $this->error('Có lỗi xảy ra khi đặt lại mật khẩu', 400),
        };
    }

    public function changePassword(Request $request)
    {
        $data = $request->all();

        if (isset($data['passwordConfirmation'])) {
            $data['password_confirmation'] = $data['passwordConfirmation'];
        }

        $request->replace($data);

        dd($request->all());

        $request->validate([
                'currentPassword'  => 'required|current_password',
                'password'         => 'required|confirmed|min:8',
                'passwordConfirmation' => 'required',
            ],
            [
                'currentPassword.required'         => 'Vui lòng nhập mật khẩu hiện tại',
                'currentPassword.current_password' => 'Sai mật khẩu',

                'password.required'   => 'Vui lòng nhập mật khẩu mới',
                'password.confirmed'  => 'Mật khẩu xác nhận không khớp',
                'password.min'        => 'Mật khẩu mới phải chứa ít nhất :min ký tự',
                'passwordConfirmation.required' => 'Vui lòng nhập xác nhận mật khẩu',
            ]
        );

        auth()->user()->update(['password' => $request->password]);

        return $this->ok('Đổi mật khẩu thành công');
    }
}
