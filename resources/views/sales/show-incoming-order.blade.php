@extends('layouts.app')

@section('content')
<div class="p-6" x-data="orderDetailManager()">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('sales.incomingorders.index') }}" 
                class="inline-flex items-center text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Detail Pesanan: {{ $order->order_number }}</h1>
            <p class="text-sm text-slate-500">Sesuaikan bonus dan diskon untuk setiap item produk.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button @click="verifyOrder({{ $order->id_order }})" 
                class="px-6 py-3 bg-brand-blue text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-brand-blue-dark transition-all active:scale-95 shadow-lg shadow-brand-blue/20">
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
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Produk</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Qty</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Bonus Qty</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Diskon / Unit (Rp)</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($order->orderDetail as $detail)
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
                                            <img src="{{ $detail->product->image ? asset('storage/' . $detail->product->image) : 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=100&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-900">{{ $detail->product->product_name }}</div>
                                            <div class="text-[10px] text-slate-400 font-black uppercase tracking-wider italic">{{ $detail->product->brand }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-black text-slate-900 text-sm italic">{{ $detail->qty }}</td>
                                <td class="px-6 py-4">
                                    <input type="number" x-model="bonus" @change="updateItem({{ $detail->id_order_detail }}, bonus, discount)"
                                        class="w-20 px-3 py-2 bg-slate-100 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-blue/50 transition-all shadow-inner">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-400">Rp</span>
                                        <input type="number" x-model="discount" @change="updateItem({{ $detail->id_order_detail }}, bonus, discount)"
                                            class="w-32 px-3 py-2 bg-slate-100 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-blue/50 transition-all shadow-inner">
                                    </div>
                                    <p class="text-[9px] text-slate-400 mt-1 font-medium italic">Modal: Rp {{ number_format($detail->product->purchase_price, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-slate-900 tracking-tight italic">
                                    Rp <span x-text="formatNumber(totalItem)"></span>
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
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Ringkasan Pesanan</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-xs font-medium">
                        <span class="text-slate-500 uppercase tracking-widest font-black">Subtotal</span>
                        <span class="text-slate-900 font-black italic">Rp <span x-text="formatNumber(totals.subtotal)"></span></span>
                    </div>
                    <div class="flex justify-between items-center text-xs font-medium">
                        <span class="text-slate-500 uppercase tracking-widest font-black">Total Diskon</span>
                        <span class="text-rose-500 font-black italic">- Rp <span x-text="formatNumber(totals.discount_total)"></span></span>
                    </div>
                    <div class="flex justify-between items-center text-xs font-medium">
                        <span class="text-slate-500 uppercase tracking-widest font-black">Pajak (11%)</span>
                        <span class="text-slate-900 font-black italic">Rp <span x-text="formatNumber(totals.tax_amount)"></span></span>
                    </div>
                    <div class="h-px bg-slate-100"></div>
                    <div class="flex flex-col gap-2">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Grand Total</span>
                        <div class="text-2xl font-black text-brand-blue tracking-tighter italic">
                            Rp <span x-text="formatNumber(totals.grand_total)"></span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-[9px] font-black uppercase tracking-widest text-slate-400">Pelanggan</div>
                            <div class="text-sm font-bold text-slate-900">{{ $order->customer->customer_name }}</div>
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
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    function orderDetailManager() {
        return {
            totals: {
                subtotal: {{ $order->subtotal }},
                discount_total: {{ $order->discount_total }},
                tax_amount: {{ $order->tax_amount }},
                grand_total: {{ $order->grand_total }},
            },

            async updateItem(id, bonus, discount) {
                try {
                    const response = await fetch(`{{ url('sales/incoming-orders/item') }}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            bonus_qty: bonus,
                            discount: discount
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.totals = data.order_totals;
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
                            title: 'Item diperbarui'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            customClass: { popup: 'rounded-xl' }
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memperbarui item.',
                        customClass: { popup: 'rounded-xl' }
                    });
                }
            },

            async verifyOrder(id) {
                const result = await Swal.fire({
                    title: 'Verifikasi Pesanan?',
                    text: "Apakah semua bonus dan diskon sudah sesuai?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#96CBFC',
                    cancelButtonColor: '#FFC2D9',
                    confirmButtonText: 'Ya, Verifikasi!',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-xl' }
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
                                customClass: { popup: 'rounded-xl' }
                            });
                            window.location.href = "{{ route('sales.incomingorders.index') }}";
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message,
                                customClass: { popup: 'rounded-xl' }
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan sistem.',
                            customClass: { popup: 'rounded-xl' }
                        });
                    }
                }
            },

            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        }
    }
</script>
@endpush