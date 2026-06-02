<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeliveryReportExport;
use App\Exports\SalesReportExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $queryDelivery = Delivery::with(['order.customer', 'driver', 'order.orderDetail.product']);
        $queryOrder = Order::with(['sales', 'customer']);

        if ($period == 'today') {
            $queryDelivery->whereDate('created_at', Carbon::today());
            $queryOrder->whereDate('order_date', Carbon::today());
        } elseif ($period == 'week') {
            $queryDelivery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $queryOrder->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $queryDelivery->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
            $queryOrder->whereMonth('order_date', Carbon::now()->month)->whereYear('order_date', Carbon::now()->year);
        }

        $deliveries = $queryDelivery->orderBy('created_at', 'desc')->get();
        $orders = $queryOrder->whereIn('status', ['completed', 'approved'])->get();

        // Group orders by sales
        $salesData = [];
        foreach ($orders as $order) {
            if ($order->sales) {
                $salesId = $order->sales->id_user;
                if (!isset($salesData[$salesId])) {
                    $salesData[$salesId] = (object)[
                        'id' => $salesId,
                        'name' => $order->sales->name,
                        'email' => $order->sales->email,
                        'total_orders' => 0,
                        'total_revenue' => 0,
                        'orders' => []
                    ];
                }
                $salesData[$salesId]->total_orders += 1;
                $salesData[$salesId]->total_revenue += $order->grand_total;
                $salesData[$salesId]->orders[] = $order;
            }
        }
        
        $salesReports = collect($salesData)->sortByDesc('total_revenue')->values();

        return view('direktur.report', compact('deliveries', 'salesReports', 'period'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type');
        $period = $request->input('period', 'month');
        
        $dateStr = Carbon::now()->format('Y-m-d');
        
        if ($type == 'pengiriman') {
            return Excel::download(new DeliveryReportExport($period), 'Laporan_Pengiriman_' . $dateStr . '.xlsx');
        } elseif ($type == 'pemasaran') {
            return Excel::download(new SalesReportExport($period), 'Laporan_Pemasaran_' . $dateStr . '.xlsx');
        }
        
        return back()->with('error', 'Tipe export tidak valid');
    }
}
