<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\StockLog;

class HomeController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['order.customer', 'driver'])->latest()->get();
        $stockLogs = StockLog::where('verification_status', 'sesuai')
            ->orWhere('verification_status', 'tidak_sesuai')
            ->with(['product', 'user'])->latest()->get()->groupBy('reference');

        return view('admin-gudang.home', compact('deliveries', 'stockLogs'));
    }
}
