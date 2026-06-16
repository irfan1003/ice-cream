<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\StockLog;
use Illuminate\Http\Request;

class StockLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = StockLog::with(['product', 'user'])->latest()->paginate(15);

        return view('direktur.stock-logs.index', compact('logs'));
    }
}
