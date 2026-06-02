<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KoordinatorSalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $koordinators = \App\Models\User::where('role', 'koordinator_sales')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.koordinator-sales', compact('koordinators', 'search'));
    }

    public function create()
    {
        return view('admin.add-koorsales');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['required'],
        ]);

        $validated['role'] = 'koordinator_sales';
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        
        \App\Models\User::create($validated);
        
        return redirect()->route('admin.koordinator.index')->with('success', 'Koordinator Sales berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email,' . $id . ',id_user'],
            'password' => ['nullable'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('admin.koordinator.index')->with('success', 'Data Koordinator Sales berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Koordinator Sales berhasil dihapus.']);
    }
}
