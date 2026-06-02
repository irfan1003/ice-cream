<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomingOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')
            ->where('status', 'pending_sales')
            ->where('sales_id', auth()->id())
            ->latest()
            ->get();

        return view('sales.incoming-orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'orderDetail.product'])
            ->where('id_order', $id)
            ->where('sales_id', auth()->id())
            ->firstOrFail();

        return view('sales.show-incoming-order', compact('order'));
    }

    public function updateItem(Request $request, $orderDetailId)
    {
        $request->validate([
            'bonus_qty' => 'required|integer|min:0',
            'discount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $detail = OrderDetail::with('product')->findOrFail($orderDetailId);
            $order = Order::findOrFail($detail->order_id);

            if ($order->sales_id != auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Logic: Discount is per unit. Base price is product's purchase_price.
            $basePrice = $detail->product->purchase_price;
            $discountPerUnit = $request->discount;
            
            $newPriceAtTime = $basePrice - $discountPerUnit;
            $newTotalItemPrice = $newPriceAtTime * $detail->qty;

            // Update detail item
            $detail->bonus_qty = $request->bonus_qty;
            $detail->discount = $discountPerUnit;
            $detail->price_at_time = $newPriceAtTime;
            $detail->total_item_price = $newTotalItemPrice;
            $detail->save();

            // Recalculate Order totals
            $allDetails = OrderDetail::where('order_id', $order->id_order)->get();
            
            // Subtotal is the sum of (price * qty) before discount? 
            // Or sum of (total_item_price)? 
            // Usually subtotal is before tax. Let's follow the previous PO logic:
            // Subtotal = sum(total_item_price)
            $newSubtotal = $allDetails->sum('total_item_price');
            $newDiscountTotal = $allDetails->sum(function($d) {
                return $d->discount * $d->qty;
            });

            // Tax is 11% of the new subtotal
            $taxAmount = $newSubtotal * 0.11;
            $newGrandTotal = $newSubtotal + $taxAmount;

            $order->subtotal = $newSubtotal;
            $order->discount_total = $newDiscountTotal;
            $order->tax_amount = $taxAmount;
            $order->grand_total = $newGrandTotal;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detail pesanan berhasil diperbarui!',
                'total_item_price' => $detail->total_item_price,
                'order_totals' => [
                    'subtotal' => $order->subtotal,
                    'discount_total' => $order->discount_total,
                    'tax_amount' => $order->tax_amount,
                    'grand_total' => $order->grand_total,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function verify($id)
    {
        try {
            $order = Order::where('id_order', $id)
                ->where('sales_id', auth()->id())
                ->firstOrFail();

            $order->status = 'pending_coordinator';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diverifikasi dan diteruskan ke Koordinator!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $order = Order::where('id_order', $id)
                ->where('sales_id', auth()->id())
                ->with('orderDetail.product')
                ->firstOrFail();

            // Restore Stock
            foreach ($order->orderDetail as $detail) {
                if ($detail->product) {
                    $detail->product->increment('current_stock', $detail->qty);
                }
            }

            // Update StockLog reference status
            StockLog::where('reference', $order->order_number)->update([
                'verification_status' => 'tidak_sesuai',
                'final_status' => 'completed',
            ]);

            $order->status = 'rejected';
            $order->rejected_note = $request->rejected_note;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil ditolak dan stok telah dikembalikan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
