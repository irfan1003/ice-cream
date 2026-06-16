@extends('layouts.app')


@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Verifikasi Barang Masuk</h1>
        <p class="text-sm text-slate-500">Review dan verifikasi kesesuaian stok yang baru datang dari supplier</p>
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
                        <th class="px-6 py-4 font-bold">Status</th>
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
                                    $statusClass = [
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'received' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusClass[$po->status] ?? 'bg-slate-50 text-slate-600 border-slate-100' }}">
                                    {{ ucwords(str_replace('_', ' ', $po->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($po->status == 'pending')
                                    <form action="{{ route('gudang.verifikasi.terima', $po->id_po_supplier) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Terima Barang
                                        </button>
                                    </form>
                                @elseif($po->status == 'received')
                                    <a href="{{ route('gudang.verifikasi.show', $po->id_po_supplier) }}"
                                        class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Mulai Verifikasi
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
