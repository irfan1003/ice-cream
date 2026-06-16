<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

        return view('partials.surat-jalan', compact('order', 'delivery'));
    }

    public function updateStatusToGudang($id)
    {
        try {
            DB::beginTransaction();

            $delivery = Delivery::with('order')->findOrFail($id);
            $delivery->update([
                'delivery_status' => 'pending_admin_gudang',
                'acc_kantor' => true,
                'barcode_office' => Str::random(40)
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Surat jalan berhasil di TTD dan diteruskan ke Admin Gudang!',
                'redirect_url' => route('admin.deliveries.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}
