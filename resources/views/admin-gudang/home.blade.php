@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Gudang</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau pengiriman dan aktivitas stok gudang.</p>
            </div>
        </div>

        <!-- Tabs -->
        <div x-data="{ activeTab: 'deliveries' }" class="space-y-6">
            <div class="flex border-b border-slate-200">
                <button @click="activeTab = 'deliveries'" 
                    :class="activeTab === 'deliveries' ? 'border-brand-blue text-brand-blue' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-3 text-sm font-bold border-b-2 transition-colors">
                    Daftar Pengiriman
                </button>
                <button @click="activeTab = 'logs'" 
                    :class="activeTab === 'logs' ? 'border-brand-pink text-brand-pink' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-3 text-sm font-bold border-b-2 transition-colors">
                    Riwayat Stok
                </button>
            </div>

            <!-- Deliveries Table -->
            <div x-show="activeTab === 'deliveries'" class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. SPB</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pesanan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($deliveries as $delivery)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-900">
                                        {{ $delivery->spb_number }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $delivery->order->order_number }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $delivery->order->customer->customer_name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $delivery->order->customer->address }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $deliveryStatusConfig = [
                                                'pending_admin_kantor' => ['label' => 'Pending Kantor', 'class' => 'bg-amber-100 text-amber-700'],
                                                'pending_admin_gudang' => ['label' => 'Pending Gudang', 'class' => 'bg-blue-100 text-blue-700'],
                                                'ready' => ['label' => 'Siap Kirim', 'class' => 'bg-indigo-100 text-indigo-700'],
                                                'shipped' => ['label' => 'Dalam Pengiriman', 'class' => 'bg-purple-100 text-purple-700'],
                                                'delivered' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700'],
                                            ];
                                            $status = $deliveryStatusConfig[$delivery->delivery_status] ?? ['label' => $delivery->delivery_status, 'class' => 'bg-slate-100 text-slate-700'];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $status['class'] }}">
                                            {{ str_replace('_', ' ', $status['label']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="showDeliveryDetail({{ json_encode($delivery) }})" 
                                                class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p class="text-slate-400">Belum ada data pengiriman.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Stock Logs Table -->
            <div x-show="activeTab === 'logs'" style="display: none;" class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Referensi</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Tipe</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total Item</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($stockLogs as $reference => $items)
                                @php
                                    $firstItem = $items->first();
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $firstItem->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-900">
                                        {{ $reference }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($firstItem->type === 'in')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700">Masuk</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-100 text-rose-700">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-900">
                                        {{ $items->count() }} Produk
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $logStatusConfig = [
                                                'pending' => ['label' => 'Pending', 'class' => 'bg-amber-100 text-amber-700'],
                                                'verified' => ['label' => 'Terverifikasi', 'class' => 'bg-emerald-100 text-emerald-700'],
                                                'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700'],
                                            ];
                                            $logStatus = $logStatusConfig[$firstItem->verification_status] ?? ['label' => $firstItem->verification_status, 'class' => 'bg-slate-100 text-slate-700'];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $logStatus['class'] }}">
                                            {{ $logStatus['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="showStockLogDetail('{{ $reference }}', {{ json_encode($items) }})" 
                                                class="p-1.5 text-slate-400 hover:text-brand-pink hover:bg-brand-pink/10 rounded-lg transition-all"
                                                title="Lihat Detail Stok">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-slate-400">Belum ada riwayat stok.</p>
                                        </div>
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
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            
            <div class="relative bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-xl transition-all">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="modalSpbNumber">Detail Pengiriman</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="modalOrderNumber"></p>
                    </div>
                    <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                            <p class="text-sm font-semibold text-slate-900" id="modalCustomerName"></p>
                            <p class="text-xs text-slate-500" id="modalCustomerAddress"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Driver</p>
                            <p class="text-sm font-semibold text-slate-900" id="modalDriverName"></p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Status Delivery</p>
                                <p class="text-sm font-bold text-slate-900" id="modalStatusText"></p>
                            </div>
                            <div id="modalAccIcons" class="flex gap-4">
                                <!-- Icons for acc_kantor and acc_gudang -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Log Detail Modal -->
    <div id="stockLogDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeStockLogModal()"></div>
            
            <div class="relative bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-xl transition-all">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="stockModalReference">Detail Pergerakan Stok</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="stockModalDate"></p>
                    </div>
                    <button onclick="closeStockLogModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="border border-slate-100 rounded-xl overflow-hidden">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-2.5 font-bold text-slate-500 uppercase text-[10px]">Produk</th>
                                    <th class="px-4 py-2.5 font-bold text-slate-500 uppercase text-[10px] text-right">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="stockModalItems" class="divide-y divide-slate-100">
                                <!-- Dynamic -->
                            </tbody>
                        </table>
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
            
            document.getElementById('modalSpbNumber').innerText = `SPB: ${delivery.spb_number}`;
            document.getElementById('modalOrderNumber').innerText = `Order: ${delivery.order.order_number}`;
            document.getElementById('modalCustomerName').innerText = delivery.order.customer.customer_name;
            document.getElementById('modalCustomerAddress').innerText = delivery.order.customer.address;
            document.getElementById('modalDriverName').innerText = delivery.driver ? delivery.driver.name : 'Belum ditentukan';
            document.getElementById('modalStatusText').innerText = delivery.delivery_status.replace(/_/g, ' ').toUpperCase();

            const accIcons = document.getElementById('modalAccIcons');
            accIcons.innerHTML = `
                <div class="flex flex-col items-center gap-1">
                    <div class="p-2 rounded-lg ${delivery.acc_kantor ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400'}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">Kantor</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="p-2 rounded-lg ${delivery.acc_gudang ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400'}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">Gudang</span>
                </div>
            `;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('deliveryDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function showStockLogDetail(reference, items) {
            const modal = document.getElementById('stockLogDetailModal');
            document.getElementById('stockModalReference').innerText = `Referensi: ${reference}`;
            
            // Format date from the first item
            const date = new Date(items[0].created_at).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            document.getElementById('stockModalDate').innerText = date;

            let itemsHtml = '';
            items.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-900">${item.product.product_name}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">${item.product.brand}</div>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-slate-900">${new Intl.NumberFormat('id-ID').format(item.quantity)}</td>
                    </tr>
                `;
            });

            document.getElementById('stockModalItems').innerHTML = itemsHtml;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeStockLogModal() {
            document.getElementById('stockLogDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
@endsection
