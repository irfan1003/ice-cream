<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::with(['user', 'zone'])
            ->when($search, function ($query, $search) {
                return $query->where('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', '%' . $search . '%');
                    });
            })
            ->paginate(10)
            ->withQueryString();

        $zones = Zone::all();

        return view('admin.customers', compact('customers', 'search', 'zones'));
    }

    public function getCustomerJson($id)
    {
        $customer = Customer::with(['user', 'zone'])->findOrFail($id);
        return response()->json($customer);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'zone_id' => 'required|exists:zones,id_zone',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->customer_name,
                'email' => $request->email,
                'password' => $request->phone, // default password is phone number
                'role' => 'pelanggan',
            ]);

            Customer::create([
                'user_id' => $user->id_user,
                'zone_id' => $request->zone_id,
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
        });

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $user = $customer->user;

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id_user : 'NULL') . ',id_user',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'zone_id' => 'required|exists:zones,id_zone',
        ]);

        DB::transaction(function () use ($request, $customer, $user) {
            if ($user) {
                $user->update([
                    'name' => $request->customer_name,
                    'email' => $request->email,
                ]);
            }

            $customer->update([
                'zone_id' => $request->zone_id,
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
        });

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $user = $customer->user;

        if ($user) {
            $user->delete(); // Cascades delete to customer
        } else {
            $customer->delete();
        }

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}

