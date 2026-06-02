@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Katalog Produk</h1>
                <p class="text-sm text-slate-500">Daftar produk dan informasi stok.</p>
            </div>

            <!-- Filters -->
            <div class="flex items-center gap-3">
                <!-- Search -->
                <form action="{{ route('direktur.products.index') }}" method="GET" class="m-0 p-0">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari produk..."
                            class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-1 focus:ring-slate-400 focus:border-slate-400 outline-none transition-all">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </form>

                <!-- Brand Filter -->
                <div class="relative w-44">
                    <select onchange="window.location.href=this.value"
                        class="w-full appearance-none pl-3 pr-10 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-1 focus:ring-slate-400 focus:border-slate-400 outline-none transition-all cursor-pointer">
                        <option value="{{ route('direktur.products.index', ['search' => $search]) }}">Semua Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ route('direktur.products.index', ['brand' => $brand, 'search' => $search]) }}"
                                {{ $brandFilter == $brand ? 'selected' : '' }}>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($products as $product)
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:border-slate-300 transition-all">
                    <!-- Image -->
                    <div class="aspect-square bg-slate-50 relative border-b border-slate-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-[9px] font-medium uppercase tracking-widest">No Image</span>
                            </div>
                        @endif
                        
                        <!-- Brand -->
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-0.5 bg-white/80 border border-slate-100 text-slate-600 text-[10px] font-semibold rounded-md shadow-sm">
                                {{ $product->brand }}
                            </span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-4 space-y-3">
                        <div>
                            <h3 class="font-semibold text-slate-800 line-clamp-1 leading-tight">{{ $product->product_name }}</h3>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Stok</span>
                                <span class="text-xs font-bold {{ $product->current_stock > 10 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $product->current_stock }} <span class="text-[10px] font-medium text-slate-400">{{ $product->unit }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Prices -->
                        <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Beli</p>
                                <p class="text-xs font-semibold text-slate-600">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Jual</p>
                                <p class="text-xs font-bold text-slate-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 bg-slate-50 rounded-xl border border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                    <p class="text-sm font-medium">Tidak ada produk ditemukan</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
