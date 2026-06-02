<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $brandFilter = $request->input('brand');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($brandFilter, function ($query, $brandFilter) {
                return $query->where('brand', $brandFilter);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $brands = Product::select('brand')->distinct()->whereNotNull('brand')->pluck('brand');

        return view('direktur.products', compact('products', 'search', 'brands', 'brandFilter'));
    }
}
