@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Direktur Dashboard</h1>
        <p class="text-slate-500 mt-1">Pantau performa operasional dan status pesanan hari ini.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Products Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Produk</p>
                    <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($totalProducts) }}</h3>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Pelanggan</p>
                    <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($totalCustomers) }}</h3>
                </div>
            </div>
        </div>

        <!-- Sales Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tim Sales</p>
                    <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($totalSales) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Trend Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="mb-4">
                <h3 class="text-base font-bold text-slate-900">Tren Penjualan (6 Bulan Terakhir)</h3>
                <p class="text-xs text-slate-500">Omzet bulanan dari pesanan reguler</p>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Sales Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="mb-4">
                <h3 class="text-base font-bold text-slate-900">Performa Tim Sales (Bulan Ini)</h3>
                <p class="text-xs text-slate-500">Top 5 Sales dengan omzet tertinggi</p>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Main Content with Tabs -->
    <div x-data="{ activeTab: 'orders' }" class="space-y-6">
        <!-- Tabs Navigation -->
        <div class="flex border-b border-slate-200">
            <button @click="activeTab = 'orders'" 
                :class="activeTab === 'orders' ? 'border-brand-blue text-brand-blue' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-6 py-3 text-sm font-bold border-b-2 transition-all duration-200">
                Pesanan Reguler
            </button>
            <button @click="activeTab = 'pos'" 
                :class="activeTab === 'pos' ? 'border-brand-pink text-brand-pink' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-6 py-3 text-sm font-bold border-b-2 transition-all duration-200">
                Purchase Order (PO)
            </button>
        </div>

        <!-- Orders Tab Content -->
        <div x-show="activeTab === 'orders'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            @php
                $orderTables = [
                    ['title' => 'Revisi Faktur', 'status' => 'revised', 'color' => 'amber', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['title' => 'Faktur Disetujui', 'status' => 'approved', 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Pesanan Dikirim', 'status' => 'shipped', 'color' => 'indigo', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['title' => 'Pesanan Terkirim', 'status' => 'delivered', 'color' => 'slate', 'icon' => 'M5 13l4 4L19 7'],
                ];
            @endphp

            @foreach ($orderTables as $table)
                @php 
                    $filteredOrders = $orders->filter(function($order) use ($table) {
                        if ($table['status'] === 'shipped') {
                            return $order->delivery && $order->delivery->delivery_status === 'shipped';
                        }
                        if ($table['status'] === 'delivered') {
                            return $order->delivery && $order->delivery->delivery_status === 'delivered';
                        }
                        if ($table['status'] === 'approved') {
                            return $order->status === 'approved' && (!$order->delivery || !in_array($order->delivery->delivery_status, ['shipped', 'delivered']));
                        }
                        return $order->status === $table['status'];
                    });
                @endphp
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-white border border-slate-100 text-slate-400 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $table['icon'] }}" />
                                </svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-900">{{ $table['title'] }}</h2>
                        </div>
                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[11px] font-bold text-slate-600 shadow-sm">
                            {{ $filteredOrders->count() }} Orders
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4 font-bold">Order #</th>
                                    <th class="px-6 py-4 font-bold">Pelanggan</th>
                                    <th class="px-6 py-4 font-bold text-right">Total</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                                @forelse ($filteredOrders as $order)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-900">#{{ $order->order_number }}</span>
                                            <div class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-slate-900 font-medium">{{ $order->customer->customer_name ?? '-' }}</p>
                                            <p class="text-slate-400 text-[11px]">Sales: {{ $order->sales->name ?? '-' }}</p>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-900 text-right">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $displayStatus = $order->status;
                                                if ($order->delivery && in_array($order->delivery->delivery_status, ['shipped', 'delivered'])) {
                                                    $displayStatus = $order->delivery->delivery_status;
                                                }

                                                $statusConfig = [
                                                    'revised' => ['label' => 'Direvisi', 'class' => 'bg-amber-50 text-amber-600 border-amber-100'],
                                                    'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                                    'shipped' => ['label' => 'Dikirim', 'class' => 'bg-indigo-50 text-indigo-600 border-indigo-100'],
                                                    'delivered' => ['label' => 'Diterima', 'class' => 'bg-slate-50 text-slate-600 border-slate-100'],
                                                ];
                                                $status = $statusConfig[$displayStatus] ?? ['label' => str_replace('_', ' ', $displayStatus), 'class' => 'bg-slate-50 text-slate-600 border-slate-100'];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $status['class'] }}">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button onclick="showOrderDetail({{ json_encode($order->load('orderDetail.product')) }})"
                                                    class="p-1.5 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand-blue hover:text-white transition-all shadow-sm"
                                                    title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                @if((in_array($order->status, ['approved', 'completed']) || ($order->delivery && in_array($order->delivery->delivery_status, ['shipped', 'delivered']))) && $order->invoice_pdf)
                                                    <a href="{{ asset('storage/' . $order->invoice_pdf) }}" target="_blank"
                                                        class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                        title="Lihat Faktur PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                            No data found in this category.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PO Tab Content -->
        <div x-show="activeTab === 'pos'" style="display: none;" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            @php
                $poTables = [
                    ['title' => 'P.O Perlu Revisi', 'status' => 'revised', 'color' => 'amber', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['title' => 'P.O Disetujui', 'status' => 'approved', 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Stok P.O Tiba', 'status' => 'stock_arrived', 'color' => 'indigo', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0L12 16l-8-3m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4'],
                    ['title' => 'P.O Dikonversi', 'status' => 'converted', 'color' => 'slate', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ];
            @endphp

            @foreach ($poTables as $table)
                @php $filteredPOs = $purchaseOrders->where('status', $table['status']); @endphp
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-white border border-slate-100 text-slate-400 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $table['icon'] }}" />
                                </svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-900">{{ $table['title'] }}</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            @if(in_array($table['status'], ['approved', 'stock_arrived', 'converted']) && $filteredPOs->count() > 0)
                                <a href="{{ route('direktur.po.export', ['status' => $table['status']]) }}" 
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[11px] font-bold hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export {{ strtoupper(str_replace('_', ' ', $table['status'])) }}
                                </a>
                            @endif
                            <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[11px] font-bold text-slate-600 shadow-sm">
                                {{ $filteredPOs->count() }} P.O
                            </span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4 font-bold">No. PO</th>
                                    <th class="px-6 py-4 font-bold">Pelanggan</th>
                                    <th class="px-6 py-4 font-bold text-right">Total</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                                @forelse ($filteredPOs as $po)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-900">#{{ $po->po_number }}</span>
                                            <div class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-slate-900 font-medium">{{ $po->customer->customer_name ?? '-' }}</p>
                                            <p class="text-slate-400 text-[11px]">Sales: {{ $po->sales->name ?? '-' }}</p>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-900 text-right">
                                            Rp {{ number_format($po->grand_total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $poStatusConfig = [
                                                    'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                                    'revised' => ['label' => 'Direvisi', 'class' => 'bg-amber-50 text-amber-600 border-amber-100'],
                                                    'stock_arrived' => ['label' => 'Stok Tiba', 'class' => 'bg-indigo-50 text-indigo-600 border-indigo-100'],
                                                    'converted' => ['label' => 'Dikonversi', 'class' => 'bg-slate-50 text-slate-600 border-slate-100'],
                                                ];
                                                $poStatus = $poStatusConfig[$po->status] ?? ['label' => str_replace('_', ' ', $po->status), 'class' => 'bg-slate-50 text-slate-600 border-slate-100'];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $poStatus['class'] }}">
                                                {{ $poStatus['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button onclick="showPODetails({{ json_encode($po) }})"
                                                    class="p-1.5 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand-pink hover:text-white transition-all shadow-sm"
                                                    title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                @if(in_array($po->status, ['approved', 'stock_arrived', 'converted']))
                                                    <a href="{{ route('direktur.po.export-single', $po->id_po) }}"
                                                        class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                        title="Ekspor Excel">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                            No data found in this category.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div id="orderDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeOrderModal()"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="modalOrderNumber">Detail Pesanan</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5" id="modalOrderDate"></p>
                    </div>
                    <button onclick="closeOrderModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Data Pelanggan</p>
                            <p class="text-sm font-bold text-slate-900" id="modalCustomerName"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Sales Penginput</p>
                            <p class="text-sm font-bold text-slate-900" id="modalSalesName"></p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rincian Item</p>
                        <div class="border border-slate-100 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50/50 border-b border-slate-100">
                                    <tr class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        <th class="px-4 py-3">Produk</th>
                                        <th class="px-4 py-3 text-center">Qty</th>
                                        <th class="px-4 py-3 text-right">Harga</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="modalOrderItems" class="divide-y divide-slate-50 text-slate-700"></tbody>
                                <tfoot class="bg-slate-50/30">
                                    <tr class="font-bold text-slate-900">
                                        <td colspan="3" class="px-4 py-4 text-right text-xs">GRAND TOTAL</td>
                                        <td class="px-4 py-4 text-right text-brand-blue-dark text-sm" id="modalOrderGrandTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PO Detail Modal -->
    <div id="poDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closePOModal()"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="modalPONumber">Detail Purchase Order</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5" id="modalPODate"></p>
                    </div>
                    <button onclick="closePOModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Data Pelanggan</p>
                            <p class="text-sm font-bold text-slate-900" id="modalPOCustomerName"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Sales Penginput</p>
                            <p class="text-sm font-bold text-slate-900" id="modalPOSalesName"></p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rincian Item</p>
                        <div class="border border-slate-100 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50/50 border-b border-slate-100">
                                    <tr class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        <th class="px-4 py-3">Produk</th>
                                        <th class="px-4 py-3 text-center">Qty</th>
                                        <th class="px-4 py-3 text-right">Harga</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="modalPOItems" class="divide-y divide-slate-50 text-slate-700"></tbody>
                                <tfoot class="bg-slate-50/30">
                                    <tr class="font-bold text-slate-900">
                                        <td colspan="3" class="px-4 py-4 text-right text-xs">GRAND TOTAL</td>
                                        <td class="px-4 py-4 text-right text-brand-pink text-sm" id="modalPOGrandTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Data Tren Penjualan Bulanan (Line Chart) ---
            const months = @json($months);
            const monthlyRevenue = @json($monthlyRevenue);

            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Omzet',
                        data: monthlyRevenue,
                        borderColor: '#4F46E5', // brand blue
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4F46E5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f1f5f9' },
                            ticks: {
                                callback: function(value) {
                                    if(value >= 1000000) return 'Rp ' + (value / 1000000) + 'M';
                                    if(value >= 1000) return 'Rp ' + (value / 1000) + 'K';
                                    return 'Rp ' + value;
                                },
                                font: { size: 10 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // --- Data Performa Sales (Bar Chart) ---
            const salesNames = @json($salesNames);
            const salesRevenues = @json($salesRevenues);

            const ctxSales = document.getElementById('salesChart').getContext('2d');
            new Chart(ctxSales, {
                type: 'bar',
                data: {
                    labels: salesNames,
                    datasets: [{
                        label: 'Omzet',
                        data: salesRevenues,
                        backgroundColor: '#10B981', // emerald
                        borderRadius: 6,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f1f5f9' },
                            ticks: {
                                callback: function(value) {
                                    if(value >= 1000000) return 'Rp ' + (value / 1000000) + 'M';
                                    if(value >= 1000) return 'Rp ' + (value / 1000) + 'K';
                                    return 'Rp ' + value;
                                },
                                font: { size: 10 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        });

        function showOrderDetail(order) {
            const modal = document.getElementById('orderDetailModal');
            document.getElementById('modalOrderNumber').innerText = `Pesanan #${order.order_number}`;
            document.getElementById('modalOrderDate').innerText = new Date(order.order_date).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
            document.getElementById('modalCustomerName').innerText = order.customer?.customer_name || 'N/A';
            document.getElementById('modalSalesName').innerText = order.sales?.name || 'N/A';

            let itemsHtml = '';
            order.order_detail.forEach(item => {
                itemsHtml += `
                    <tr class="hover:bg-slate-50/30">
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-900">${item.product?.product_name || 'N/A'}</div>
                            ${item.bonus_qty > 0 ? `<span class="text-[9px] bg-emerald-50 text-emerald-600 border border-emerald-100 px-1.5 py-0.5 rounded font-bold uppercase tracking-tighter mt-1 inline-block">Bonus: ${item.bonus_qty}</span>` : ''}
                        </td>
                        <td class="px-4 py-3 text-center font-medium">${item.qty}</td>
                        <td class="px-4 py-3 text-right text-slate-500">Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}</td>
                        <td class="px-4 py-3 text-right font-bold text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(item.total_item_price)}</td>
                    </tr>
                `;
            });
            document.getElementById('modalOrderItems').innerHTML = itemsHtml;
            document.getElementById('modalOrderGrandTotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(order.grand_total)}`;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeOrderModal() {
            document.getElementById('orderDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function showPODetails(po) {
            const modal = document.getElementById('poDetailModal');
            document.getElementById('modalPONumber').innerText = `Purchase Order #${po.po_number}`;
            document.getElementById('modalPODate').innerText = new Date(po.po_date).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
            document.getElementById('modalPOCustomerName').innerText = po.customer?.customer_name || 'N/A';
            document.getElementById('modalPOSalesName').innerText = po.sales?.name || 'N/A';

            let itemsHtml = '';
            po.details.forEach(item => {
                itemsHtml += `
                    <tr class="hover:bg-slate-50/30">
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-900">${item.product?.product_name || 'N/A'}</div>
                            ${item.bonus_qty > 0 ? `<span class="text-[9px] bg-emerald-50 text-emerald-600 border border-emerald-100 px-1.5 py-0.5 rounded font-bold uppercase tracking-tighter mt-1 inline-block">Bonus: ${item.bonus_qty}</span>` : ''}
                        </td>
                        <td class="px-4 py-3 text-center font-medium">${item.qty}</td>
                        <td class="px-4 py-3 text-right text-slate-500">Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}</td>
                        <td class="px-4 py-3 text-right font-bold text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(item.total_item_price)}</td>
                    </tr>
                `;
            });
            document.getElementById('modalPOItems').innerHTML = itemsHtml;
            document.getElementById('modalPOGrandTotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(po.grand_total)}`;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePOModal() {
            document.getElementById('poDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
@endsection
