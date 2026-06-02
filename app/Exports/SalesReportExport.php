<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function collection()
    {
        $queryOrder = Order::with(['sales']);

        if ($this->period == 'today') {
            $queryOrder->whereDate('order_date', Carbon::today());
        } elseif ($this->period == 'week') {
            $queryOrder->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->period == 'month') {
            $queryOrder->whereMonth('order_date', Carbon::now()->month)->whereYear('order_date', Carbon::now()->year);
        }

        $orders = $queryOrder->whereIn('status', ['completed', 'approved'])->get();

        $salesData = [];
        foreach ($orders as $order) {
            if ($order->sales) {
                $salesId = $order->sales->id_user;
                if (!isset($salesData[$salesId])) {
                    $salesData[$salesId] = [
                        'name' => $order->sales->name,
                        'total_orders' => 0,
                        'total_revenue' => 0,
                    ];
                }
                $salesData[$salesId]['total_orders'] += 1;
                $salesData[$salesId]['total_revenue'] += $order->grand_total;
            }
        }
        
        return collect($salesData)->sortByDesc('total_revenue')->values();
    }

    public function headings(): array
    {
        return [
            'Nama Sales',
            'Total Pesanan',
            'Total Pendapatan (Omzet)',
        ];
    }
}
