<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminInOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::with(['customer', 'sales', 'orderDetail.product'])
            ->where('status', 'pending_admin')
            ->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('customer_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.incoming-orders', compact('orders', 'search'));
    }

    public function previewInvoice($id)
    {
        $order = Order::with(['customer.zone', 'sales', 'orderDetail.product'])->findOrFail($id);
        return view('admin.invoice-preview', compact('order'));
    }

    public function approve($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->update([
                'status' => 'pending_director'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diteruskan ke Direktur.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDetails(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_details,id_order_detail',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.bonus_qty' => 'nullable|integer|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();
            $order = Order::with('orderDetail')->findOrFail($id);
            
            $subtotal = 0;
            $totalDiscount = 0;

            foreach ($request->items as $itemData) {
                $detail = \App\Models\OrderDetail::findOrFail($itemData['id']);
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

            $order->update([
                'subtotal' => $subtotal,
                'discount_total' => $totalDiscount,
                'tax_amount' => $tax,
                'grand_total' => $grandTotal,
                'status' => 'pending_director'
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detail pesanan berhasil diperbarui dan diteruskan ke Direktur.'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
