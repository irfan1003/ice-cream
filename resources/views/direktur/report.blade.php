@extends('layouts.app')

@section('content')
    <div x-data="{
        activeTab: 'stok',
        startDate: '{{ $startDate->format('Y-m-d') }}',
        endDate: '{{ $endDate->format('Y-m-d') }}'
    }" class="space-y-6">

        {{-- ===================== HEADER ===================== --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Laporan</h1>
                <p class="text-sm text-slate-500 mt-1">Analisis stok, pengiriman, pemasaran, dan pelanggan.</p>
            </div>
        </div>

        {{-- ===================== FILTER GLOBAL ===================== --}}
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <form id="filterForm" action="{{ route('direktur.report.index') }}" method="GET"
                class="flex flex-wrap items-end gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="startDate" x-model="startDate"
                        value="{{ $startDate->format('Y-m-d') }}"
                        class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-brand-blue">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="endDate" x-model="endDate"
                        value="{{ $endDate->format('Y-m-d') }}"
                        class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-brand-blue">
                </div>
                <input type="hidden" name="tab" x-bind:value="activeTab">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-700 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filter Data
                </button>
                <a href="{{ route('direktur.report.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel (Stok)
                </a>
            </form>
        </div>

        {{-- ===================== TABS NAV ===================== --}}
        <div class="flex border-b border-slate-200 gap-1 overflow-x-auto">
            @php
                $tabs = [
                    ['key' => 'stok', 'label' => 'Laporan Stok', 'color' => 'indigo'],
                    ['key' => 'pengiriman', 'label' => 'Laporan Pengiriman', 'color' => 'sky'],
                    ['key' => 'pemasaran', 'label' => 'Laporan Pemasaran', 'color' => 'pink'],
                    ['key' => 'pelanggan', 'label' => 'Laporan Pelanggan', 'color' => 'amber'],
                ];
            @endphp
            @foreach ($tabs as $tab)
                <button @click="activeTab = '{{ $tab['key'] }}'"
                    :class="activeTab === '{{ $tab['key'] }}'
                        ?
                        'border-slate-900 text-slate-900 bg-white' :
                        'border-transparent text-slate-400 hover:text-slate-600'"
                    class="px-5 py-3 text-sm font-bold border-b-2 transition-all duration-200 whitespace-nowrap -mb-px">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        {{-- ===================== TAB 1: LAPORAN STOK ===================== --}}
        <div x-show="activeTab === 'stok'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Tabel Mutasi Stok</h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Periode: {{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }}
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600">
                        {{ $products->count() }} Produk
                    </span>
                </div>

                {{-- Tabel Matriks Mutasi Stok --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            {{-- Header Row 1: Tanggal-tanggal --}}
                            <tr class="bg-emerald-700 text-white">
                                <th class="px-4 py-3 font-bold text-center align-middle border border-emerald-600 min-w-[160px]"
                                    rowspan="2">NAMA ITEM</th>
                                <th class="px-4 py-3 font-bold text-center align-middle border border-emerald-600 min-w-[70px]"
                                    rowspan="2">STOK AWAL</th>
                                @foreach ($dates as $date)
                                    <th class="px-2 py-2 font-bold text-center border border-emerald-600 min-w-[120px]"
                                        colspan="3">
                                        {{ $date->format('d/m/Y') }}
                                    </th>
                                @endforeach
                                <th class="px-3 py-3 font-bold text-center align-middle border border-emerald-600 min-w-[80px]"
                                    rowspan="2">SUB<br>TOTAL ORD</th>
                                <th class="px-3 py-3 font-bold text-center align-middle border border-emerald-600 min-w-[80px]"
                                    rowspan="2">SUB<br>TOTAL BNS</th>
                            </tr>
                            {{-- Header Row 2: Sub-kolom ORD / BNS / DISC --}}
                            <tr class="bg-emerald-600 text-white">
                                @foreach ($dates as $date)
                                    <th class="px-2 py-2 font-bold text-center border border-emerald-500">ORD</th>
                                    <th class="px-2 py-2 font-bold text-center border border-emerald-500">BNS</th>
                                    <th class="px-2 py-2 font-bold text-center border border-emerald-500">DISC</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($products as $product)
                                @php
                                    $pid = $product->id_product;
                                    $stokAwal = ($product->current_stock ?? 0) + ($soldAfterStart[$pid] ?? 0);
                                    $totalOrd = 0;
                                    $totalBns = 0;
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td
                                        class="px-4 py-2.5 font-semibold text-slate-900 border-r border-slate-100 whitespace-nowrap">
                                        {{ $product->product_name }}
                                        @if ($product->brand)
                                            <div class="text-[10px] text-slate-400 font-normal">{{ $product->brand }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5 text-center font-bold text-slate-700 border-r border-slate-100">
                                        {{ $stokAwal }}
                                    </td>
                                    @foreach ($dates as $date)
                                        @php
                                            $dateKey = $date->format('Y-m-d');
                                            $ord = $mutationData[$pid][$dateKey]['ord'] ?? 0;
                                            $bns = $mutationData[$pid][$dateKey]['bns'] ?? 0;
                                            $disc = $mutationData[$pid][$dateKey]['disc'] ?? 0;
                                            $totalOrd += $ord;
                                            $totalBns += $bns;
                                        @endphp
                                        <td
                                            class="px-2 py-2.5 text-center {{ $ord > 0 ? 'font-semibold text-slate-900' : 'text-slate-300' }} border-l border-slate-100">
                                            {{ $ord > 0 ? $ord : '' }}
                                        </td>
                                        <td
                                            class="px-2 py-2.5 text-center {{ $bns > 0 ? 'font-semibold text-emerald-600' : 'text-slate-300' }} border-l border-dashed border-slate-100">
                                            {{ $bns > 0 ? $bns : '' }}
                                        </td>
                                        <td
                                            class="px-2 py-2.5 text-center {{ $disc > 0 ? 'font-semibold text-rose-500' : 'text-slate-300' }} border-l border-dashed border-slate-100">
                                            {{ $disc > 0 ? number_format($disc, 0, ',', '.') : '' }}
                                        </td>
                                    @endforeach
                                    <td
                                        class="px-3 py-2.5 text-center font-bold text-slate-900 border-l border-slate-200 bg-slate-50">
                                        {{ $totalOrd > 0 ? $totalOrd : '-' }}
                                    </td>
                                    <td
                                        class="px-3 py-2.5 text-center font-bold text-emerald-600 border-l border-slate-200 bg-slate-50">
                                        {{ $totalBns > 0 ? $totalBns : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100" class="px-6 py-12 text-center text-slate-400 italic">
                                        Tidak ada data produk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ===================== TAB 2: LAPORAN PENGIRIMAN ===================== --}}
        <div x-show="activeTab === 'pengiriman'" style="display:none" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900">Data Pengiriman</h2>
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600">
                        {{ $deliveries->count() }} Data
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Order
                                </th>
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal
                                </th>
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan
                                </th>
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Driver</th>
                                <th
                                    class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600">
                            @forelse($deliveries as $delivery)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3 font-bold text-slate-900">
                                        {{ $delivery->order->order_number ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">
                                        {{ \Carbon\Carbon::parse($delivery->created_at)->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3 font-medium text-slate-800">
                                        {{ $delivery->order->customer->customer_name ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">
                                        {{ $delivery->driver->name ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @php
                                            $stConfig = [
                                                'shipped' => [
                                                    'label' => 'Dalam Pengiriman',
                                                    'class' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                ],
                                                'delivered' => [
                                                    'label' => 'Terkirim',
                                                    'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                ],
                                            ];
                                            $st = $stConfig[$delivery->delivery_status] ?? [
                                                'label' => $delivery->delivery_status,
                                                'class' => 'bg-slate-50 text-slate-600 border-slate-100',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $st['class'] }}">
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                        Tidak ada data pengiriman pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ===================== TAB 3: LAPORAN PEMASARAN ===================== --}}
        <div x-show="activeTab === 'pemasaran'" style="display:none"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900">Performa Sales & Omzet</h2>
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600">
                        {{ count($salesReports) }} Sales
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Sales
                                </th>
                                <th
                                    class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">
                                    Total Pesanan</th>
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">
                                    Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600">
                            @forelse($salesReports as $sales)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3">
                                        <div class="font-bold text-slate-900">{{ $sales->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $sales->email }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-center font-semibold text-slate-700">
                                        {{ $sales->total_orders }} Pesanan
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-slate-900">
                                        Rp {{ number_format($sales->total_revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">
                                        Tidak ada data pemasaran pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (count($salesReports) > 0)
                            <tfoot class="border-t-2 border-slate-200 bg-slate-50">
                                <tr class="font-bold text-slate-900">
                                    <td class="px-5 py-3 text-xs uppercase tracking-wider">TOTAL</td>
                                    <td class="px-5 py-3 text-center">{{ collect($salesReports)->sum('total_orders') }}
                                        Pesanan</td>
                                    <td class="px-5 py-3 text-right text-brand-pink-dark">
                                        Rp {{ number_format(collect($salesReports)->sum('total_revenue'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- ===================== TAB 4: LAPORAN PELANGGAN ===================== --}}
        <div x-show="activeTab === 'pelanggan'" style="display:none"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900">Data Pembelian Pelanggan</h2>
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600">
                        {{ $customers->count() }} Pelanggan Aktif
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan
                                </th>
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</th>
                                <th
                                    class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">
                                    Jumlah Order</th>
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">
                                    Total Pembelian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3">
                                        <div class="font-bold text-slate-900">{{ $customer->customer_name }}</div>
                                        <div class="text-xs text-slate-400">{{ $customer->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-slate-500">
                                        {{ $customer->address ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-center font-semibold text-slate-700">
                                        {{ $customer->order_count }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-slate-900">
                                        Rp {{ number_format($customer->total_spending, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                        Tidak ada data pelanggan yang bertransaksi pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Sinkronisasi tab aktif dari URL param (agar setelah filter, tab tetap sama)
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            if (activeTab) {
                // Alpine.js sudah handle via x-data, tapi kita set ulang via event
                const el = document.querySelector('[x-data]');
                if (el && el._x_dataStack) {
                    el._x_dataStack[0].activeTab = activeTab;
                }
            }
        });
    </script>
@endpush
