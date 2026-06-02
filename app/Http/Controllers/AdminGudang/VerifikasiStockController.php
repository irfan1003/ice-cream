<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifikasiStockController extends Controller
{
    public function index()
    {
        $incomingGoods = \App\Models\StockLog::with('product')
            ->where('type', 'in')
            ->where('final_status', 'draft')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('reference');

        return view('admin-gudang.verifikasi-stock', compact('incomingGoods'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id_log' => 'required|exists:stock_logs,id_log',
            'items.*.verification_status' => 'required|in:sesuai,tidak_sesuai',
            'items.*.warehouse_note' => 'nullable|string'
        ]);

        foreach ($request->items as $item) {
            \App\Models\StockLog::where('id_log', $item['id_log'])->update([
                'verification_status' => $item['verification_status'],
                'warehouse_note' => $item['warehouse_note'] ?? null,
                'final_status' => 'draft'
            ]);
        }

        return redirect()->back()->with('success', 'Verifikasi stok berhasil disimpan');
    }
}
