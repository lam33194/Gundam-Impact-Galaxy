<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserStoreRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);

        return $this->ok("Lấy danh sách người dùng thành công", [
            'users' => UserResource::collection($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create($validatedData);

        return $this -> created("Tạo người dùng thành công",[
            'user' => $user,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        return (!$user)
            ? $this->not_found("Người dùng không tồn tại")
            : $this->ok("Lấy thông tin người dùng thành công", [
                'user' => new UserResource($user),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");
        
        $user->delete();

        return $this->no_content();
    }
}
