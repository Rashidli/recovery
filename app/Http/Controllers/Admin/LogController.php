<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DriverStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class LogController extends Controller
{
    public function store(Request $request) :JsonResponse
    {
        $request->validate([
            'driver_status' => 'required|string',
            'driver_status_gps' => 'nullable',
            'logged_at' => 'required|date',
        ]);

        $order = Order::find($request->order_id);
        $existingStatusLog = $order->order_logs()
            ->where('driver_status', $request->driver_status)
            ->first();
        $driverAssignLog = $order->order_logs()
            ->where('driver_status', 'driver_assigned')
            ->first();

        if (!$driverAssignLog) {
            return response()->json([
                'error' => 'You must first assign a driver.'
            ], 400);
        }

        if ($existingStatusLog) {
            return response()->json(['error' => 'This status already exists and cannot be added again'], 400);
        }

        $lastLog = $order->order_logs()->whereNotNull('driver_status')->orderBy('logged_at', 'desc')->first();

        if ($lastLog) {
            $lastPriority = DriverStatus::getStatusPriority($lastLog->driver_status);
            $newPriority = DriverStatus::getStatusPriority($request->driver_status);

            if ($newPriority !== $lastPriority + 1) {
                return response()->json(['error' => 'Status sequence is incorrect'], 400);
            }

            if (strtotime($request->logged_at) <= strtotime($lastLog->logged_at)) {
                return response()->json(['error' => 'Logged at must be greater than the previous log dates'], 400);
            }
        }

        $logCount = $order->order_logs()->count();
        $order->order_logs()->create([
            'driver_status' => $request->driver_status,
            'driver_status_gps' => $request->driver_status_gps,
            'status_text' => DriverStatus::getStatusText($request->driver_status),
            'logged_at' => \Carbon\Carbon::parse($request->logged_at),
            'user' => auth()->user()->name,
            'row' => $logCount + 1,
        ]);

        return response()->json(['success' => true]);
    }




    public function update(Request $request, $id): JsonResponse
    {

        $request->validate([
            'driver_status' => 'required|string',
            'driver_status_gps' => 'nullable',
            'logged_at' => 'required|date',
        ]);

        $log = OrderLog::findOrFail($id);
        $order = $log->order;

        // Find the log that immediately precedes the current log based on `logged_at`
        $previousLog = $order->order_logs()
            ->where('id', '!=', $log->id)
            ->where('logged_at', '<', $log->logged_at)
            ->orderBy('logged_at', 'desc')
            ->first();

        // Check if there's already a log with the same driver_status
        $existingStatusLog = $order->order_logs()
            ->where('driver_status', $request->driver_status)
            ->where('id', '!=', $log->id)
            ->first();

        if ($existingStatusLog) {
            return response()->json(['error' => 'This status already exists and cannot be added again'], 400);
        }

        // If the status is being updated, check the priority
        if ($request->driver_status !== $log->driver_status) {
            if ($previousLog) {
                $prevPriority = DriverStatus::getStatusPriority($previousLog->driver_status);
                $newPriority = DriverStatus::getStatusPriority($request->driver_status);

                if ($newPriority !== $prevPriority + 1 && $newPriority !== $prevPriority) {
                    return response()->json(['error' => 'Status sequence is incorrect'], 400);
                }
            }
        }

        // Ensure `logged_at` is later than the `previousLog`'s `logged_at`
        if ($previousLog && strtotime($request->logged_at) <= strtotime($previousLog->logged_at)) {
            return response()->json(['error' => 'Logged at must be greater than the previous log dates'], 400);
        }

        // Update log details
        $log->update([
            'driver_status' => $request->driver_status,
            'driver_status_gps' => $request->driver_status_gps,
            'logged_at' => \Carbon\Carbon::parse($request->logged_at),
            'status_text' => DriverStatus::getStatusText($request->driver_status),
        ]);

        if ($request->driver_status == 'driver_assigned') {
            $driverLog = $order->driver_logs->first();

            if ($driverLog) {
                $driverLog->update([
                    'logged_at' => \Carbon\Carbon::parse($request->logged_at),
                ]);
            } else {
                return response()->json(['error' => 'No driver logs found for this order.'], 404);
            }
        }

        return response()->json(['success' => true]);
    }



    public function edit($id): JsonResponse
    {
        $log = OrderLog::findOrFail($id);
        return response()->json($log);
    }

    public function destroy($id): JsonResponse
    {
        OrderLog::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
