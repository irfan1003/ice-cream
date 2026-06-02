<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapPenjualanExport;

class RekapPenjualanController extends Controller
{
    public function index()
    {
        $details = OrderDetail::with(['order.customer', 'order.sales', 'order.delivery', 'product'])
            ->whereHas('order', function ($query) {
                $query->where('status', 'paid')
                    ->whereHas('delivery', function ($q) {
                        $q->where('delivery_status', 'delivered');
                    });
            })
            ->latest()
            ->paginate(20);

        return view('admin.rekap-penjualan', compact('details'));
    }

    public function export(Request $request)
    {
        $period = $request->input('period', 'week');
        $startDate = $period === 'month' ? Carbon::now()->subMonth() : Carbon::now()->subWeek();

        $fileName = 'rekap_penjualan_' . $period . '_' . Carbon::now()->format('YmdHis') . '.xlsx';

        return Excel::download(new RekapPenjualanExport($startDate), $fileName);
    }
}
