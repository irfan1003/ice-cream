@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header & Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Rekap Penjualan</h1>
                <p class="text-sm text-slate-500 mt-1">Laporan pesanan yang telah selesai dan terkirim.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.rekap-penjualan.export') }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="period" class="block w-full pl-3 pr-10 py-2 text-sm border-slate-200 focus:outline-none focus:ring-brand-pink focus:border-brand-pink rounded-xl">
                        <option value="week">1 Minggu Terakhir</option>
                        <option value="month">1 Bulan Terakhir</option>
                    </select>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Ekspor Excel
                    </button>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pesanan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Sales</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Qty</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Harga Item</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($details as $detail)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $detail->order->order_number }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $detail->order->delivery->spb_number ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-700">{{ $detail->order->customer->customer_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $detail->order->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $detail->order->sales->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $detail->product->product_name }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $detail->product->unit ?? 'PCS' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">
                                    {{ $detail->qty }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900">
                                    Rp {{ number_format($detail->total_item_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('admin.incorders.preview', $detail->order->id_order) }}" 
                                                class="p-2 text-slate-400 hover:text-brand-pink-dark hover:bg-pink-50 rounded-lg transition-all"
                                                title="Detail Pesanan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500 italic">
                                    Belum ada data penjualan yang selesai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($details->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $details->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection