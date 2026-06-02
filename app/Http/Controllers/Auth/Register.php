<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Controller
{
    public function showRegistrationForm()
    {
        $zones = Zone::all();
        return view('auth.register', compact('zones'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'customer_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'zone_id' => ['required', 'exists:zones,id_zone'],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pelanggan',
            ]);

            Customer::create([
                'user_id' => $user->id_user,
                'zone_id' => $request->zone_id,
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            DB::commit();

            Auth::login($user);

            return redirect()->route('customers.home');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal melakukan registrasi: ' . $e->getMessage()])->withInput();
        }
    }
}
