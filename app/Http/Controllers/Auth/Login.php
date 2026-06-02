<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Login extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $role = Auth::user()->role;
            $request->session()->regenerate();

            if ($role == 'admin_kantor') {
                return redirect()->intended('/')->with('success', 'Welcome back!');
            }

            if ($role == 'admin_gudang') {
                return redirect()->route('gudang.home')->with('success', 'Welcome back!');
            }

            if ($role == 'pelanggan') {
                return redirect()->route('customers.home')->with('success', 'Welcome back!');
            }

            if ($role == 'sales') {
                return redirect()->route('sales.home')->with('success', 'Welcome back!');
            }

            if ($role == 'koordinator_sales') {
                return redirect()->route('koor.sales.home')->with('success', 'Welcome back!');
            }

            if ($role == 'direktur') {
                return redirect()->route('direktur.home')->with('success', 'Welcome back!');
            }

            if ($role == 'driver') {
                return redirect()->route('driver.home')->with('success', 'Welcome back!');
            }
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }
}
