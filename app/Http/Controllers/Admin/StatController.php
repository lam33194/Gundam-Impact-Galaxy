<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    // // Một số thông tin thống kê khác 
    // // Tổng số đơn hàng
    // $totalOrders = DB::table('orders')->count();
    // // Tổng số sản phẩm (product)
    // $totalProducts = DB::table('products')->count();

    public function user()
    {
        $totalCompleteRevenue = Order::where('status_order','complete')->sum('total_price');

        $monthlyCompleteRevenue = Order::where('status_order','complete')
        ->whereMonth('created_at',now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_price');

        $startDate = Carbon::now()->subDays(6)->startOfDay();

        $endDate = Carbon::now()->endOfDay();
        $totalNewCustomers = DB::table('users')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();
        $dailyNewCustomers = DB::table('users')
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get();

    $chartData = collect();
    for ($i = 0; $i < 7; $i++) {
        $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
        $count = $dailyNewCustomers->firstWhere('date', $date)->count ?? 0;
        $chartData->push(['date' => $date, 'count' => $count]);
    }

    $newCustomers = DB::table('users')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderByDesc('created_at')
        ->get();

    $maxDay = $chartData->sortByDesc('count')->first();

    $average = round($chartData->avg('count'), 1);

    $topCustomers = DB::table('users')
    ->join('orders', 'users.id', '=', 'orders.user_id')
    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
    ->select('users.id', 'users.name', DB::raw('SUM(order_items.quantity) as total_products'))
    ->groupBy('users.id', 'users.name')
    ->orderByDesc('total_products')
    ->limit(5)
    ->get();
    $customerCounts = DB::table('users')
    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
    ->where('created_at', '>=', now()->subDays(6)->startOfDay()) // 7 ngày gần nhất
    ->groupBy('date')
    ->orderBy('date')
    ->get();

   $labels = $customerCounts->pluck('date')->map(function ($date) {
    return \Carbon\Carbon::parse($date)->format('d/m'); // định dạng: 13/04
});

$data = $customerCounts->pluck('count');



    return view('admin.stats.user', compact(
        'totalCompleteRevenue',
        'monthlyCompleteRevenue',
        'totalNewCustomers',
        'chartData',
        'newCustomers',
        'maxDay',
        'average',
        'topCustomers',
        'labels',
        'data',
        'customerCounts'
    ));
    
    }
  

}
