<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrderStoreRequest;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'user',
        'orderItems',
        'orderItems.product',
        'orderItems.variant',
        'payment',
    ];

    public function index(Request $request)
    {
        $orders = Order::query();

        $this->loadRelations($orders, $request);

        return $this->ok('Lấy danh sách hóa đơn thành công', [
            'orders' => OrderResource::collection($orders->get()),
        ]);
    }

    public function show(Request $request, string $order_code)
    {
        $order = Order::whereOrderCode($order_code)->first();

        if (!$order) return $this->not_found('Đơn hàng không tồn tại');

        $this->loadRelations($order, $request, true);

        return $this->ok('Lấy chi tiết hóa đơn thành công', [
            'orders' => new OrderResource($order),
        ]);
    }

    public function update(Request $request, string $order_code)
    {
        $order = Order::whereOrderCode($order_code)->first();

        if (!$order) return $this->not_found('Đơn hàng không tồn tại');

        $validatedData = $request->validate([
            'status' => 'required|in:pending,cancelled,processing,shipped,delivered',
        ], [
            // 
        ]);

        $order->update($validatedData);

        $this->loadRelations($order, $request, true);

        return $this->ok('Cập nhật đơn hàng thành công', [
            'order' => new OrderResource($order),
        ]);
    }

    public function destroy(string $order_code)
    {
        $order = Order::whereOrderCode($order_code)->first();

        if (!$order) return $this->not_found('Đơn hàng không tồn tại');

        $order->delete();

        return $this->no_content();
    }
}
