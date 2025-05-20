<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Order::with('images', 'drivers', 'order_logs');

        if ($this->request->filled('reference_number')) {
            $query->where('reference_number', 'like', '%' . $this->request->reference_number . '%');
        }

        if ($this->request->filled('phone')) {
            $query->where('phone', 'like', '%' . $this->request->phone . '%');
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('date')) {
            $query->whereDate('created_at', $this->request->date);
        }

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $startDate = $this->request->start_date;
            $endDate = \Carbon\Carbon::parse($this->request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
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
            'Reached Date/Time',
            'Completed Date/Time',
            'Driver Name',
            'Driver Phone',
            'Estimate Amount',
            'Zone Code',
            'Vat',
            'Total',
            'Waiting charge',
            'Reached cancelled charge',
            'Vendor amount',
            'Remark',
            'Trip number',
            'Vendor name',
            'Vendor price',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->reference_number,
            \Carbon\Carbon::parse($order->time)->format('Y-m-d'),
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
            optional($order->time)->format('Y-m-d h:i:s A'),
            optional($order->order_logs->where('status', 'accepted')->first()?->logged_at)->format('Y-m-d h:i:s A'),
            optional($order->order_logs->where('driver_status', 'driver_assigned')->first()?->logged_at)->format('Y-m-d h:i:s A'),
            optional($order->order_logs->where('driver_status', 'driver_reached_customer')->first()?->logged_at)->format('Y-m-d h:i:s A'),
            optional($order->order_logs->where('driver_status', 'driver_drop')->first()?->logged_at)->format('Y-m-d h:i:s A'),
            optional($order->drivers->first())->driver_name ?? '',
            optional($order->drivers->first())->driver_phone ?? '',
            $order->estimated_amt,
            $order->zone_codes,
            $order->vat,
            $order->total,
            $order->waiting_charge,
            $order->reached_cancelled_charge,
            $order->vendor_amount,
            $order->remarks,
            $order->trip_number,
            $order->vendor_name,
            $order->vendor_amount,
        ];
    }
}
