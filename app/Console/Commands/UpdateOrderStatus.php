<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class UpdateOrderStatus extends Command
{
    // Command signature
    protected $signature = 'orders:update-status';

    // Command description
    protected $description = 'Update orders status to accepted after one minute of creation';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $orders = Order::where('status', 'new')
            ->get();

        foreach ($orders as $order) {
            $logCount = $order->order_logs()->count();
            $order->status = 'accepted';
            $order->save();
            $order->order_logs()->create([
                'status' => 'accepted',
                'logged_at' => now(),
                'user' => 'Admin',
                'row' => $logCount + 1
            ]);
        }

        $this->info('Order statuses updated successfully.');
    }
}
