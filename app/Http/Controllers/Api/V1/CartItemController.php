<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartItemStoreRequest;
use App\Http\Resources\V1\CartItemResource;
use App\Models\Product;
use App\Models\Variant;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    use ApiResponse;

    public function show_cart_items(Request $request)
    {
        $user = $request->user();

        return $this->ok('Lấy thông tin giỏ hàng thành công', [
            'cartItems' => CartItemResource::collection($user->cartItems),
        ]);
    }

    public function add_to_cart(CartItemStoreRequest $request)
    {
        // productId, variantId, quantity
        $validatedData = $request->toArray();

        $user = $request->user();

        $productId  = $validatedData['productId'];
        $variantId  = $validatedData['variantId'];
        $quantity   = $validatedData['quantity'];

        $product = Product::find($productId);
        $variant = Variant::with('variantValues')->find($variantId);

        // nếu nhập sai variantId
        if ($variant->product != $product) return $this->not_found('Biến thể không tồn tại hoặc không thuộc sản phẩm này');

        if ($quantity > $variant->stock) return $this->failedValidation('Số lượng sản phẩm phải thấp hơn số hàng tồn kho');

        // bổ sung thông tin snapshot của biến thể
        $validatedData['variant_name'] = $variant->variant_name;
        $validatedData['sku']          = $variant->sku;
        $validatedData['extra_price']  = $variant->extra_price;
        foreach ($variant->variantValues as $value) {
            $validatedData['attributes'][] = [$value->variantAttribute->name => $value->value];
        }

        // lấy thông tin sản phẩm trong giỏ hàng của user
        $cartItem = $user->cartItems()->where([
            'product_id' => $productId,
            'variant_id' => $variantId,
        ])->first();

        // nếu user đã có sản phẩm này trong giỏ hàng 
        if ($cartItem) {

            if ($quantity > 0) {

                // cập nhật số lượng sản phẩm và thuộc tính biến thể trong giỏ hàng  
                $cartItem->update([
                    'quantity'   => $quantity,
                    'attributes' => $validatedData['attributes'],
                ]);

                return $this->ok('Lưu giỏ hàng thành công', [
                    'cartItem' => new CartItemResource($cartItem)
                ]);
            } else {
                // nếu nhập số lượng = 0 (quantity min:0)
                $cartItem->delete();
                return $this->ok('Đã xóa sản phẩm khỏi giỏ hàng');
            }

            // nếu sản phẩm chưa có trong giỏ hàng
        } else {

            // thêm mới sản phẩm vào giỏ hàng nên quantity ít nhất >= 1
            if ($quantity <= 0) return $this->failedValidation('Vui lòng chọn ít nhất 1 số lượng sản phẩm');

            // thểm thông tin sản phẩm vào giỏ hàng
            $cartItem = $user->cartItems()->create(array_merge(
                $validatedData,
                [
                    'product_price' => $product->price,
                    'product_name'  => $product->name,
                ]
            ));

            return $this->created('Thêm vào giỏ hàng thành công', [
                'cartItem' => new CartItemResource($cartItem),
            ]);
        }
    }

    public function clear_cart_items()
    {
        request()->user()->cartItems()->delete();

        return $this->no_content();
    }
}
