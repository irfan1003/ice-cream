<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseOrders;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $salesId = auth()->id();

        // Include all possible statuses
        $orderStatuses = [
            'pending_sales',
            'pending_coordinator', 
            'pending_director', 
            'revised', 
            'approved', 
            'pending_admin', 
            'completed', 
            'paid',
            'rejected'
        ];
        
        $poStatuses = [
            'pending_sales',
            'pending_coordinator', 
            'pending_admin', 
            'pending_director', 
            'approved', 
            'revised', 
            'rejected'
        ];

        $orders = Order::with(['customer', 'orderDetail.product', 'delivery'])
            ->where('sales_id', $salesId)
            ->whereIn('status', $orderStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        $purchaseOrders = PurchaseOrders::with(['customer', 'details.product'])
            ->where('sales_id', $salesId)
            ->whereIn('status', $poStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'pending_sales_orders' => Order::where('sales_id', $salesId)->where('status', 'pending_sales')->count(),
            'pending_sales_pos' => PurchaseOrders::where('sales_id', $salesId)->where('status', 'pending_sales')->count(),
            'customers' => Order::where('sales_id', $salesId)
                ->select('customer_id')
                ->union(
                    PurchaseOrders::where('sales_id', $salesId)
                        ->select('customer_id')
                )
                ->get()
                ->count(),
            'products' => Product::count(),
        ];

        return view('sales.home', compact('orders', 'purchaseOrders', 'stats'));
    }
}
