<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    // Dùng Eloquent
    public function revenue()
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

        // Doanh thu tổng
        $dailyRevenue = array_sum(array_values($dailyData));
        $weeklyRevenue = array_sum(array_values($weeklyData));
        $monthlyRevenue = array_sum(array_values($monthlyData));

        return view('admin.stats.index', compact(
            'dailyData',
            'weeklyData',
            'monthlyData',
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
        ));
    }
}
// // Một số thông tin thống kê khác 
// // Tổng số đơn hàng
// $totalOrders = DB::table('orders')->count();
// // Đơn hàng đang chờ xác nhận
// $pendingOrders = DB::table('orders')
//     ->where('status_order', 'pending')
//     ->count();
// // Tổng số sản phẩm (product)
// $totalProducts = DB::table('products')->count();