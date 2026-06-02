@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Katalog Es Krim</h1>
        <p class="text-sm text-slate-500">Kelola stok dan harga produk es krim Anda.</p>
    </div>

    <!-- Search & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <form action="{{ route('products.index') }}" method="GET" class="w-full md:w-80">
            @if (request('brand'))
                <input type="hidden" name="brand" value="{{ request('brand') }}">
            @endif
            <div
                class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 custom-shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari es krim..."
                    class="bg-transparent border-none focus:ring-0 text-sm w-full">
                <button type="submit" class="hidden"></button>
            </div>
        </form>
        <a href="{{ route('products.create') }}"
            class="px-5 py-2.5 bg-brand-pink text-white text-sm font-bold rounded-xl hover:bg-brand-pink-dark transition-all flex items-center gap-2 shadow-sm whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Produk
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-4 mb-8 overflow-x-auto pb-2">
        <a href="{{ route('products.index', ['search' => request('search')]) }}"
            class="px-4 py-1.5 {{ !request('brand') ? 'bg-brand-blue-dark text-white' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50' }} text-xs font-bold rounded-full whitespace-nowrap">
            Semua Produk
        </a>
        @foreach ($brands as $brand)
            <a href="{{ route('products.index', ['brand' => $brand, 'search' => request('search')]) }}"
                class="px-4 py-1.5 {{ request('brand') == $brand ? 'bg-brand-blue-dark text-white' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50' }} text-xs font-bold rounded-full whitespace-nowrap">
                {{ $brand }}
            </a>
        @endforeach
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        @foreach ($products as $product)
            <div
                class="bg-white rounded-2xl border border-slate-100 custom-shadow overflow-hidden group hover:border-brand-pink transition-all">
                <div class="h-48 overflow-hidden relative">
                    <img src="{{ Storage::url($product->image) }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-brand-pink uppercase tracking-widest">{{ $product->brand }}</span>
                        <span class="text-[10px] font-medium text-slate-400">#{{ $product->id_product }}</span>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-4 line-clamp-1">{{ $product->product_name }}</h4>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between border-b border-slate-50 pb-1">
                            <span class="text-xs text-slate-400">Ecer</span>
                            <span class="text-xs font-bold text-slate-700">{{ Number::currency($product->selling_price, in: 'IDR', locale: 'id') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-1">
                            <span class="text-xs text-slate-400">Modal</span>
                            <span class="text-xs font-medium text-slate-500">{{ Number::currency($product->purchase_price, in: 'IDR', locale: 'id') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-slate-400">Stok</span>
                            <span class="text-xs font-bold {{ $product->current_stock < 10 ? 'text-red-500' : 'text-slate-700' }}">
                                {{ $product->current_stock }} {{ $product->unit }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pt-2 border-t border-slate-50">
                        <a href="{{ route('products.edit', $product->id_product) }}" 
                            class="flex-1 flex items-center justify-center gap-2 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <button onclick="deleteProduct({{ $product->id_product }}, '{{ $product->product_name }}')"
                            class="flex-1 flex items-center justify-center gap-2 py-2 bg-rose-50 text-rose-600 text-xs font-bold rounded-lg hover:bg-rose-600 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function deleteProduct(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Produk?',
                text: `Anda akan menghapus "${name}". Data ini tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E11D48',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/products/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                }
            }
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
    @endpush

    <!-- Pagination -->
    @if ($products->hasPages())
        <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500 order-2 md:order-1">
                Menampilkan <span class="font-bold text-slate-900">{{ $products->firstItem() }}</span> - 
                <span class="font-bold text-slate-900">{{ $products->lastItem() }}</span> dari 
                <span class="font-bold text-slate-900">{{ $products->total() }}</span> produk
            </p>
            <div class="flex items-center gap-1.5 order-1 md:order-2">
                {{-- Previous Page Link --}}
                @if ($products->onFirstPage())
                    <span class="w-9 h-9 flex items-center justify-center bg-slate-50 text-slate-300 rounded-xl cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-slate-600 rounded-xl hover:border-brand-pink hover:text-brand-pink transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif

                {{-- Page Links --}}
                @foreach ($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                    @if ($page == $products->currentPage())
                        <span class="w-9 h-9 flex items-center justify-center bg-brand-pink text-white text-xs font-bold rounded-xl shadow-sm shadow-brand-pink/20">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:border-brand-pink hover:text-brand-pink transition-all">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-slate-600 rounded-xl hover:border-brand-pink hover:text-brand-pink transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="w-9 h-9 flex items-center justify-center bg-slate-50 text-slate-300 rounded-xl cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    @endif
@endsection
