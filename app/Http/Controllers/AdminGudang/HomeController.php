<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;

class HomeController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['order.customer', 'driver'])->latest()->get();
        $stockLogs = [];

        return view('admin-gudang.home', compact('deliveries', 'stockLogs'));
    }
}
