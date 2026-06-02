<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Delivery;
use App\Models\StockLog;

class AdminDeliveryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $deliveries = Delivery::with(['order.customer'])
            // ->where('delivery_status', 'pending_admin_kantor')
            ->when($search, function ($query, $search) {
                return $query->where('spb_number', 'like', "%{$search}%")
                    ->orWhereHas('order.customer', function ($q) use ($search) {
                        $q->where('customer_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.deliveries', compact('deliveries', 'search'));
    }

    public function previewSuratJalan($id)
    {
        $delivery = Delivery::findOrFail($id);
        $order = \App\Models\Order::with(['customer.zone', 'sales', 'orderDetail.product'])
            ->findOrFail($delivery->order_id);

        // Fetch signatures
        $adminKantor = \App\Models\User::where('role', 'admin_kantor')->whereNotNull('signature')->first();
        $adminGudang = \App\Models\User::where('role', 'admin_gudang')->whereNotNull('signature')->first();

        return view('partials.surat-jalan', compact('order', 'delivery', 'adminKantor', 'adminGudang'));
    }

    public function updateStatusToGudang($id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $delivery = Delivery::with('order')->findOrFail($id);
            $delivery->update([
                'delivery_status' => 'pending_admin_gudang',
                'acc_kantor' => true
            ]);

            if ($delivery->order) {
                StockLog::where('reference', $delivery->order->order_number)->update([
                    'verification_status' => 'sesuai',
                    'final_status' => 'completed',
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Surat jalan berhasil di TTD dan diteruskan ke Admin Gudang.',
                'redirect_url' => route('admin.deliveries.index')
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}
