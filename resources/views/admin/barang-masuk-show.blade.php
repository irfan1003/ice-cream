@extends('layouts.app')


@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.barang-masuk.index') }}"
                class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 mb-2 text-sm font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Detail PO Supplier</h1>
            <p class="text-sm text-slate-500">Finalisasi PO Supplier dan sinkronisasi stok</p>
        </div>
    </div>

    @if (session('success'))
        <div
            class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl flex items-center gap-3 animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div
            class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-xl flex items-center gap-3 animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- PO Info Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi PO Supplier</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-slate-500 font-medium">Nomor PO</span>
                <p class="text-slate-900 font-bold">{{ $poSupplier->po_number }}</p>
            </div>
            <div>
                <span class="text-slate-500 font-medium">Supplier</span>
                <p class="text-slate-900 font-bold">{{ $poSupplier->supplier->supplier_name ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-500 font-medium">Tanggal PO</span>
                <p class="text-slate-900 font-bold">{{ \Carbon\Carbon::parse($poSupplier->po_date)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 text-slate-400 text-[10px] uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4 font-bold w-12"></th>
                        <th class="px-6 py-4 font-bold">Produk</th>
                        <th class="px-6 py-4 font-bold text-center">Qty Dipesan</th>
                        <th class="px-6 py-4 font-bold text-center">Qty Diterima</th>
                        <th class="px-6 py-4 font-bold text-center">Sesuai</th>
                        <th class="px-6 py-4 font-bold">Catatan Perbedaan Gudang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @foreach ($poSupplier->details as $detail)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-slate-400 text-xs font-bold">#{{ $loop->iteration }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $detail->product->product_name ?? '-' }}</div>
                                @if ($detail->product && $detail->product->brand)
                                    <span
                                        class="text-xs text-slate-400 font-medium uppercase">{{ $detail->product->brand }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-900">
                                {{ $detail->qty }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-emerald-600">
                                {{ $detail->qty_received ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($detail->is_compatible)
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">Ya</span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-600 border border-amber-100">Tidak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $detail->reject_reason ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    @php
        $hasIncompatible = $poSupplier->details->contains(fn($item) => !$item->is_compatible);
    @endphp

    <div class="flex flex-wrap items-center justify-end gap-3">
        @if ($hasIncompatible)
            <form action="{{ route('admin.barang-masuk.send-back', $poSupplier->id_po_supplier) }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-amber-600 text-white px-6 py-3 rounded-lg text-sm font-bold hover:bg-amber-700 transition-colors shadow-lg shadow-amber-600/20 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h10a4 4 0 100-8H3v8z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h10a4 4 0 110 8H3v-8z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12H17m0 0l-2-2m2 2l-2 2" />
                    </svg>
                    Kirim Balik ke Gudang
                </button>
            </form>

            <form action="{{ route('admin.barang-masuk.force-finalize', $poSupplier->id_po_supplier) }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Potong Nota (Selesai Apa Adanya)
                </button>
            </form>
        @else
            <form action="{{ route('admin.barang-masuk.finalize', $poSupplier->id_po_supplier) }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-emerald-600 text-white px-6 py-3 rounded-lg text-sm font-bold hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Verifikasi Akhir & Sinkronisasi Stok
                </button>
            </form>
        @endif
    </div>
@endsection
