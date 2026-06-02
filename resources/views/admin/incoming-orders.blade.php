@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pesanan Masuk (Admin)</h1>
                <p class="text-sm text-slate-500 mt-1">Daftar pesanan yang menunggu verifikasi Admin.</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.incorders.index') }}" method="GET" class="relative group">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Pesanan / Pelanggan..."
                    class="w-full md:w-72 pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-brand-blue focus:border-brand-blue outline-none transition-all shadow-sm">
                <div
                    class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand-blue transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>
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
                            <th class="px-6 py-4 text-center">Status</th>
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
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-cyan-100 text-cyan-700">
                                        Menunggu Admin
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="showOrderDetails({{ json_encode($order) }})"
                                            class="p-2 text-slate-400 hover:text-brand-blue hover:bg-brand-blue/10 rounded-lg transition-all"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('admin.incorders.preview', $order->id_order) }}" target="_blank"
                                            class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                            title="Buat Faktur">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>

                                        <!-- <a href="{{ route('admin.incorders.preview', $order->id_order) }}" target="_blank"
                                                   class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                                   title="Lihat Faktur">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                </a> -->

                                        <button onclick="approveOrder({{ $order->id_order }}, '#{{ $order->order_number }}')"
                                            class="p-2 text-slate-400 hover:text-brand-blue-dark hover:bg-brand-blue/10 rounded-lg transition-all"
                                            title="Verifikasi & Teruskan ke Direktur">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p>Tidak ada pesanan masuk.</p>
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function approveOrder(orderId, orderNumber) {
                Swal.fire({
                    title: 'Verifikasi Pesanan?',
                    text: `Pesanan ${orderNumber} akan diverifikasi dan diteruskan ke Direktur untuk persetujuan akhir.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0F172A',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Verifikasi!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('/admin/orders') }}/${orderId}/approve`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: data.message,
                                        confirmButtonColor: '#0F172A',
                                        customClass: { popup: 'rounded-2xl' }
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.message,
                                        customClass: { popup: 'rounded-2xl' }
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan sistem saat memproses verifikasi.',
                                    customClass: { popup: 'rounded-2xl' }
                                });
                            });
                    }
                });
            }

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
        </script>
    @endpush
@endsection