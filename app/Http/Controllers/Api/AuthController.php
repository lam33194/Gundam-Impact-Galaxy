<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use App\Services\Client\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Đăng nhập
     * name email password
     * @method post : api/v1/signIn
     */
    public function signIn(LoginRequest $request)
    {
        try {

            $data = $request->validated();

            $user = $this->userService->getUserByEmail($data['email']);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email không tồn tại !!!'
                ], Response::HTTP_NOT_FOUND);
            }

            if (!Hash::check($data['password'], $user->password)) {
                return $this->errorResponse('Thông tin tài khoản không chính xác', Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('UserToken')->plainTextToken;


            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'Đăng nhập thành công', Response::HTTP_OK);
        } catch (\Throwable $th) {

            $this->errorResponse(
                'Đã xảy ra lỗi, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Tạo tài khoản
     * name email password
     * @method post api/v1/signUp
     */
    public function signUp(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $user = $this->userService->storeUserService($data);

            $token = $user->createToken('UserToken')->plainTextToken;

            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'Đăng ký thành công', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Logout
     * 
     * @method post api/v1/logout
     */
    public function logout(Request $request)
    {
        try {
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();

                return $this->successResponse([], 'Thao tác thành công !!!', Response::HTTP_OK);
            }

            return $this->errorResponse('Không tìm thấy người dùng !!!', 401);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return $this->errorResponse('Lỗi hệ thống!', 500);
        }
    }

    // Lấy thông tin người dùng 

    public function getUsers()
    {
        $user = User::with('user_vouchers.voucher')
            ->findOrFail(request()->user()->id);

        return $this->successResponse($user, 'Thao tác thành công !!!', Response::HTTP_OK);
    }
}
