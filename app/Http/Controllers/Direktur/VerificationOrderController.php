<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
        $order = Order::with(['customer.zone', 'sales', 'orderDetail.product'])->findOrFail($id);
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
            $order = Order::with(['customer.zone', 'sales', 'orderDetail.product'])->findOrFail($id);

            // Signature logic
            // User requested signature_2.png specifically, but ideally we use the logged in user's signature
            // If signature_2.png is required as per prompt:
            $signaturePath = storage_path('app/public/signatures/signature_2.png');

            if (!file_exists($signaturePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tanda tangan tidak ditemukan di storage.'
                ], 404);
            }

            // Convert signature to base64 for PDF embedding
            $type = pathinfo($signaturePath, PATHINFO_EXTENSION);
            $data = file_get_contents($signaturePath);
            $signatureBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            // Generate PDF using Browsershot (Puppeteer)
            $html = view('direktur.faktur-final', [
                'order' => $order,
                'isPdf' => true,
                'signature' => $signatureBase64
            ])->render();

            $fileName = 'invoice_' . $order->order_number . '_' . time() . '.pdf';
            $filePath = 'invoices/' . $fileName;
            $fullPath = storage_path('app/public/' . $filePath);

            // Ensure directory exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            Browsershot::html($html)
                ->setNodeBinary('C:/Program Files/nodejs/node.exe')
                ->setNpmBinary('C:/Program Files/nodejs/npm.cmd')
                ->setChromePath('C:/Users/A c e r/.cache/puppeteer/chrome-headless-shell/win64-148.0.7778.97/chrome-headless-shell-win64/chrome-headless-shell.exe')
                ->noSandbox()
                ->windowSize(1400, 2000)
                ->showBackground()
                ->margins(20, 20, 20, 20)
                ->format('A4')
                ->save($fullPath);

            // Update Order
            $order->update([
                'status' => 'approved',
                'invoice_pdf' => $filePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil disetujui dan Faktur PDF telah digenerate.',
                'invoice_url' => asset('storage/' . $filePath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
