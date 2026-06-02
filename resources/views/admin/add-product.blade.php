@extends('layouts.app')

@push('scripts')
    <script>
        // Image Preview with Upload
        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');
        const previewContent = document.getElementById('previewContent');

        imageUpload.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    previewContent.classList.add('opacity-0');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush

@section('content')
    <div class="mb-8">
        <div class="flex items-center gap-2 text-slate-400 text-xs mb-2">
            <a href="ice-cream.html" class="hover:text-brand-pink transition-colors">Katalog Es Krim</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 font-medium">Tambah Produk Baru</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Produk Es Krim</h1>
        <p class="text-sm text-slate-500">Lengkapi formulir di bawah ini untuk menambahkan produk baru ke katalog.</p>
    </div>

    <div class="max-w-4xl">
        <form class="space-y-6" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Section: General Info -->
            <div class="bg-white rounded-2xl border border-slate-200 custom-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Dasar</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Brand / Merk</label>
                        <input name="brand" type="text" placeholder="Masukkan merk (cth: Walls)"
                            value="{{ old('brand') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('brand') border-red-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-brand-pink/20 focus:border-brand-pink transition-all text-sm outline-none">
                        @error('brand')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Produk</label>
                        <input name="product_name" type="text" placeholder="Masukkan nama produk"
                            value="{{ old('product_name') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('product_name') border-red-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-brand-pink/20 focus:border-brand-pink transition-all text-sm outline-none">
                        @error('product_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Satuan</label>
                        <select name="unit"
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('unit') border-red-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-brand-pink/20 focus:border-brand-pink transition-all text-sm outline-none appearance-none">
                            <option value="Pcs" {{ old('unit') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="Dus" {{ old('unit') == 'Dus' ? 'selected' : '' }}>Dus</option>
                        </select>
                        @error('unit')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Pricing & Image -->
            <div class="bg-white rounded-2xl border border-slate-200 custom-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Harga & Media</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Beli (Modal)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-slate-400 text-sm">Rp</span>
                            <input name="purchase_price" type="text" placeholder="0"
                                value="{{ old('purchase_price') }}"
                                class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border @error('purchase_price') border-red-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-brand-pink/20 focus:border-brand-pink transition-all text-sm outline-none">
                        </div>
                        @error('purchase_price')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Jual (Ecer)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-slate-400 text-sm">Rp</span>
                            <input name="selling_price" type="text" placeholder="0"
                                value="{{ old('selling_price') }}"
                                class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border @error('selling_price') border-red-400 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-brand-pink/20 focus:border-brand-pink transition-all text-sm outline-none">
                        </div>
                        @error('selling_price')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Gambar Produk</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="imageUpload"
                                class="flex flex-col items-center justify-center w-full h-40 border-2 @error('image') border-red-400 @else border-slate-200 @enderror border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-all group overflow-hidden relative">
                                <div id="previewContent" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-10 h-10 mb-3 text-slate-300 group-hover:text-brand-pink transition-colors"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="mb-1 text-sm text-slate-500"><span class="font-bold">Klik untuk upload</span>
                                        atau tarik gambar ke sini</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-widest">PNG, JPG atau JPEG
                                        (Maks. 2MB)</p>
                                </div>
                                <img id="imagePreview" class="absolute inset-0 w-full h-full object-cover hidden" />
                                <input name="image" id="imageUpload" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>
                        @error('image')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('products.index') }}"
                    class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit"
                    class="px-8 py-2.5 bg-brand-pink text-white text-sm font-bold rounded-xl hover:bg-brand-pink-dark transition-all custom-shadow shadow-brand-pink/20">Simpan
                    Produk</button>
            </div>
        </form>
    </div>
@endsection
