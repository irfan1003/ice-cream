@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pesanan Masuk</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola dan verifikasi pesanan dari sales untuk diproses ke Admin.</p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden custom-shadow">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pesanan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Sales</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-slate-900">#{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-900 font-medium">{{ $order->customer->customer_name ?? 'Guest' }}</div>
                                    <div class="text-xs text-slate-500">{{ $order->customer->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $order->sales->name ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-900">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Show Button -->
                                        <button onclick="showDetails({{ $order->id_order }})"
                                            class="p-1.5 text-slate-400 hover:text-brand-blue-dark hover:bg-brand-blue-light rounded-lg transition-all"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>

                                        <!-- Verify Button -->
                                        <form action="{{ route('koor.orders.verify', $order->id_order) }}" method="POST"
                                            id="verify-form-{{ $order->id_order }}">
                                            @csrf
                                            <button type="button" onclick="confirmVerify({{ $order->id_order }})"
                                                class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                                title="Verifikasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Reject Button -->
                                        <button type="button" 
                                            onclick="handleReject({{ $order->id_order }}, '{{ $order->orderBySales->role ?? 'pelanggan' }}')"
                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                            title="Tolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('koor.orders.reject', $order->id_order) }}" method="POST"
                                            id="reject-form-{{ $order->id_order }}" class="hidden">
                                            @csrf
                                            <input type="hidden" name="rejected_note" id="note-input-{{ $order->id_order }}">
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                        <p>Tidak ada pesanan masuk yang perlu diverifikasi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

            <div class="relative bg-white rounded-2xl w-full max-w-2xl overflow-hidden custom-shadow transition-all">
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

                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                            <p class="text-sm font-semibold text-slate-900" id="modalCustomerName"></p>
                            <p class="text-xs text-slate-500" id="modalCustomerPhone"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sales</p>
                            <p class="text-sm font-semibold text-slate-900" id="modalSalesName"></p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Daftar Item</p>
                        <div class="border border-slate-100 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-2 font-semibold text-slate-600">Produk</th>
                                        <th class="px-4 py-2 font-semibold text-slate-600 text-center">Qty</th>
                                        <th class="px-4 py-2 font-semibold text-slate-600 text-center">Bonus</th>
                                        <th class="px-4 py-2 font-semibold text-slate-600 text-right">Harga</th>
                                        <th class="px-4 py-2 font-semibold text-slate-600 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="modalItems" class="divide-y divide-slate-100"></tbody>
                                <tfoot class="bg-slate-50 font-semibold">
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-right text-slate-600">Subtotal</td>
                                        <td class="px-4 py-2 text-right text-slate-900" id="modalSubtotal"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-right text-slate-600">Potongan/Diskon</td>
                                        <td class="px-4 py-2 text-right text-rose-600" id="modalDiscount"></td>
                                    </tr>
                                    <tr class="bg-brand-blue-light/30">
                                        <td colspan="4" class="px-4 py-3 text-right text-slate-900">Total Akhir</td>
                                        <td class="px-4 py-3 text-right text-brand-blue-dark text-lg" id="modalGrandTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject Note -->
    <div id="rejectModal" class="fixed inset-0 z-[70] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-md overflow-hidden custom-shadow transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Alasan Penolakan</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Berikan alasan mengapa pesanan ini ditolak.</p>
                    </div>
                    <button onclick="closeRejectModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Catatan Penolakan</label>
                    <textarea id="modalRejectedNoteInput" rows="4" placeholder="Contoh: Stok barang tidak mencukupi atau alamat tidak jelas..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all"></textarea>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button onclick="closeRejectModal()" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Batal</button>
                    <button id="confirmRejectBtn" class="px-6 py-2 bg-rose-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-rose-500/20 hover:bg-rose-600 transition-all">Tolak Pesanan</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            @if (session('success'))
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif

            @if (session('error'))
                Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
            @endif

            function confirmVerify(id) {
                Swal.fire({
                    title: 'Verifikasi Pesanan?',
                    text: "Pesanan akan diteruskan ke Admin untuk diproses.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Verifikasi!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`verify-form-${id}`).submit();
                    }
                });
            }

            let currentOrderId = null;

            function handleReject(id, role) {
                if (role === 'sales') {
                    openRejectModal(id);
                } else {
                    confirmRejectDirect(id);
                }
            }

            function openRejectModal(id) {
                currentOrderId = id;
                document.getElementById('rejectModal').classList.remove('hidden');
                document.getElementById('modalRejectedNoteInput').value = '';
                document.body.style.overflow = 'hidden';
            }

            function closeRejectModal() {
                document.getElementById('rejectModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            document.getElementById('confirmRejectBtn').addEventListener('click', function() {
                const note = document.getElementById('modalRejectedNoteInput').value;
                if (!note.trim()) {
                    Swal.fire('Oops!', 'Alasan penolakan wajib diisi.', 'error');
                    return;
                }
                document.getElementById(`note-input-${currentOrderId}`).value = note;
                document.getElementById(`reject-form-${currentOrderId}`).submit();
            });

            function confirmRejectDirect(id) {
                Swal.fire({
                    title: 'Kembalikan ke Sales?',
                    text: "Pesanan yang dibuat pelanggan akan dikembalikan ke Sales untuk verifikasi ulang.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Kembalikan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`reject-form-${id}`).submit();
                    }
                });
            }

            function showDetails(id) {
                const modal = document.getElementById('detailModal');
                const itemsContainer = document.getElementById('modalItems');

                itemsContainer.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-slate-400 italic">Memuat data...</td></tr>';
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                fetch(`/koor-sales/pesanan-masuk/${id}`)
                    .then(response => response.json())
                    .then(order => {
                        document.getElementById('modalOrderNumber').innerText = `Detail Pesanan #${order.order_number}`;
                        document.getElementById('modalOrderDate').innerText = new Date(order.order_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                        document.getElementById('modalCustomerName').innerText = order.customer ? order.customer.customer_name : 'Guest';
                        document.getElementById('modalCustomerPhone').innerText = order.customer ? (order.customer.phone || '-') : '-';
                        document.getElementById('modalSalesName').innerText = order.sales ? order.sales.name : 'Unknown';

                        let itemsHtml = '';
                        const details = order.orderDetail || order.order_detail || [];

                        details.forEach(item => {
                            itemsHtml += `
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-900">${item.product ? item.product.product_name : 'Unknown Product'}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-700">${item.qty}</td>
                                    <td class="px-4 py-3 text-center text-slate-500">${item.bonus_qty || 0}</td>
                                    <td class="px-4 py-3 text-right text-slate-600">Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}</td>
                                    <td class="px-4 py-3 text-right font-medium text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(item.total_item_price)}</td>
                                </tr>
                            `;
                        });

                        itemsContainer.innerHTML = itemsHtml || '<tr><td colspan="5" class="px-4 py-8 text-center text-slate-400 italic">Tidak ada item.</td></tr>';
                        document.getElementById('modalSubtotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(order.subtotal)}`;
                        document.getElementById('modalDiscount').innerText = `- Rp ${new Intl.NumberFormat('id-ID').format(order.discount_total)}`;
                        document.getElementById('modalGrandTotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(order.grand_total)}`;
                    });
            }

            function closeModal() {
                document.getElementById('detailModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        </script>
    @endpush
@endsection
