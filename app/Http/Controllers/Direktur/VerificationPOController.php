<?php

namespace App\Http\Controllers\Direktur;

use App\Exports\ProductPOExportDirektur;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PurchaseOrders;
use Maatwebsite\Excel\Facades\Excel;
class VerificationPOController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $purchaseOrders = PurchaseOrders::with(['customer', 'sales', 'details.product'])
            ->where('status', 'pending_director')
            ->when($search, function ($query, $search) {
                return $query->where('po_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('customer_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('direktur.Verification-po', compact('purchaseOrders', 'search'));
    }

    public function approve($id)
    {
        try {
            $po = PurchaseOrders::findOrFail($id);
            $po->update(['status' => 'approved']);

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order berhasil disetujui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui PO: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $po = PurchaseOrders::findOrFail($id);
            $po->update(['status' => 'revised']);

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order telah dikembalikan untuk revisi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak PO: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcelDirektur($status)
    {
        try {
            return Excel::download(
                new ProductPOExportDirektur($status),
                'Purchase_Orders_Summary_' . $status . '_' . date('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export excel: ' . $e->getMessage());
        }
    }

    public function exportSinglePO($id)
    {
        try {
            $po = PurchaseOrders::findOrFail($id);
            return Excel::download(
                new \App\Exports\SinglePOExport($id),
                'Purchase_Order_' . $po->po_number . '.xlsx'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export excel: ' . $e->getMessage());
        }
    }
}
