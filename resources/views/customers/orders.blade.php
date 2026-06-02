@extends('layouts.app')

@section('content')
    <div x-data="cartSystem()"
        class="relative flex flex-col lg:flex-row h-[calc(100vh-120px)] bg-slate-50/50 rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
        <!-- Product Section -->
        <div class="flex-1 flex flex-col min-w-0 bg-white">
            <!-- Header: Search & Filters -->
            <div class="p-6 border-b border-slate-100 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Katalog Es Krim</h1>
                        <p class="text-sm text-slate-500">Pilih es krim favoritmu dan nikmati kesegarannya!</p>
                    </div>
                    <div class="relative w-full md:w-80 group">
                        <input type="text" placeholder="Cari es krim..." x-model="searchQuery"
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
                        <div @click="addToCart(product)" @dblclick="incrementQty(product.id_product)"
                            class="group relative bg-white rounded-[2rem] p-4 border border-slate-100 hover:border-brand-pink transition-all duration-300 hover:shadow-2xl hover:shadow-brand-pink/10 hover:-translate-y-1 cursor-pointer">
                            <!-- Image Container -->
                            <div
                                class="relative aspect-square rounded-[1.5rem] bg-slate-100 overflow-hidden mb-4 shadow-inner">
                                <img :src="product.image ? '{{ asset('/') }}' + product.image : 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=400&auto=format&fit=crop'"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute top-3 left-3 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg shadow-lg shadow-black/10"
                                    x-show="product.current_stock > 0">
                                    Ready: <span x-text="product.current_stock"></span>
                                </div>
                                <div class="absolute top-3 left-3 bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg shadow-lg shadow-black/10"
                                    x-show="product.current_stock <= 0">
                                    Out of Stock
                                </div>
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                        x-text="product.brand"></p>
                                    <div class="flex gap-0.5">
                                        <template x-for="i in 5">
                                            <svg class="w-2.5 h-2.5 text-amber-400 fill-current" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </template>
                                    </div>
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 line-clamp-1 group-hover:text-brand-pink-dark transition-colors"
                                    x-text="product.product_name"></h3>

                                <div class="flex items-center justify-between pt-2">
                                    <span class="text-lg font-black text-slate-900 tracking-tight">
                                        <span class="text-xs font-medium text-slate-400 mr-0.5">Rp</span><span
                                            x-text="formatNumber(product.purchase_price)"></span>
                                    </span>
                                    <button @click.stop="addToCart(product)"
                                        class="w-10 h-10 rounded-2xl bg-brand-pink text-white flex items-center justify-center shadow-lg shadow-brand-pink/30 transition-all hover:scale-110 active:scale-90 group/btn">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 group-hover/btn:rotate-90 transition-transform" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M12 4v16m8-8H4" />
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
        <div id="cartPanel"
            class="w-full lg:w-[400px] bg-white border-t lg:border-t-0 lg:border-l border-slate-100 flex flex-col relative z-10 shadow-2xl lg:shadow-none">
            <!-- Cart Header -->
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">Pesanan Saya</h2>
                    <p class="text-xs text-slate-500 font-medium"><span x-text="cart.length"></span> Items selected</p>
                </div>
                <button @click="clearCart()"
                    class="p-2.5 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto px-6 space-y-4 scrollbar-hide">
                <template x-for="item in cart" :key="item.id_product">
                    <div class="flex items-center gap-4 group">
                        <div
                            class="w-16 h-16 rounded-2xl bg-slate-50 overflow-hidden flex-shrink-0 border border-slate-100 group-hover:border-brand-blue/30 transition-colors">
                            <img :src="item.image ?  '{{ asset('/') }}' + item.image : 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=400&auto=format&fit=crop'"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-900 truncate" x-text="item.product_name"></h4>
                            <p class="text-xs font-medium text-slate-400">Rp <span
                                    x-text="formatNumber(item.purchase_price)"></span></p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <div class="flex items-center bg-slate-100 rounded-xl p-1 shadow-inner">
                                <button @click="decrementQty(item.id_product)"
                                    class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors">-</button>
                                <span class="text-sm font-black text-slate-900 px-3" x-text="item.qty"></span>
                                <button @click="incrementQty(item.id_product)"
                                    class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm text-brand-blue font-black hover:bg-brand-blue hover:text-white transition-all">+</button>
                            </div>
                            <span class="text-xs font-bold text-slate-900">Rp <span
                                    x-text="formatNumber(item.purchase_price * item.qty)"></span></span>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full opacity-30 py-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-sm font-bold">Keranjang Kosong</p>
                </div>
            </div>

            <!-- Summary & Checkout -->
            <div class="p-6 bg-slate-50/50 space-y-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Subtotal</span>
                        <span class="text-slate-900 font-bold tracking-tight">Rp <span
                                x-text="formatNumber(subtotal)"></span></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Pajak (11%)</span>
                        <span class="text-slate-900 font-bold tracking-tight">Rp <span
                                x-text="formatNumber(tax)"></span></span>
                    </div>
                    <div class="h-px bg-slate-200/50 w-full"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-base font-black text-slate-900 uppercase tracking-wider">Total</span>
                        <span class="text-2xl font-black text-brand-pink-dark tracking-tighter italic">Rp <span
                                x-text="formatNumber(grandTotal)"></span></span>
                    </div>
                </div>

                <button @click="showSalesModal = true" :disabled="cart.length === 0 || loading"
                    class="w-full relative group disabled:opacity-50">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-brand-pink to-brand-blue rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                    </div>
                    <div
                        class="relative flex items-center justify-between px-6 py-4 bg-slate-900 text-white rounded-2xl leading-none transition-transform hover:scale-[1.02] active:scale-95 overflow-hidden">
                        <span class="font-black text-sm uppercase tracking-widest"
                            x-text="loading ? 'Memproses...' : 'Pesan Sekarang'"></span>
                        <div class="flex items-center gap-2">
                            <span class="text-brand-blue font-black tracking-tight">Rp <span
                                    x-text="formatNumber(grandTotal)"></span></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white"
                                :class="loading ? 'animate-spin' : 'animate-pulse'" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path x-show="!loading" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                <path x-show="loading" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Sales Selection Modal -->
        <div x-show="showSalesModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            style="display: none;">

            <div @click.away="showSalesModal = false"
                class="bg-white w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100">
                <div class="p-8 space-y-6">
                    <div class="text-center space-y-2">
                        <div
                            class="w-16 h-16 bg-brand-blue-light rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brand-blue" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900">Pilih Petugas Sales</h3>
                        <p class="text-sm text-slate-500">Silakan pilih sales yang melayani Anda hari ini.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="relative">
                            <select x-model="selectedSalesId"
                                class="w-full pl-4 pr-10 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-900 focus:outline-none focus:border-brand-blue transition-all appearance-none">
                                <option value="">-- Pilih Sales --</option>
                                <template x-for="s in sales" :key="s.id_user">
                                    <option :value="s.id_user" x-text="s.name"></option>
                                </template>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button @click="showSalesModal = false"
                                class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-colors">
                                Batal
                            </button>
                            <button @click="submitOrder()" :disabled="!selectedSalesId"
                                class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 disabled:opacity-50 transition-all shadow-lg shadow-black/10">
                                Submit Pesanan
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
        function cartSystem() {
            return {
                products: @json($products),
                sales: @json($sales),
                cart: [],
                searchQuery: '',
                brandFilter: 'all',
                loading: false,
                showSalesModal: false,
                selectedSalesId: '',

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

                get tax() {
                    return this.subtotal * 0.11;
                },

                get grandTotal() {
                    return this.subtotal + this.tax;
                },

                addToCart(product) {
                    if (product.current_stock <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Stok Habis',
                            text: 'Produk ini sedang tidak tersedia.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        return;
                    }

                    const existingItem = this.cart.find(item => item.id_product === product.id_product);
                    if (!existingItem) {
                        this.cart.push({
                            ...product,
                            qty: 1
                        });
                    }
                },

                incrementQty(productId) {
                    const item = this.cart.find(i => i.id_product === productId);
                    if (item) {
                        if (item.qty < item.current_stock) {
                            item.qty++;
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Stok Terbatas',
                                text: 'Anda sudah mencapai batas stok yang tersedia.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    } else {
                        // Jika belum di cart, add
                        const product = this.products.find(p => p.id_product === productId);
                        if (product) this.addToCart(product);
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

                clearCart() {
                    this.cart = [];
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                async submitOrder() {
                    if (!this.selectedSalesId) return;
                    this.showSalesModal = false;

                    const result = await Swal.fire({
                        title: 'Konfirmasi Pesanan',
                        text: "Apakah Anda yakin ingin memproses pesanan ini?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#96CBFC',
                        cancelButtonColor: '#FFC2D9',
                        confirmButtonText: 'Ya, Pesan!',
                        cancelButtonText: 'Batal'
                    });

                    if (result.isConfirmed) {
                        this.loading = true;
                        try {
                            const response = await fetch("{{ route('customers.order.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    sales_id: this.selectedSalesId,
                                    items: this.cart.map(item => ({
                                        id: item.id_product,
                                        qty: item.qty
                                    }))
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message + ' Nomor Pesanan: ' + data.order_number,
                                    confirmButtonColor: '#96CBFC'
                                });
                                window.location.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: data.message
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server.'
                            });
                        } finally {
                            this.loading = false;
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush