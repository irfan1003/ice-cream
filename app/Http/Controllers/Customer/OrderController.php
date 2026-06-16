<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $sales = User::where('role', 'sales')->get();
        return view('customers.orders', compact('products', 'sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|exists:users,id_user',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id_product',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $customer = Customer::where('user_id', auth()->user()->id_user)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Data profil pelanggan tidak ditemukan. Silakan hubungi admin.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderDetails = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['id']);

                // Validasi Stock
                if ($product->current_stock < $item['qty']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok produk {$product->product_name} tidak mencukupi."
                    ], 422);
                }

                $itemTotal = $product->purchase_price * $item['qty'];
                $subtotal += $itemTotal;

                $orderDetails[] = [
                    'product_id' => $product->id_product,
                    'qty' => $item['qty'],
                    'price_at_time' => $product->purchase_price,
                    'total_item_price' => $itemTotal,
                    'discount' => 0,
                    'bonus_qty' => 0,
                ];

                // Update Stock
                $product->current_stock -= $item['qty'];
                $product->save();
            }

            $taxAmount = $subtotal * 0.11;
            $grandTotal = $subtotal + $taxAmount;

            $order = Order::create([
                'customer_id' => $customer->id_customer,
                'sales_id' => $request->sales_id,
                'created_by' => auth()->id(),
                'order_date' => now(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_total' => 0,
                'grand_total' => $grandTotal,
                'status' => 'pending_sales',
            ]);

            $order->order_number = 'DOSO' . str_pad($order->id_order, 8, '0', STR_PAD_LEFT);
            $order->save();

            foreach ($orderDetails as $detail) {
                $detail['order_id'] = $order->id_order;
                OrderDetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order_number' => $order->order_number
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
