<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\User;

class IncOrdersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::with(['customer', 'sales', 'orderDetail.product', 'delivery'])
            ->where('status', 'approved')
            ->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('customer_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin-gudang.incoming-orders', compact('orders', 'search'));
    }

    public function previewSuratJalan($id)
    {
        $order = Order::with(['customer.zone', 'sales', 'orderDetail.product', 'delivery'])->findOrFail($id);
        $delivery = $order->delivery;

        // Fetch signatures
        $adminKantor = User::where('role', 'admin_kantor')->whereNotNull('signature')->first();
        $adminGudang = User::where('role', 'admin_gudang')->whereNotNull('signature')->first();

        return view('partials.surat-jalan', compact('order', 'delivery', 'adminKantor', 'adminGudang'));
    }

    public function getDrivers()
    {
        $drivers = User::where('role', 'driver')->get(['id_user', 'name', 'email']);
        return response()->json($drivers);
    }

    public function processToDelivery(Request $request, $id)
    {
        try {
            $request->validate([
                'driver_id' => 'required|exists:users,id_user',
            ]);

            $order = Order::with(['customer.zone', 'sales', 'orderDetail.product'])->findOrFail($id);

            // Generate unique SPB Number
            $spbNumber = 'SPB/' . now()->format('Ymd') . '/' . str_pad($order->id_order, 5, '0', STR_PAD_LEFT);

            // Create Delivery Record with driver
            \App\Models\Delivery::create([
                'order_id' => $order->id_order,
                'spb_number' => $spbNumber,
                'driver_id' => $request->driver_id,
                'file_surat_jalan' => null,
                'delivery_status' => 'pending_admin_kantor'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat jalan diteruskan ke admin kantor',
                'redirect_url' => route('gudang.incorders.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsReady($id)
    {
        try {
            $order = Order::with('delivery')->findOrFail($id);
            if ($order->delivery) {
                $order->delivery->update([
                    'delivery_status' => 'ready',
                    'acc_gudang' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Surat jalan berhasil disetujui',
                'redirect_url' => route('gudang.incorders.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}
