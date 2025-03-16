<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserStoreRequest;
use App\Http\Requests\V1\UserUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\StorageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use ApiResponse, StorageFile;

    public function index(Request $request)
    {
        $users = User::query();

        return $this->ok("Lấy danh sách người dùng thành công", [
            'users' => UserResource::collection($users->get())
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('avatar')) {
            // upload file vào storage
            $avatarPath = $request->file('avatar')->store('avatars');
            $validatedData['avatar'] = $avatarPath;
        }

        $user = User::create($validatedData);

        return $this->created("Tạo người dùng thành công", [
            'user' => new UserResource($user),
        ]);
    }

    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");

        return $this->ok("Lấy thông tin người dùng thành công", [
            'user' => new UserResource($user),
        ]);
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");

        // validated data
        $validatedData = $request->validated();

        if ($request->hasFile('avatar')) {
            $this->deleteAvatar($user);

            // upload file vào storage
            $avatarPath = $request->file('avatar')->store('avatars');
            $validatedData['avatar'] = $avatarPath;
        }

        $user->update($validatedData);

        return $this->ok("Cập nhật thành công", [
            'user' => new UserResource($user),
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");

        $this->delete_storage_file($user,'avatar');

        $user->delete();

        return $this->no_content();
    }
}
