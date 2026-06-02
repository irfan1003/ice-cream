<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\PurchaseOrders;

class HomeController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalSales = User::where('role', 'sales')->count();

        // Existing Orders
        $receivedOrders = Order::with(['customer', 'sales', 'orderDetail.product', 'delivery'])->where('status', 'revised')->latest()->get();
        $approvedOrders = Order::with(['customer', 'sales', 'delivery'])
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereDoesntHave('delivery')
                    ->orWhereHas('delivery', function ($dq) {
                        $dq->whereNotIn('delivery_status', ['shipped', 'delivered']);
                    });
            })
            ->latest()->get();
        $shippedOrders = Order::with(['customer', 'sales', 'delivery'])
            ->whereHas('delivery', function ($q) {
                $q->where('delivery_status', 'shipped');
            })
            ->latest()->get();
        $deliveredOrders = Order::with(['customer', 'sales', 'delivery'])
            ->where('status', '!=', 'paid')
            ->whereHas('delivery', function ($q) {
                $q->where('delivery_status', 'delivered');
            })
            ->latest()->get();

        $paidOrders = Order::with(['customer', 'sales', 'delivery'])
            ->where('status', 'paid')
            ->latest()->get();

        // New Purchase Orders for Monitoring
        $purchaseOrders = PurchaseOrders::with(['customer', 'sales', 'details.product'])
            ->whereIn('status', ['approved', 'revised', 'stock_arrived', 'converted'])
            ->latest()
            ->get();

        return view('admin.home', compact(
            'totalProducts',
            'totalCustomers',
            'totalSales',
            'receivedOrders',
            'approvedOrders',
            'shippedOrders',
            'deliveredOrders',
            'paidOrders',
            'purchaseOrders'
        ));
    }

    public function markAsPaid($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->status = 'paid';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate menjadi sudah dibayar.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }
}
