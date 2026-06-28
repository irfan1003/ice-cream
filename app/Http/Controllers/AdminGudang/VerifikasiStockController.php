<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierPo;
use App\Models\SupplierPoDetail;
use Illuminate\Support\Facades\DB;

class VerifikasiStockController extends Controller
{
    public function index()
    {
        // Get Supplier POs with pending and received statuses
        $poSuppliers = SupplierPo::with('supplier')
            ->whereIn('status', ['ordered'])
            ->orderBy('po_date', 'desc')
            ->get();

        return view('admin-gudang.verifikasi-stock', compact('poSuppliers'));
    }

    public function terimaBarang($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $po = SupplierPo::findOrFail($id);
                $po->update(['status' => 'received']);
            });

            return redirect()->route('gudang.verifikasi.index')->with('success', 'PO Supplier berhasil diterima!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menerima PO Supplier: ' . $e->getMessage());
        }
    }

    public function showVerifikasi($id)
    {
        $poSupplier = SupplierPo::with(['supplier', 'details.product'])->findOrFail($id);
        return view('admin-gudang.verifikasi-stock-show', compact('poSupplier'));
    }

    public function storeVerifikasi(Request $request, $id)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.is_compatible' => 'nullable|boolean',
            'details.*.qty_received' => 'nullable|integer|min:0',
            'details.*.reject_reason' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $poSupplier = SupplierPo::findOrFail($id);
                $hasIncompatible = false;

                foreach ($request->details as $detailId => $data) {
                    $detail = SupplierPoDetail::findOrFail($detailId);
                    $isCompatible = isset($data['is_compatible']) && $data['is_compatible'] == '1';

                    // Jika sesuai, gunakan qty dari detail
                    // Jika tidak sesuai, pastikan user mengisi qty_received
                    if ($isCompatible) {
                        $qtyReceived = $detail->qty;
                    } else {
                        $qtyReceived = $data['qty_received'] ?? null;
                        // Validasi manual: jika tidak sesuai, qty_received harus diisi
                        if ($qtyReceived === null) {
                            throw new \Exception('Qty Diterima harus diisi untuk barang yang tidak sesuai');
                        }
                    }

                    $detail->update([
                        'is_compatible' => $isCompatible,
                        'qty_received' => $qtyReceived,
                        'reject_reason' => !$isCompatible ? ($data['reject_reason'] ?? null) : null,
                    ]);

                    if (!$isCompatible) {
                        $hasIncompatible = true;
                    }
                }

                // Update PO status
                if ($hasIncompatible) {
                    $poSupplier->update(['status' => 'pending_office']);
                } else {
                    $poSupplier->update(['status' => 'pending_director_rec']);
                }
            });

            return redirect()->route('gudang.verifikasi.index')->with('success', 'Verifikasi PO Supplier berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memverifikasi PO Supplier: ' . $e->getMessage());
        }
    }
}
