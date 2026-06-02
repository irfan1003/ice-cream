@extends('layouts.app')

@section('content')
    <div class="mb-8 text-center md:text-left">
        <a href="{{ route('admin.barang-masuk.index') }}"
            class="text-slate-500 hover:text-brand-blue-dark transition-colors inline-flex items-center gap-2 text-sm mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Catat Barang Masuk</h1>
        <p class="text-sm text-slate-500">Input data stok yang datang untuk diverifikasi oleh gudang</p>
    </div>

    <form action="{{ route('admin.barang-masuk.store') }}" method="POST">
        @csrf
        <div class="space-y-6">
            <!-- Reference Card -->
            <div class="bg-white rounded-xl border border-slate-200 custom-shadow p-6 lg:p-8">
                <div class="max-w-md mx-auto md:mx-0">
                    <label for="reference"
                        class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide text-[10px]">No.
                        Referensi / Nomor DO</label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference') }}"
                        class="block w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all"
                        placeholder="Contoh: DO-20231012-001" required>
                    @error('reference')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Items Table Card -->
            <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm" id="itemsTable">
                        <thead class="bg-slate-50 text-slate-500 font-bold text-[10px] uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 w-1/2">Pilih Produk</th>
                                <th class="px-6 py-4">Jumlah (Pcs)</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="tableBody">
                            <tr class="item-row">
                                <td class="px-6 py-4">
                                    <select name="items[0][product_id]"
                                        class="product-select block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-blue focus:border-brand-blue transition-all"
                                        required>
                                        <option value="">-- Pilih Produk --</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="items[0][quantity]" min="1"
                                        class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-blue focus:border-brand-blue transition-all"
                                        placeholder="0" required>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                        class="remove-row p-2 text-slate-300 hover:text-rose-500 transition-colors disabled:opacity-30"
                                        disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                    <button type="button" id="addRow"
                        class="text-brand-blue-dark hover:text-brand-blue font-bold text-xs flex items-center gap-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        INPUT MORE PRODUCT
                    </button>
                </div>
            </div>

            <!-- Action Section -->
            <div class="flex items-center justify-end gap-3 pb-8">
                <a href="{{ route('admin.barang-masuk.index') }}"
                    class="px-6 py-2.5 border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="bg-brand-pink hover:bg-brand-pink-dark text-white px-8 py-2.5 rounded-lg text-sm font-bold transition-all shadow-lg shadow-brand-pink/20">
                    Simpan Semua Data
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let products = [];
            let rowCount = 1;

            // Fetch products for dropdowns
            fetch('{{ route('products.json') }}')
                .then(response => response.json())
                .then(data => {
                    products = data;
                    populateDropdown(document.querySelector('.product-select'));
                });

            function populateDropdown(selectElement) {
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id_product;
                    option.textContent = product.product_name;
                    selectElement.appendChild(option);
                });
            }

            // Add new row
            document.getElementById('addRow').addEventListener('click', function() {
                const tbody = document.getElementById('tableBody');
                const newRow = tbody.querySelector('.item-row').cloneNode(true);

                // Reset inputs and update names
                const select = newRow.querySelector('select');
                const input = newRow.querySelector('input');
                const removeBtn = newRow.querySelector('.remove-row');

                select.name = `items[${rowCount}][product_id]`;
                select.value = '';
                input.name = `items[${rowCount}][quantity]`;
                input.value = '';
                removeBtn.disabled = false;

                tbody.appendChild(newRow);
                rowCount++;
            });

            // Remove row
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const row = e.target.closest('.item-row');
                    const tbody = document.getElementById('tableBody');
                    if (tbody.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                    }
                }
            });
        });
    </script>
@endsection
