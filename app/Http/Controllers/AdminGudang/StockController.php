<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin-gudang.stock.index', compact('products'));
    }

    public function showLogDetail($productId)
    {
        $product = Product::findOrFail($productId);
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $logs = StockLog::with('user')
            ->where('product_id', $productId)
            ->latest()
            ->get();

        $totalIn = $logs->where('type', 'in')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('quantity');

        $totalOut = $logs->where('type', 'out')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('quantity');

        $stokAwal = $product->current_stock - $totalIn + $totalOut;

        return response()->json([
            'product' => $product,
            'logs' => $logs,
            'summary' => [
                'stok_awal' => $stokAwal,
                'total_masuk' => $totalIn,
                'total_keluar' => $totalOut,
            ]
        ]);
    }

    public function adjustment(Request $request, $productId)
    {
        $validated = $request->validate([
            'jumlah_fisik' => 'required|integer|min:0',
            'alasan' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $productId) {
                $product = Product::findOrFail($productId);
                $currentStock = $product->current_stock;
                $difference = $validated['jumlah_fisik'] - $currentStock;

                if ($difference !== 0) {
                    $type = $difference > 0 ? 'in' : 'out';
                    $quantity = abs($difference);

                    StockLog::create([
                        'product_id' => $productId,
                        'user_id' => auth()->id(),
                        'type' => $type,
                        'quantity' => $quantity,
                        'reference_note' => 'Stock Opname: ' . $validated['alasan'],
                    ]);

                    $product->current_stock = $validated['jumlah_fisik'];
                    $product->save();
                }
            });

            return redirect()->route('gudang.stock.index')->with('success', 'Adjustment stok berhasil!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan adjustment stok: ' . $e->getMessage());
        }
    }
}
