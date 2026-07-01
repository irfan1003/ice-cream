@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Pelanggan</h1>
            <p class="text-sm text-slate-500">Kelola data pelanggan dan informasi wilayah mereka</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                    placeholder="Cari pelanggan...">
            </form>
            <button onclick="openAddCustomerModal()"
                class="bg-brand-pink hover:bg-brand-pink-dark text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pelanggan
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold text-[10px] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Nama Toko / Pelanggan</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3">No. Telepon</th>
                        <th class="px-6 py-3">Wilayah</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $customer->customer_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $customer->user->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate" title="{{ $customer->address }}">
                                {{ $customer->address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs">
                                @if ($customer->zone)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-md font-bold uppercase">
                                        {{ $customer->zone->zone_name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Tanpa Wilayah</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <button onclick="showCustomerDetail({{ $customer->id_customer }})" 
                                        title="Lihat Detail"
                                        class="p-2 text-slate-400 hover:text-brand-blue-dark hover:bg-blue-50 rounded-lg transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button onclick="openEditCustomerModal({{ $customer->id_customer }})" 
                                        title="Edit Pelanggan"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteCustomer({{ $customer->id_customer }}, '{{ addslashes($customer->customer_name) }}')" 
                                        title="Hapus Pelanggan"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
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
                                Tidak ada data pelanggan ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Pelanggan -->
    <div id="customerDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeCustomerModal()"></div>
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <h3 class="text-base font-bold text-slate-900">Detail Pelanggan</h3>
                    <button onclick="closeCustomerModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4" id="modalContent">
                    <div class="animate-pulse flex space-x-4">
                        <div class="flex-1 space-y-6 py-1">
                            <div class="h-2 bg-slate-200 rounded"></div>
                            <div class="space-y-3">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="h-2 bg-slate-200 rounded col-span-2"></div>
                                    <div class="h-2 bg-slate-200 rounded col-span-1"></div>
                                </div>
                                <div class="h-2 bg-slate-200 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create & Update Pelanggan (Horizontal Layout) -->
    <div id="customerFormModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeCustomerFormModal()"></div>
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="customerModalTitle">Tambah Pelanggan</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="customerModalSubtitle">Masukkan data pelanggan baru</p>
                    </div>
                    <button onclick="closeCustomerFormModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="customerForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="_method" id="customerMethod" value="POST">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label for="customerName" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Toko / Pelanggan <span class="text-rose-500">*</span></label>
                                    <input type="text" name="customer_name" id="customerName" required placeholder="Contoh: Toko Berkah"
                                        class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand-pink focus:border-brand-pink transition-all">
                                </div>
                                <div>
                                    <label for="customerEmail" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Email <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" id="customerEmail" required placeholder="Contoh: toko@berkah.com"
                                        class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand-pink focus:border-brand-pink transition-all">
                                </div>
                                <div>
                                    <label for="customerPhone" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">No. Telepon <span class="text-rose-500">*</span></label>
                                    <input type="text" name="phone" id="customerPhone" required placeholder="Contoh: 081234567890"
                                        class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand-pink focus:border-brand-pink transition-all">
                                    <p class="text-[10px] text-slate-400 italic mt-1.5">* No. Telepon akan digunakan sebagai password default akun pelanggan</p>
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="space-y-4">
                                <div>
                                    <label for="customerZone" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Wilayah <span class="text-rose-500">*</span></label>
                                    <select name="zone_id" id="customerZone" required
                                        class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand-pink focus:border-brand-pink bg-white transition-all">
                                        <option value="">Pilih Wilayah</option>
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id_zone }}">{{ $zone->zone_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="customerAddress" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                                    <textarea name="address" id="customerAddress" rows="4" placeholder="Masukkan alamat lengkap toko..."
                                        class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand-pink focus:border-brand-pink transition-all resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/30">
                        <button type="button" onclick="closeCustomerFormModal()"
                            class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="btnSaveCustomer"
                            class="px-4 py-2 text-sm font-medium text-white bg-brand-pink hover:bg-brand-pink-dark rounded-lg transition-colors">
                            <span id="btnSaveCustomerText">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Gagal Menyimpan!',
                    html: `
                        <div class="text-left text-sm text-slate-600 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    `,
                    icon: 'error',
                    confirmButtonColor: '#E0A1B8'
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <script>
        let customerModalTitle = document.getElementById('customerModalTitle');
        let customerModalSubtitle = document.getElementById('customerModalSubtitle');
        let customerForm = document.getElementById('customerForm');
        let customerMethod = document.getElementById('customerMethod');
        let btnSaveCustomerText = document.getElementById('btnSaveCustomerText');
        
        let customerNameInput = document.getElementById('customerName');
        let customerEmailInput = document.getElementById('customerEmail');
        let customerPhoneInput = document.getElementById('customerPhone');
        let customerZoneInput = document.getElementById('customerZone');
        let customerAddressInput = document.getElementById('customerAddress');

        function openAddCustomerModal() {
            customerModalTitle.innerText = 'Tambah Pelanggan';
            customerModalSubtitle.innerText = 'Masukkan data pelanggan baru';
            customerForm.action = "{{ route('admin.customers.store') }}";
            customerMethod.value = "POST";
            btnSaveCustomerText.innerText = 'Simpan';
            
            customerNameInput.value = '';
            customerEmailInput.value = '';
            customerPhoneInput.value = '';
            customerZoneInput.value = '';
            customerAddressInput.value = '';

            document.getElementById('customerFormModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openEditCustomerModal(id) {
            customerModalTitle.innerText = 'Edit Pelanggan';
            customerModalSubtitle.innerText = 'Perbarui data pelanggan';
            customerForm.action = `/admin/customers/${id}`;
            customerMethod.value = "PUT";
            btnSaveCustomerText.innerText = 'Perbarui';

            fetch(`/admin/customers/${id}/json`)
                .then(response => response.json())
                .then(data => {
                    customerNameInput.value = data.customer_name || '';
                    customerEmailInput.value = data.user?.email || '';
                    customerPhoneInput.value = data.phone || '';
                    customerZoneInput.value = data.zone_id || '';
                    customerAddressInput.value = data.address || '';

                    document.getElementById('customerFormModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                })
                .catch(err => {
                    Swal.fire('Error', 'Gagal memuat data pelanggan.', 'error');
                });
        }

        function closeCustomerFormModal() {
            document.getElementById('customerFormModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function deleteCustomer(id, name) {
            Swal.fire({
                title: 'Hapus Pelanggan?',
                html: `Pelanggan <strong>"${name}"</strong> beserta akun user-nya akan dihapus secara permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/customers/${id}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function showCustomerDetail(id) {
            const modal = document.getElementById('customerDetailModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            fetch(`/admin/customers/${id}/json`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Pelanggan</label>
                                <p class="text-sm font-bold text-slate-900">${data.customer_name}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</label>
                                <p class="text-sm text-slate-600">${data.user?.email || '-'}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No. Telepon</label>
                                <p class="text-sm text-slate-600">${data.phone || '-'}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</label>
                                <p class="text-sm font-bold text-blue-600">${data.zone?.zone_name || '-'}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat</label>
                                <p class="text-sm text-slate-600">${data.address || '-'}</p>
                            </div>
                        </div>
                    `;
                })
                .catch(err => {
                    document.getElementById('modalContent').innerHTML = '<p class="text-red-500">Gagal memuat data.</p>';
                });
        }

        function closeCustomerModal() {
            document.getElementById('customerDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
@endsection
