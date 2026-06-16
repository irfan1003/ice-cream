@extends('layouts.app')


@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Verifikasi Akhir PO Supplier</h1>
        <p class="text-sm text-slate-500">Finalisasi PO Supplier dan sinkronisasi stok</p>
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

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/50 text-slate-400 text-[10px] uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4 font-bold">Nomor PO</th>
                        <th class="px-6 py-4 font-bold">Supplier</th>
                        <th class="px-6 py-4 font-bold">Tanggal PO</th>
                        <th class="px-6 py-4 font-bold">Status Verifikasi Gudang</th>
                        <th class="px-6 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @foreach ($poSuppliers as $po)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900">{{ $po->po_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $po->supplier->supplier_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($po->po_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $hasIncompatible = $po->details->contains(fn($item) => !$item->is_compatible);
                                @endphp
                                @if ($hasIncompatible)
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-600 border border-amber-100">
                                        Ada Barang Tidak Sesuai
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        Semua Sesuai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.barang-masuk.show', $po->id_po_supplier) }}"
                                    class="inline-flex items-center gap-2 bg-slate-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-700 transition-colors">
                                    Detail & Proses
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
