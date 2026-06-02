@extends('layouts.app')

@section('content')
    <div x-data="salesCartSystem()"
        class="relative flex flex-col lg:flex-row h-[calc(100vh-120px)] bg-slate-50/50 rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
        
        <!-- Product Section -->
        <div class="flex-1 flex flex-col min-w-0 bg-white">
            <!-- Header: Search & Filters -->
            <div class="p-6 border-b border-slate-100 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Katalog Produk (Sales)</h1>
                        <p class="text-sm text-slate-500">Pilih produk untuk membuat pesanan pelanggan.</p>
                    </div>
                    <div class="relative w-full md:w-80 group">
                        <input type="text" placeholder="Cari produk..." x-model="searchQuery"
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-100 border-none rounded-2xl text-sm focus:ring-2 focus:ring-brand-blue/50 transition-all group-hover:bg-slate-200/50">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand-blue transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Category/Brand Pills -->
                <div class="flex items-center gap-3 overflow-x-auto scrollbar-hide -mx-2 px-2 py-1">
                    <button @click="brandFilter = 'all'"
                        :class="brandFilter === 'all' ? 'bg-slate-900 text-white shadow-slate-900/10' : 'bg-white border-slate-200 text-slate-600'"
                        class="px-5 py-2 rounded-xl text-xs font-bold whitespace-nowrap shadow-lg transition-transform active:scale-95 border">
                        Semua Menu
                    </button>
                    <template x-for="brand in uniqueBrands">
                        <button @click="brandFilter = brand"
                            :class="brandFilter === brand ? 'bg-brand-blue text-white shadow-brand-blue/10 border-brand-blue' : 'bg-white border-slate-200 text-slate-600'"
                            class="px-5 py-2 border rounded-xl text-xs font-bold whitespace-nowrap hover:bg-slate-50 transition-all active:scale-95"
                            x-text="brand"></button>
                    </template>
                </div>
            </div>

            <!-- Scrollable Grid -->
            <div class="flex-1 overflow-y-auto p-6 bg-slate-50/30 scrollbar-hide">
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-5 gap-6">
                    <template x-for="product in filteredProducts" :key="product.id_product">
                        <div @click="addToCart(product)"
                            class="group relative bg-white rounded-[2rem] p-4 border border-slate-100 hover:border-brand-pink transition-all duration-300 hover:shadow-2xl hover:shadow-brand-pink/10 hover:-translate-y-1 cursor-pointer">
                            
                            <!-- Image Container -->
                            <div class="relative aspect-square rounded-[1.5rem] bg-slate-100 overflow-hidden mb-4 shadow-inner">
                                <img :src="product.image ? '{{ asset('/') }}' + product.image : 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=400&auto=format&fit=crop'"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute top-3 left-3 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg shadow-lg shadow-black/10">
                                    Stok: <span x-text="product.current_stock"></span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" x-text="product.brand"></p>
                                <h3 class="text-sm font-extrabold text-slate-900 line-clamp-1 group-hover:text-brand-pink-dark transition-colors"
                                    x-text="product.product_name"></h3>

                                <div class="flex items-center justify-between pt-2">
                                    <span class="text-lg font-black text-slate-900 tracking-tight">
                                        <span class="text-xs font-medium text-slate-400 mr-0.5">Rp</span><span
                                            x-text="formatNumber(product.purchase_price)"></span>
                                    </span>
                                    <button @click.stop="addToCart(product)"
                                        class="w-10 h-10 rounded-2xl bg-brand-pink text-white flex items-center justify-center shadow-lg shadow-brand-pink/30 transition-all hover:scale-110 active:scale-90 group/btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="w-full lg:w-[400px] bg-white border-t lg:border-t-0 lg:border-l border-slate-100 flex flex-col relative z-10">
            <!-- Cart Header -->
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">Daftar Pesanan</h2>
                    <p class="text-xs text-slate-500 font-medium"><span x-text="cart.length"></span> Item terpilih</p>
                </div>
                <button @click="clearCart()" class="p-2.5 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto px-6 space-y-4 scrollbar-hide">
                <template x-for="item in cart" :key="item.id_product">
                    <div class="flex items-center gap-4 group">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 overflow-hidden flex-shrink-0 border border-slate-100">
                            <img :src="item.image ? '{{ asset('/') }}' + item.image : 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=400&auto=format&fit=crop'"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-900 truncate" x-text="item.product_name"></h4>
                            <p class="text-xs font-medium text-slate-400">Rp <span x-text="formatNumber(item.purchase_price)"></span></p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <div class="flex items-center bg-slate-100 rounded-xl p-1">
                                <button @click="decrementQty(item.id_product)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-900">-</button>
                                <span class="text-sm font-black text-slate-900 px-3" x-text="item.qty"></span>
                                <button @click="incrementQty(item.id_product)" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm text-brand-blue font-black">+</button>
                            </div>
                            <span class="text-xs font-bold text-slate-900">Rp <span x-text="formatNumber(item.purchase_price * item.qty)"></span></span>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full opacity-30 py-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-sm font-bold">Keranjang Kosong</p>
                </div>
            </div>

            <!-- Summary & Checkout -->
            <div class="p-6 bg-slate-50/50 space-y-6">
                <div class="flex justify-between items-center">
                    <span class="text-base font-black text-slate-900 uppercase tracking-wider">Total Est.</span>
                    <span class="text-2xl font-black text-brand-pink-dark tracking-tighter italic">Rp <span x-text="formatNumber(subtotal)"></span></span>
                </div>

                <button @click="openFinalModal()" :disabled="cart.length === 0"
                    class="w-full relative group disabled:opacity-50">
                    <div class="absolute -inset-1 bg-gradient-to-r from-brand-pink to-brand-blue rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                    <div class="relative flex items-center justify-center px-6 py-4 bg-slate-900 text-white rounded-2xl transition-transform hover:scale-[1.02] active:scale-95">
                        <span class="font-black text-sm uppercase tracking-widest">Pesan Sekarang</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Finalize Order Modal -->
        <div x-show="showFinalModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" 
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            style="display: none;">

            <div @click.away="showFinalModal = false"
                class="bg-white w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 flex flex-col max-h-[90vh]">
                
                <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="text-xl font-black text-slate-900">Finalisasi Pesanan</h3>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi detail bonus dan diskon untuk pelanggan.</p>
                </div>

                <div class="flex-1 overflow-y-auto p-8 space-y-8">
                    <!-- Customer Selection -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih Pelanggan</label>
                        <select x-model="selectedCustomerId" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl text-sm font-bold focus:border-brand-blue outline-none transition-all">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id_customer }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Items Detail List -->
                    <div class="space-y-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Detail Item</p>
                        <template x-for="(item, index) in cart" :key="item.id_product">
                            <div class="p-5 bg-slate-50 rounded-3xl border border-slate-100 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 overflow-hidden">
                                            <img :src="item.image ? '{{ asset('/') }}' + item.image : 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=400&auto=format&fit=crop'" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-extrabold text-slate-900" x-text="item.product_name"></h4>
                                            <p class="text-[10px] font-bold text-slate-400" x-text="'Harga: Rp ' + formatNumber(item.purchase_price)"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Item</p>
                                        <p class="text-sm font-black text-brand-blue" x-text="'Rp ' + formatNumber((item.purchase_price - (item.discount || 0)) * item.qty)"></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Qty</label>
                                        <input type="number" x-model.number="item.qty" @input="validateStock(item)"
                                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-brand-blue">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bonus Qty</label>
                                        <input type="number" x-model.number="item.bonus_qty"
                                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-brand-blue">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Diskon / Item</label>
                                        <input type="number" x-model.number="item.discount"
                                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-brand-blue">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer Summary -->
                <div class="p-8 bg-slate-900 text-white space-y-6">
                    <div class="flex justify-between items-center">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Grand Total Akhir</p>
                            <h2 class="text-3xl font-black tracking-tighter text-brand-blue">Rp <span x-text="formatNumber(finalGrandTotal)"></span></h2>
                        </div>
                        <div class="flex gap-3">
                            <button @click="showFinalModal = false" class="px-6 py-3 bg-white/10 hover:bg-white/20 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-colors">
                                Batal
                            </button>
                            <button @click="submitOrder()" :disabled="!selectedCustomerId || loading" class="px-8 py-3 bg-brand-blue text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-brand-blue/20 disabled:opacity-50">
                                <span x-text="loading ? 'Memproses...' : 'Kirim Pesanan'"></span>
                            </button>
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
        function salesCartSystem() {
            return {
                products: @json($products),
                cart: [],
                searchQuery: '',
                brandFilter: 'all',
                loading: false,
                showFinalModal: false,
                selectedCustomerId: '',

                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchesSearch = p.product_name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesBrand = this.brandFilter === 'all' || p.brand === this.brandFilter;
                        return matchesSearch && matchesBrand;
                    });
                },

                get uniqueBrands() {
                    return [...new Set(this.products.map(p => p.brand))];
                },

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.purchase_price * item.qty), 0);
                },

                get finalGrandTotal() {
                    const discountedSubtotal = this.cart.reduce((sum, item) => {
                        return sum + ((item.purchase_price - (item.discount || 0)) * item.qty);
                    }, 0);
                    const tax = discountedSubtotal * 0.11;
                    return discountedSubtotal + tax;
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id_product === product.id_product);
                    if (!existingItem) {
                        this.cart.push({
                            ...product,
                            qty: 1,
                            bonus_qty: 0,
                            discount: 0
                        });
                    }
                },

                incrementQty(productId) {
                    const item = this.cart.find(i => i.id_product === productId);
                    if (item) {
                        if (item.qty < item.current_stock) {
                            item.qty++;
                        } else {
                            this.toast('Stok Terbatas', 'warning');
                        }
                    }
                },

                decrementQty(productId) {
                    const itemIndex = this.cart.findIndex(i => i.id_product === productId);
                    if (itemIndex > -1) {
                        if (this.cart[itemIndex].qty > 1) {
                            this.cart[itemIndex].qty--;
                        } else {
                            this.cart.splice(itemIndex, 1);
                        }
                    }
                },

                validateStock(item) {
                    if (item.qty > item.current_stock) {
                        item.qty = item.current_stock;
                        this.toast('Stok tidak mencukupi', 'warning');
                    }
                    if (item.qty < 1) item.qty = 1;
                },

                clearCart() {
                    this.cart = [];
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(Math.max(0, num));
                },

                openFinalModal() {
                    this.showFinalModal = true;
                },

                toast(title, icon) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    Toast.fire({ icon, title });
                },

                async submitOrder() {
                    if (!this.selectedCustomerId) return;

                    const result = await Swal.fire({
                        title: 'Konfirmasi Pesanan',
                        text: "Kirim pesanan ini ke koordinator?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0F172A',
                        cancelButtonColor: '#94A3B8',
                        confirmButtonText: 'Ya, Kirim!',
                        cancelButtonText: 'Batal'
                    });

                    if (result.isConfirmed) {
                        this.loading = true;
                        try {
                            const response = await fetch("{{ route('sales.order.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    customer_id: this.selectedCustomerId,
                                    items: this.cart.map(item => ({
                                        id: item.id_product,
                                        qty: item.qty,
                                        bonus_qty: item.bonus_qty,
                                        discount: item.discount
                                    }))
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message + ' Nomor: ' + data.order_number,
                                    confirmButtonColor: '#0F172A'
                                });
                                window.location.reload();
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                            }
                        } catch (error) {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Kesalahan server.' });
                        } finally {
                            this.loading = false;
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
@endpush