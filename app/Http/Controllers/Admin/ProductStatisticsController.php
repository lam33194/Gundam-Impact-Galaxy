<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStatisticsController extends Controller
{
    public function index(Request $request)
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


        return view('admin.product_statistics.index', compact('products', 'start', 'end', 'limit'));
    }
}
