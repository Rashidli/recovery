<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:list-reports|create-reports|edit-reports|delete-reports', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-reports', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-reports', ['only' => ['edit']]);
        $this->middleware('permission:delete-reports', ['only' => ['destroy']]);
    }

    public function index()
    {

        $dailyOrders = Order::query()
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere('status', 'reached_cancelled');
            })
            ->whereDate('time', Carbon::today())
            ->selectRaw('COUNT(*) as order_count, COALESCE(SUM(vendor_amount), 0) as total_amount')
            ->first();

        $weeklyOrders = Order::query()
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere('status', 'reached_cancelled');
            })
            ->whereBetween('time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->selectRaw('COUNT(*) as order_count, COALESCE(SUM(vendor_amount), 0) as total_amount')
            ->first();

        $monthlyOrders = Order::query()
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere('status', 'reached_cancelled');
            })
            ->whereMonth('time', Carbon::now()->month)
            ->selectRaw('COUNT(*) as order_count, COALESCE(SUM(vendor_amount), 0) as total_amount')
            ->first();

        $totalOrders = Order::query()
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere('status', 'reached_cancelled');
            })
            ->selectRaw('COUNT(*) as order_count, COALESCE(SUM(vendor_amount), 0) as total_amount')
            ->first();

        return view('admin.reports.index', compact(
            'dailyOrders',
            'weeklyOrders',
            'monthlyOrders','totalOrders'));
    }

}
