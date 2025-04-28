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
        // Giá trị trung bình mỗi đơn hàng
        $averageOrderValue = Order::statusOrderFilter(Order::STATUS_ORDER_DELIVERED)->avg('total_price');
        // Total orders
        $totalOrders = Order::count();

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
            'averageOrderValue',
            'totalOrders',
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
        // Lấy start_date và end_date từ request, mặc định là 6 ngày trước và hôm nay
        $startDate = request()->has('from_date')
            ? Carbon::parse(request('from_date'))
            : null;

        $endDate = request()->has('to_date')
            ? Carbon::parse(request('to_date'))
            : Carbon::now();

        // Tính doanh thu theo thời gian mặc định: 10 ngày, 10 tuần, 12 tháng, 4 năm
        if (!$startDate) {
            $dailyStart = Carbon::now()->subDays(9); // 10 ngày gần nhất (bao gồm hôm nay)
            $weeklyStart = Carbon::now()->subWeeks(6); // 10 tuần gần nhất
            $monthlyStart = Carbon::now()->subMonths(11); // 12 tháng gần nhất
            $yearlyStart = Carbon::now()->subYears(3); // 4 năm gần nhất
        } else {
            // Nếu có start_date, dùng chung cho tất cả
            $dailyStart = $weeklyStart = $monthlyStart = $yearlyStart = $startDate;
        }

        if ($startDate && $endDate->lt($startDate)) {
            $endDate = $startDate->copy();
        }

        // --- Dữ liệu theo ngày (tối đa 30 ngày từ start_date) ---
        $dailyData = [];
        $daysLimit = $startDate
            ? min($startDate->diffInDays($endDate) + 1, 30) // Có lọc: tối đa 30 ngày
            : 10;

        $dailyStartDate = $startDate ?: $dailyStart;

        for ($i = 0; $i < $daysLimit; $i++) {
            $date = $dailyStartDate->copy()->addDays($i);
            $dailyData[$date->format('d/m')] = 0;
        }

        $dailyRecords = Order::paid()
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
            ->whereBetween('created_at', [$dailyStartDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($dailyRecords as $record) {
            $dateKey = Carbon::parse($record->date)->format('d/m');
            if (isset($dailyData[$dateKey])) {
                $dailyData[$dateKey] = $record->revenue;
            }
        }

        $dailyData = collect($dailyData)->toArray();

        // --- Dữ liệu theo tuần (mặc định 10 tuần, hoặc tối đa 10 tuần nếu có lọc) ---
        $weeklyData = [];
        $weeksLimit = $startDate
            ? min(ceil($startDate->diffInWeeks($endDate) + 1), 10) // Có lọc: tối đa 10 tuần
            : 10;
        $weeklyStartDate = $startDate ?: $weeklyStart;
        $currentWeek = Carbon::now()->weekOfYear;
        $currentYear = Carbon::now()->year;

        for ($i = 0; $i < $weeksLimit; $i++) {
            $weekStart = $weeklyStartDate->copy()->addWeeks($i);
            $weekNumber = $weekStart->weekOfYear;
            $weekYear = $weekStart->year;
            $weekDiff = ($currentYear * 52 + $currentWeek) - ($weekYear * 52 + $weekNumber);

            if ($weekDiff > 5) {
                $key = "Tuần $weekNumber $weekYear";
            } else {
                $key = $weekDiff > 0 ? "$weekDiff tuần trước" : "tuần này";
            }
            $weeklyData[$key] = 0;
        }

        $weeklyRecords = Order::paid()
            ->selectRaw('WEEK(created_at, 1) as week, YEAR(created_at) as year, SUM(total_price) as revenue')
            ->whereBetween('created_at', [$weeklyStartDate, $weeklyStartDate->copy()->addWeeks($weeksLimit - 1)])
            ->groupBy('week', 'year')
            ->orderBy('year')
            ->orderBy('week')
            ->get();

        foreach ($weeklyRecords as $record) {
            $weekStart = Carbon::create($record->year, 1, 1)->addWeeks($record->week - 1);
            $weekNumber = $weekStart->weekOfYear;
            $weekYear = $record->year;
            $weekDiff = ($currentYear * 52 + $currentWeek) - ($weekYear * 52 + $weekNumber);

            if ($weekDiff > 5) {
                $key = "Tuần $weekNumber $weekYear";
            } else {
                $key = $weekDiff > 0 ? "$weekDiff tuần trước" : "tuần này";
            }

            if (isset($weeklyData[$key])) {
                $weeklyData[$key] = $record->revenue;
            }
        }

        $weeklyData = collect($weeklyData)->toArray();

        // --- Dữ liệu theo tháng (mặc định 12 tháng, hoặc tối đa 12 tháng nếu có lọc) ---
        $monthlyData = [];
        $monthsLimit = $startDate
            ? min($startDate->diffInMonths($endDate) + 1, 12) // Có lọc: tối đa 12 tháng
            : 12;
        $monthlyStartDate = $startDate ?: $monthlyStart;

        for ($i = 0; $i < $monthsLimit; $i++) {
            $monthDate = $monthlyStartDate->copy()->addMonths($i);
            $monthlyData[$monthDate->format('m/Y')] = 0;
        }

        $monthlyRecords = Order::paid()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as revenue')
            ->whereBetween('created_at', [$monthlyStartDate, $endDate])
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        foreach ($monthlyRecords as $record) {
            $monthKey = Carbon::create($record->year, $record->month, 1)->format('m/Y');
            if (isset($monthlyData[$monthKey])) {
                $monthlyData[$monthKey] = $record->revenue;
            }
        }

        $monthlyData = collect($monthlyData)->toArray();

        // --- Dữ liệu theo năm (mặc định 4 năm, hoặc tối đa 4 năm nếu có lọc) ---
        $yearlyData = [];
        $yearsLimit = $startDate
            ? min($startDate->diffInYears($endDate) + 1, 4) // Có lọc: tối đa 4 năm
            : 4;
        $yearlyStartDate = $startDate ?: $yearlyStart;

        for ($i = 0; $i < $yearsLimit; $i++) {
            $year = $yearlyStartDate->copy()->addYears($i);
            $yearlyData[$year->format('Y')] = 0;
        }

        $yearlyRecords = Order::paid()
            ->selectRaw('YEAR(created_at) as year, SUM(total_price) as revenue')
            ->whereBetween('created_at', [$yearlyStartDate, $endDate])
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        foreach ($yearlyRecords as $record) {
            $yearKey = (string) $record->year;
            if (isset($yearlyData[$yearKey])) {
                $yearlyData[$yearKey] = $record->revenue;
            }
        }

        $yearlyData = collect($yearlyData)->toArray();

        // --- Tính doanh thu tổng ---
        $dailyRevenue = array_sum(array_values($dailyData));
        $weeklyRevenue = array_sum(array_values($weeklyData));
        $monthlyRevenue = array_sum(array_values($monthlyData));
        $yearlyRevenue = array_sum(array_values($yearlyData));

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