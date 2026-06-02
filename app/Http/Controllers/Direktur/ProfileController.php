<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('direktur.profile', compact('user'));
    }

    public function updateSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);

        try {
            $user = Auth::user();
            $signatureData = $request->signature;

            // Remove header from base64 string
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $imageName = 'signature_' . $user->id_user . '.png';

            // Delete old file if exists
            if ($user->signature && Storage::disk('public')->exists($user->signature)) {
                Storage::disk('public')->delete($user->signature);
            }

            // Save new file
            Storage::disk('public')->put('signatures/' . $imageName, base64_decode($signatureData));

            // Update user record
            $user->update([
                'signature' => 'signatures/' . $imageName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Signature berhasil diperbarui!',
                'signature_url' => asset('storage/signatures/' . $imageName) . '?t=' . time()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui signature: ' . $e->getMessage()
            ], 500);
        }
    }
}
