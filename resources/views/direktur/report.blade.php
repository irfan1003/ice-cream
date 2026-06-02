@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Laporan</h1>
            <p class="text-slate-500 mt-1">Laporan pengiriman dan pemasaran.</p>
        </div>
        
        <!-- Filter Form -->
        <form action="{{ route('direktur.report.index') }}" method="GET" class="flex items-center gap-3">
            <select name="period" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-brand-blue focus:border-brand-blue block p-2.5 shadow-sm">
                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="month" {{ request('period', 'month') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
            <button type="submit" class="bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-slate-800 transition-colors shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <!-- Main Content with Tabs -->
    <div x-data="{ activeTab: 'pengiriman' }" class="space-y-6">
        <!-- Tabs Navigation -->
        <div class="flex border-b border-slate-200">
            <button @click="activeTab = 'pengiriman'" 
                :class="activeTab === 'pengiriman' ? 'border-brand-blue text-brand-blue' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-6 py-3 text-sm font-bold border-b-2 transition-all duration-200">
                Laporan Pengiriman
            </button>
            <button @click="activeTab = 'pemasaran'" 
                :class="activeTab === 'pemasaran' ? 'border-brand-pink text-brand-pink' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-6 py-3 text-sm font-bold border-b-2 transition-all duration-200">
                Laporan Pemasaran
            </button>
        </div>

        <!-- Pengiriman Tab Content -->
        <div x-show="activeTab === 'pengiriman'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-white border border-slate-100 text-slate-400 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-900">Data Pengiriman</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('direktur.report.export', ['type' => 'pengiriman', 'period' => request('period', 'month')]) }}" 
                            class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[11px] font-bold hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Excel
                        </a>
                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[11px] font-bold text-slate-600 shadow-sm">
                            {{ $deliveries->count() ?? 0 }} Data
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                <th class="px-6 py-4 font-bold">No. Order</th>
                                <th class="px-6 py-4 font-bold">Tanggal Kirim</th>
                                <th class="px-6 py-4 font-bold">Pelanggan</th>
                                <th class="px-6 py-4 font-bold">Alamat</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            @forelse ($deliveries ?? [] as $delivery)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-slate-900">#{{ $delivery->order->order_number ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($delivery->delivery_date ?? $delivery->created_at)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ $delivery->order->customer->customer_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ $delivery->order->customer->address ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusConfig = [
                                                'shipped' => ['label' => 'Dalam Pengiriman', 'class' => 'bg-indigo-50 text-indigo-600 border-indigo-100'],
                                                'delivered' => ['label' => 'Terkirim', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                            ];
                                            $status = $statusConfig[$delivery->delivery_status] ?? ['label' => $delivery->delivery_status, 'class' => 'bg-slate-50 text-slate-600 border-slate-100'];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $status['class'] }}">
                                            {{ $status['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="showDeliveryDetail({{ json_encode($delivery) }})"
                                            class="p-1.5 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand-blue hover:text-white transition-all shadow-sm"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                        Tidak ada data pengiriman untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pemasaran Tab Content -->
        <div x-show="activeTab === 'pemasaran'" style="display: none;" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-white border border-slate-100 text-slate-400 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-900">Data Kinerja Pemasaran (Sales)</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('direktur.report.export', ['type' => 'pemasaran', 'period' => request('period', 'month')]) }}" 
                            class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[11px] font-bold hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Excel
                        </a>
                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[11px] font-bold text-slate-600 shadow-sm">
                            {{ $salesReports->count() ?? 0 }} Sales
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                <th class="px-6 py-4 font-bold">Nama Sales</th>
                                <th class="px-6 py-4 font-bold text-center">Total Pesanan</th>
                                <th class="px-6 py-4 font-bold text-right">Total Pendapatan (Omzet)</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            @forelse ($salesReports ?? [] as $sales)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $sales->name }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $sales->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-slate-900">
                                        {{ $sales->total_orders }} Pesanan
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-900 text-right">
                                        Rp {{ number_format($sales->total_revenue, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="showSalesDetail({{ json_encode($sales) }})"
                                            class="p-1.5 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand-pink hover:text-white transition-all shadow-sm"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                        Tidak ada data pemasaran untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Detail Modal -->
    <div id="deliveryDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeDeliveryModal()"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="modalDeliveryOrderNumber">Detail Pengiriman</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5" id="modalDeliveryDate"></p>
                    </div>
                    <button onclick="closeDeliveryModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Data Pelanggan</p>
                            <p class="text-sm font-bold text-slate-900" id="modalDeliveryCustomerName"></p>
                            <p class="text-[11px] text-slate-500 mt-1" id="modalDeliveryAddress"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Driver</p>
                            <p class="text-sm font-bold text-slate-900" id="modalDeliveryDriverName"></p>
                            <span id="modalDeliveryStatusBadge" class="mt-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border inline-block"></span>
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
                                    </tr>
                                </thead>
                                <tbody id="modalDeliveryItems" class="divide-y divide-slate-50 text-slate-700"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Detail Modal -->
    <div id="salesDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeSalesModal()"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="modalSalesNameTitle">Detail Pemasaran</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5" id="modalSalesEmail"></p>
                    </div>
                    <button onclick="closeSalesModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Pesanan</p>
                            <p class="text-sm font-bold text-slate-900" id="modalSalesTotalOrders"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Omzet</p>
                            <p class="text-sm font-bold text-brand-pink" id="modalSalesTotalRevenue"></p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Daftar Pesanan</p>
                        <div class="border border-slate-100 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50/50 border-b border-slate-100">
                                    <tr class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        <th class="px-4 py-3">No. Order</th>
                                        <th class="px-4 py-3">Pelanggan</th>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3 text-right">Nilai Pesanan</th>
                                    </tr>
                                </thead>
                                <tbody id="modalSalesItems" class="divide-y divide-slate-50 text-slate-700"></tbody>
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
        function showDeliveryDetail(delivery) {
            const modal = document.getElementById('deliveryDetailModal');
            
            const orderNumber = delivery.order?.order_number || '-';
            const customerName = delivery.order?.customer?.customer_name || '-';
            const address = delivery.order?.customer?.address || '-';
            const driverName = delivery.driver?.name || '-';
            
            document.getElementById('modalDeliveryOrderNumber').innerText = `Pengiriman Order #${orderNumber}`;
            document.getElementById('modalDeliveryDate').innerText = new Date(delivery.delivery_date || delivery.created_at).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
            document.getElementById('modalDeliveryCustomerName').innerText = customerName;
            document.getElementById('modalDeliveryAddress').innerText = address;
            document.getElementById('modalDeliveryDriverName').innerText = driverName;
            
            const badge = document.getElementById('modalDeliveryStatusBadge');
            if (delivery.delivery_status === 'shipped') {
                badge.className = 'mt-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border inline-block bg-indigo-50 text-indigo-600 border-indigo-100';
                badge.innerText = 'Dalam Pengiriman';
            } else if (delivery.delivery_status === 'delivered') {
                badge.className = 'mt-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border inline-block bg-emerald-50 text-emerald-600 border-emerald-100';
                badge.innerText = 'Terkirim';
            } else {
                badge.className = 'mt-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border inline-block bg-slate-50 text-slate-600 border-slate-100';
                badge.innerText = delivery.delivery_status || 'N/A';
            }

            let itemsHtml = '';
            if (delivery.order && delivery.order.order_detail && delivery.order.order_detail.length > 0) {
                delivery.order.order_detail.forEach(item => {
                    itemsHtml += `
                        <tr class="hover:bg-slate-50/30">
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-900">${item.product?.product_name || 'N/A'}</div>
                            </td>
                            <td class="px-4 py-3 text-center font-medium">${item.qty}</td>
                        </tr>
                    `;
                });
            } else {
                itemsHtml = `<tr><td colspan="2" class="px-4 py-4 text-center text-slate-400 italic">Tidak ada detail item</td></tr>`;
            }
            
            document.getElementById('modalDeliveryItems').innerHTML = itemsHtml;
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeliveryModal() {
            document.getElementById('deliveryDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function showSalesDetail(sales) {
            const modal = document.getElementById('salesDetailModal');
            
            document.getElementById('modalSalesNameTitle').innerText = sales.name;
            document.getElementById('modalSalesEmail').innerText = sales.email;
            document.getElementById('modalSalesTotalOrders').innerText = `${sales.total_orders} Pesanan`;
            document.getElementById('modalSalesTotalRevenue').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(sales.total_revenue)}`;

            let itemsHtml = '';
            if (sales.orders && sales.orders.length > 0) {
                sales.orders.forEach(order => {
                    itemsHtml += `
                        <tr class="hover:bg-slate-50/30">
                            <td class="px-4 py-3 font-bold text-slate-900">#${order.order_number}</td>
                            <td class="px-4 py-3">${order.customer?.customer_name || '-'}</td>
                            <td class="px-4 py-3 text-slate-500">${new Date(order.order_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}</td>
                            <td class="px-4 py-3 text-right font-medium text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(order.grand_total)}</td>
                        </tr>
                    `;
                });
            } else {
                itemsHtml = `<tr><td colspan="4" class="px-4 py-4 text-center text-slate-400 italic">Tidak ada pesanan tercatat</td></tr>`;
            }
            
            document.getElementById('modalSalesItems').innerHTML = itemsHtml;
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSalesModal() {
            document.getElementById('salesDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
@endsection