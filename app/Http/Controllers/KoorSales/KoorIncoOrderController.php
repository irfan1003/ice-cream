<?php

namespace App\Http\Controllers\KoorSales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\StockLog;

class KoorIncoOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'sales', 'orderBySales'])
            ->where('status', 'pending_coordinator')
            ->latest()
            ->get();
            
        return view('koordinator-sales.incoming-order', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'sales', 'orderDetail.product'])->findOrFail($id);
        
        return response()->json($order);
    }

    public function verify($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'pending_admin';
        $order->save();

        return redirect()->back()->with('success', 'Pesanan berhasil diverifikasi ke Admin.');
    }

    public function reject(Request $request, $id)
    {
        $order = Order::with(['orderDetail.product', 'orderByPelanggan'])->findOrFail($id);
        
        // created_by is a user, let's check the role
        $creator = \App\Models\User::find($order->created_by);
        
        if (!$creator) {
            return redirect()->back()->with('error', 'Pencipta pesanan tidak ditemukan.');
        }

        if ($creator->role === 'pelanggan') {
            // Jika yang buat pelanggan, kembalikan ke pending_sales tanpa alasan
            $order->status = 'pending_sales';
            $order->save();
            return redirect()->back()->with('success', 'Pesanan dikembalikan ke Sales untuk pengecekan.');
        } else {
            // Jika yang buat sales, butuh alasan dan status jadi rejected + balikkan stok
            $request->validate([
                'rejected_note' => 'required'
            ]);

            try {
                \Illuminate\Support\Facades\DB::beginTransaction();

                // Balikkan stok
                foreach ($order->orderDetail as $detail) {
                    if ($detail->product) {
                        $detail->product->increment('current_stock', $detail->qty);
                    }
                }

                // Update StockLog reference status
                StockLog::where('reference', $order->order_number)->update([
                    'verification_status' => 'tidak_sesuai',
                    'final_status' => 'completed',
                ]);

                $order->status = 'rejected';
                $order->rejected_note = $request->rejected_note;
                $order->save();

                \Illuminate\Support\Facades\DB::commit();

                return redirect()->back()->with('success', 'Pesanan berhasil ditolak dan stok dikembalikan.');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menolak pesanan: ' . $e->getMessage());
            }
        }
    }
}
