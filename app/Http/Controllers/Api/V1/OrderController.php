<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrderStoreRequest;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        // 
    }

    public function store(OrderStoreRequest $request)
    {
        $data = $request->validated();
 
        $user = $request->user();

        // lấy thông tin giỏ hàng
        $cartItems = $user->cartItems()->with(['variant.product', 'variant.size', 'variant.color'])->get();

        if (empty($cartItems->toArray())) return $this->error('Giỏ hàng của bạn đang trống');

        // Bắt đầu transaction
        return DB::transaction(function () use ($user, $data, $cartItems) {

            // tạo order
            $order = $user->orders()->create(array_merge(
                $data,
                [
                    'order_sku'      => 'ORD-' . strtoupper(uniqid()),
                    'status_order'   => Order::STATUS_ORDER_PENDING,
                    'status_payment' => Order::STATUS_PAYMENT_UNPAID,
                    'total_price'    => $user->total_price,
                ]
            ));

            // chuyển data cartItems sang orderItems
            $orderItemsData = [];

            foreach ($cartItems as $cartItem) {
                $variant = $cartItem->variant;
                $product = $variant->product;

                // Kiểm tra tồn kho
                if ($cartItem->quantity > $variant->quantity) {
                    throw new \Exception("Sản phẩm {$product->name} ({$product->sku}) hiện đã hết hàng số lượng tồn kho.");
                }

                $orderItemsData[] = [
                    'order_id'              => $order->id,
                    'product_variant_id'    => $variant->id,
                    'quantity'              => $cartItem->quantity,
                    'product_name'          => $product->name,
                    'product_sku'           => $product->sku,
                    'product_img_thumbnail' => $product->thumb_image,
                    'product_price_regular' => $product->price_regular,
                    'product_price_sale'    => $product->price_sale,
                    'variant_size_name'     => $variant->size->name,
                    'variant_color_name'    => $variant->color->name,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ];
            }

            $order->orderItems()->insert($orderItemsData);

            // Xóa giỏ hàng
            $user->cartItems()->delete();

            // Giảm số lượng tồn kho trong product_variants
            foreach ($cartItems as $cartItem) {
                $cartItem->variant->decrement('quantity', $cartItem->quantity);
            }

            return $this->ok('Đơn hàng của bạn đã được tạo', $order);
        });
    }
}
