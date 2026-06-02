<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PurchaseOrders;
use App\Models\Order;
use App\Models\OrderDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductPOExport;

class AdminInPOController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $purchaseOrders = PurchaseOrders::with(['customer', 'sales', 'details.product'])
            ->where('status', 'pending_admin')
            ->when($search, function ($query, $search) {
                return $query->where('po_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('customer_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.incoming-po', compact('purchaseOrders', 'search'));
    }

    public function approve($id)
    {
        try {
            $po = PurchaseOrders::findOrFail($id);
            $po->update([
                'status' => 'pending_director'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order berhasil diteruskan ke Direktur.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui PO: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel($status)
    {
        try {
            return Excel::download(
                new ProductPOExport($status),
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

    public function updateDetails(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:purchase_order_details,id_po_detail',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.bonus_qty' => 'nullable|integer|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();
            $po = PurchaseOrders::with('details')->findOrFail($id);

            $subtotal = 0;
            $totalDiscount = 0;

            foreach ($request->items as $itemData) {
                $detail = \App\Models\PurcaheOrderDetail::findOrFail($itemData['id']);
                $qty = $itemData['qty'];
                $bonusQty = $itemData['bonus_qty'] ?? 0;
                $discountPerItem = $itemData['discount'] ?? 0;

                $priceAtTime = $detail->price_at_time;
                $itemTotal = ($priceAtTime - $discountPerItem) * $qty;

                $detail->update([
                    'qty' => $qty,
                    'bonus_qty' => $bonusQty,
                    'discount' => $discountPerItem,
                    'total_item_price' => $itemTotal
                ]);

                $subtotal += $priceAtTime * $qty;
                $totalDiscount += $discountPerItem * $qty;
            }

            $tax = ($subtotal - $totalDiscount) * 0.11;
            $grandTotal = ($subtotal - $totalDiscount) + $tax;

            $po->update([
                'subtotal' => $subtotal,
                'discount_total' => $totalDiscount,
                'tax_amount' => $tax,
                'grand_total' => $grandTotal,
                'status' => 'pending_director'
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detail Purchase Order berhasil diperbarui dan diteruskan ke Direktur.'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui PO: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);

        try {
            \DB::beginTransaction();
            $po = PurchaseOrders::with('details')->findOrFail($id);
            $oldStatus = $po->status;

            $po->update(['status' => $request->status]);

            if ($request->status === 'stock_arrived' && $oldStatus !== 'stock_arrived') {
                foreach ($po->details as $detail) {
                    \App\Models\StockLog::create([
                        'product_id' => $detail->product_id,
                        'user_id' => auth()->id(),
                        'verification_status' => 'pending',
                        'quantity' => $detail->qty + ($detail->bonus_qty ?? 0),
                        'reference' => $po->po_number,
                        'type' => 'in',
                        'final_status' => 'draft'
                    ]);
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status PO berhasil diperbarui menjadi ' . str_replace('_', ' ', $request->status)
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status PO: ' . $e->getMessage()
            ], 500);
        }
    }

    public function convert($id)
    {
        try {
            \DB::beginTransaction();
            $po = PurchaseOrders::with('details')->findOrFail($id);

            // Create Order
            $order = Order::create([
                'customer_id' => $po->customer_id,
                'sales_id' => $po->sales_id,
                'po_id' => $po->id_po,
                'created_by' => $po->created_by,
                'order_date' => now(),
                'subtotal' => $po->subtotal,
                'tax_amount' => $po->tax_amount,
                'discount_total' => $po->discount_total,
                'grand_total' => $po->grand_total,
                'status' => 'pending_admin',
            ]);

            // Set Order Number
            $order->update([
                'order_number' => 'DOSO' . str_pad($order->id_order, 8, '0', STR_PAD_LEFT)
            ]);

            // Copy Details
            foreach ($po->details as $detail) {
                OrderDetail::create([
                    'order_id' => $order->id_order,
                    'product_id' => $detail->product_id,
                    'qty' => $detail->qty,
                    'bonus_qty' => $detail->bonus_qty,
                    'discount' => $detail->discount,
                    'price_at_time' => $detail->price_at_time,
                    'total_item_price' => $detail->total_item_price,
                ]);
            }

            // Update PO Status
            $po->update(['status' => 'converted']);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PO berhasil dikonversi menjadi Pesanan Reguler.'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonversi PO: ' . $e->getMessage()
            ], 500);
        }
    }
}
