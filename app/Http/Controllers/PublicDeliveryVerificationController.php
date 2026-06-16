<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class PublicDeliveryVerificationController extends Controller
{
    public function verifyOffice($barcode_key)
    {
        $delivery = Delivery::where('barcode_office', $barcode_key)->first();

        if ($delivery) {
            return view('verification.delivery', [
                'status' => 'valid',
                'type' => 'office',
                'delivery' => $delivery
            ]);
        } else {
            return view('verification.delivery', [
                'status' => 'invalid',
                'type' => 'office'
            ]);
        }
    }

    public function verifyGudang($barcode_key)
    {
        $delivery = Delivery::where('barcode_gudang', $barcode_key)->first();

        if ($delivery) {
            return view('verification.delivery', [
                'status' => 'valid',
                'type' => 'gudang',
                'delivery' => $delivery
            ]);
        } else {
            return view('verification.delivery', [
                'status' => 'invalid',
                'type' => 'gudang'
            ]);
        }
    }
}