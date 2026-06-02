<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        $products = $query->latest()->paginate(8)->withQueryString();
        $brands = ['Aice', 'Campina', 'Korudo', 'Gracia'];

        return view('admin.products', compact('products', 'brands'));
    }

    public function create()
    {
        return view('admin.add-product');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => ['required', 'in:Aice,Campina,Korudo,Gracia'],
            'product_name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:2048'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50']
        ]);

        $validated['current_stock'] = 0;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit-product', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'brand' => ['required', 'in:Aice,Campina,Korudo,Gracia'],
            'product_name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50']
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }

    public function productJson()
    {
        $products = Product::get(['id_product', 'product_name']);
        return response()->json($products);
    }
}
