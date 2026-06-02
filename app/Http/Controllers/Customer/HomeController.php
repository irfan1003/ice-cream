<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        $orders = [];
        $purchaseOrders = [];
        
        if ($customer) {
            $orders = $customer->orders()
                ->with(['orderDetail.product', 'delivery'])
                ->orderBy('order_date', 'desc')
                ->get();

            $purchaseOrders = $customer->purchaseOrders()
                ->with(['details.product'])
                ->orderBy('po_date', 'desc')
                ->get();
        }

        return view('customers.home', compact('orders', 'purchaseOrders'));
    }
}
