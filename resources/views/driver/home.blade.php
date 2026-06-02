@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tugas Pengiriman</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola dan perbarui status pengiriman barang Anda.</p>
            </div>
        </div>

        <!-- Deliveries Table -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. SPB</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</th>
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
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ $delivery->order->customer->customer_name }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 max-w-xs truncate">
                                    {{ $delivery->order->customer->address }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusConfig = [
                                            'ready' => ['label' => 'Siap Kirim', 'class' => 'bg-indigo-100 text-indigo-700'],
                                            'shipped' => ['label' => 'Dalam Perjalanan', 'class' => 'bg-amber-100 text-amber-700'],
                                            'completed' => ['label' => 'Diterima', 'class' => 'bg-emerald-100 text-emerald-700'],
                                        ];
                                        $status = $statusConfig[$delivery->delivery_status] ?? ['label' => $delivery->delivery_status, 'class' => 'bg-slate-100 text-slate-700'];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Cetak Surat Jalan -->
                                        <button onclick="generateSpb({{ $delivery->id_deliver }})" 
                                                class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                                title="Cetak Surat Jalan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </button>

                                        <!-- View Detail -->
                                        <button onclick="showDetail({{ json_encode($delivery) }})" 
                                                class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>

                                        @if($delivery->delivery_status !== 'completed')
                                            <!-- Update Status Dropdown/Button -->
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open" 
                                                        class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                                        title="Perbarui Status">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                </button>
                                                <div x-show="open" @click.away="open = false" 
                                                    class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden"
                                                    style="display: none;">
                                                    @if($delivery->delivery_status === 'ready')
                                                        <button onclick="updateStatus({{ $delivery->id_deliver }}, 'shipped')" 
                                                                class="w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700 flex items-center gap-2">
                                                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                                            Set as Shipped
                                                        </button>
                                                    @endif
                                                    <button onclick="updateStatus({{ $delivery->id_deliver }}, 'delivered')" 
                                                            class="w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 flex items-center gap-2">
                                                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                                        Set as Delivered
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-slate-400">Tidak ada tugas pengiriman aktif.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            
            <div class="relative bg-white rounded-xl w-full max-w-lg overflow-hidden shadow-xl transition-all">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="modalSpb">Detail Pengiriman</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="modalOrder"></p>
                    </div>
                    <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelanggan</p>
                        <p class="text-sm font-semibold text-slate-900" id="modalCustomer"></p>
                        <p class="text-xs text-slate-500 leading-relaxed" id="modalAddress"></p>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Item Barang</p>
                        <div class="space-y-2" id="modalItems">
                            <!-- Dynamic -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showDetail(delivery) {
            const modal = document.getElementById('detailModal');
            document.getElementById('modalSpb').innerText = `SPB: ${delivery.spb_number}`;
            document.getElementById('modalOrder').innerText = `Pesanan #${delivery.order.order_number}`;
            document.getElementById('modalCustomer').innerText = delivery.order.customer.customer_name;
            document.getElementById('modalAddress').innerText = delivery.order.customer.address;

            let itemsHtml = '';
            delivery.order.order_detail.forEach(item => {
                itemsHtml += `
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-medium text-slate-700">${item.product.product_name}</div>
                        <div class="text-sm font-bold text-slate-900">${item.qty} Pcs</div>
                    </div>
                `;
            });
            document.getElementById('modalItems').innerHTML = itemsHtml;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function updateStatus(id, status) {
            const statusLabel = status === 'shipped' ? 'Dikirim' : 'Diterima';
            
            Swal.fire({
                title: `Update ke ${statusLabel}?`,
                text: `Status pesanan akan diubah menjadi ${statusLabel}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: status === 'delivered' ? '#10b981' : '#f59e0b',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/driver/delivery/${id}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: status })
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
        function generateSpb(id) {
            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Harap tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/driver/delivery/${id}/spb`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.close();
                    window.open(data.pdf_url, '_blank');
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
            });
        }
    </script>
    @endpush
@endsection