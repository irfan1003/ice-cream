<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerificationOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::with(['customer.zone', 'sales', 'orderDetail.product'])
            ->where('status', 'pending_director')
            ->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('customer_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10);

        return view('direktur.Verification-orders', compact('orders', 'search'));
    }

    public function previewInvoiceFinal($id)
    {
        $order = Order::with(['customer', 'customer.zone', 'sales', 'orderDetail.product'])->findOrFail($id);
        return view('direktur.faktur-final', compact('order'));
    }

    public function revise($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->update(['status' => 'revised']);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikembalikan untuk revisi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal merevisi pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approve($id)
    {
        try {
            $invoiceUrl = null;
            DB::transaction(function () use ($id, &$invoiceUrl) {
                $order = Order::with(['customer.zone', 'sales', 'orderDetail.product'])->findOrFail($id);

                $barcodeKey = Str::random(40);
                $order->barcode_key = $barcodeKey;

                $html = view('direktur.faktur-final', [
                    'order' => $order,
                    'isPdf' => true
                ])->render();

                $fileName = 'invoice_' . $order->order_number . '_' . time() . '.pdf';
                $filePath = 'invoices/' . $fileName;
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

                $order->update([
                    'status' => 'approved',
                    'invoice_pdf' => $filePath,
                    'barcode_key' => $barcodeKey
                ]);

                $invoiceUrl = asset('storage/' . $filePath);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil disetujui dan Faktur PDF telah digenerate.',
                'invoice_url' => $invoiceUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
