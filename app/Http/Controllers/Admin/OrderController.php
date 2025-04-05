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
        $orders = Order::with(['orderItems'])->latest('id')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }
}
