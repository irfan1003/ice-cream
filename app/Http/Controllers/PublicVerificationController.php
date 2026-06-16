<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PublicVerificationController extends Controller
{
    public function verify($barcode_key)
    {
        $order = Order::where('barcode_key', $barcode_key)->first();

        if ($order) {
            return view('verification.result', [
                'status' => 'valid',
                'order' => $order
            ]);
        } else {
            return view('verification.result', [
                'status' => 'invalid'
            ]);
        }
    }
}