@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pesanan Masuk</h1>
            <p class="text-sm text-slate-500">Daftar pesanan dari pelanggan yang perlu Anda tinjau.</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-600 rounded-xl border border-amber-100 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-widest">Menunggu Tinjauan</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Order #</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Pelanggan</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Grand Total</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $order->customer->customer_name }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">{{ $order->customer->phone }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $order->order_date }}</td>
                        <td class="px-6 py-4">
                            <span class="font-black text-slate-900 tracking-tight text-sm">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-600">
                                Pending Sales
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('sales.incomingorders.show', $order->id_order) }}" 
                                    class="w-9 h-9 flex items-center justify-center bg-slate-50 text-slate-600 rounded-lg border border-slate-100 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all active:scale-95 group/btn"
                                    title="Detail Pesanan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <button onclick="verifyOrder({{ $order->id_order }})" 
                                    class="w-9 h-9 flex items-center justify-center bg-blue-50 text-brand-blue rounded-lg border border-blue-100 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all active:scale-95 shadow-sm shadow-blue-500/10 group/btn"
                                    title="Verifikasi Pesanan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                <button onclick="rejectOrder({{ $order->id_order }})" 
                                    class="w-9 h-9 flex items-center justify-center bg-rose-50 text-rose-500 rounded-lg border border-rose-100 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all active:scale-95 shadow-sm shadow-rose-500/10 group/btn"
                                    title="Tolak Pesanan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="font-bold">Tidak ada pesanan masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
            
            <div class="relative bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-xl transition-all border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Alasan Penolakan</h3>
                    <button onclick="closeRejectModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <input type="hidden" id="rejectOrderId">
                    <div class="space-y-4">
                        <div>
                            <label for="rejected_note" class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Catatan Penolakan</label>
                            <textarea id="rejected_note" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 transition-all outline-none text-sm" placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="closeRejectModal()" class="flex-1 px-4 py-3 rounded-xl bg-slate-50 text-slate-600 font-bold text-sm hover:bg-slate-100 transition-all">
                                Batal
                            </button>
                            <button onclick="confirmRejectOrder()" class="flex-1 px-4 py-3 rounded-xl bg-rose-500 text-white font-bold text-sm hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20">
                                Simpan & Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function verifyOrder(id) {
        const result = await Swal.fire({
            title: 'Verifikasi Pesanan?',
            text: "Pesanan ini akan diteruskan ke Koordinator Sales.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#96CBFC',
            cancelButtonColor: '#FFC2D9',
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl'
            }
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`{{ url('sales/incoming-orders') }}/${id}/verify`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        confirmButtonColor: '#96CBFC',
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        customClass: { popup: 'rounded-2xl' }
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan sistem.',
                    customClass: { popup: 'rounded-2xl' }
                });
            }
        }
    }

    function rejectOrder(id) {
        document.getElementById('rejectOrderId').value = id;
        document.getElementById('rejected_note').value = '';
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    async function confirmRejectOrder() {
        const id = document.getElementById('rejectOrderId').value;
        const note = document.getElementById('rejected_note').value;

        if (!note.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan isi catatan penolakan terlebih dahulu.',
                customClass: { popup: 'rounded-2xl' }
            });
            return;
        }

        const result = await Swal.fire({
            title: 'Tolak Pesanan?',
            text: "Pesanan ini akan ditandai sebagai ditolak (rejected).",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F43F5E',
            cancelButtonColor: '#CBD5E1',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl'
            }
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`{{ url('sales/incoming-orders') }}/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        rejected_note: note
                    })
                });
                const data = await response.json();
                
                if (data.success) {
                    closeRejectModal();
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        confirmButtonColor: '#96CBFC',
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        customClass: { popup: 'rounded-2xl' }
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan sistem.',
                    customClass: { popup: 'rounded-2xl' }
                });
            }
        }
    }
</script>
@endpush

