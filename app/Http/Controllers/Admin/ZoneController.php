<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $zones = Zone::withCount('customer')
            ->when($search, function ($query, $search) {
                return $query->where('zone_name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.zone', compact('zones', 'search'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'zone_name' => 'required|string|max:255|unique:zones,zone_name',
            ], [
                'zone_name.required' => 'Nama wilayah wajib diisi.',
                'zone_name.unique' => 'Nama wilayah sudah digunakan.',
                'zone_name.max' => 'Nama wilayah maksimal 255 karakter.',
            ]);

            Zone::create([
                'zone_name' => $request->zone_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wilayah berhasil ditambahkan.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['zone_name'][0] ?? 'Validasi gagal.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan wilayah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $zone = Zone::findOrFail($id);

            $request->validate([
                'zone_name' => 'required|string|max:255|unique:zones,zone_name,' . $id . ',id_zone',
            ], [
                'zone_name.required' => 'Nama wilayah wajib diisi.',
                'zone_name.unique' => 'Nama wilayah sudah digunakan.',
                'zone_name.max' => 'Nama wilayah maksimal 255 karakter.',
            ]);

            $zone->update([
                'zone_name' => $request->zone_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wilayah berhasil diperbarui.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['zone_name'][0] ?? 'Validasi gagal.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui wilayah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $zone = Zone::findOrFail($id);

            // Check if zone has customers
            if ($zone->customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wilayah ini masih memiliki pelanggan terkait dan tidak bisa dihapus.',
                ], 422);
            }

            $zone->delete();

            return response()->json([
                'success' => true,
                'message' => 'Wilayah berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus wilayah: ' . $e->getMessage(),
            ], 500);
        }
    }
}
