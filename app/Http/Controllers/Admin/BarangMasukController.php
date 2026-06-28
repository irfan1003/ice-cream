<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierPo;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $poSuppliers = SupplierPo::with(['supplier', 'details'])
            ->where('status', 'pending_office')
            ->orderBy('po_date', 'desc')
            ->get();

        return view('admin.barang-masuk', compact('poSuppliers'));
    }

    public function show($id)
    {
        $poSupplier = SupplierPo::with(['supplier', 'details.product'])->findOrFail($id);
        return view('admin.barang-masuk-show', compact('poSupplier'));
    }

    public function finalize($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $po = SupplierPo::with('details')->findOrFail($id);
                $po->update(['status' => 'received']);

                foreach ($po->details as $item) {
                    $product = Product::findOrFail($item->product_id);
                    $product->current_stock += $item->qty_received;
                    $product->save();

                    StockLog::create([
                        'product_id' => $item->product_id,
                        'user_id' => auth()->id(),
                        'type' => 'in',
                        'quantity' => $item->qty_received,
                        'po_supplier_id' => $id,
                        'order_id' => null,
                        'reference_note' => 'Restock dari PO #' . $po->po_number
                    ]);
                }
            });

            return redirect()->route('admin.barang-masuk.index')->with('success', 'PO Supplier berhasil diterima dan stok berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memverifikasi PO Supplier: ' . $e->getMessage());
        }
    }

    public function sendBackToWarehouse($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $po = SupplierPo::findOrFail($id);
                $po->update(['status' => 'ordered']);
            });

            return redirect()->route('admin.barang-masuk.index')->with('success', 'PO Supplier dikirim kembali ke Gudang untuk verifikasi ulang!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim PO Supplier kembali ke Gudang: ' . $e->getMessage());
        }
    }

    public function forceFinalizeWithDiscrepancy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $po = SupplierPo::with('details')->findOrFail($id);
                $po->update(['status' => 'received']);

                foreach ($po->details as $item) {
                    $product = Product::findOrFail($item->product_id);
                    $product->current_stock += $item->qty_received;
                    $product->save();

                    StockLog::create([
                        'product_id' => $item->product_id,
                        'user_id' => auth()->id(),
                        'type' => 'in',
                        'quantity' => $item->qty_received,
                        'po_supplier_id' => $id,
                        'order_id' => null,
                        'reference_note' => 'Restock PO #' . $po->po_number . ' (Penyesuaian Barang Kurang/Rusak)'
                    ]);
                }
            });

            return redirect()->route('admin.barang-masuk.index')->with('success', 'PO Supplier berhasil diterima dengan penyesuaian stok!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memverifikasi PO Supplier: ' . $e->getMessage());
        }
    }
}
