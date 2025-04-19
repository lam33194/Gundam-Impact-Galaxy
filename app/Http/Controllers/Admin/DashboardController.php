<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $newOrderThisMonth = $this->newOrdersThisMonth();
        $newOrderLastMonth = $this->newOrdersLastMonth();
        $percentageChange = $this->calculatePercentageChange($newOrderThisMonth, $newOrderLastMonth);
        $orderChartData = $this->getOrderChartData();

        // Tổng đơn hàng đã hoàn thành
        $totalOrderDelivered = Order::statusOrderFilter(Order::STATUS_ORDER_DELIVERED)->count();
        // Tổng đơn hàng bị hủy
        $totalOrderCanceled = Order::statusOrderFilter(Order::STATUS_ORDER_CANCELED)->count();

        return view('admin.dashboard', compact(
            'newOrderThisMonth',
            'percentageChange',
            'orderChartData',
            'newOrderLastMonth',
            'totalOrderDelivered',
            'totalOrderCanceled',
        ));
    }

    protected function newOrdersThisMonth()
    {
        return Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    protected function newOrdersLastMonth()
    {
        return Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
    }

    protected function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;

        return round((($current - $previous) / $previous) * 100, 2);
    }

    // lấy data cho biểu đồ so sánh số đơn hàng được tạo so với tháng trước
    protected function getOrderChartData()
    {
        $data = [];
        $startDate = now()->subDays(32); // 32 days ago

        // Create 8 periods of 4 days each
        for ($i = 0; $i < 8; $i++) {
            $periodStart = $startDate->copy();
            $periodStart->addDays($i * 4);

            $periodEnd = $periodStart->copy();
            $periodEnd->addDays(3); // 4 days total (0,1,2,3)

            // Count orders in this period
            $count = Order::whereBetween('created_at', [
                $periodStart->startOfDay(),
                $periodEnd->endOfDay()
            ])->count();
            
            $data[] = $count;
        }

        return $data;
    }

    // lấy data cho biểu đồ so sánh số đơn hàng bị hủy so với tháng trước
    public function getOrderCanceledChartData()
    {
        // $endDate = now()->subDay();
        // $startDate = now()->subDays(32);

        // // Lấy tất cả đơn hàng bị hủy trong khoảng thời gian
        // $orders = Order::statusOrderFilter(Order::STATUS_ORDER_CANCELED)
        //     ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
        //     ->pluck('created_at');

        // // Khởi tạo mảng kết quả
        // $data = array_fill(0, 8, 0);

        // // Phân loại đơn hàng vào từng khoảng
        // foreach ($orders as $createdAt) {
        //     $daysDiff = $startDate->diffInDays($createdAt);
        //     $period = (int) ($daysDiff / 4); // Xác định khoảng (0-7)
        //     $data[$period]++;
        // }
        // return $data;
    }
}