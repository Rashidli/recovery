<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
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
            'monthlyOrders', 'totalOrders'));
    }

    public function getOrderStats(Request $request)
    {
        $query = Order::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date);
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('time', [$startDate, $endDate]);
        }

        $totalOrders = $query->whereNotIn('status', ['new', 'accepted','in_progress'])->count();

        $orders = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->whereNotIn('status', ['new', 'accepted','in_progress'])
            ->get();

        $chartData = $orders->map(function ($order) use ($totalOrders) {
            $percentage = $totalOrders > 0 ? ($order->count / $totalOrders) * 100 : 0;

            return [
                'status' => OrderStatus::from($order->status)->label(),
                'count' => $order->count,
                'color' => OrderStatus::from($order->status)->color(),
                'percentage' => round($percentage, 2),
            ];
        });

        return view('admin.reports.report', compact('chartData', 'totalOrders'));
    }

    public function reportDriverStats(Request $request)
    {
        $query = Order::query();

        // Default to last day if no dates are provided
        $startDate = $request->filled('start_date')
            ? \Carbon\Carbon::parse($request->start_date)
            : \Carbon\Carbon::now()->subDay()->startOfDay();

        $endDate = $request->filled('end_date')
            ? \Carbon\Carbon::parse($request->end_date)->endOfDay()
            : \Carbon\Carbon::now()->subDay()->endOfDay();

        // Apply date filter
        $query->whereBetween('time', [$startDate, $endDate]);

        $totalOrders = $query->count();

        // Calculate counts
        $pendingCount = $query->get()->filter->is_pending_driver_assignment->count();
        $notPendingCount = $totalOrders - $pendingCount;

        // Calculate percentages
        $pendingPercentage = $totalOrders > 0 ? round(($pendingCount / $totalOrders) * 100, 1) : 0;
        $notPendingPercentage = $totalOrders > 0 ? round(($notPendingCount / $totalOrders) * 100, 1) : 0;

        $chartData = [
            [
                'status' => 'Driver Reached in Time',
                'count' => $notPendingCount,
                'percentage' => $notPendingPercentage,
                'color' => '#28a745', // Green
            ],
            [
                'status' => 'Driver Did Not Reach in Time',
                'count' =>  $pendingCount,
                'percentage' =>  $pendingPercentage,
                'color' => '#dc3545', // Red
            ],
        ];

        return view('admin.reports.report_driver', compact('chartData', 'totalOrders'));
    }



}
