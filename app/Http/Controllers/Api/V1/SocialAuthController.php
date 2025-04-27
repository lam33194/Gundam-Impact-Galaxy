<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
                'password' => bcrypt(uniqid()),
            ]);

            // Lưu ảnh avatar vào Storage nếu chưa có avatar
            if ($googleUser->getAvatar()) {
                $response = Http::get($googleUser->getAvatar());

                if (!$user->avatar && $response->successful()) {
                    $fileName = 'avatars/' . uniqid('google_avatar_') . '.jpg';

                    Storage::disk('public')->put($fileName, $response->body());

                    $user->avatar = $fileName;
                    $user->save();
                }
            }

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