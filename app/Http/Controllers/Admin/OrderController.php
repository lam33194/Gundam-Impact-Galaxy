<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private const VIEW_PATH = 'admin.orders.';
    public function index()
    {
        $orders = Order::with(['orderItems'])->latest('id')->paginate(20);
        return view(self::VIEW_PATH . __FUNCTION__, compact('orders'));
    }

    public function edit(Order $order)
    {
        //
    }
    public function update(Request $request, Order $order)
    {
        //
    }
}
