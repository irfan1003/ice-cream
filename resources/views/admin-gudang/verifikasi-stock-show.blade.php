@extends('layouts.app')


@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('gudang.verifikasi.index') }}"
                class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 mb-2 text-sm font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Verifikasi Barang Datang</h1>
            <p class="text-sm text-slate-500">Verifikasi kesesuaian barang dari PO Supplier</p>
        </div>
    </div>

    @if (session('success'))
        <div
            class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl flex items-center gap-3 animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div
            class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-xl flex items-center gap-3 animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- PO Info Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi PO Supplier</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-slate-500 font-medium">Nomor PO</span>
                <p class="text-slate-900 font-bold">{{ $poSupplier->po_number }}</p>
            </div>
            <div>
                <span class="text-slate-500 font-medium">Supplier</span>
                <p class="text-slate-900 font-bold">{{ $poSupplier->supplier->supplier_name ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-500 font-medium">Tanggal PO</span>
                <p class="text-slate-900 font-bold">{{ \Carbon\Carbon::parse($poSupplier->po_date)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('gudang.verifikasi.store', $poSupplier->id_po_supplier) }}" method="POST">
        @csrf
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/50 text-slate-400 text-[10px] uppercase tracking-wider border-b border-slate-100">
                            <th class="px-6 py-4 font-bold w-12"></th>
                            <th class="px-6 py-4 font-bold">Produk</th>
                            <th class="px-6 py-4 font-bold text-center">Qty Dipesan</th>
                            <th class="px-6 py-4 font-bold text-center">Qty Diterima</th>
                            <th class="px-6 py-4 font-bold text-center">Sesuai</th>
                            <th class="px-6 py-4 font-bold">Catatan (Jika Tidak Sesuai)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                        @foreach ($poSupplier->details as $detail)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-slate-400 text-xs font-bold">#{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $detail->product->product_name ?? '-' }}</div>
                                    @if ($detail->product && $detail->product->brand)
                                        <span
                                            class="text-xs text-slate-400 font-medium uppercase">{{ $detail->product->brand }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-900">
                                    {{ $detail->qty }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number"
                                        name="details[{{ $detail->id_supplier_po_detail }}][qty_received]"
                                        value="{{ old('details.' . $detail->id_supplier_po_detail . '.qty_received', $detail->qty_received) }}"
                                        data-qty="{{ $detail->qty }}"
                                        class="qty-received-input w-24 px-3 py-2 border border-slate-300 rounded-lg text-sm text-center focus:ring-1 focus:ring-brand-blue focus:border-brand-blue transition-all">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox"
                                                name="details[{{ $detail->id_supplier_po_detail }}][is_compatible]"
                                                value="1"
                                                {{ old('details.' . $detail->id_supplier_po_detail . '.is_compatible', $detail->is_compatible) ? 'checked' : '' }}
                                                class="is-compatible-checkbox w-5 h-5 text-brand-blue rounded border-slate-300 focus:ring-brand-blue focus:border-brand-blue transition-colors">
                                            <span class="text-sm text-slate-600 font-medium">Ya</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text"
                                        name="details[{{ $detail->id_supplier_po_detail }}][reject_reason]"
                                        value="{{ old('details.' . $detail->id_supplier_po_detail . '.reject_reason', $detail->reject_reason) }}"
                                        placeholder="Masukkan alasan jika tidak sesuai"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-1 focus:ring-brand-blue focus:border-brand-blue transition-all">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-end">
                <button type="submit"
                    class="bg-brand-blue-dark text-white px-6 py-3 rounded-lg text-sm font-bold hover:bg-brand-blue transition-all shadow-lg shadow-brand-blue-dark/20">
                    Submit Verifikasi
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.is-compatible-checkbox').forEach(function(checkbox) {
                const row = checkbox.closest('tr');
                const qtyInput = row.querySelector('.qty-received-input');

                function updateQtyReceived() {
                    if (checkbox.checked) {
                        qtyInput.value = qtyInput.dataset.qty;
                        qtyInput.readOnly = true;
                        qtyInput.classList.add('bg-slate-100', 'cursor-not-allowed');
                    } else {
                        qtyInput.value = '';
                        qtyInput.readOnly = false;
                        qtyInput.classList.remove('bg-slate-100', 'cursor-not-allowed');
                    }
                }

                updateQtyReceived();
                checkbox.addEventListener('change', updateQtyReceived);
            });
        });
    </script>
@endsection
