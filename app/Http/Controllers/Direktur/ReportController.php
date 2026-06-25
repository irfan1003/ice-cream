<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockMutationExport;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan dengan 4 tab:
     * 1. Laporan Stok (Mutasi Stok)
     * 2. Laporan Pengiriman
     * 3. Laporan Pemasaran (Sales)
     * 4. Laporan Pelanggan
     */
    public function index(Request $request)
    {
        // --- Ambil rentang tanggal dari request, default: 7 hari terakhir ---
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subDays(6)->startOfDay();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        // ====================================================================
        // TAB 1: LAPORAN STOK (MUTASI STOK)
        // ====================================================================

        // Ambil semua produk
        $products = Product::orderBy('product_name')->get();

        // Buat array tanggal antara startDate dan endDate
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->copy();
            $currentDate->addDay();
        }

        // Ambil semua order detail dalam rentang tanggal, group by product_id dan tanggal
        // Hanya untuk order dengan status 'paid'
        $orderDetails = OrderDetail::with(['order', 'product'])
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();

        // Susun data mutasi stok: [product_id][tanggal_string] = [ord, bns, disc]
        $mutationData = [];
        foreach ($orderDetails as $detail) {
            $productId = $detail->product_id;
            $dateKey = Carbon::parse($detail->order->created_at)->format('Y-m-d');

            if (!isset($mutationData[$productId][$dateKey])) {
                $mutationData[$productId][$dateKey] = ['ord' => 0, 'bns' => 0, 'disc' => 0];
            }

            $mutationData[$productId][$dateKey]['ord'] += $detail->qty;
            $mutationData[$productId][$dateKey]['bns'] += $detail->bonus_qty ?? 0;
            $mutationData[$productId][$dateKey]['disc'] += $detail->discount ?? 0;
        }

        // Hitung stok awal (stok sebelum startDate) per produk
        // Stok awal = current_stock + total qty terjual dari startDate sampai sekarang
        $soldAfterStart = OrderDetail::whereHas('order', function ($q) use ($startDate) {
            $q->where('status', 'paid')
                ->where('created_at', '>=', $startDate);
        })
            ->selectRaw('product_id, SUM(qty) as total_qty')
            ->groupBy('product_id')
            ->pluck('total_qty', 'product_id');

        // ====================================================================
        // TAB 2: LAPORAN PENGIRIMAN
        // ====================================================================
        $deliveries = Delivery::with(['order.customer', 'driver'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // ====================================================================
        // TAB 3: LAPORAN PEMASARAN (OMZET & PERFORMA SALES)
        // ====================================================================
        $salesUsers = User::where('role', 'sales')->get();

        $salesReports = [];
        foreach ($salesUsers as $salesUser) {
            $salesOrders = Order::where('sales_id', $salesUser->id_user)
                ->where('status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $salesReports[] = (object) [
                'id' => $salesUser->id_user,
                'name' => $salesUser->name,
                'email' => $salesUser->email,
                'total_orders' => $salesOrders->count(),
                'total_revenue' => $salesOrders->sum('grand_total'),
            ];
        }

        // Urutkan berdasarkan total_revenue tertinggi
        usort($salesReports, fn($a, $b) => $b->total_revenue <=> $a->total_revenue);

        // ====================================================================
        // TAB 4: LAPORAN PELANGGAN
        // ====================================================================
        $customers = Customer::with([
            'orders' => function ($q) use ($startDate, $endDate) {
                $q->where('status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }
        ])
            ->get()
            ->map(function ($customer) {
                $customer->order_count = $customer->orders->count();
                $customer->total_spending = $customer->orders->sum('grand_total');
                return $customer;
            })
            ->filter(fn($c) => $c->order_count > 0)
            ->sortByDesc('total_spending')
            ->values();

        return view('direktur.report', compact(
            'products',
            'dates',
            'mutationData',
            'soldAfterStart',
            'deliveries',
            'salesReports',
            'customers',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export Laporan Mutasi Stok ke file Excel (.xlsx)
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subDays(6)->startOfDay();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $fileName = 'Laporan_Mutasi_Stok_'
            . $startDate->format('d-m-Y') . '_sd_'
            . $endDate->format('d-m-Y') . '.xlsx';

        return Excel::download(new StockMutationExport($startDate, $endDate), $fileName);
    }
}
