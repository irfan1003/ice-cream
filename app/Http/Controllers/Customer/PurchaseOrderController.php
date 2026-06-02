<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrders;
use App\Models\PurcaheOrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $sales = User::where('role', 'sales')->get();
        return view('customers.purchase-orders', compact('products', 'sales'));
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
            $poDetails = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['id']);

                $itemTotal = $product->purchase_price * $item['qty'];
                $subtotal += $itemTotal;

                $poDetails[] = [
                    'product_id' => $product->id_product,
                    'qty' => $item['qty'],
                    'price_at_time' => $product->purchase_price,
                    'total_item_price' => $itemTotal,
                    'discount' => 0,
                    'bonus_qty' => 0,
                ];

                // Note: Stock is NOT decreased for Purchase Orders
            }

            $taxAmount = $subtotal * 0.11;
            $grandTotal = $subtotal + $taxAmount;

            $po = PurchaseOrders::create([
                'customer_id' => $customer->id_customer,
                'sales_id' => $request->sales_id,
                'po_date' => now(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_total' => 0,
                'grand_total' => $grandTotal,
                'status' => 'pending_sales',
            ]);

            $po->po_number = 'PO' . str_pad($po->id_po, 8, '0', STR_PAD_LEFT);
            $po->save();
            foreach ($poDetails as $detail) {
                $detail['po_id'] = $po->id_po;
                PurcaheOrderDetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order berhasil dibuat!',
                'po_number' => $po->po_number
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
