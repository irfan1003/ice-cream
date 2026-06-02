<?php

namespace App\Http\Controllers\KoorSales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PurchaseOrders;

class KoorIncoPOController extends Controller
{
    public function index()
    {
        $pos = PurchaseOrders::with(['customer', 'sales', 'creator'])
            ->where('status', 'pending_coordinator')
            ->latest()
            ->get();

        return view('koordinator-sales.incoming-po', compact('pos'));
    }

    public function show($id)
    {
        $po = PurchaseOrders::with(['customer', 'sales', 'details.product'])->findOrFail($id);

        return response()->json($po);
    }

    public function verify($id)
    {
        $po = PurchaseOrders::findOrFail($id);
        $po->status = 'pending_admin';
        $po->save();

        return redirect()->back()->with('success', 'P.O berhasil diverifikasi ke Admin.');
    }

    public function reject(Request $request, $id)
    {
        $po = PurchaseOrders::with('creator')->findOrFail($id);
        
        $creator = $po->creator;
        
        if (!$creator) {
            return redirect()->back()->with('error', 'Pembuat P.O tidak ditemukan.');
        }

        if ($creator->role === 'pelanggan') {
            // Jika yang buat pelanggan, kembalikan ke pending_admin seperti orders
            $po->status = 'pending_admin';
            $po->save();
            return redirect()->back()->with('success', 'P.O dikembalikan ke Admin.');
        } else {
            // Jika yang buat sales, butuh alasan dan status jadi rejected
            $request->validate([
                'rejected_note' => 'required'
            ]);

            $po->status = 'rejected';
            $po->rejected_note = $request->rejected_note;
            $po->save();

            return redirect()->back()->with('success', 'P.O berhasil ditolak.');
        }
    }
}
