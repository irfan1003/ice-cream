<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class SalesProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by Product Name
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // Filter by Brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by Stock Status
        if ($request->filled('status')) {
            if ($request->status === 'ready') {
                $query->where('current_stock', '>', 0);
            } elseif ($request->status === 'habis') {
                $query->where('current_stock', '<=', 0);
            }
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $brands = Product::select('brand')->distinct()->pluck('brand');

        return view('sales.products', compact('products', 'brands'));
    }
}
