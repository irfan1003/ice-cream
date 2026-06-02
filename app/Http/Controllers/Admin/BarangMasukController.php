<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $incomingGoods = \App\Models\StockLog::with('product')
            ->where('type', 'in')
            ->whereNot('final_status', 'completed')
            ->get()
            ->groupBy('reference');

        return view('admin.barang-masuk', compact('incomingGoods'));
    }

    public function create()
    {
        return view('admin.add-barangmasuk');
    }

    public function store(Request $request)
    {
        $request->validate([
            'reference' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id_product',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $item) {
            \App\Models\StockLog::create([
                'product_id' => $item['product_id'],
                'user_id' => auth()->id(),
                'quantity' => $item['quantity'],
                'reference' => $request->reference,
                'type' => 'in',
                'verification_status' => 'pending',
                'final_status' => 'draft',
            ]);
        }

        return redirect()->route('admin.barang-masuk.index')->with('success', 'Barang masuk berhasil dicatat');
    }

    public function edit($id)
    {
        $log = \App\Models\StockLog::findOrFail($id);
        $products = \App\Models\Product::all();
        return view('admin.edit-barangmasuk', compact('log', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product',
            'quantity' => 'required|integer|min:1',
        ]);

        $log = \App\Models\StockLog::findOrFail($id);
        $log->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('admin.barang-masuk.index')->with('success', 'Barang masuk berhasil diperbarui');
    }

    public function resetVerification($reference)
    {
        \App\Models\StockLog::where('reference', $reference)->update([
            'verification_status' => 'pending'
        ]);

        return redirect()->route('admin.barang-masuk.index')->with('success', 'Barang masuk dikirim ke admin gudang untuk verifikasi ulang');
    }

    public function updateStock($reference)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($reference) {
            $logs = \App\Models\StockLog::where('reference', $reference)
                ->where('final_status', 'draft')
                ->get();

            foreach ($logs as $log) {
                $log->update(['final_status' => 'completed']);

                \App\Models\Product::where('id_product', $log->product_id)
                    ->increment('current_stock', $log->quantity);
            }
        });

        return redirect()->route('admin.barang-masuk.index')->with('success', 'Stok berhasil diperbarui dan status log telah diselesaikan');
    }
}
