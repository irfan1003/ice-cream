<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $sales = User::with('customers')
            ->where('role', 'sales')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->paginate(8)
            ->withQueryString();

        return view('admin.sales', compact('sales', 'search'));
    }

    public function create()
    {
        return view('admin.add-sales');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['required'],
        ]);

        $validated['role'] = 'sales';
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        return redirect()->route('admin.sales.index')->with('success', 'Sales berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email,' . $id . ',id_user'],
            'password' => ['nullable'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('admin.sales.index')->with('success', 'Data sales berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Sales berhasil dihapus.']);
    }
}
