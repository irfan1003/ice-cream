<?php

namespace App\Http\Controllers\KoorSales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class KoorProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $brands = Product::distinct()->pluck('brand');

        return view('koordinator-sales.products', compact('products', 'brands'));
    }
}
