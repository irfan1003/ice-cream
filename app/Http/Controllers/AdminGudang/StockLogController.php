<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockLogController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $logs = [];

        return view('admin-gudang.stock-log', compact('logs', 'period'));
    }
}
