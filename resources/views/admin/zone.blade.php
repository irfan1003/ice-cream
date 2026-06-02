@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Wilayah</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola data wilayah pengiriman pelanggan.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Search Form -->
                <form action="{{ route('admin.zones.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari wilayah..."
                        class="w-full md:w-64 pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-brand-pink focus:border-brand-pink outline-none transition-all shadow-sm">
                    <div
                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand-pink transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>

                <!-- Add Button -->
                <button onclick="openAddModal()"
                    class="bg-brand-pink hover:bg-brand-pink-dark text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Wilayah
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4 w-12">No</th>
                            <th class="px-6 py-4">Nama Wilayah</th>
                            <th class="px-6 py-4 text-center">Jumlah Pelanggan</th>
                            <th class="px-6 py-4 text-center">Dibuat</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($zones as $index => $zone)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-400 font-medium">
                                    {{ $zones->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-slate-900">{{ $zone->zone_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">
                                        {{ $zone->customer_count }} Pelanggan
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-500 text-xs">
                                    {{ $zone->created_at ? $zone->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="openEditModal({{ $zone->id_zone }}, '{{ addslashes($zone->zone_name) }}')"
                                            class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                            title="Edit Wilayah">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button onclick="deleteZone({{ $zone->id_zone }}, '{{ addslashes($zone->zone_name) }}')"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                            title="Hapus Wilayah">
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
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <p>Tidak ada data wilayah ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($zones->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $zones->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Zone Modal -->
    <div id="zoneModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeZoneModal()"></div>

            <div class="relative bg-white rounded-xl w-full max-w-md overflow-hidden shadow-xl transition-all border border-slate-100">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="zoneModalTitle">Tambah Wilayah</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="zoneModalSubtitle">Masukkan nama wilayah baru</p>
                    </div>
                    <button onclick="closeZoneModal()"
                        class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <input type="hidden" id="zoneId" value="">
                    <div class="space-y-2">
                        <label for="zoneName" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Nama Wilayah <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="zoneName" placeholder="Contoh: Jakarta Selatan"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-brand-pink focus:border-brand-pink outline-none transition-all"
                            autocomplete="off">
                        <p id="zoneNameError" class="text-xs text-rose-500 hidden"></p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button onclick="closeZoneModal()"
                        class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button id="btnSaveZone" onclick="saveZone()"
                        class="px-4 py-2 text-sm font-medium text-white bg-brand-pink hover:bg-brand-pink-dark rounded-lg transition-colors flex items-center gap-2">
                        <span id="btnSaveZoneText">Simpan</span>
                        <svg id="btnSaveZoneSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            let isEditing = false;

            function openAddModal() {
                isEditing = false;
                document.getElementById('zoneModalTitle').innerText = 'Tambah Wilayah';
                document.getElementById('zoneModalSubtitle').innerText = 'Masukkan nama wilayah baru';
                document.getElementById('zoneId').value = '';
                document.getElementById('zoneName').value = '';
                document.getElementById('zoneNameError').classList.add('hidden');
                document.getElementById('btnSaveZoneText').innerText = 'Simpan';

                document.getElementById('zoneModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                setTimeout(() => document.getElementById('zoneName').focus(), 100);
            }

            function openEditModal(id, name) {
                isEditing = true;
                document.getElementById('zoneModalTitle').innerText = 'Edit Wilayah';
                document.getElementById('zoneModalSubtitle').innerText = 'Perbarui nama wilayah';
                document.getElementById('zoneId').value = id;
                document.getElementById('zoneName').value = name;
                document.getElementById('zoneNameError').classList.add('hidden');
                document.getElementById('btnSaveZoneText').innerText = 'Perbarui';

                document.getElementById('zoneModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                setTimeout(() => document.getElementById('zoneName').focus(), 100);
            }

            function closeZoneModal() {
                document.getElementById('zoneModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function saveZone() {
                const zoneName = document.getElementById('zoneName').value.trim();
                const zoneId = document.getElementById('zoneId').value;
                const errorEl = document.getElementById('zoneNameError');
                const btn = document.getElementById('btnSaveZone');
                const btnText = document.getElementById('btnSaveZoneText');
                const btnSpinner = document.getElementById('btnSaveZoneSpinner');

                // Client-side validation
                if (!zoneName) {
                    errorEl.innerText = 'Nama wilayah wajib diisi.';
                    errorEl.classList.remove('hidden');
                    return;
                }

                errorEl.classList.add('hidden');
                btn.disabled = true;
                btnText.textContent = isEditing ? 'Memperbarui...' : 'Menyimpan...';
                btnSpinner.classList.remove('hidden');

                let url, method;
                if (isEditing) {
                    url = `/admin/zones/${zoneId}`;
                    method = 'PUT';
                } else {
                    url = '/admin/zones';
                    method = 'POST';
                }

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ zone_name: zoneName })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(json => { throw new Error(json.message || 'Gagal menyimpan') });
                    }
                    return response.json();
                })
                .then(data => {
                    closeZoneModal();
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btnText.textContent = isEditing ? 'Perbarui' : 'Simpan';
                    btnSpinner.classList.add('hidden');

                    errorEl.innerText = error.message;
                    errorEl.classList.remove('hidden');
                });
            }

            function deleteZone(id, name) {
                Swal.fire({
                    title: 'Hapus Wilayah?',
                    html: `Wilayah <strong>"${name}"</strong> akan dihapus secara permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`/admin/zones/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(json => { throw new Error(json.message || 'Gagal menghapus') });
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(error.message);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value && result.value.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: result.value.message,
                            icon: 'success',
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            }

            // Allow Enter key to submit form in modal
            document.getElementById('zoneName').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveZone();
                }
            });
        </script>
    @endpush
@endsection