<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
class DashboardController extends Controller{
    public function index(){
        $totalCompleteRevenue = Order::where('status','complete')->sum('total_amount');


        $monthlyCompleteRevenue = Order::where('status','complete')
        ->whereMonth('created_at',now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_amount');

        return view('admin.dashboard', compact('totalCompleteRevenue','monthlyCompleteRevenue'));
    }
}