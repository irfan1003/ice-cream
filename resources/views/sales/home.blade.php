@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Sales</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau performa dan status pesanan pelanggan Anda.</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pending PO Sales -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending PO (Sales)</p>
                        <h3 class="text-2xl font-black text-slate-900">{{ $stats['pending_sales_pos'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Pending Order Sales -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending Order (Sales)</p>
                        <h3 class="text-2xl font-black text-slate-900">{{ $stats['pending_sales_orders'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pelanggan</p>
                        <h3 class="text-2xl font-black text-slate-900">{{ $stats['customers'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Produk</p>
                        <h3 class="text-2xl font-black text-slate-900">{{ $stats['products'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div x-data="{ activeTab: 'orders' }" class="space-y-6">
            <div class="flex border-b border-slate-200">
                <button @click="activeTab = 'orders'" 
                    :class="activeTab === 'orders' ? 'border-brand-blue text-brand-blue' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-3 text-sm font-bold border-b-2 transition-colors">
                    Pesanan Reguler
                </button>
                <button @click="activeTab = 'pos'" 
                    :class="activeTab === 'pos' ? 'border-brand-pink text-brand-pink' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-3 text-sm font-bold border-b-2 transition-colors">
                    Purchase Order (PO)
                </button>
            </div>

            <!-- Orders Tab Content -->
        <div x-show="activeTab === 'orders'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            @php
                $orderTables = [
                    ['title' => 'Menunggu Persetujuan', 'statuses' => ['pending_sales', 'pending_coordinator', 'pending_admin', 'pending_director'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Perlu Revisi', 'statuses' => ['revised'], 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['title' => 'Disetujui & Diproses', 'statuses' => ['approved', 'processing'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Dikirim & Selesai', 'statuses' => ['shipped', 'delivered', 'completed'], 'icon' => 'M5 13l4 4L19 7'],
                    ['title' => 'Ditolak', 'statuses' => ['rejected'], 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp

            @foreach ($orderTables as $table)
                @php
                    $filteredOrders = collect($orders)->filter(function ($order) use ($table) {
                        $actualStatus = $order->status;
                        if (in_array($order->status, ['approved', 'completed']) && $order->delivery) {
                            if (in_array($order->delivery->delivery_status, ['shipped', 'delivered'])) {
                                $actualStatus = $order->delivery->delivery_status;
                            }
                        }
                        return in_array($actualStatus, $table['statuses']);
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
                                    <th class="px-6 py-4 font-bold">No. Pesanan</th>
                                    <th class="px-6 py-4 font-bold">Pelanggan</th>
                                    <th class="px-6 py-4 font-bold">Tanggal</th>
                                    <th class="px-6 py-4 font-bold text-right">Total</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($filteredOrders as $order)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-slate-900">
                                            #{{ $order->order_number }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{ $order->customer->customer_name }}</div>
                                            <div class="text-[10px] text-slate-400 uppercase tracking-widest">{{ $order->customer->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-900">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $displayStatus = $order->status;
                                                if ($order->delivery && in_array($order->delivery->delivery_status, ['shipped', 'delivered'])) {
                                                    $displayStatus = $order->delivery->delivery_status;
                                                }

                                                $statusConfig = [
                                                    'pending_sales' => ['label' => 'Menunggu Sales', 'class' => 'bg-amber-100 text-amber-700 border border-amber-200'],
                                                    'pending_coordinator' => ['label' => 'Menunggu Koordinator', 'class' => 'bg-blue-100 text-blue-700 border border-blue-200'],
                                                    'pending_director' => ['label' => 'Menunggu Direktur', 'class' => 'bg-indigo-100 text-indigo-700 border border-indigo-200'],
                                                    'revised' => ['label' => 'Direvisi', 'class' => 'bg-orange-100 text-orange-700 border border-orange-200'],
                                                    'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-700 border border-emerald-200'],
                                                    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 border border-rose-200'],
                                                    'pending_admin' => ['label' => 'Menunggu Admin', 'class' => 'bg-cyan-100 text-cyan-700 border border-cyan-200'],
                                                    'shipped' => ['label' => 'Dikirim', 'class' => 'bg-purple-100 text-purple-700 border border-purple-200'],
                                                    'delivered' => ['label' => 'Diterima', 'class' => 'bg-slate-100 text-slate-700 border border-slate-200'],
                                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700 border border-emerald-200'],
                                                ];
                                                $status = $statusConfig[$displayStatus] ?? ['label' => $displayStatus, 'class' => 'bg-slate-100 text-slate-700 border border-slate-200'];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $status['class'] }}">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button onclick="showOrderDetail({{ json_encode($order) }})" 
                                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all shadow-sm border border-transparent hover:border-blue-100"
                                                        title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">
                                            Tidak ada pesanan di kategori ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Purchase Orders Tab Content -->
        <div x-show="activeTab === 'pos'" style="display: none;" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            @php
                $poTables = [
                    ['title' => 'Menunggu Persetujuan', 'statuses' => ['pending_sales', 'pending_coordinator', 'pending_admin', 'pending_director'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Perlu Revisi', 'statuses' => ['revised'], 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['title' => 'Disetujui', 'statuses' => ['approved'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Stok Tiba & Dikonversi', 'statuses' => ['stock_arrived', 'converted'], 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0L12 16l-8-3m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4'],
                    ['title' => 'Ditolak', 'statuses' => ['rejected'], 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp

            @foreach ($poTables as $table)
                @php
                    $filteredPOs = collect($purchaseOrders)->filter(function ($po) use ($table) {
                        return in_array($po->status, $table['statuses']);
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
                            {{ $filteredPOs->count() }} P.O
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4 font-bold">No. PO</th>
                                    <th class="px-6 py-4 font-bold">Pelanggan</th>
                                    <th class="px-6 py-4 font-bold">Tanggal</th>
                                    <th class="px-6 py-4 font-bold text-right">Total</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($filteredPOs as $po)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-slate-900">
                                            {{ $po->po_number }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{ $po->customer->customer_name }}</div>
                                            <div class="text-[10px] text-slate-400 uppercase tracking-widest">{{ $po->customer->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-900">
                                            Rp {{ number_format($po->grand_total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $poStatusConfig = [
                                                    'pending_sales' => ['label' => 'Menunggu Sales', 'class' => 'bg-amber-100 text-amber-700 border border-amber-200'],
                                                    'pending_coordinator' => ['label' => 'Menunggu Koordinator', 'class' => 'bg-blue-100 text-blue-700 border border-blue-200'],
                                                    'pending_admin' => ['label' => 'Menunggu Admin', 'class' => 'bg-cyan-100 text-cyan-700 border border-cyan-200'],
                                                    'pending_director' => ['label' => 'Menunggu Direktur', 'class' => 'bg-indigo-100 text-indigo-700 border border-indigo-200'],
                                                    'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-700 border border-emerald-200'],
                                                    'revised' => ['label' => 'Direvisi', 'class' => 'bg-orange-100 text-orange-700 border border-orange-200'],
                                                    'stock_arrived' => ['label' => 'Stok Tiba', 'class' => 'bg-purple-100 text-purple-700 border border-purple-200'],
                                                    'converted' => ['label' => 'Dikonversi', 'class' => 'bg-slate-100 text-slate-700 border border-slate-200'],
                                                    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 border border-rose-200'],
                                                ];
                                                $poStatus = $poStatusConfig[$po->status] ?? ['label' => $po->status, 'class' => 'bg-slate-100 text-slate-700 border border-slate-200'];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $poStatus['class'] }}">
                                                {{ $poStatus['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button onclick="showPODetail({{ json_encode($po) }})" 
                                                        class="p-1.5 text-slate-400 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition-all shadow-sm border border-transparent hover:border-pink-100"
                                                        title="Lihat Detail PO">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">
                                            Tidak ada PO di kategori ini.
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
    </div>

    <!-- Order Detail Modal -->
    <div id="orderDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            
            <div class="relative bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-xl transition-all">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="modalOrderNumber">Detail Pesanan</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="modalOrderDate"></p>
                    </div>
                    <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                    <!-- Rejected Note -->
                    <div id="rejectionSection" class="hidden">
                        <div class="bg-rose-50 border border-rose-100 rounded-lg p-4">
                            <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-1">Catatan Penolakan</p>
                            <p class="text-sm text-rose-700" id="modalRejectedNote"></p>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Daftar Item</p>
                        <div class="border border-slate-100 rounded-lg overflow-hidden">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-2.5 font-semibold text-slate-600">Produk</th>
                                        <th class="px-4 py-2.5 font-semibold text-slate-600 text-center">Qty</th>
                                        <th class="px-4 py-2.5 font-semibold text-slate-600 text-right">Harga</th>
                                        <th class="px-4 py-2.5 font-semibold text-slate-600 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="modalItems" class="divide-y divide-slate-100">
                                    <!-- Dynamic -->
                                </tbody>
                                <tfoot class="bg-slate-50/50">
                                    <tr class="text-slate-600">
                                        <td colspan="3" class="px-4 py-2 text-right">Subtotal</td>
                                        <td class="px-4 py-2 text-right text-slate-900" id="modalSubtotal"></td>
                                    </tr>
                                    <tr class="text-slate-600">
                                        <td colspan="3" class="px-4 py-2 text-right text-xs">Pajak (11%)</td>
                                        <td class="px-4 py-2 text-right text-slate-900" id="modalTax"></td>
                                    </tr>
                                    <tr class="text-slate-600">
                                        <td colspan="3" class="px-4 py-2 text-right">Diskon</td>
                                        <td class="px-4 py-2 text-right text-rose-600" id="modalDiscount"></td>
                                    </tr>
                                    <tr class="font-bold text-slate-900">
                                        <td colspan="3" class="px-4 py-3 text-right">Grand Total</td>
                                        <td class="px-4 py-3 text-right text-blue-600 text-base" id="modalGrandTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PO Detail Modal (Identical Structure) -->
    <div id="poDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closePOModal()"></div>
            
            <div class="relative bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-xl transition-all">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="poModalNumber">Detail Purchase Order</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="poModalDate"></p>
                    </div>
                    <button onclick="closePOModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                    <!-- Rejected Note -->
                    <div id="poRejectionSection" class="hidden">
                        <div class="bg-rose-50 border border-rose-100 rounded-lg p-4">
                            <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-1">Catatan Penolakan</p>
                            <p class="text-sm text-rose-700" id="poModalRejectedNote"></p>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Daftar Item PO</p>
                        <div class="border border-slate-100 rounded-lg overflow-hidden">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-2.5 font-semibold text-slate-600">Produk</th>
                                        <th class="px-4 py-2.5 font-semibold text-slate-600 text-center">Qty</th>
                                        <th class="px-4 py-2.5 font-semibold text-slate-600 text-right">Harga</th>
                                        <th class="px-4 py-2.5 font-semibold text-slate-600 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="poModalItems" class="divide-y divide-slate-100">
                                    <!-- Dynamic -->
                                </tbody>
                                <tfoot class="bg-slate-50/50">
                                    <tr class="text-slate-600">
                                        <td colspan="3" class="px-4 py-2 text-right">Subtotal</td>
                                        <td class="px-4 py-2 text-right text-slate-900" id="poModalSubtotal"></td>
                                    </tr>
                                    <tr class="text-slate-600">
                                        <td colspan="3" class="px-4 py-2 text-right text-xs">Pajak (11%)</td>
                                        <td class="px-4 py-2 text-right text-slate-900" id="poModalTax"></td>
                                    </tr>
                                    <tr class="text-slate-600">
                                        <td colspan="3" class="px-4 py-2 text-right">Diskon</td>
                                        <td class="px-4 py-2 text-right text-rose-600" id="poModalDiscount"></td>
                                    </tr>
                                    <tr class="font-bold text-slate-900">
                                        <td colspan="3" class="px-4 py-3 text-right">Grand Total</td>
                                        <td class="px-4 py-3 text-right text-pink-600 text-base" id="poModalGrandTotal"></td>
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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function showOrderDetail(order) {
            const modal = document.getElementById('orderDetailModal');
            const itemsContainer = document.getElementById('modalItems');
            const rejectionSection = document.getElementById('rejectionSection');
            
            document.getElementById('modalOrderNumber').innerText = `Pesanan #${order.order_number}`;
            document.getElementById('modalOrderDate').innerText = new Date(order.order_date).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });

            if (order.status === 'rejected') {
                rejectionSection.classList.remove('hidden');
                document.getElementById('modalRejectedNote').innerText = order.rejected_note || 'Tidak ada catatan penolakan.';
            } else {
                rejectionSection.classList.add('hidden');
            }

            let itemsHtml = '';
            const details = order.order_detail || [];
            
            details.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-900">${item.product ? item.product.product_name : 'Produk tidak tersedia'}</div>
                        </td>
                        <td class="px-4 py-3 text-center text-slate-600">${item.qty}</td>
                        <td class="px-4 py-3 text-right text-slate-600">Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}</td>
                        <td class="px-4 py-3 text-right font-medium text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(item.total_item_price)}</td>
                    </tr>
                `;
            });

            itemsContainer.innerHTML = itemsHtml;
            document.getElementById('modalSubtotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(order.subtotal)}`;
            document.getElementById('modalTax').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(order.tax_amount || 0)}`;
            document.getElementById('modalDiscount').innerText = `- Rp ${new Intl.NumberFormat('id-ID').format(order.discount_total || 0)}`;
            document.getElementById('modalGrandTotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(order.grand_total)}`;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('orderDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function showPODetail(po) {
            const modal = document.getElementById('poDetailModal');
            const itemsContainer = document.getElementById('poModalItems');
            const rejectionSection = document.getElementById('poRejectionSection');
            
            document.getElementById('poModalNumber').innerText = `PO ${po.po_number}`;
            document.getElementById('poModalDate').innerText = new Date(po.po_date).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });

            if (po.status === 'rejected') {
                rejectionSection.classList.remove('hidden');
                document.getElementById('poModalRejectedNote').innerText = po.rejected_note || 'Tidak ada catatan penolakan.';
            } else {
                rejectionSection.classList.add('hidden');
            }

            let itemsHtml = '';
            const details = po.details || [];
            
            details.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-900">${item.product ? item.product.product_name : 'Produk tidak tersedia'}</div>
                        </td>
                        <td class="px-4 py-3 text-center text-slate-600">${item.qty}</td>
                        <td class="px-4 py-3 text-right text-slate-600">Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}</td>
                        <td class="px-4 py-3 text-right font-medium text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(item.total_item_price)}</td>
                    </tr>
                `;
            });

            itemsContainer.innerHTML = itemsHtml;
            document.getElementById('poModalSubtotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(po.subtotal)}`;
            document.getElementById('poModalTax').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(po.tax_amount || 0)}`;
            document.getElementById('poModalDiscount').innerText = `- Rp ${new Intl.NumberFormat('id-ID').format(po.discount_total || 0)}`;
            document.getElementById('poModalGrandTotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(po.grand_total)}`;

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
