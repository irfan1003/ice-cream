@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Monitoring Mutasi Stok</h1>
        <p class="text-slate-500 mt-1">Laporan pergerakan stok barang secara real-time.</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-white border border-slate-100 text-slate-400 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h2 class="text-base font-bold text-slate-900">Data Riwayat Stok</h2>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[11px] font-bold text-slate-600 shadow-sm">
                    {{ $logs->total() }} Data
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 font-bold">Tanggal & Waktu</th>
                        <th class="px-6 py-4 font-bold">Produk</th>
                        <th class="px-6 py-4 font-bold text-center">Tipe</th>
                        <th class="px-6 py-4 font-bold text-center">Jumlah</th>
                        <th class="px-6 py-4 font-bold">Eksekutor</th>
                        <th class="px-6 py-4 font-bold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @foreach ($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">{{ $log->created_at->format('d/m/Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $log->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $log->product->product_name ?? '-' }}</div>
                                @if ($log->product?->brand)
                                    <span
                                        class="text-xs text-slate-400 font-medium uppercase">{{ $log->product->brand }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $log->type === 'in' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                    {{ $log->type === 'in' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 text-center font-bold {{ $log->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">{{ $log->user->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ $log->reference_note ?? '-' }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection
