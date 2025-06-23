<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $revenueToday = Order::where('status', 'completed')->whereDate('created_at', today())->sum('total_amount');
        $ordersTodayCount = Order::whereDate('created_at', today())->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $recentOrders = Order::latest()->take(5)->get();

        // Data untuk chart penjualan 7 hari terakhir
        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = $salesData->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        });
        $chartData = $salesData->pluck('total');

        return view('admin.dashboard', compact(
            'revenueToday',
            'ordersTodayCount',
            'pendingOrdersCount',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }
}
