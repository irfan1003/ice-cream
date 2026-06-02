<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockLog;

class StockLogController extends Controller
{
    public function index(Request $request)
    {
        $query = StockLog::with(['product', 'user']);
        
        $period = $request->input('period', 'month');
        if ($period == 'today') {
            $query->whereDate('created_at', \Carbon\Carbon::today());
        } elseif ($period == 'week') {
            $query->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('created_at', \Carbon\Carbon::now()->month)->whereYear('created_at', \Carbon\Carbon::now()->year);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();
        return view('admin.stock-log', compact('logs', 'period'));
    }
}
