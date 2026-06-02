@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Koordinator Sales</h1>
            <p class="text-sm text-slate-500">Kelola data koordinator sales dan tim mereka</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.koordinator.index') }}" method="GET" class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                    placeholder="Cari koordinator...">
            </form>
            <a href="{{ route('admin.koordinator.create') }}"
                class="bg-brand-pink hover:bg-brand-pink-dark text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Koordinator
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold text-[10px] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Nama Koordinator</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($koordinators as $koordinator)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $koordinator->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $koordinator->email }}</td>
                            <td class="px-6 py-4 text-xs">
                                <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded-md font-bold uppercase">
                                    Koordinator
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editKoor({{ json_encode($koordinator) }})"
                                        title="Edit Koordinator"
                                        class="p-1.5 text-slate-400 hover:text-brand-blue-dark transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteKoor({{ $koordinator->id_user }}, '{{ $koordinator->name }}')"
                                        title="Hapus Koordinator"
                                        class="p-1.5 text-slate-400 hover:text-rose-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
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
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500 italic">
                                Tidak ada data koordinator ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($koordinators->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $koordinators->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Koordinator Modal -->
    <div id="editKoorModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl transition-all border border-slate-100">
                <form id="editKoorForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Edit Koordinator Sales</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">Perbarui informasi akun koordinator.</p>
                        </div>
                        <button type="button" onclick="closeEditModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" id="editName" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email Aktif</label>
                            <input type="email" name="email" id="editEmail" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Password Baru (Opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin merubah"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-brand-blue text-white text-sm font-bold rounded-xl shadow-lg shadow-brand-blue/20 hover:bg-brand-blue-dark transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function editKoor(koor) {
            const modal = document.getElementById('editKoorModal');
            const form = document.getElementById('editKoorForm');
            const nameInput = document.getElementById('editName');
            const emailInput = document.getElementById('editEmail');

            form.action = `/admin/koordinator-sales/${koor.id_user}`;
            nameInput.value = koor.name;
            emailInput.value = koor.email;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editKoorModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        async function deleteKoor(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Koordinator?',
                text: `Anda akan menghapus ${name}. Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/koordinator-sales/${id}`, {
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
                            title: 'Berhasil!',
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
@endsection
