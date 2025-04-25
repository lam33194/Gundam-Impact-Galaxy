<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    use ApiResponse;
    
    public function googleLogin()
    {
        return $this->ok('Redirect URL', Socialite::driver('google')->stateless()->redirect()->getTargetUrl());
    }

    public function googleCallback()
    {
        try {
            // thông tin tài khoản google
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // nếu email chưa tồn tại trong db, tạo user mới
            $user = User::firstOrCreate([
                'email'    => $googleUser->getEmail(),
            ], [
                'name'     => $googleUser->getName(),
                'email'    => $googleUser->getEmail(),
                'avatar'   => $googleUser->getAvatar(),
                'password' => bcrypt(uniqid())
            ]);

            return $this->ok('Đăng nhập thành công', [
                $user,
                $user->createToken('token-google-login')->plainTextToken,
            ]);

        } catch (\Exception $e) {
            return $this->error('Xác thực thất bại',401,[
                'message' => $e->getMessage(),
            ]);
        }
    }
}