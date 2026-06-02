<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = \App\Models\Customer::with(['user', 'zone'])
            ->when($search, function ($query, $search) {
                return $query->where('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', '%' . $search . '%');
                    });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers', compact('customers', 'search'));
    }
    public function getCustomerJson($id)
    {
        $customer = \App\Models\Customer::with(['user', 'zone'])->findOrFail($id);
        return response()->json($customer);
    }
}
