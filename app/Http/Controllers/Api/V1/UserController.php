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
        'cartItems',
        'orders',
        'orders.orderItems',
        'addresses',
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

        return $this->ok("Lấy thông tin người dùng thành công", [
            $user
        ]);
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

        // Cập nhật user_addresses
        $user->addresses()->updateOrCreate([
                'user_id'    => $user->id,
                // 'is_primary' => $request->is_primary,
            ],
            [
                'address'    => $data['address'],
                'ward'       => $data['ward'],
                'district'   => $data['district'],
                'city'       => $data['city'],
                // 'is_primary' => $data['is_primary'],
            ]
        );

        $this->loadRelations($user, $request, true);

        return $this->ok('Cập nhật thông tin thành công', $user);
    }
}
