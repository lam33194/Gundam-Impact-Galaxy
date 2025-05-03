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
            $query->statusPaymentFilter($request->status_payment);
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

        $validSortColumns = ['total_price', 'created_at'];

        // Apply sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        if (in_array($sortBy, $validSortColumns)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->latest();
        }

        $orders = $query->paginate(15)->appends($request->query());

        return view('admin.orders.index', compact('orders', 'sortBy', 'sortDirection'));
    }

    public function edit(Order $order)
    {
        // Lấy danh sách trạng thái
        $orderStatus = Order::STATUS_ORDER_DETAILS;

        $order->load(['orderItems.variant.product:id']);

        // Xác định các trạng thái có thể chọn
        $allowedStatuses = $this->getAllowedStatuses($order->status_order);

        return view('admin.orders.edit', compact('order', 'orderStatus', 'allowedStatuses'));
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

    public function confirm(Order $order)
    {
        if ($order->status_order == Order::STATUS_ORDER_PENDING) {
            $order->update([
                'status_order' => Order::STATUS_ORDER_CONFIRMED
            ]);
        } else {
            return redirect()->back()->with('error', 'Trạng thái đơn hàng không hợp lệ');
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function bulkAction(Request $request)
    {
        $data = $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:orders,id',
            'action' => 'required',
        ],[
            'ids.required' => 'Không có đơn hàng nào được chọn',
            'ids.*.exists' => 'Đơn hàng không tồn tại',
            'action.*'     => 'Thao tác không hợp lệ'
        ]);

        $ids = $data['ids'];

        switch ($data['action']) {
            case Order::STATUS_ORDER_CONFIRMED:
                // Chỉ lấy những đơn pending
                $query = Order::pending()->whereIn('id', $ids);

                if ($query->exists()) {
                    $orders = $query->get();

                    foreach ($orders as $order) {
                        $order->update(['status_order' => Order::STATUS_ORDER_CONFIRMED]);
                    }

                    return redirect()->back()->with('success', 'Xác nhận '.$orders->count().' đơn hàng thành công');
                }
            return redirect()->back()->with('error', 'Không có đơn hàng nào được xác nhận');

            // case Order::STATUS_ORDER_PREPARING:
            //     // ...
            // return redirect()->back()->with('error', 'Không có đơn hàng nào được xác nhận');

            // case Order::STATUS_ORDER_SHIPPING:
            //     // ...
            // return redirect()->back()->with('error', 'Không có đơn hàng nào được xác nhận');

            // case Order::STATUS_ORDER_DELIVERED:
            //     // ...
            // return redirect()->back()->with('error', 'Không có đơn hàng nào được xác nhận');

            // case Order::STATUS_ORDER_CANCELED:
            //     // ...
            // return redirect()->back()->with('error', 'Không có đơn hàng nào được xác nhận');

            default: return redirect()->back()->with('error', 'Thao tác không hợp lệ');
        };
    }

    private function getAllowedStatuses($currentStatus)
    {
        $statusKeys = array_keys(Order::STATUS_ORDER_DETAILS);

        $currentIndex = array_search($currentStatus, $statusKeys);

        $allowed = [
            $currentStatus, // Trạng thái hiện tại
            $statusKeys[$currentIndex + 1] ?? null, // Trạng thái tiếp theo
            Order::STATUS_ORDER_CANCELED // Trạng thái hủy
        ];

        return array_filter($allowed); // Loại bỏ null
    }
}
