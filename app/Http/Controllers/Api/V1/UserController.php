<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserUpdateRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use App\Traits\StorageFile;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse, StorageFile, LoadRelations;

    protected $validRelations = [
        'orders',
        'orders.orderItems',
        'orders.orderItems.variant.product',
        'orders.orderItems.variant.size',
        'orders.orderItems.variant.color',
        'addresses',
        'comments',
        'comments.product',
        'comments.commentImages',
    ];

    public function index(Request $request)
    {
        $users = User::query();

        $this->loadRelations($users, $request);
        
        $perPage = request()->query('per_page', 10);

        return response()->json($users->paginate($perPage)->appends($request->query()));
    }

    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");

        $this->loadRelations($user, request(), true);

        return $this->ok("Lấy thông tin người dùng thành công", $user);
    }

    public function update(UserUpdateRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $this->delete_storage_file($user, 'avatar');

            // upload file vào storage
            $avatarPath = $request->file('avatar')->store('avatars');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        $this->loadRelations($user, $request, true);

        return $this->ok('Cập nhật thông tin thành công', $user);
    }
}
