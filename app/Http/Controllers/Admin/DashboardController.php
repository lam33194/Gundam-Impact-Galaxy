<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        // Top user
        $userChartData = $this->getTopUsers();

        // Top product
        $productChartData = $this->getTopProducts(request());

        // Data doanh thu
        $revenueChartData = $this->revenueChartData();

        return view('admin.dashboard', compact(
            'newOrderThisMonth',
            'percentageChange',
            'orderChartData',
            'newOrderLastMonth',
            'totalOrderDelivered',
            'totalOrderCanceled',
            // Thống kê user
            'userChartData',
            // Thống kê product
            'productChartData',
            // Thống kê doanh thu
            'revenueChartData',
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

            $data[] = [
                'date' => $periodStart->format('d/m'),
                'count' => $count,
            ];
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

    // Thống kế user ==============================================================================

    // public function getTopUsers()
    // {
    //     $topUsers = User::select('users.id', 'users.name', 'users.email')
    //         ->selectRaw('SUM(orders.total_price) as total_spent')
    //         ->join('orders', 'users.id', '=', 'orders.user_id')
    //         ->groupBy('users.id', 'users.name', 'users.email')
    //         ->orderByDesc('total_spent')
    //         ->take(5)
    //         ->get();

    //     return $topUsers;
    // }

    public function getTopUsers()
    {
        $topUsers = User::select(
            'users.id',
            'users.name',
            DB::raw('SUM(orders.total_price) as total_spent'),
            DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
            DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_quantity')
        )
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status_order', Order::STATUS_ORDER_DELIVERED)
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        return [
            'users' => $topUsers->pluck('name')->toArray(),
            'total_spent' => $topUsers->pluck('total_spent')->toArray(),
            'total_quantity' => $topUsers->pluck('total_quantity')->toArray(),
            'total_orders' => $topUsers->pluck('total_orders')->toArray(),
        ];
    }

    // Thống kê product ==============================================================================

    public function getTopProducts(Request $request)
    {
        // Lấy thời gian và số lượng sản phẩm muốn xem
        $start = $request->start ?? now()->subMonth()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();
        $limit = $request->limit ?? 10;

        $products = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        return [
            'product_name' => $products->pluck('product_name')->toArray(),
            'total_sold' => $products->pluck('total_sold')->toArray(),
            'start' => $start,
            'end' => $end,
            'limit' => $limit,
        ];
    }

    // Thống kê doanh thu ==============================================================================
    public function revenueChartData()
    {
        // Dữ liệu theo ngày (7 ngày gần nhất)
        $dailyData = [];
        // Ngày bắt đầu (6 ngày trước)
        $startDate = Carbon::now()->subDays(6);
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dailyData[$date->format('d/m')] = 0;
            // ['01/01' => 0]
        }

        // lấy đơn hàng đã thanh toán   
        $dailyRecords = Order::paid()
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($dailyRecords as $record) {
            $dailyData[Carbon::parse($record->date)->format('d/m')] = $record->revenue;
        }

        $dailyData = collect($dailyData)->toArray();

        // Lấy 5 tuần gần nhất
        $weeklyData = [];
        $startWeek = Carbon::now()->subWeeks(4);

        for ($i = 0; $i < 5; $i++) {
            $weeksAgo = 4 - $i;
            $key = $weeksAgo > 0 ? "$weeksAgo tuần trước" : "tuần này";
            $weeklyData[$key] = 0;
        }

        // Lấy dữ liệu từ database
        $weeklyRecords = Order::paid()
            ->selectRaw('WEEK(created_at) as week, SUM(total_price) as revenue')
            ->where('created_at', '>=', $startWeek)
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        foreach ($weeklyRecords as $record) {
            $weekDiff = Carbon::now()->weekOfYear - $record->week;
            $key = $weekDiff > 0 ? "$weekDiff tuần trước" : "tuần này";
            $weeklyData[$key] = $record->revenue;
        }

        $weeklyData = collect($weeklyData)->toArray();

        // Dữ liệu theo tháng (12 tháng gần nhất)
        $monthlyData = [];
        $startMonth = Carbon::now()->subMonths(11);
        for ($i = 0; $i < 12; $i++) {
            $monthDate = $startMonth->copy()->addMonths($i);
            $monthlyData[$monthDate->format('m/Y')] = 0;
        }

        $monthlyRecords = Order::paid()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as revenue')
            ->where('created_at', '>=', $startMonth)
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        foreach ($monthlyRecords as $record) {
            $monthKey = Carbon::create($record->year, $record->month, 1)->format('m/Y');
            $monthlyData[$monthKey] = $record->revenue;
        }

        $monthlyData = collect($monthlyData)->toArray();

        // Dữ liệu theo năm (4 năm gần nhất)
        $yearlyData = [];
        $startYear = Carbon::now()->subYears(3);
        for ($i = 0; $i < 4; $i++) {
            $year = $startYear->copy()->addYears($i);
            $yearlyData[$year->format('Y')] = 0;
        }

        $yearlyRecords = Order::paid()
            ->selectRaw('YEAR(created_at) as year, SUM(total_price) as revenue')
            ->where('created_at', '>=', $startYear)
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        foreach ($yearlyRecords as $record) {
            $yearlyData[$record->year] = $record->revenue;
        }

        $yearlyData = collect($yearlyData)->toArray();

        // Doanh thu tổng
        $dailyRevenue = array_sum(array_values($dailyData));
        $weeklyRevenue = array_sum(array_values($weeklyData));
        $monthlyRevenue = array_sum(array_values($monthlyData));
        $yearlyRevenue = array_sum(array_values($yearlyData));

        // dd(
        //     $dailyData,      // [01/01 => n, 02/01 => ..., 07/01 => n]    doanh thu mỗi ngày
        //     $weeklyData,     // [4 tuần trước => n, 3 ..., tuần này => n] doanh thu mỗi tuần
        //     $monthlyData,    // [01/2025 => n, 02/202x ..., 12/202x => n] doanh thu mỗi năm
        //     $yearlyData,
        //     $dailyRevenue,   // Tổng doanh thu 7 ngày trở lại
        //     $weeklyRevenue,  // Tổng doanh thu 4 tuần trở lại
        //     $monthlyRevenue, // Tổng doanh thu 1 năm trở lại
        //     $yearlyRevenue,
        // );

        return [
            'dailyData' => $dailyData,
            'weeklyData' => $weeklyData,
            'monthlyData' => $monthlyData,
            'yearlyData' => $yearlyData,
            'dailyRevenue' => $dailyRevenue,
            'weeklyRevenue' => $weeklyRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'yearlyRevenue' => $yearlyRevenue,
        ];
    }
}