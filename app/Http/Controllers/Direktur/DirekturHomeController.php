<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseOrders;
use App\Models\User;
use Illuminate\Http\Request;

class DirekturHomeController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'sales', 'delivery'])
            ->whereIn('status', ['revised', 'approved', 'completed', 'paid'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $purchaseOrders = PurchaseOrders::with(['customer', 'sales', 'details.product'])
            ->whereIn('status', ['approved', 'revised', 'stock_arrived', 'converted'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalSales = User::where('role', 'sales')->count();

        // Data Grafik: Tren Penjualan 6 Bulan Terakhir
        $monthlyRevenue = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $months[] = $date->translatedFormat('F Y');
            $revenue = Order::whereIn('status', ['approved', 'completed', 'paid'])
                ->whereYear('order_date', $date->year)
                ->whereMonth('order_date', $date->month)
                ->sum('grand_total');
            $monthlyRevenue[] = $revenue;
        }

        // Data Grafik: Performa Sales Bulan Ini
        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;

        $topSales = Order::with('sales')
            ->whereIn('status', ['approved', 'completed', 'paid'])
            ->whereMonth('order_date', $currentMonth)
            ->whereYear('order_date', $currentYear)
            ->selectRaw('sales_id, sum(grand_total) as total_revenue')
            ->groupBy('sales_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        $salesNames = [];
        $salesRevenues = [];
        foreach ($topSales as $ts) {
            $salesNames[] = $ts->sales ? $ts->sales->name : 'Unknown';
            $salesRevenues[] = $ts->total_revenue;
        }

        return view('direktur.home', compact(
            'orders',
            'purchaseOrders',
            'totalProducts',
            'totalCustomers',
            'totalSales',
            'months',
            'monthlyRevenue',
            'salesNames',
            'salesRevenues'
        ));
    }
}
