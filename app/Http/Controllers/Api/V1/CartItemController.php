<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartStoreRequest;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartItemController extends Controller
{
    use LoadRelations;

    public function index()
    {
        //
    }

    /**
     * Mô tả: Thêm sản phẩm vào giỏ hàng
     * 
     * Endpoint: ...api/v1/carts
     * 
     * Request Body 
     * - user_id: ID biến thể 
     * - product_variant_id: ID biến thể 
     * - quantity: Số lượng sp
     */
    public function store(CartStoreRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        if ($data['user_id'] != $user->id) return response()->json([
            'message' => 'Bạn không có quyền thực hiện hành động này'
        ], Response::HTTP_FORBIDDEN);

        $cartItems = $user->cartItems()->create($data);

        return response()->json($cartItems);
    }

    public function show(string $id)
    {
        //
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
