@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Admin Dashboard</h1>
        <p class="text-slate-500">Welcome back, {{ auth()->user()->name }}. Here's what's happening today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Products Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-blue-light rounded-xl flex items-center justify-center text-brand-blue-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Products</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalProducts) }}</h3>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-pink-light rounded-xl flex items-center justify-center text-brand-pink-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Customers</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalCustomers) }}</h3>
                </div>
            </div>
        </div>

        <!-- Sales Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-blue-light rounded-xl flex items-center justify-center text-brand-blue-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Sales Staff</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalSales) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content with Tabs -->
    <div x-data="{ activeTab: 'orders' }" class="space-y-6">
        <!-- Tabs Navigation -->
        <div class="flex border-b border-slate-200">
            <button @click="activeTab = 'orders'"
                :class="activeTab === 'orders' ? 'border-brand-blue text-brand-blue' :
                    'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-6 py-3 text-sm font-bold border-b-2 transition-all duration-200">
                Pesanan Reguler
            </button>
            <button @click="activeTab = 'pos'"
                :class="activeTab === 'pos' ? 'border-brand-pink text-brand-pink' :
                    'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-6 py-3 text-sm font-bold border-b-2 transition-all duration-200">
                Purchase Order (PO)
            </button>
        </div>

        <!-- Orders Tab Content -->
        <div x-show="activeTab === 'orders'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            @php
                $tables = [
                    [
                        'title' => 'Faktur Perlu Direvisi',
                        'data' => $revisedOrders,
                        'color' => 'amber',
                        'icon' =>
                            'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                    ],
                    [
                        'title' => 'Faktur Disetujui',
                        'data' => $approvedOrders,
                        'color' => 'emerald',
                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'title' => 'Pesanan Dikirim',
                        'data' => $shippedOrders,
                        'color' => 'indigo',
                        'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                    ],
                    [
                        'title' => 'Pesanan Terkirim',
                        'data' => $deliveredOrders,
                        'color' => 'slate',
                        'icon' => 'M5 13l4 4L19 7',
                    ],
                    [
                        'title' => 'Pesanan Sudah Bayar (Paid)',
                        'data' => $paidOrders,
                        'color' => 'emerald',
                        'icon' =>
                            'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                ];
            @endphp

            @foreach ($tables as $table)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-white border border-slate-100 text-slate-400 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $table['icon'] }}" />
                                </svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-900">{{ $table['title'] }}</h2>
                        </div>
                        <span
                            class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[11px] font-bold text-slate-600 shadow-sm">
                            {{ $table['data']->count() }} Orders
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4 font-bold">Order #</th>
                                    <th class="px-6 py-4 font-bold">Customer</th>
                                    <th class="px-6 py-4 font-bold">Sales</th>
                                    <th class="px-6 py-4 font-bold">Date</th>
                                    <th class="px-6 py-4 font-bold text-right">Amount</th>
                                    <th class="px-6 py-4 font-bold text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse ($table['data'] as $order)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-900">#{{ $order->order_number }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-slate-900 font-medium">
                                                {{ $order->customer->customer_name ?? '-' }}</p>
                                            <p class="text-slate-400 text-[11px]">{{ $order->customer->phone ?? '-' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ $order->sales->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-900 text-right">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if ($order->status === 'revised')
                                                    <button onclick="editOrder({{ json_encode($order) }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                                        title="Edit Pesanan">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <button onclick="resubmitOrder({{ $order->id_order }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                                        title="Kirim Ulang ke Direktur">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                        </svg>
                                                    </button>
                                                    <a href="{{ route('admin.incorders.preview', $order->id_order) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                                        title="Lihat Detail">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                @elseif(in_array($order->status, ['approved', 'completed']) ||
                                                        ($order->delivery && in_array($order->delivery->delivery_status, ['shipped', 'delivered'])))
                                                    <a href="{{ asset('storage/' . $order->invoice_pdf) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                        title="Lihat PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    </a>
                                                    @if ($order->status !== 'paid' && $order->delivery && $order->delivery->delivery_status === 'delivered')
                                                        <button onclick="markAsPaid({{ $order->id_order }})"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                                            title="Tandai Sudah Bayar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                            No orders found in this category.
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
        <div x-show="activeTab === 'pos'" style="display: none;"
            class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            @php
                $poTables = [
                    [
                        'title' => 'P.O Disetujui',
                        'status' => 'approved',
                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'title' => 'P.O Revisi',
                        'status' => 'revised',
                        'icon' =>
                            'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                    ],
                ];
            @endphp

            @foreach ($poTables as $table)
                @php
                    $filteredPOs = $purchaseOrders->where('status', $table['status']);
                @endphp
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-white border border-slate-100 text-slate-400 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $table['icon'] }}" />
                                </svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-900">{{ $table['title'] }}</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            @if ($table['status'] === 'approved' && $filteredPOs->count() > 0)
                                <a href="{{ route('admin.po.export', ['status' => 'approved']) }}"
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[11px] font-bold hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export APPROVED
                                </a>
                            @endif
                            <span
                                class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[11px] font-bold text-slate-600 shadow-sm">
                                {{ $filteredPOs->count() }} P.O
                            </span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4 font-bold">No. PO</th>
                                    <th class="px-6 py-4 font-bold">Pelanggan</th>
                                    <th class="px-6 py-4 font-bold text-right">Total</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse ($filteredPOs as $po)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-900">#{{ $po->po_number }}</span>
                                            <div class="text-[10px] text-slate-400 mt-0.5">
                                                {{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-slate-900 font-medium">
                                                {{ $po->customer->customer_name ?? '-' }}</p>
                                            <p class="text-slate-400 text-[11px]">Sales: {{ $po->sales->name ?? '-' }}</p>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-900 text-right">
                                            Rp {{ number_format($po->grand_total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $poStatusConfig = [
                                                    'approved' => [
                                                        'label' => 'Disetujui',
                                                        'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    ],
                                                    'revised' => [
                                                        'label' => 'Revisi',
                                                        'class' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    ],
                                                ];
                                                $poStatus = $poStatusConfig[$po->status] ?? [
                                                    'label' => str_replace('_', ' ', $po->status),
                                                    'class' => 'bg-slate-50 text-slate-600 border-slate-100',
                                                ];
                                            @endphp
                                            <span
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $poStatus['class'] }}">
                                                {{ $poStatus['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                {{-- View detail --}}
                                                <button onclick="showPODetails({{ json_encode($po) }})"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand-blue hover:text-white transition-all shadow-sm"
                                                    title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                @if ($po->status === 'approved')
                                                    {{-- Convert to regular order --}}
                                                    <button onclick="convertPO({{ $po->id_po }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white transition-all shadow-sm"
                                                        title="Ubah ke Pesanan Reguler">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                    </button>
                                                    {{-- Export single --}}
                                                    <a href="{{ route('admin.po.export-single', $po->id_po) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                        title="Ekspor Excel">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                @if ($po->status === 'revised')
                                                    {{-- Edit revised PO --}}
                                                    <button onclick="editRevisedPO({{ json_encode($po) }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                                        title="Edit PO Revisi">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    {{-- Resubmit to director --}}
                                                    <button onclick="resubmitPO({{ $po->id_po }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                                        title="Kirim Ulang ke Direktur">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                        </svg>
                                                    </button>
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

    <!-- PO Detail Modal -->
    <div id="poDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                onclick="closeModal('poDetailModal')"></div>
            <div
                class="relative bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="modalPONumber">Detail Purchase Order</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5" id="modalPODate"></p>
                    </div>
                    <button onclick="closeModal('poDetailModal')"
                        class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Data Pelanggan
                            </p>
                            <p class="text-sm font-bold text-slate-900" id="modalCustomerName"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Sales
                                Penginput</p>
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
                                <tbody id="modalItems" class="divide-y divide-slate-50 text-slate-700"></tbody>
                                <tfoot class="bg-slate-50/30">
                                    <tr class="font-bold text-slate-900">
                                        <td colspan="3" class="px-4 py-4 text-right text-xs">GRAND TOTAL</td>
                                        <td class="px-4 py-4 text-right text-brand-blue-dark text-sm"
                                            id="modalGrandTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div id="editOrderModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                onclick="closeModal('editOrderModal')"></div>
            <div
                class="relative bg-white rounded-2xl w-full max-w-3xl overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-amber-50/40">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="editOrderNumber">Edit Order Revisi</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Sesuaikan qty, bonus, dan diskon item yang direvisi.
                        </p>
                    </div>
                    <button type="button" onclick="closeModal('editOrderModal')"
                        class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 max-h-[65vh] overflow-y-auto">
                    <input type="hidden" id="editOrderId">
                    <div class="border border-slate-100 rounded-xl overflow-hidden">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-4 py-3">Produk</th>
                                    <th class="px-4 py-3 text-center w-24">Qty</th>
                                    <th class="px-4 py-3 text-center w-24">Bonus</th>
                                    <th class="px-4 py-3 text-center w-32">Diskon / pcs</th>
                                    <th class="px-4 py-3 text-right">Harga Satuan</th>
                                </tr>
                            </thead>
                            <tbody id="editOrderItems" class="divide-y divide-slate-50 text-slate-700"></tbody>
                        </table>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" onclick="resubmitOrderFromEdit()"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim ke Direktur
                    </button>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeModal('editOrderModal')"
                            class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Batal</button>
                        <button type="button" onclick="submitEditOrder()"
                            class="px-6 py-2 bg-amber-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all">Simpan
                            Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Revised PO Modal -->
    <div id="editRevisedPOModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                onclick="closeModal('editRevisedPOModal')"></div>
            <div
                class="relative bg-white rounded-2xl w-full max-w-3xl overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-amber-50/40">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="editRevisedPONumber">Edit P.O Revisi</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Sesuaikan qty, bonus, dan diskon item yang direvisi.
                        </p>
                    </div>
                    <button type="button" onclick="closeModal('editRevisedPOModal')"
                        class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 max-h-[65vh] overflow-y-auto">
                    <input type="hidden" id="editRevisedPOId">
                    <div class="border border-slate-100 rounded-xl overflow-hidden">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-4 py-3">Produk</th>
                                    <th class="px-4 py-3 text-center w-24">Qty</th>
                                    <th class="px-4 py-3 text-center w-24">Bonus</th>
                                    <th class="px-4 py-3 text-center w-32">Diskon / pcs</th>
                                    <th class="px-4 py-3 text-right">Harga Satuan</th>
                                </tr>
                            </thead>
                            <tbody id="editRevisedPOItems" class="divide-y divide-slate-50 text-slate-700"></tbody>
                        </table>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" onclick="resubmitFromEdit()"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim ke Direktur
                    </button>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeModal('editRevisedPOModal')"
                            class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Batal</button>
                        <button type="button" onclick="submitRevisedPO()"
                            class="px-6 py-2 bg-amber-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all">Simpan
                            Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script>
            // ── PO Detail Modal ──────────────────────────────────────────
            function showPODetails(po) {
                document.getElementById('modalPONumber').innerText = `Purchase Order #${po.po_number}`;
                document.getElementById('modalPODate').innerText = new Date(po.po_date).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                document.getElementById('modalCustomerName').innerText = po.customer?.customer_name || 'N/A';
                document.getElementById('modalSalesName').innerText = po.sales?.name || 'N/A';

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
                        </tr>`;
                });
                document.getElementById('modalItems').innerHTML = itemsHtml;
                document.getElementById('modalGrandTotal').innerText =
                    `Rp ${new Intl.NumberFormat('id-ID').format(po.grand_total)}`;

                document.getElementById('poDetailModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // ── Convert PO ───────────────────────────────────────────────
            async function convertPO(id) {
                const result = await Swal.fire({
                    title: 'Konfirmasi Konversi',
                    text: "Apakah Anda yakin ingin mengkonversi PO ini menjadi Pesanan Reguler?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8B5CF6',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Konversi!',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/purchase-orders/${id}/convert`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                    }
                }
            }

            // ── Mark as Paid ─────────────────────────────────────────────
            async function markAsPaid(id) {
                const result = await Swal.fire({
                    title: 'Konfirmasi Pembayaran',
                    text: "Apakah Anda yakin ingin menandai pesanan ini sebagai SUDAH BAYAR (PAID)?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Sudah Bayar',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/orders/${id}/mark-as-paid`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                    }
                }
            }

            // ── Edit Revised PO ──────────────────────────────────────────
            function editRevisedPO(po) {
                document.getElementById('editRevisedPOId').value = po.id_po;
                document.getElementById('editRevisedPONumber').innerText = `Edit P.O Revisi #${po.po_number}`;

                let rows = '';
                po.details.forEach(item => {
                    rows += `
                        <tr>
                            <td class="px-4 py-3">
                                <input type="hidden" name="item_id[]" value="${item.id_po_detail}">
                                <span class="font-bold text-slate-900">${item.product?.product_name || 'N/A'}</span>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="qty[]" value="${item.qty}" min="1"
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-center text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="bonus_qty[]" value="${item.bonus_qty ?? 0}" min="0"
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-center text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="discount[]" value="${item.discount ?? 0}" min="0" step="0.01"
                                    class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-center text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                            </td>
                            <td class="px-4 py-3 text-right text-slate-500 font-medium">
                                Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}
                            </td>
                        </tr>`;
                });

                document.getElementById('editRevisedPOItems').innerHTML = rows;
                document.getElementById('editRevisedPOModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function getRevisedPOPayload() {
                const tbody = document.getElementById('editRevisedPOItems');
                return Array.from(tbody.querySelectorAll('tr')).map(row => ({
                    id: row.querySelector('input[name="item_id[]"]').value,
                    qty: parseInt(row.querySelector('input[name="qty[]"]').value),
                    bonus_qty: parseInt(row.querySelector('input[name="bonus_qty[]"]').value) || 0,
                    discount: parseFloat(row.querySelector('input[name="discount[]"]').value) || 0,
                }));
            }

            async function submitRevisedPO() {
                const id = document.getElementById('editRevisedPOId').value;
                try {
                    const response = await fetch(`/admin/purchase-orders/${id}/update-revised`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: getRevisedPOPayload()
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            })
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                }
            }

            async function resubmitFromEdit() {
                const id = document.getElementById('editRevisedPOId').value;

                const confirm = await Swal.fire({
                    title: 'Simpan & Kirim ke Direktur?',
                    text: 'Perubahan akan disimpan, kemudian PO dikirim ulang ke Direktur untuk persetujuan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4F46E5',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                });
                if (!confirm.isConfirmed) return;

                try {
                    // 1. Save changes
                    const saveResp = await fetch(`/admin/purchase-orders/${id}/update-revised`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: getRevisedPOPayload()
                        })
                    });
                    const saveData = await saveResp.json();
                    if (!saveData.success) {
                        Swal.fire('Gagal Simpan', saveData.message, 'error');
                        return;
                    }

                    // 2. Resubmit
                    const submitResp = await fetch(`/admin/purchase-orders/${id}/resubmit`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const submitData = await submitResp.json();
                    if (submitData.success) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: submitData.message,
                                timer: 2000,
                                showConfirmButton: false
                            })
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', submitData.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                }
            }

            async function resubmitPO(id) {
                const result = await Swal.fire({
                    title: 'Kirim Ulang ke Direktur?',
                    text: 'PO akan dikirim ulang ke Direktur untuk persetujuan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4F46E5',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/purchase-orders/${id}/resubmit`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                    }
                }
            }

            // ── Edit Revised Order ─────────────────────────────────────────
            function editOrder(order) {
                console.log('Order data:', order);
                document.getElementById('editOrderId').value = order.id_order;
                document.getElementById('editOrderNumber').innerText = `Edit Order Revisi #${order.order_number}`;

                let rows = '';
                const orderDetails = order.orderDetail || order.order_detail;
                console.log('Order details:', orderDetails);
                if (orderDetails && orderDetails.length > 0) {
                    orderDetails.forEach(item => {
                        rows += `
                            <tr>
                                <td class="px-4 py-3">
                                    <input type="hidden" name="item_id[]" value="${item.id_order_detail}">
                                    <span class="font-bold text-slate-900">${item.product?.product_name || 'N/A'}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="qty[]" value="${item.qty}" min="1"
                                        class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-center text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="bonus_qty[]" value="${item.bonus_qty ?? 0}" min="0"
                                        class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-center text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="discount[]" value="${item.discount ?? 0}" min="0" step="0.01"
                                        class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-center text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                                </td>
                                <td class="px-4 py-3 text-right text-slate-500 font-medium">
                                    Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}
                                </td>
                            </tr>`;
                    });
                }

                document.getElementById('editOrderItems').innerHTML = rows;
                document.getElementById('editOrderModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function getRevisedOrderPayload() {
                const tbody = document.getElementById('editOrderItems');
                const rows = tbody.querySelectorAll('tr');
                console.log('Rows:', rows);
                const payload = Array.from(rows).map(row => {
                    const idInput = row.querySelector('input[name="item_id[]"]');
                    const qtyInput = row.querySelector('input[name="qty[]"]');
                    const bonusQtyInput = row.querySelector('input[name="bonus_qty[]"]');
                    const discountInput = row.querySelector('input[name="discount[]"]');
                    return {
                        id: idInput ? idInput.value : null,
                        qty: qtyInput ? parseInt(qtyInput.value) : null,
                        bonus_qty: bonusQtyInput ? parseInt(bonusQtyInput.value) || 0 : 0,
                        discount: discountInput ? parseFloat(discountInput.value) || 0 : 0,
                    };
                });
                console.log('Payload:', payload);
                return payload;
            }

            async function submitEditOrder() {
                const id = document.getElementById('editOrderId').value;
                console.log('Submit edit order for ID:', id);
                const payload = getRevisedOrderPayload();
                console.log('Full request payload:', {
                    items: payload
                });
                try {
                    const response = await fetch(`/admin/orders/${id}/update-revised`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: payload
                        })
                    });
                    console.log('Response status:', response.status);
                    const text = await response.text();
                    console.log('Response body:', text);
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', e);
                        Swal.fire('Error', 'Respons server tidak valid.', 'error');
                        return;
                    }
                    if (data.success) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            })
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    Swal.fire('Error', 'Gagal menghubungi server: ' + error.message, 'error');
                }
            }

            async function resubmitOrderFromEdit() {
                const id = document.getElementById('editOrderId').value;

                const confirm = await Swal.fire({
                    title: 'Simpan & Kirim ke Direktur?',
                    text: 'Perubahan akan disimpan, kemudian Order dikirim ulang ke Direktur untuk persetujuan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4F46E5',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                });
                if (!confirm.isConfirmed) return;

                try {
                    // 1. Save changes
                    const saveResp = await fetch(`/admin/orders/${id}/update-revised`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: getRevisedOrderPayload()
                        })
                    });
                    const saveData = await saveResp.json();
                    if (!saveData.success) {
                        Swal.fire('Gagal Simpan', saveData.message, 'error');
                        return;
                    }

                    // 2. Resubmit
                    const submitResp = await fetch(`/admin/orders/${id}/resubmit`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const submitData = await submitResp.json();
                    if (submitData.success) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: submitData.message,
                                timer: 2000,
                                showConfirmButton: false
                            })
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', submitData.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                }
            }

            async function resubmitOrder(id) {
                const result = await Swal.fire({
                    title: 'Kirim Ulang ke Direktur?',
                    text: 'Order akan dikirim ulang ke Direktur untuk persetujuan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4F46E5',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/orders/${id}/resubmit`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                    }
                }
            }
        </script>
    @endpush
@endsection
