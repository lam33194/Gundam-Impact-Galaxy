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
    ];

    public function index(Request $request)
    {
        $carts = $request->user()->cartItems()->getQuery();

        $this->loadRelations($carts, $request);

        $this->loadSubRelations($carts);

        return $this->ok('Lấy dữ liệu giỏ hàng thành công', $carts->get());
    }

    public function store(CartStoreRequest $request)
    {
        $data = $request->validated();

        $cartItem = $request->user()->cartItems()->where([
            'product_variant_id' => $data['product_variant_id']
        ])->first();

        $variant = ProductVariant::find($data['product_variant_id']);

        if (!$variant->product->is_active) return $this->error('Sản phẩm không có sẵn');

        // Nếu sản phẩm đã tồn tại, tăng quantity sp đó trong giỏ hàng
        if ($cartItem) {
            if ($cartItem->quantity + $data['quantity'] > $cartItem->variant->quantity)
                return $this->failed_validation('Số lượng sản phẩm không được vượt quá số lượng tồn kho');

            $cartItem->increment('quantity', $data['quantity']);

            // $cartItem->unsetRelation('variant');

            // $this->loadRelations($cartItem, $request, true);
            
            return $this->created('Thêm vào giỏ hàng thành công');
        }

        if ($data['quantity'] > $variant->quantity)
            return $this->failed_validation('Số lượng sản phẩm không được vượt quá số lượng tồn kho');

        $cartItem = $request->user()->cartItems()->create($data);

        // $cartItem->unsetRelation('variant');

        // $this->loadRelations($cartItem, $request, true);

        return $this->created('Thêm vào giỏ hàng thành công');
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);
        
        $cartItem = $request->user()->cartItems()->find($id);

        if (!$cartItem->variant->product->is_active) {
            $cartItem->delete();
            return $this->error('Sản phẩm không có sẵn');
        }

        if (!$cartItem) return $this->not_found('Không tìm thấy sản phẩm trong giỏ hàng');

        if ($data['quantity'] == 0) {
            $cartItem->delete();
            return $this->ok('Xóa sản phẩm khỏi giỏ hàng thành công');
        }

        if ($data['quantity'] > $cartItem->variant->quantity) {
            return $this->failed_validation('Số lượng sản phẩm không được vượt quá số lượng tồn kho');
        };

        $cartItem->update([
            'quantity' => $data['quantity'] 
        ]);

        $cartItem->unsetRelation('variant');

        $this->loadRelations($cartItem, $request, true);

        $this->loadSubRelations($cartItem, true);

        return $this->ok('Cập nhật số lượng sản phẩm thành cônng', $cartItem);
    }

    public function destroy()
    {
        auth('sanctum')->user()->cartItems()->delete();

        return $this->no_content();
    }

    private function loadSubRelations($cartItem, bool $isInstance = false)
    {
        $getMethod = $isInstance ? 'getRelations' : 'getEagerLoads';

        $loadMethod = $isInstance ? 'loadMissing' : 'with';
       
        if (array_key_exists('variant', $cartItem->$getMethod())) {
            $cartItem->$loadMethod([
                'variant.product:id,name,slug,sku,thumb_image,price_regular,price_sale',
                'variant.size:id,name',
                'variant.color:id,name,code',
            ]);
        }
    }
}
