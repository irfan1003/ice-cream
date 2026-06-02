@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Verifikasi P.O (Direktur)</h1>
                <p class="text-sm text-slate-500 mt-1">Purchase Orders yang menunggu persetujuan akhir Anda.</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Export All Button -->
                <a href="{{ route('direktur.po.export', ['status' => 'pending_director']) }}"
                    class="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm shadow-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Semua
                </a>

                <!-- Search Form -->
                <form action="{{ route('direktur.verificationpo.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. PO / Pelanggan..."
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
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">No. PO</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4 text-right">Total</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($purchaseOrders as $po)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">#{{ $po->po_number }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $po->customer->customer_name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $po->customer->zone->zone_name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-900">
                                    Rp {{ number_format($po->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">
                                        Menunggu Direktur
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <!-- Show Modal -->
                                        <button onclick="showPODetails({{ json_encode($po) }})"
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

                                        <!-- Export Single PO -->
                                        <a href="{{ route('direktur.po.export-single', $po->id_po) }}"
                                            class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                            title="Ekspor Excel">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>

                                        <!-- Reject Button -->
                                        <button onclick="rejectPO({{ $po->id_po }}, '#{{ $po->po_number }}')"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                            title="Tolak & Revisi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>

                                        <!-- Approve Button -->
                                        <button onclick="approvePO({{ $po->id_po }}, '#{{ $po->po_number }}')"
                                            class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                            title="Setujui P.O">
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
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p>Tidak ada Purchase Order yang menunggu persetujuan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($purchaseOrders->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $purchaseOrders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="poDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            <div
                class="relative bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-xl transition-all border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="modalPONumber">Detail Purchase Order</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="modalPODate"></p>
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
                <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                            <p class="text-sm font-semibold text-slate-900" id="modalCustomerName"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sales Penginput
                            </p>
                            <p class="text-sm font-semibold text-slate-900" id="modalSalesName"></p>
                        </div>
                    </div>
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
                                <tbody id="modalItems" class="divide-y divide-slate-100"></tbody>
                                <tfoot class="bg-slate-50/50">
                                    <tr class="font-bold text-slate-900 bg-slate-50">
                                        <td colspan="3" class="px-4 py-3 text-right text-base">Grand Total</td>
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
            function approvePO(id, number) {
                Swal.fire({
                    title: 'Setujui P.O?',
                    text: `P.O ${number} akan disetujui secara permanen.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        processAction(`{{ url('/direktur/verification-po') }}/${id}/approve`);
                    }
                });
            }

            function rejectPO(id, number) {
                Swal.fire({
                    title: 'Tolak P.O?',
                    text: `P.O ${number} akan dikembalikan untuk direvisi.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#E11D48',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        processAction(`{{ url('/direktur/verification-po') }}/${id}/reject`);
                    }
                });
            }

            function processAction(url) {
                fetch(url, {
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
                                confirmButtonColor: '#0F172A'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                        }
                    });
            }

            function showPODetails(po) {
                const modal = document.getElementById('poDetailModal');
                document.getElementById('modalPONumber').innerText = `Detail P.O #${po.po_number}`;
                document.getElementById('modalPODate').innerText = new Date(po.po_date).toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'long', year: 'numeric'
                });
                document.getElementById('modalCustomerName').innerText = po.customer?.customer_name || 'N/A';
                document.getElementById('modalSalesName').innerText = po.sales?.name || 'N/A';

                let itemsHtml = '';
                po.details.forEach(item => {
                    itemsHtml += `
                                                <tr class="hover:bg-slate-50/50">
                                                    <td class="px-4 py-3">
                                                        <div class="font-medium text-slate-900">${item.product?.product_name || 'N/A'}</div>
                                                        ${item.bonus_qty > 0 ? `<span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold uppercase tracking-tighter">Bonus: ${item.bonus_qty}</span>` : ''}
                                                    </td>
                                                    <td class="px-4 py-3 text-center text-slate-600">${item.qty}</td>
                                                    <td class="px-4 py-3 text-right text-slate-600">Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}</td>
                                                    <td class="px-4 py-3 text-right font-medium text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(item.total_item_price)}</td>
                                                </tr>
                                            `;
                });
                document.getElementById('modalItems').innerHTML = itemsHtml;
                document.getElementById('modalGrandTotal').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(po.grand_total)}`;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                document.getElementById('poDetailModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        </script>
    @endpush
@endsection