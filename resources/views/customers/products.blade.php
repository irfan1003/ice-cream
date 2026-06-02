@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Katalog Produk</h1>
            <p class="text-sm text-slate-500">Temukan produk terbaik kami dengan harga kompetitif.</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-widest">Tersedia {{ $products->total() }} Item</span>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm mb-8">
        <form action="{{ route('customers.products.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <div class="relative group flex-1 min-w-[280px]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 group-focus-within:text-brand-blue transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama atau brand produk..." 
                    class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border-transparent focus:border-brand-blue/30 focus:bg-white focus:ring-4 focus:ring-brand-blue/5 rounded-xl text-sm transition-all outline-none">
            </div>

            <!-- Brand Filter -->
            <div class="relative min-w-[180px]">
                <select name="brand" onchange="this.form.submit()" 
                    class="block w-full px-4 py-2.5 bg-slate-50 border-transparent focus:border-brand-blue/30 focus:bg-white focus:ring-4 focus:ring-brand-blue/5 rounded-xl text-sm transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Semua Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Stock Status -->
            <div class="relative min-w-[180px]">
                <select name="status" onchange="this.form.submit()" 
                    class="block w-full px-4 py-2.5 bg-slate-50 border-transparent focus:border-brand-blue/30 focus:bg-white focus:ring-4 focus:ring-brand-blue/5 rounded-xl text-sm transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready Stock</option>
                    <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Reset -->
            <a href="{{ route('customers.products.index') }}" 
                class="h-10 px-4 flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-rose-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Reset
            </a>
        </form>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="group bg-white rounded-2xl p-4 border border-slate-100 hover:border-brand-blue/30 transition-all duration-300 hover:shadow-xl hover:shadow-brand-blue/5 hover:-translate-y-1">
                <!-- Image -->
                <div class="relative aspect-square rounded-xl bg-slate-50 overflow-hidden mb-4">
                    <img src="{{ $product->image ? asset($product->image) : 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=400&auto=format&fit=crop' }}" 
                        alt="{{ $product->product_name }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 bg-white/90 backdrop-blur-md rounded-lg text-[9px] font-black text-slate-700 shadow-sm uppercase tracking-wider border border-white">
                            {{ $product->brand }}
                        </span>
                    </div>

                    @if($product->current_stock <= 0)
                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center">
                            <span class="bg-rose-500 text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg">Habis</span>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="space-y-4">
                    <div>
                        <h3 class="font-bold text-slate-900 leading-tight group-hover:text-brand-blue transition-colors line-clamp-2 min-h-[2.5rem] text-sm">
                            {{ $product->product_name }}
                        </h3>
                    </div>

                    <!-- Details (All Fillables) -->
                    <div class="space-y-2">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100/50">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Harga Jual</span>
                                <span class="text-xs font-black text-blue-600">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100/50">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Harga Beli</span>
                                <span class="text-xs font-black text-slate-600">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-2.5 bg-blue-50/30 rounded-xl border border-blue-100/50">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-blue-400 uppercase tracking-widest block mb-0.5">Stok Tersedia</span>
                                <span class="text-sm font-black text-slate-900">{{ $product->current_stock }} <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $product->unit }}</span></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $product->current_stock > 0 ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $product->current_stock > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                </span>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ $product->current_stock > 0 ? 'Ready' : 'Out' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Produk Tidak Ditemukan</h3>
                <p class="text-sm text-slate-500 mt-1 font-medium">Coba gunakan kata kunci lain atau pilih filter berbeda.</p>
                <a href="{{ route('customers.products.index') }}" class="mt-6 px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all active:scale-95 shadow-lg shadow-slate-900/10">Reset Filter</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $products->links() }}
    </div>
</div>
@endsection