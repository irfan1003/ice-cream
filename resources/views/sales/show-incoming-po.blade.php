@extends('layouts.app')

@section('content')
    <div class="p-6" x-data="poManager()">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('sales.incomingpo.index') }}"
                    class="inline-flex items-center text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Detail PO: {{ $po->po_number }}</h1>
                    <span
                        class="px-2 py-0.5 bg-amber-100 text-amber-600 rounded text-[9px] font-black uppercase tracking-widest">
                        {{ str_replace('_', ' ', $po->status) }}
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-1">Sesuaikan bonus dan diskon untuk setiap item produk.</p>
            </div>

            <div class="flex items-center gap-3">
                <button @click="confirmReject()" 
                    class="h-10 px-6 flex items-center justify-center bg-rose-50 text-rose-500 border border-rose-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all active:scale-95 shadow-sm shadow-rose-500/5">
                    Tolak Pesanan
                </button>
                <button @click="confirmVerify()" 
                    class="h-10 px-8 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all active:scale-95 shadow-sm shadow-emerald-500/5">
                    Verifikasi & Teruskan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content: Item List -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Produk</th>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                        Qty</th>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                        Bonus Qty</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Diskon / Unit (Rp)</th>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($po->details as $detail)
                                    <tr class="group hover:bg-slate-50/30 transition-colors" x-data="{ 
                                        bonus: {{ $detail->bonus_qty }}, 
                                        discount: {{ $detail->discount }},
                                        price: {{ $detail->product->purchase_price }},
                                        qty: {{ $detail->qty }},
                                        get totalItem() { return (this.price - this.discount) * this.qty }
                                    }">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                                                    <img src="{{ $detail->product->image ? asset($detail->product->image) : 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=100&auto=format&fit=crop' }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">
                                                        {{ $detail->product->product_name }}</div>
                                                    <div
                                                        class="text-[10px] text-slate-400 font-black uppercase tracking-wider italic">
                                                        {{ $detail->product->brand }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-black text-slate-900 text-sm italic">
                                            {{ $detail->qty }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="number" x-model="bonus"
                                                @change="updateItem({{ $detail->id_po_detail }}, bonus, discount)"
                                                class="w-20 px-3 py-2 bg-slate-100 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner text-center">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold text-slate-400">Rp</span>
                                                <input type="number" x-model="discount"
                                                    @change="updateItem({{ $detail->id_po_detail }}, bonus, discount)"
                                                    class="w-32 px-3 py-2 bg-slate-100 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                                            </div>
                                            <p class="text-[9px] text-slate-400 mt-1 font-medium italic">Modal: Rp
                                                {{ number_format($detail->product->purchase_price, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex flex-col items-end">
                                                <span class="font-black text-slate-900 tracking-tight italic">Rp <span
                                                        x-text="formatNumber(totalItem)"></span></span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase">@ Rp <span
                                                        x-text="formatNumber(price - discount)"></span></span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Order Summary -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 space-y-6">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Ringkasan Pembayaran</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-slate-500 uppercase tracking-widest font-black">Subtotal</span>
                            <span class="text-slate-900 font-black italic" id="summary-subtotal">Rp
                                {{ number_format($po->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-slate-500 uppercase tracking-widest font-black">Total Diskon</span>
                            <span class="text-rose-500 font-black italic" id="summary-discount">- Rp
                                {{ number_format($po->discount_total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-slate-500 uppercase tracking-widest font-black">Pajak (11%)</span>
                            <span class="text-slate-900 font-black italic" id="summary-tax">Rp
                                {{ number_format($po->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-px bg-slate-100"></div>
                        <div class="flex flex-col gap-2">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Grand
                                Total</span>
                            <div class="text-2xl font-black text-emerald-600 tracking-tighter italic"
                                id="summary-grand-total">
                                Rp {{ number_format($po->grand_total, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400">Pelanggan</div>
                                <div class="text-sm font-bold text-slate-900">{{ $po->customer->customer_name }}</div>
                                <div class="text-[10px] text-slate-400 italic">
                                    {{ $po->customer->store_name ?? 'Toko Retail' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <div id="rejectModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeRejectModal()"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-md p-8 shadow-2xl border border-slate-100">
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-2">Tolak Purchase Order</h3>
                <p class="text-slate-500 text-sm mb-6">Berikan alasan penolakan agar pelanggan dapat melakukan revisi.</p>

                <form action="{{ route('sales.incomingpo.reject', $po->id_po) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Catatan
                            Penolakan</label>
                        <textarea name="rejected_note" rows="4" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-rose-500 focus:bg-white rounded-xl text-sm transition-all outline-none resize-none font-medium"
                            placeholder="Contoh: Stok tidak mencukupi..."></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="closeRejectModal()"
                            class="flex-1 px-6 py-3 bg-slate-50 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-rose-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-100">
                            Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <form id="verifyForm" action="{{ route('sales.incomingpo.verify', $po->id_po) }}" method="POST" class="hidden">@csrf
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function poManager() {
            return {
                isProcessing: false,

                updateItem(detailId, bonusQty, discount) {
                    this.isProcessing = true;
                    const url = `{{ route('sales.incomingpo.item.update', ':id') }}`.replace(':id', detailId);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            bonus_qty: bonusQty,
                            discount: discount
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const format = (num) => new Intl.NumberFormat('id-ID').format(num);

                                // Summary updates
                                document.getElementById('summary-subtotal').innerText = `Rp ${format(data.new_subtotal)}`;
                                document.getElementById('summary-tax').innerText = `Rp ${format(data.new_tax)}`;
                                document.getElementById('summary-discount').innerText = `- Rp ${format(data.new_discount_total || 0)}`; // Assuming backend sends this now or we calculate
                                document.getElementById('summary-grand-total').innerText = `Rp ${format(data.new_grand_total)}`;

                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    customClass: { popup: 'rounded-xl' }
                                });
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Data diperbarui'
                                });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Error', text: data.message, customClass: { popup: 'rounded-xl' } });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem', customClass: { popup: 'rounded-xl' } });
                        })
                        .finally(() => {
                            this.isProcessing = false;
                        });
                },

                confirmVerify() {
                    Swal.fire({
                        title: 'Verifikasi Purchase Order?',
                        text: "Data akan diteruskan ke koordinator sales.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#059669',
                        cancelButtonColor: '#f43f5e',
                        confirmButtonText: 'Ya, Verifikasi!',
                        cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-2xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('verifyForm').submit();
                        }
                    });
                },

                confirmReject() {
                    document.getElementById('rejectModal').classList.remove('hidden');
                    document.getElementById('rejectModal').classList.add('flex');
                    document.body.style.overflow = 'hidden';
                },

                closeRejectModal() {
                    document.getElementById('rejectModal').classList.add('hidden');
                    document.getElementById('rejectModal').classList.remove('flex');
                    document.body.style.overflow = 'auto';
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                }
            }
        }
    </script>
@endpush