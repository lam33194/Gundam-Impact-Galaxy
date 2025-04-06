<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function edit(Order $order)
    {
        $orderStatus = Order::STATUS_ORDER;

        $order->load(['orderItems.variant:id,image']);

        return view('admin.orders.edit', compact('order', 'orderStatus'));
    }

    public function update(Request $request, Order $order)
    {
        //
    }
}
