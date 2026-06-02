<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $products = Product::where('current_stock', '>', 0)->get();
        $customers = Customer::orderBy('customer_name', 'asc')->get();

        return view('sales.orders', compact('products', 'customers'));
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
            \DB::beginTransaction();
            $order = new \App\Models\Order();
            $order->customer_id = $request->customer_id;
            $order->sales_id = auth()->id();
            $order->created_by = auth()->id();
            $order->order_date = now();
            $order->status = 'pending_coordinator';
            $order->subtotal = 0;
            $order->tax_amount = 0;
            $order->discount_total = 0;
            $order->grand_total = 0;
            $order->save();

            $subtotal = 0;
            $totalDiscount = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                $qty = $item['qty'];
                $bonusQty = $item['bonus_qty'] ?? 0;
                $discountPerItem = $item['discount'] ?? 0;

                $priceAtTime = $product->purchase_price;
                $itemTotal = ($priceAtTime - $discountPerItem) * $qty;

                $detail = new \App\Models\OrderDetail();
                $detail->order_id = $order->id_order;
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
            $order->order_number = 'DOSO' . str_pad($order->id_order, 8, '0', STR_PAD_LEFT);
            $order->update([
                'subtotal' => $subtotal,
                'discount_total' => $totalDiscount,
                'tax_amount' => $tax,
                'grand_total' => $grandTotal
            ]);

            foreach ($request->items as $item) {
                StockLog::create([
                    'product_id' => $item['id'],
                    'user_id' => auth()->user()->id_user,
                    'verification_status' => 'pending',
                    'quantity' => $item['qty'],
                    'reference' => $order->order_number,
                    'type' => 'out',
                    'warehouse_note' => "Booking Order #{$order->order_number}",
                    'final_status' => 'draft',
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat.',
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
