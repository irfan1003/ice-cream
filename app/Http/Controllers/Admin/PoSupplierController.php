<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierPo;
use App\Models\SupplierPoDetail;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PoSupplierExport; // Akan kita buat nanti

class PoSupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $poSuppliers = SupplierPo::with(['supplier', 'details.product'])
            ->when($search, function ($query, $search) {
                $query->where('po_number', 'like', '%' . $search . '%')
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('supplier_name', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('po_date', 'desc')
            ->paginate($perPage);

        $suppliers = Supplier::all();
        $products = Product::all();

        return view('admin.supplier.po-supplier', compact('poSuppliers', 'suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id_supplier',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id_product',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $poNumber = 'PO-SUP-' . $now->format('Y/m/d-H:i:s');
            $poSupplier = SupplierPo::create([
                'supplier_id' => $validatedData['supplier_id'],
                'created_by' => auth()->id(), // Assuming authenticated user
                'po_number' => $poNumber,
                'po_date' => $now,
                'status' => 'pending', // Default status
            ]);

            foreach ($validatedData['products'] as $productData) {
                $poSupplier->details()->create([
                    'product_id' => $productData['product_id'],
                    'qty' => $productData['qty'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.po-supplier.index')->with('success', 'Purchase Order berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat Purchase Order: ' . $e->getMessage());
        }
    }

    public function show(SupplierPo $po_supplier)
    {
        return response()->json($po_supplier->load(['supplier', 'details.product']));
    }

    public function update(Request $request, SupplierPo $po_supplier)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id_supplier',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id_product',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $po_supplier->update([
                'supplier_id' => $validatedData['supplier_id'],
            ]);

            $po_supplier->details()->delete(); // Hapus detail lama
            foreach ($validatedData['products'] as $productData) {
                $po_supplier->details()->create([
                    'product_id' => $productData['product_id'],
                    'qty' => $productData['qty'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.po-supplier.index')->with('success', 'Purchase Order berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui Purchase Order: ' . $e->getMessage());
        }
    }

    public function destroy(SupplierPo $po_supplier)
    {
        DB::beginTransaction();
        try {
            $po_supplier->details()->delete();
            $po_supplier->delete();
            DB::commit();
            return redirect()->route('admin.po-supplier.index')->with('success', 'Purchase Order berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Purchase Order: ' . $e->getMessage());
        }
    }

    public function export($id)
    {
        try {
            $po = SupplierPo::findOrFail($id);
            return Excel::download(
                new PoSupplierExport($id),
                'Purchase_Order_Supplier_' . str_replace('/', '-', $po->po_number) . '.xlsx'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export excel: ' . $e->getMessage());
        }
    }
}
