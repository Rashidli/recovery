<?php

namespace App\Livewire;

use App\Models\OrderLog;
use Livewire\Component;

class OrderLogSortTable extends Component
{
    public $order;

    public function mount($order)
    {
        $this->order = $order;
    }

    public function updateOrderLogOrder($orderLogs)
    {

        foreach ($orderLogs as $log) {
            OrderLog::whereId($log['value'])->update(['row' => $log['order']]);
        }

    }

    public function render()
    {

        $order_logs = $this->order->order_logs()->orderBy('row')->get();
        return view('admin.livewire.order_log-sort-table', compact('order_logs'));

    }

}
