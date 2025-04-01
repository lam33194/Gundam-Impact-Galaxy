<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;
// use VnPayController;

class UserController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'cartItems',
        'cartItems.productVariant',
    ];

    public function index(Request $request)
    {
        $users = User::query();

        $this->loadRelations($users, $request);

        return $this->ok("Lấy danh sách người dùng thành công", $users->paginate(10));
    }

    public function store(Request $request)
    {
        // 
    }

    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");

        $this->loadRelations($user, request(), true);

        return $this->ok("Lấy thông tin người dùng thành công", $user);
    }

    public function update(Request $request, string $id)
    {
        // 
    }

    public function destroy(string $id)
    {
        // 
    }
}