@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Supplier</h1>
            <p class="text-sm text-slate-500">Kelola data supplier dan informasi kontak mereka</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.supplier.index') }}" method="GET" class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                    placeholder="Cari supplier...">
            </form>
            <button onclick="showSupplierModal()"
                class="bg-brand-pink hover:bg-brand-pink-dark text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Supplier
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold text-[10px] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Nama Supplier</th>
                        <th class="px-6 py-3">No. Telepon</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3">PIC Name</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $supplier->supplier_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate" title="{{ $supplier->address }}">
                                {{ $supplier->address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $supplier->pic_name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="showSupplierModal({{ $supplier->id_supplier }})" title="Edit Supplier"
                                        class="p-2 text-brand-blue-dark hover:bg-blue-50 rounded-lg transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteSupplier({{ $supplier->id_supplier }})" title="Hapus Supplier"
                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 italic">
                                Tidak ada data supplier ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($suppliers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

    <!-- Add/Edit Supplier Modal -->
    <div id="supplierModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeSupplierModal()">
            </div>
            <div
                class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="supplierForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                        <h3 class="text-base font-bold text-slate-900" id="modalTitle">Tambah Supplier</h3>
                        <button type="button" onclick="closeSupplierModal()"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="supplier_name" class="block text-sm font-medium text-slate-700">Nama
                                Supplier</label>
                            <input type="text" name="supplier_name" id="supplier_name" required
                                class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue-dark focus:border-brand-blue-dark sm:text-sm">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700">No. Telepon</label>
                            <input type="text" name="phone" id="phone" required
                                class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue-dark focus:border-brand-blue-dark sm:text-sm">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" name="email" id="email"
                                class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue-dark focus:border-brand-blue-dark sm:text-sm">
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-slate-700">Alamat</label>
                            <textarea name="address" id="address" rows="3"
                                class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue-dark focus:border-brand-blue-dark sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label for="pic_name" class="block text-sm font-medium text-slate-700">Nama PIC</label>
                            <input type="text" name="pic_name" id="pic_name"
                                class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-blue-dark focus:border-brand-blue-dark sm:text-sm">
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 text-right">
                        <button type="button" onclick="closeSupplierModal()"
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
            function showSupplierModal(id = null) {
                const modal = document.getElementById('supplierModal');
                const form = document.getElementById('supplierForm');
                const modalTitle = document.getElementById('modalTitle');
                const formMethod = document.getElementById('formMethod');

                // Clear previous errors
                document.querySelectorAll('.text-red-500').forEach(el => el.remove());
                document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

                if (id) {
                    modalTitle.textContent = 'Edit Supplier';
                    form.action = `{{ url('admin/supplier') }}/${id}`;
                    formMethod.value = 'PUT';

                    // Fetch supplier data
                    fetch(`{{ url('admin/supplier') }}/${id}/json`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('supplier_name').value = data.supplier_name;
                            document.getElementById('phone').value = data.phone;
                            document.getElementById('email').value = data.email;
                            document.getElementById('address').value = data.address;
                            document.getElementById('pic_name').value = data.pic_name;
                        })
                        .catch(error => console.error('Error fetching supplier data:', error));
                } else {
                    modalTitle.textContent = 'Tambah Supplier';
                    form.action = `{{ route('admin.supplier.store') }}`;
                    formMethod.value = 'POST';
                    form.reset(); // Clear form fields for new entry
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSupplierModal() {
                document.getElementById('supplierModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function deleteSupplier(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data supplier ini akan dihapus secara permanen!",
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
                        form.action = `{{ url('admin/supplier') }}/${id}`;
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

            // Handle validation errors (if any)
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: 'Terjadi kesalahan validasi:<br><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    showConfirmButton: true
                });
                // Re-open modal if there were validation errors
                @if (old('id_supplier'))
                    showSupplierModal({{ old('id_supplier') }});
                @else
                    showSupplierModal();
                @endif
            @endif
        </script>
    @endpush
@endsection
