<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrders;
use App\Models\PurcaheOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesPOController extends Controller
{
    public function index()
    {
        $products = Product::all(); // All products, even with 0 stock
        $customers = Customer::orderBy('customer_name', 'asc')->get();

        return view('sales.purchase-orders', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id_customer',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id_product',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.bonus_qty' => 'nullable|integer|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $po = new PurchaseOrders();
            $po->customer_id = $request->customer_id;
            $po->sales_id = auth()->id();
            $po->created_by = auth()->id();
            $po->po_date = now();
            $po->status = 'pending_coordinator';
            $po->subtotal = 0;
            $po->tax_amount = 0;
            $po->discount_total = 0;
            $po->grand_total = 0;
            $po->save();

            $subtotal = 0;
            $totalDiscount = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                $qty = $item['qty'];
                $bonusQty = $item['bonus_qty'] ?? 0;
                $discountPerItem = $item['discount'] ?? 0;

                $priceAtTime = $product->purchase_price;
                $itemTotal = ($priceAtTime - $discountPerItem) * $qty;

                $detail = new PurcaheOrderDetail();
                $detail->po_id = $po->id_po;
                $detail->product_id = $product->id_product;
                $detail->qty = $qty;
                $detail->bonus_qty = $bonusQty;
                $detail->discount = $discountPerItem;
                $detail->price_at_time = $priceAtTime;
                $detail->total_item_price = $itemTotal;
                $detail->save();

                $subtotal += $priceAtTime * $qty;
                $totalDiscount += $discountPerItem * $qty;
            }

            $tax = ($subtotal - $totalDiscount) * 0.11;
            $grandTotal = ($subtotal - $totalDiscount) + $tax;

            $po->po_number = 'PO' . str_pad($po->id_po, 8, '0', STR_PAD_LEFT);

            $po->update([
                'po_number' => $po->po_number,
                'subtotal' => $subtotal,
                'discount_total' => $totalDiscount,
                'tax_amount' => $tax,
                'grand_total' => $grandTotal
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order berhasil dibuat.',
                'order_number' => $po->po_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat Purchase Order: ' . $e->getMessage()
            ], 500);
        }
    }
}
