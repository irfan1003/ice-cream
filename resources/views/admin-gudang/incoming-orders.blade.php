@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pesanan Masuk (Gudang)</h1>
                <p class="text-sm text-slate-500 mt-1">Daftar pesanan yang telah disetujui dan siap diproses.</p>
            </div>

            <!-- Search Form -->
            <div class="flex items-center gap-3">
                <form action="{{ route('gudang.incorders.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Pesanan / Pelanggan..."
                        class="w-full md:w-72 pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-brand-pink focus:border-brand-pink outline-none transition-all shadow-sm">
                    <div
                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand-pink transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">No. Pesanan</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4 text-right">Total</th>
                            <th class="px-6 py-4 text-center">Status Orders</th>
                            <th class="px-6 py-4 text-center">Status Delivery/Surat Jalan</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">#{{ $order->order_number }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $order->customer->customer_name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $order->customer->phone }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-900">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">
                                        Disetujui
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($order->delivery)
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">
                                            {{ str_replace('_', ' ', $order->delivery->delivery_status) }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">
                                            pesanan ini belum membuat surat jalan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="showOrderDetails({{ json_encode($order) }})"
                                            class="p-2 text-slate-400 hover:text-brand-pink hover:bg-brand-pink/10 rounded-lg transition-all"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('gudang.incorders.surat-jalan', $order->id_order) }}" target="_blank"
                                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                            title="Preview Surat Jalan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                            </svg>
                                        </a>

                                        @if (!$order->delivery)
                                            <button onclick="openDriverModal({{ $order->id_order }})"
                                                class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                                title="Proses ke Pengiriman">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                </svg>
                                            </button>
                                        @endif

                                        @if ($order->delivery && $order->delivery->delivery_status == 'pending_admin_gudang')
                                            <button onclick="markAsReady({{ $order->id_order }})"
                                                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                title="Tandai Ready (Siap Kirim)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p>Tidak ada pesanan yang siap diproses.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="orderDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

            <div
                class="relative bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-xl transition-all border border-slate-100">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="modalOrderNumber">Detail Pesanan</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="modalOrderDate"></p>
                    </div>
                    <button onclick="closeModal()"
                        class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                    <!-- Customer & Sales Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                            <p class="text-sm font-semibold text-slate-900" id="modalCustomerName"></p>
                            <p class="text-[11px] text-slate-500" id="modalCustomerPhone"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sales Penginput
                            </p>
                            <p class="text-sm font-semibold text-slate-900" id="modalSalesName"></p>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Daftar Item</p>
                        <div class="border border-slate-100 rounded-lg overflow-hidden">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        <th class="px-4 py-2.5">Produk</th>
                                        <th class="px-4 py-2.5 text-center">Qty</th>
                                        <th class="px-4 py-2.5 text-right">Harga</th>
                                        <th class="px-4 py-2.5 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="modalItems" class="divide-y divide-slate-100">
                                    <!-- Dynamic content -->
                                </tbody>
                                <tfoot class="bg-slate-50/50">
                                    <tr class="text-slate-600">
                                        <td colspan="3" class="px-4 py-2 text-right">Subtotal</td>
                                        <td class="px-4 py-2 text-right text-slate-900" id="modalSubtotal"></td>
                                    </tr>
                                    <tr class="text-slate-600">
                                        <td colspan="3" class="px-4 py-2 text-right">Diskon</td>
                                        <td class="px-4 py-2 text-right text-rose-600" id="modalDiscount"></td>
                                    </tr>
                                    <tr class="font-bold text-slate-900 bg-slate-50">
                                        <td colspan="3" class="px-4 py-3 text-right">Grand Total</td>
                                        <td class="px-4 py-3 text-right text-brand-blue-dark text-base"
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

    <!-- Driver Selection Modal -->
    <div id="driverModal" class="fixed inset-0 z-[70] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeDriverModal()"></div>

            <div class="relative bg-white rounded-xl w-full max-w-md overflow-hidden shadow-xl transition-all border border-slate-100">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Pilih Driver</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih driver untuk mengantar pesanan ini</p>
                    </div>
                    <button onclick="closeDriverModal()"
                        class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-4">
                    <input type="hidden" id="driverModalOrderId" value="">

                    <!-- Loading State -->
                    <div id="driverLoading" class="flex flex-col items-center justify-center py-8">
                        <svg class="animate-spin h-8 w-8 text-emerald-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm text-slate-500">Memuat daftar driver...</p>
                    </div>

                    <!-- Driver List -->
                    <div id="driverListContainer" class="hidden space-y-2 max-h-[50vh] overflow-y-auto">
                        <div id="driverList" class="space-y-2">
                            <!-- Dynamic driver items -->
                        </div>

                        <!-- Empty State -->
                        <div id="driverEmpty" class="hidden flex flex-col items-center justify-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-sm text-slate-500">Tidak ada driver tersedia.</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button onclick="closeDriverModal()"
                        class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button id="btnConfirmDriver" onclick="confirmDriverSelection()" disabled
                        class="px-4 py-2 text-sm font-medium text-white bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="btnConfirmDriverText">Proses Pengiriman</span>
                        <svg id="btnConfirmDriverSpinner" class="hidden animate-spin h-4 w-4 text-white inline ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function showOrderDetails(order) {
                const modal = document.getElementById('orderDetailModal');

                // Set basic info
                document.getElementById('modalOrderNumber').innerText = `Detail Pesanan #${order.order_number}`;
                document.getElementById('modalOrderDate').innerText = new Date(order.order_date).toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'long', year: 'numeric'
                });
                document.getElementById('modalCustomerName').innerText = order.customer?.customer_name || 'N/A';
                document.getElementById('modalCustomerPhone').innerText = order.customer?.phone || '-';
                document.getElementById('modalSalesName').innerText = order.sales?.name || 'N/A';

                // Populate items
                let itemsHtml = '';
                const details = order.order_detail || [];

                details.forEach(item => {
                    itemsHtml += `
                                                                                                                                    <tr class="hover:bg-slate-50/50">
                                                                                                                                        <td class="px-4 py-3">
                                                                                                                                            <div class="font-medium text-slate-900">${item.product?.product_name || 'Unknown Product'}</div>
                                                                                                                                            ${item.bonus_qty > 0 ? `<span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold uppercase tracking-tighter">Bonus: ${item.bonus_qty}</span>` : ''}
                                                                                                                                        </td>
                                                                                                                                        <td class="px-4 py-3 text-center text-slate-600">${item.qty}</td>
                                                                                                                                        <td class="px-4 py-3 text-right text-slate-600">Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}</td>
                                                                                                                                        <td class="px-4 py-3 text-right font-medium text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(item.total_item_price)}</td>
                                                                                                                                    </tr>
                                                                                                                                `;
                });

                if (details.length === 0) {
                    itemsHtml = '<tr><td colspan="4" class="px-4 py-8 text-center text-slate-400 italic">Tidak ada item.</td></tr>';
                }

                document.getElementById('modalItems').innerHTML = itemsHtml;
                document.getElementById('modalSubtotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(order.subtotal)}`;
                document.getElementById('modalDiscount').innerText = `- Rp ${new Intl.NumberFormat('id-ID').format(order.discount_total || 0)}`;
                document.getElementById('modalGrandTotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(order.grand_total)}`;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                document.getElementById('orderDetailModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            let selectedDriverId = null;

            function openDriverModal(orderId) {
                const modal = document.getElementById('driverModal');
                document.getElementById('driverModalOrderId').value = orderId;
                selectedDriverId = null;
                document.getElementById('btnConfirmDriver').disabled = true;

                // Show loading, hide list
                document.getElementById('driverLoading').classList.remove('hidden');
                document.getElementById('driverListContainer').classList.add('hidden');

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Fetch drivers
                fetch('{{ route("gudang.incorders.drivers") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(drivers => {
                    document.getElementById('driverLoading').classList.add('hidden');
                    document.getElementById('driverListContainer').classList.remove('hidden');

                    const driverList = document.getElementById('driverList');
                    const driverEmpty = document.getElementById('driverEmpty');

                    if (drivers.length === 0) {
                        driverList.classList.add('hidden');
                        driverEmpty.classList.remove('hidden');
                        return;
                    }

                    driverList.classList.remove('hidden');
                    driverEmpty.classList.add('hidden');

                    let html = '';
                    drivers.forEach(driver => {
                        html += `
                            <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-emerald-50/50 hover:border-emerald-300 transition-all driver-option" data-driver-id="${driver.id_user}">
                                <input type="radio" name="driver_selection" value="${driver.id_user}"
                                    class="w-4 h-4 text-emerald-500 border-slate-300 focus:ring-emerald-500"
                                    onchange="selectDriver(${driver.id_user})">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">${driver.name}</p>
                                            <p class="text-[11px] text-slate-400">${driver.email}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        `;
                    });
                    driverList.innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('driverLoading').classList.add('hidden');
                    document.getElementById('driverListContainer').classList.remove('hidden');
                    document.getElementById('driverList').innerHTML = `
                        <div class="text-center py-4 text-rose-500 text-sm">Gagal memuat daftar driver.</div>
                    `;
                });
            }

            function selectDriver(driverId) {
                selectedDriverId = driverId;
                document.getElementById('btnConfirmDriver').disabled = false;

                // Highlight selected
                document.querySelectorAll('.driver-option').forEach(el => {
                    el.classList.remove('border-emerald-400', 'bg-emerald-50');
                });
                const selected = document.querySelector(`.driver-option[data-driver-id="${driverId}"]`);
                if (selected) {
                    selected.classList.add('border-emerald-400', 'bg-emerald-50');
                }
            }

            function closeDriverModal() {
                document.getElementById('driverModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                selectedDriverId = null;
            }

            function confirmDriverSelection() {
                if (!selectedDriverId) return;

                const orderId = document.getElementById('driverModalOrderId').value;
                const btn = document.getElementById('btnConfirmDriver');
                const btnText = document.getElementById('btnConfirmDriverText');
                const btnSpinner = document.getElementById('btnConfirmDriverSpinner');

                btn.disabled = true;
                btnText.textContent = 'Memproses...';
                btnSpinner.classList.remove('hidden');

                fetch(`/admin-gudang/inc-orders/${orderId}/process-delivery`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ driver_id: selectedDriverId })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(json => { throw new Error(json.message || 'Gagal memproses') });
                    }
                    return response.json();
                })
                .then(data => {
                    closeDriverModal();
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btnText.textContent = 'Proses Pengiriman';
                    btnSpinner.classList.add('hidden');
                    Swal.fire({
                        title: 'Gagal!',
                        text: error.message,
                        icon: 'error'
                    });
                });
            }

            function markAsReady(id) {
                Swal.fire({
                    title: 'Tandai Ready?',
                    text: "Status pengiriman akan diubah menjadi Ready (Siap Kirim).",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Ready!',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`/admin-gudang/inc-orders/${id}/ready`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(json => {
                                        throw new Error(json.message || 'Gagal memperbarui status');
                                    });
                                }
                                return response.json();
                            })
                            .catch(error => {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: result.value.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection