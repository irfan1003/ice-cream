<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurcaheOrderDetail;
use App\Models\PurchaseOrders;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalSales = User::where('role', 'sales')->count();

        // Orders
        $approvedOrders = Order::with(['customer', 'sales', 'delivery'])
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereDoesntHave('delivery')
                    ->orWhereHas('delivery', function ($dq) {
                        $dq->whereNotIn('delivery_status', ['shipped', 'delivered']);
                    });
            })
            ->latest()->get();

        $shippedOrders = Order::with(['customer', 'sales', 'delivery'])
            ->whereHas('delivery', function ($q) {
                $q->where('delivery_status', 'shipped');
            })
            ->latest()->get();

        $deliveredOrders = Order::with(['customer', 'sales', 'delivery'])
            ->where('status', '!=', 'paid')
            ->whereHas('delivery', function ($q) {
                $q->where('delivery_status', 'delivered');
            })
            ->latest()->get();

        $paidOrders = Order::with(['customer', 'sales', 'delivery'])
            ->where('status', 'paid')
            ->latest()->get();

        // Purchase Orders: only approved & revised shown on dashboard
        $purchaseOrders = PurchaseOrders::with(['customer', 'sales', 'details.product'])
            ->whereIn('status', ['approved', 'revised'])
            ->latest()
            ->get();

        return view('admin.home', compact(
            'totalProducts',
            'totalCustomers',
            'totalSales',
            'approvedOrders',
            'shippedOrders',
            'deliveredOrders',
            'paidOrders',
            'purchaseOrders'
        ));
    }

    public function markAsPaid($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->status = 'paid';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate menjadi sudah dibayar.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update qty, bonus, and discount for a revised PO's items.
     */
    public function updateRevisedPO(Request $request, $id)
    {
        $request->validate([
            'items'             => 'required|array',
            'items.*.id'        => 'required|exists:purchase_order_details,id_po_detail',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.bonus_qty' => 'nullable|integer|min:0',
            'items.*.discount'  => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $po = PurchaseOrders::with('details')->findOrFail($id);

            if ($po->status !== 'revised') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya PO dengan status "revised" yang dapat diedit.'
                ], 422);
            }

            $subtotal      = 0;
            $totalDiscount = 0;

            foreach ($request->items as $itemData) {
                $detail          = PurcaheOrderDetail::findOrFail($itemData['id']);
                $qty             = $itemData['qty'];
                $bonusQty        = $itemData['bonus_qty'] ?? 0;
                $discountPerItem = $itemData['discount'] ?? 0;
                $priceAtTime     = $detail->price_at_time;
                $itemTotal       = ($priceAtTime - $discountPerItem) * $qty;

                $detail->update([
                    'qty'              => $qty,
                    'bonus_qty'        => $bonusQty,
                    'discount'         => $discountPerItem,
                    'total_item_price' => $itemTotal,
                ]);

                $subtotal      += $priceAtTime * $qty;
                $totalDiscount += $discountPerItem * $qty;
            }

            $tax        = ($subtotal - $totalDiscount) * 0.11;
            $grandTotal = ($subtotal - $totalDiscount) + $tax;

            $po->update([
                'subtotal'       => $subtotal,
                'discount_total' => $totalDiscount,
                'tax_amount'     => $tax,
                'grand_total'    => $grandTotal,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detail PO berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui PO: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resubmit a revised PO back to the director for re-approval.
     */
    public function resubmitPO($id)
    {
        try {
            $po = PurchaseOrders::findOrFail($id);

            if ($po->status !== 'revised') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya PO dengan status "revised" yang dapat dikirim ulang.'
                ], 422);
            }

            $po->update(['status' => 'pending_director']);

            return response()->json([
                'success' => true,
                'message' => 'PO berhasil dikirim ulang ke Direktur untuk persetujuan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim ulang PO: ' . $e->getMessage()
            ], 500);
        }
    }
}
