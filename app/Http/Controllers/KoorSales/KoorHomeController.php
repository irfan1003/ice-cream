<?php

namespace App\Http\Controllers\KoorSales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PurchaseOrders;
use Illuminate\Http\Request;

class KoorHomeController extends Controller
{
    public function index()
    {
        $pendingOrdersCount = Order::where('status', 'pending_coordinator')->count();
        $pendingPOsCount = PurchaseOrders::where('status', 'pending_coordinator')->count();

        return view('koordinator-sales.home', compact('pendingOrdersCount', 'pendingPOsCount'));
    }
}
