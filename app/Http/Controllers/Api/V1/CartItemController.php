<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartStoreRequest;
use App\Models\ProductVariant;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartItemController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'user',
        'variant',
        'variant.product',
    ];

    public function index(Request $request)
    {
        $carts = $request->user()->cartItems;

        // Tính tổng tiền
        // $total = $carts->sum(function ($cartItem) {
            // $product = $cartItem->productVariant->product;
            // $price = $product->price_sale > 0 ? $product->price_sale : $product->price_regular;
            // return $price * $cartItem->quantity;
        // });

        $this->loadRelations($carts, $request, true);

        return $this->ok('Lấy dữ liệu giỏ hàng thành công', $carts);
    }

    public function store(CartStoreRequest $request)
    {
        $data = $request->validated();

        $cartItem = $request->user()->cartItems()->where([
            'product_variant_id' => $data['product_variant_id']
        ])->first();

        // Nếu sản phẩm đã tồn tại, tăng quantity sp đó trong giỏ hàng
        if ($cartItem) {
            if ($cartItem->quantity + $data['quantity'] > $cartItem->variant->quantity)
                return $this->failedValidation('Số lượng sản phẩm không được vượt quá số lượng tồn kho');

            $cartItem->increment('quantity', $data['quantity']);

            $this->loadRelations($cartItem, $request, true);

            return $this->ok('Thêm vào giỏ hàng thành công', $cartItem);
        }

        $variant = ProductVariant::find($data['product_variant_id']);

        if ($data['quantity'] > $variant->quantity)
            return $this->failedValidation('Số lượng sản phẩm không được vượt quá số lượng tồn kho');

        $cartItem = $request->user()->cartItems()->create($data);

        $this->loadRelations($cartItem, $request, true);

        return $this->ok('Thêm vào giỏ hàng thành công', $cartItem);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);
        
        $cartItem = $request->user()->cartItems()->find($id);

        if (!$cartItem) return $this->not_found('Không tìm thấy sản phẩm trong giỏ hàng');

        if ($data['quantity'] == 0) {
            $cartItem->delete();
            return $this->ok('Xóa sản phẩm khỏi giỏ hàng thành công');
        }

        if ($data['quantity'] > $cartItem->variant->quantity) {
            return $this->failedValidation('Số lượng sản phẩm không được vượt quá số lượng tồn kho');
        };

        $cartItem->update([
            'quantity' => $data['quantity'] 
        ]);

        $cartItem->unsetRelation('variant');

        $this->loadRelations($cartItem, $request, true);

        return $this->ok('Cập nhật số lượng sản phẩm thành cônng', $cartItem);
    }

    public function destroy()
    {
        request()->user()->cartItems()->delete();

        return response()->json(['message' => 'Xóa giỏ hàng thành công']);
    }
}
