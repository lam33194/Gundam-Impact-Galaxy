<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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

    public function update(Request $request, string $id)
    {
        // 
    }

}
