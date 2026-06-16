@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Pesanan P.O Supplier</h1>
            <p class="text-sm text-slate-500">Kelola pesanan pembelian dari supplier Anda.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.po-supplier.index') }}" method="GET" class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                    placeholder="Cari P.O...">
            </form>
            <button onclick="showPoModal()"
                class="bg-brand-pink hover:bg-brand-pink-dark text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah P.O
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold text-[10px] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">No. P.O</th>
                        <th class="px-6 py-3">Supplier</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($poSuppliers as $po)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $po->po_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-700">{{ $po->supplier->supplier_name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($po->po_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusStyles = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'received' => 'bg-blue-100 text-blue-700',
                                        'pending_director' => 'bg-indigo-100 text-indigo-700',
                                        'pending_office' => 'bg-purple-100 text-purple-700',
                                        'verified' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'received' => 'Diterima Gudang',
                                        'pending_director' => 'Menunggu Direktur',
                                        'pending_office' => 'Menunggu Admin Kantor',
                                        'verified' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 text-xs font-bold rounded-full {{ $statusStyles[$po->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $statusLabels[$po->status] ?? ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($po->status !== 'verified')
                                        <button onclick="showPoModal({{ $po->id_po_supplier }})" title="Edit P.O"
                                            class="p-2 text-brand-blue-dark hover:bg-blue-50 rounded-lg transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.po-supplier.export', $po->id_po_supplier) }}"
                                        title="Export Excel"
                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-all inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </a>
                                    @if ($po->status !== 'verified')
                                        <button onclick="deletePo({{ $po->id_po_supplier }})" title="Hapus P.O"
                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500 italic">
                                Tidak ada data P.O ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($poSuppliers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $poSuppliers->links() }}
            </div>
        @endif
    </div>

    <!-- Add/Edit PO Modal -->
    <div id="poModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closePoModal()">
            </div>
            <div
                class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-3xl border border-slate-100">
                <form id="poForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                        <h3 class="text-base font-bold text-slate-900" id="modalTitle">Tambah P.O Supplier</h3>
                        <button type="button" onclick="closePoModal()"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-slate-700">Supplier</label>
                                <select name="supplier_id" id="supplier_id" required
                                    class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue-dark focus:border-brand-blue-dark sm:text-sm">
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id_supplier }}">{{ $supplier->supplier_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Produk</label>
                            <div id="productList" class="space-y-3">
                                <!-- Products will be added here dynamically -->
                            </div>
                            <button type="button" onclick="addProductRow()"
                                class="mt-3 text-brand-blue-dark hover:text-brand-blue text-sm font-medium flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Produk
                            </button>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 text-right">
                        <button type="button" onclick="closePoModal()"
                            class="mr-2 px-4 py-2 text-sm font-medium text-slate-700 rounded-md border border-slate-300 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue-dark">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-brand-blue-dark rounded-md hover:bg-brand-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue-dark">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const products = @json($products->map(fn($p) => ['id' => $p->id_product, 'name' => $p->product_name, 'brand' => $p->brand]));
            let productRowIndex = 0;

            function addProductRow(product = null) {
                const productList = document.getElementById('productList');
                const rowId = `product-row-${productRowIndex++}`;

                const row = document.createElement('div');
                row.id = rowId;
                row.className = 'flex flex-wrap items-end gap-3 p-3 bg-slate-50 rounded-lg';
                row.innerHTML = `
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs text-slate-500 mb-1">Produk</label>
                    <select name="products[${productRowIndex - 1}][product_id]" required
                        class="w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-brand-blue-dark focus:border-brand-blue-dark">
                        <option value="">Pilih Produk</option>
                        ${products.map(p => `<option value="${p.id}" ${product && product.product_id == p.id ? 'selected' : ''}>${p.brand} - ${p.name}</option>`).join('')}
                    </select>
                </div>
                <div class="w-24">
                    <label class="block text-xs text-slate-500 mb-1">Qty</label>
                    <input type="number" name="products[${productRowIndex - 1}][qty]" value="${product ? product.qty : 1}" required min="1"
                        class="w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-brand-blue-dark focus:border-brand-blue-dark">
                </div>
                <button type="button" onclick="removeProductRow('${rowId}')"
                    class="p-2 text-red-500 hover:bg-red-100 rounded-md transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
                productList.appendChild(row);
            }

            function removeProductRow(rowId) {
                const row = document.getElementById(rowId);
                if (row) {
                    row.remove();
                }
            }

            function showPoModal(id = null) {
                const modal = document.getElementById('poModal');
                const form = document.getElementById('poForm');
                const modalTitle = document.getElementById('modalTitle');
                const formMethod = document.getElementById('formMethod');
                const productList = document.getElementById('productList');

                // Clear previous content
                productList.innerHTML = '';
                productRowIndex = 0;

                if (id) {
                    modalTitle.textContent = 'Edit P.O Supplier';
                    form.action = `{{ url('admin/po-supplier') }}/${id}`;
                    formMethod.value = 'PUT';

                    // Fetch PO data
                    fetch(`{{ url('admin/po-supplier') }}/${id}/json`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('supplier_id').value = data.supplier_id;

                            // Add product rows
                            if (data.details && data.details.length > 0) {
                                data.details.forEach(detail => {
                                    addProductRow(detail);
                                });
                            } else {
                                addProductRow();
                            }
                        })
                        .catch(error => console.error('Error fetching PO data:', error));
                } else {
                    modalTitle.textContent = 'Tambah P.O Supplier';
                    form.action = `{{ route('admin.po-supplier.store') }}`;
                    formMethod.value = 'POST';
                    document.getElementById('supplier_id').value = '';
                    addProductRow();
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closePoModal() {
                document.getElementById('poModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function deletePo(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data P.O ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ url('admin/po-supplier') }}/${id}`;
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // Display success/error messages from session
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
        </script>
    @endpush
@endsection
