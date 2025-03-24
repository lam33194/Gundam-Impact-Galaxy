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
        'orderItems'
    ];

    public function index()
    {
        //
    }

    public function show(string $order_code)
    {
        //
    }

    public function store(OrderStoreRequest $request)
    {
        // shippingAddress, shippingFee, note
        $validatedData = $request->toArray();

        $order = Order::create([array_merge(
            $validatedData,
            [
                'user_id'      => auth()->id(),
                'order_code'   => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => 0,
                'status'       => 'pending',
            ]
        )]);

        return $this->ok('Tạo đơn hàng thành công', [
            'order' => new OrderResource($order),
        ]);
    }

    public function update(Request $request, string $order_code)
    {
        //
    }

    public function destroy(string $order_code)
    {
        //
    }

    public function show_order_items(string $order_code)
    {
        //
    }

    public function add_order_items(Request $request, string $order_code)
    {
        // $order = Order::where('order_code', $order_code)->first();
    }

    public function clear_order_items(string $order_code)
    {
        //
    }
}
