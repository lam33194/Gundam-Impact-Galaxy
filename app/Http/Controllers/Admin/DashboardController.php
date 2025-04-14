<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCompleteRevenue = Order::where('status_order','complete')->sum('total_price');

        $monthlyCompleteRevenue = Order::where('status_order','complete')
        ->whereMonth('created_at',now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_price');

        return view('admin.dashboard', compact('totalCompleteRevenue','monthlyCompleteRevenue'));


        
    }
}
