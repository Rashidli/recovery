<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DriverStatus;
use App\Http\Controllers\Controller;
use App\Models\FromCity;
use App\Models\Order;
use App\Models\ServiceCategory;
use App\Models\ServiceCenter;
use App\Models\VehicleMake;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:list-orders|create-orders|edit-orders|delete-orders', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-orders', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-orders', ['only' => ['edit']]);
        $this->middleware('permission:delete-orders', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        Session::put('page', request()->fullUrl());

        $isAdmin = auth()->user()->hasRole('Admin');
        $query = Order::query()->with('drivers');

        if ($request->filled('reference_number')) {
            $query->where('reference_number', 'like', '%' . $request->reference_number . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {

            $startDate = \Carbon\Carbon::parse($request->start_date);
            $endDate = \Carbon\Carbon::parse($request->end_date);

            $query->whereBetween('time', [$startDate, $endDate]);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $query->withCount('drivers')
            ->orderByRaw('
            CASE
                WHEN status IN ("canceled", "reached_cancelled") THEN 1
                ELSE 0
            END ASC
        ') // Bu şərt canceled və reached_cancelled statuslarını aşağı salır
            ->orderByRaw('drivers_count = 0 DESC') // Drivers olmayanları yuxarı çıxarır
            ->orderBy('drivers_count', 'asc') // Daha az driver olanları növbəti
            ->orderByDesc('created_at'); // Ən yeni tarixlər sonra

        $limit = $request->filled('limit') && is_numeric($request->limit) ? $request->limit : 50;

        $orders = $query->paginate($limit)->withQueryString();

        $today = \Carbon\Carbon::today();
        $statusCounts = Order::whereDate('created_at', $today)
            ->select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        $totalOrdersToday = Order::whereDate('created_at', $today)->count();
        return view('admin.orders.index', compact('orders', 'isAdmin', 'statusCounts', 'totalOrdersToday'));
    }

    public function create()
    {

        $vehicle_makes = VehicleMake::query()->with('vehicle_models')->get();
        $service_categories = ServiceCategory::query()->with('types')->get();
        $isAdmin = auth()->user()->hasRole('Admin');
        $from_cities = FromCity::query()->with('areas')->get();
        return view('admin.orders.create', compact('vehicle_makes', 'service_categories', 'from_cities', 'isAdmin'));
    }

    public function show(Order $order)
    {
        //
    }

    public function edit(Order $order)
    {

        $hasDriver = $order->drivers->isNotEmpty();
        $isAdmin = auth()->user()->hasRole('Admin');
        $vehicle_makes = VehicleMake::query()->with('vehicle_models')->get();
        $service_categories = ServiceCategory::query()->with('types')->get();
        $from_cities = FromCity::query()->with('areas')->get();
        $serviceCenters = ServiceCenter::all();
        $vendors = Vendor::all();
        return view('admin.orders.edit', compact('order', 'serviceCenters',
            'isAdmin', 'vehicle_makes', 'service_categories', 'from_cities', 'hasDriver','vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'vehicle_make' => 'nullable|string',
            'vehicle_model' => 'nullable|string',
            'vehicle_plate_no' => 'nullable|string',
            'time' => 'nullable|string',
            'phone' => 'nullable|string',
            'service_category' => 'nullable|string',
            'from_city' => 'nullable|string',
            'from_area' => 'nullable|string',
            'comment' => 'nullable|string',
            'zone_codes' => 'nullable|string',
            'service_type' => 'nullable|string',
            'to_location_details' => 'nullable|string',
            'from_location_details' => 'nullable|string',
            'from_gps_coordinates' => 'nullable|string',
            'to_city' => 'nullable|string',
            'to_area' => 'nullable|string',
            'trip_number' => 'nullable|string',
            'starting_time' => 'nullable|string',
            'estimated_amt' => 'nullable|string',
            'reached_time' => 'nullable|string',
            'ending_time' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('uploads', 'public');
        }

        $validated['time'] = now();

        $order = Order::create($validated);
        $logCount = $order->order_logs()->count();
        $order->order_logs()->create([
            'status' => 'new',
            'logged_at' => now(),
            'user' => auth()->user()->name,
            'row' => $logCount + 1,
        ]);
        if ($request->hasFile('order_files')) {
            foreach ($request->file('order_files') as $order_file) {
                $file_name = $order_file->store('uploads', 'public');
                $order->images()->create([
                    'file' => $file_name
                ]);
            }
        }

        return redirect()->route('orders.index')->with('message', 'Order created successfully!');
    }

    // Update the specified order
    public function update(Request $request, Order $order)
    {

        $validated = $request->validate([
            'reference_number' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'vehicle_make' => 'nullable|string',
            'vehicle_model' => 'nullable|string',
            'vehicle_plate_no' => 'nullable|string',
            'time' => 'nullable|string',
            'phone' => 'nullable|string',
            'service_category' => 'nullable|string',
            'from_city' => 'nullable|string',
            'from_area' => 'nullable|string',
            'comment' => 'nullable|string',
            'service_type' => 'nullable|string',
            'zone_codes' => 'nullable|string',
            'to_location_details' => 'nullable|string',
            'from_location_details' => 'nullable|string',
            'from_gps_coordinates' => 'nullable|string',
            'to_city' => 'nullable|string',
            'to_area' => 'nullable|string',
            'trip_number' => 'nullable|string',
            'waiting_charge' => 'nullable|string',
            'starting_time' => 'nullable|string',
            'estimated_amt' => 'nullable|string',
            'vendor_amount' => 'nullable|numeric',
            'vendor_name' => 'nullable|string',
            'reached_time' => 'nullable|string',
            'ending_time' => 'nullable|string',
            'status' => 'nullable',
            'driver_status' => 'nullable',
            'file' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('uploads', 'public');
        }

        if ($request->hasFile('order_files')) {
            foreach ($request->file('order_files') as $order_file) {
                $file_name = $order_file->store('uploads', 'public');
                $order->images()->create([
                    'order_file' => $file_name
                ]);
            }
        }

//        if ($request->status && $request->status !== $order->status) {
//            $existingStatusLog = $order->order_logs()->where('status', $request->status)->first();
//            if(!$existingStatusLog){
//                $order->order_logs()->create([
//                    'status' => $request->status,
//                    'logged_at' => now(),
//                ]);
//            }
//        }

        if ($request->driver_status && $request->driver_status !== $order->driver_status) {
            $existingStatusLog = $order->order_logs()->where('driver_status', $request->driver_status)->first();

            if (!$existingStatusLog) {
                $order->order_logs()->create([
                    'driver_status' => $request->driver_status,
                    'driver_status_gps' => $request->driver_status_gps,
                    'status_text' => DriverStatus::getStatusText($request->driver_status),
                    'logged_at' => now(),
                    'user' => auth()->user()->name
                ]);
            }
        }

        $order->update($validated);
        $logCount = $order->order_logs()->count();
        if ($request->drivers) {
            foreach ($request->drivers as $driverData) {
                if ($driverData['driver_id'] !== null) {
                    $existingDriver = $order->drivers()->find($driverData['driver_id']);
                    if ($existingDriver) {
                        //update driver
                        $oldDriverName = $existingDriver->driver_name;

                        $existingDriver->update([
                            'driver_name' => $driverData['driver_name'],
                            'driver_car_number' => $driverData['driver_car_number'],
                            'driver_phone' => $driverData['driver_phone'],
                        ]);

                        if ($oldDriverName !== $driverData['driver_name']) {
                            $order->driver_logs()->create([
                                'driver_name' => $driverData['driver_name'],
                                'user_name' => auth()->user()->name,
                                'user_id' => auth()->user()->id,
                                'logged_at' => now()->format('Y-m-d\TH:i'),
                            ]);
                        }
                    }
                } else {

                    if ($order->status == 'new') {
                        return redirect()->back()->with('error', 'First accept order');
                    } else {
                        if ($driverData['driver_name'] !== null) {
                            if (!$order->drivers->count()) {
                                // new driver
                                $newDriver = $order->drivers()->create([

                                    'driver_name' => $driverData['driver_name'],
                                    'driver_car_number' => $driverData['driver_car_number'],
                                    'driver_phone' => $driverData['driver_phone'],
                                ]);
                                $order->driver_logs()->create([
                                    'driver_name' => $driverData['driver_name'],
                                    'user_name' => auth()->user()->name,
                                    'user_id' => auth()->user()->id,
                                    'logged_at' => now()->format('Y-m-d\TH:i'),
                                ]);
                                // Log the creation of the new driver
                                $order->order_logs()->create([
                                    'status_text' => 'Driver Assigned: ',
                                    'driver_status' => DriverStatus::DRIVER_ASSIGN,
                                    'user' => auth()->user()->name,
                                    'logged_at' => now()->format('Y-m-d\TH:i'),
                                    'row' => $logCount + 1,
                                ]);
                            }
                        }
                    }
                }
            }
        }


        return redirect()->back()->with('message', 'Order updated successfully!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('message', 'Order deleted successfully');
    }

    public function exportToCSV(Request $request)
    {

        $query = Order::with('images', 'drivers');

        if ($request->filled('reference_number')) {
            $query->where('reference_number', 'like', '%' . $request->reference_number . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay(); // Set end of day for end_date

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $query->get();

        $headers = [
            'Serial No',
            'Reference No',
            'Job Open Date',
            'Status',
            'Customer Name',
            'Plate No',
            'Mobile No',
            'From City',
            'From Location',
            'Pick Up Location',
            'Drop Off Location',
            'To City',
            'To Area',
            'Service Category',
            'Service Type',
            'Veh. Make',
            'Veh. Model',
            'Comments',
            'Created Date Time',
            'Job Accepted Date/Time',
            'Driver Assigned Date/Time',
            'Driver Accepted Date/Time',
            'Reached Date/Time',
            'Completed Date/Time',
            'Driver Name',
            'Driver Phone',
            'Estimate Amount',
        ];

        $response = new StreamedResponse(function () use ($orders, $headers) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $headers);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->reference_number,
                    \Carbon\Carbon::parse($order->time)->format('Y-m-d h:i:s A'),
                    $order->status,
                    $order->customer_name,
                    $order->vehicle_plate_no,
                    $order->phone,
                    $order->from_city,
                    $order->from_area,
                    $order->from_location_details,
                    $order->to_location_details,
                    $order->to_city,
                    $order->to_area,
                    $order->service_category,
                    $order->service_type,
                    $order->vehicle_make,
                    $order->vehicle_model,
                    $order->comment,
                    \Carbon\Carbon::parse($order->time)->format('Y-m-d h:i:s A'),
                    $order->order_logs->where('status', 'accepted')->first()?->logged_at ? \Carbon\Carbon::parse($order->order_logs->where('status', 'accepted')->first()->logged_at)->format('Y-m-d h:i:s A') : '',
                    $order->order_logs->where('driver_status', 'driver_assigned')->first()?->logged_at ? \Carbon\Carbon::parse($order->order_logs->where('driver_status', 'driver_assigned')->first()->logged_at)->format('Y-m-d h:i:s A') : '',
                    $order->order_logs->where('driver_status', 'driver_reached_customer')->first()?->logged_at ? \Carbon\Carbon::parse($order->order_logs->where('driver_status', 'driver_reached_customer')->first()->logged_at)->format('Y-m-d h:i:s A') : '',
                    $order->order_logs->where('driver_status', 'driver_drop')->first()?->logged_at ? \Carbon\Carbon::parse($order->order_logs->where('driver_status', 'driver_drop')->first()->logged_at)->format('Y-m-d h:i:s A') : '',
                    $order->drivers->first()->driver_name ?? '',
                    $order->drivers->first()->driver_phone ?? '',
                    $order->estimated_amt,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders.csv"');

        return $response;
    }

    private function getOrderFiles($order)
    {
        $base_url = url('/storage/');

        return $order->images->map(function ($image) use ($base_url) {
            return $image->order_file ? $base_url . '/' . $image->order_file : '';
        })->implode(', ');
    }


    private function getDriverDetails($order)
    {
        return $order->drivers->map(function ($driver) {
            return "Name: {$driver->driver_name}, Phone: {$driver->driver_phone}, Car No: {$driver->driver_car_number}";
        })->implode(' | ');
    }

    public function change_status(Order $order)
    {
        if ($order->status !== 'accepted') {
            $order->status = 'accepted';
            $order->order_logs()->create([
                'status' => 'accepted',
                'logged_at' => now(),
                'user' => auth()->user()->name,
            ]);
            $order->save();
        }
        return redirect()->back()->with('success', 'Status changed');
    }

    public function fetchServiceCenters($vehicle_make_id)
    {
        // Assuming you have a ServiceCenter model and it has a vehicle_make_id foreign key
        $service_centers = ServiceCenter::where('vehicle_make_id', $vehicle_make_id)->get(['id', 'name', 'city']);

        return response()->json($service_centers);
    }

    public function deleteImage($id)
    {

        DB::table('order_files')->where('id', '=', $id)->delete();
        return redirect()->back()->with('message', 'Image deleted');

    }
}
