@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Barang Masuk</h1>
            <p class="text-sm text-slate-500">Sesuaikan informasi stok masuk untuk referensi #{{ $log->reference }}</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.barang-masuk.index') }}" class="hover:text-brand-blue-dark transition-colors">Input Barang
                Masuk</a>
            <span class="text-slate-300">/</span>
            <span>Edit Log</span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
        <div class="p-6 lg:p-8">
            <form action="{{ route('admin.barang-masuk.update', $log->id_log) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Selection -->
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-slate-700 mb-2">Pilih Produk</label>
                        <select name="product_id" id="product_id"
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all appearance-none bg-no-repeat bg-right"
                            style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27 fill=%27none%27 viewBox=%270%200%2020%2020%27%3E%3Cpath stroke=%27%236B7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27m6%208%204%204%204-4%27%2F%3E%3C%2Fsvg%3E'); background-size: 1.5em 1.5em; padding-right: 2.5rem;">
                            @foreach ($products as $product)
                                <option value="{{ $product->id_product }}"
                                    {{ old('product_id', $log->product_id) == $product->id_product ? 'selected' : '' }}>
                                    {{ $product->product_name }} ({{ $product->brand }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity Input -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-slate-700 mb-2">Jumlah Datang (Pcs)</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $log->quantity) }}"
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                            required>
                        @error('quantity')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex flex-col md:flex-row md:items-center gap-4 justify-between">
                    <div class="flex  justify-start">
                       <a href="{{ route('admin.barang-masuk.index') }}"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-bold transition-all text-sm">
                            Batal
                        </a>
                    </div>
                    <div class="flex gap-3 justify-end">
                        
                        <button type="submit"
                            class="bg-brand-pink hover:bg-brand-pink-dark text-white px-6 py-2 rounded-lg font-bold transition-all text-sm flex items-center gap-2 shadow-lg shadow-brand-pink/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection