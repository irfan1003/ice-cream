<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot;

class DriverHomeController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::where('driver_id', Auth::id())
            ->whereIn('delivery_status', ['ready', 'shipped', 'delivered'])
            ->with(['order.customer', 'order.orderDetail.product'])
            ->latest()
            ->get();

        return view('driver.home', compact('deliveries'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:shipped,delivered'
        ]);

        try {
            $delivery = Delivery::where('driver_id', Auth::id())->findOrFail($id);
            $order = $delivery->order;

            // Update Delivery Status based on logic
            if ($request->status === 'delivered') {
                $delivery->update(['delivery_status' => 'delivered']);
                // Update Order Status to completed when delivered
                $order->update(['status' => 'completed']);
            } else if ($request->status === 'shipped') {
                $delivery->update(['delivery_status' => 'shipped']);
                // Order status stays as it is (likely 'approved' or 'panding_admin')
            }

            return response()->json([
                'success' => true,
                'message' => 'Status pengiriman berhasil diperbarui ke ' . ucfirst($request->status)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateSpb($id)
    {
        try {
            $delivery = Delivery::with(['order.customer.zone', 'order.orderDetail.product'])->findOrFail($id);

            $html = view('partials.faktur-surat-jalan', [
                'delivery' => $delivery
            ])->render();

            $fileName = 'spb_' . $delivery->spb_number . '_' . time() . '.pdf';
            $filePath = 'spb/' . $fileName;
            $fullPath = storage_path('app/public/' . $filePath);

            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            $browsershot = Browsershot::html($html);

            if (app()->environment('local')) {

                $browsershot->setNodeBinary('C:/Program Files/nodejs/node.exe')
                    ->setNpmBinary('C:/Program Files/nodejs/npm.cmd')
                    ->setChromePath('C:\Program Files\Google\Chrome\Application\chrome.exe');
            } else {

                $browsershot->setChromePath('/usr/bin/chromium')
                    ->setNodeModulePath(base_path('node_modules'))
                    ->noSandbox();
            }

            $browsershot->windowSize(1400, 2000)
                ->showBackground()
                ->margins(20, 20, 20, 20)
                ->format('A4')
                ->save($fullPath);

            return response()->json([
                'success' => true,
                'message' => 'Surat Jalan berhasil digenerate.',
                'pdf_url' => asset('storage/' . $filePath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate Surat Jalan: ' . $e->getMessage()
            ], 500);
        }
    }
}
