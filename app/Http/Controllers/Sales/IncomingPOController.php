<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PurchaseOrders;
use App\Models\PurcaheOrderDetail;
use Illuminate\Support\Facades\DB;

class IncomingPOController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrders::with('customer')
            ->where('status', 'pending_sales')
            ->orderBy('po_date', 'desc')
            ->get();

        return view('sales.incoming-po', compact('purchaseOrders'));
    }

    public function show($id)
    {
        $po = PurchaseOrders::with(['customer', 'details.product'])->findOrFail($id);
        return view('sales.show-incoming-po', compact('po'));
    }

    public function updateItem(Request $request, $id)
    {
        // Validate inputs, allowing 0 for removing discount/bonus
        $request->validate([
            'bonus_qty' => 'required|integer|min:0',
            'discount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $detail = PurcaheOrderDetail::findOrFail($id);
            $po = PurchaseOrders::findOrFail($detail->po_id);
            $product = $detail->product;

            // Logic: Discount is per unit. 
            // If discount is 0, price_at_time returns to the original product purchase_price.
            $basePrice = $product->purchase_price;
            $discountPerUnit = $request->discount;
            
            $newPriceAtTime = $basePrice - $discountPerUnit;
            $newTotalItemPrice = $newPriceAtTime * $detail->qty;

            $detail->update([
                'bonus_qty' => $request->bonus_qty,
                'discount' => $discountPerUnit,
                'price_at_time' => $newPriceAtTime,
                'total_item_price' => $newTotalItemPrice,
            ]);

            // Recalculate PO Totals
            $allDetails = $po->details;
            $newSubtotal = $allDetails->sum('total_item_price');
            $newDiscountTotal = $allDetails->sum(function($d) {
                return $d->discount * $d->qty;
            });
            
            // Tax is usually 11%
            $taxAmount = $newSubtotal * 0.11;
            $grandTotal = $newSubtotal + $taxAmount;

            $po->update([
                'subtotal' => $newSubtotal,
                'discount_total' => $newDiscountTotal,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully',
                'new_price' => $newPriceAtTime,
                'new_item_total' => $newTotalItemPrice,
                'new_subtotal' => $newSubtotal,
                'new_tax' => $taxAmount,
                'new_grand_total' => $grandTotal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function verify($id)
    {
        $po = PurchaseOrders::findOrFail($id);
        $po->update(['status' => 'pending_coordinator']);

        return redirect()->route('sales.incomingpo.index')->with('success', 'Purchase Order verified and forwarded to coordinator.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['rejected_note' => 'required|string']);

        $po = PurchaseOrders::findOrFail($id);
        // Using 'rejected' status as it's more logical for a "Tolak" action
        $po->update([
            'status' => 'rejected',
            'rejected_note' => $request->rejected_note
        ]);

        return redirect()->route('sales.incomingpo.index')->with('success', 'Purchase Order rejected.');
    }
}
