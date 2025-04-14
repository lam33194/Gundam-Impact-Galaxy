<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Apply date filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Apply order status filter
        if ($request->filled('status_order')) {
            $query->statusOrderFilter($request->status_order);
        }

        // Apply payment status filter
        if ($request->filled('status_payment')) {
            $query->statusPaymentFilter($request->status_order);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_sku', 'like', "%{$search}%")
                ->orWhere('user_name', 'like', "%{$search}%")
                ->orWhere('id',        'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(15);

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
        $status = $request->only('status_order');

        switch ($status['status_order']) {
            case Order::STATUS_ORDER_DELIVERED:
                $order->update(array_merge($status, ['status_payment' => Order::STATUS_PAYMENT_PAID]));
            break;

            default:
                $order->update($status);
            break;
        }

        return back()->with('success', 'Cập nhật trạng thái thành công');
    }
}
