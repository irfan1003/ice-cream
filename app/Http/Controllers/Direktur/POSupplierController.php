<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierPo;
use Illuminate\Support\Facades\DB;

class POSupplierController extends Controller
{
    public function index()
    {
        $poSuppliers = SupplierPo::with('supplier')
            ->where('status', 'pending_director')
            ->orderBy('po_date', 'desc')
            ->get();

        return view('direktur.po-supplier.index', compact('poSuppliers'));
    }

    public function show($id)
    {
        $poSupplier = SupplierPo::with(['supplier', 'details.product'])->findOrFail($id);
        return view('direktur.po-supplier.show', compact('poSupplier'));
    }

    public function approve($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $po = SupplierPo::findOrFail($id);
                $po->update(['status' => 'pending_office']);
            });

            return redirect()->route('direktur.po-supplier.index')->with('success', 'PO Supplier berhasil disetujui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui PO Supplier: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $po = SupplierPo::findOrFail($id);
                $po->update(['status' => 'pending']);
            });

            return redirect()->route('direktur.po-supplier.index')->with('success', 'PO Supplier berhasil ditolak!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak PO Supplier: ' . $e->getMessage());
        }
    }
}
