<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    use ApiResponse;
    
    public function googleLogin()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
        ],200);
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
                'password' => bcrypt(uniqid())
            ]);

            return $this->ok('Đăng nhập thành công', [
                'user' => new UserResource($user),
                'token' => $user->createToken('token-google-login')->plainTextToken,
            ]);

        } catch (\Exception $e) {
            return $this->error('Xác thực thất bại',401,[
                'message' => $e->getMessage(),
            ]);
        }
    }
}