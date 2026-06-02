<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by name or brand
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by Brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        // Filter by Stock Status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'ready') {
                $query->where('current_stock', '>', 0);
            } elseif ($request->status == 'habis') {
                $query->where('current_stock', '<=', 0);
            }
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $brands = Product::distinct()->pluck('brand');

        return view('customers.products', compact('products', 'brands'));
    }
}
