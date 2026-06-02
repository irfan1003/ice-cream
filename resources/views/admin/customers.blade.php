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
            {{-- <button
                class="bg-brand-pink hover:bg-brand-pink-dark text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pelanggan
            </button> --}}
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
                                <div class="flex items-center justify-center">
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

    <!-- Modal Detail Pelanggan (Placeholder) -->
    <div id="customerDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeCustomerModal()"></div>
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
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

    @push('scripts')
    <script>
        function showCustomerDetail(id) {
            // Placeholder logic for viewing customer details
            const modal = document.getElementById('customerDetailModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // In a real scenario, you'd fetch data via AJAX here
            fetch(`/admin/customers/${id}/json`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Pelanggan</label>
                                <p class="text-sm font-bold text-slate-900">${data.customer_name}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</label>
                                <p class="text-sm text-slate-600">${data.user?.email || '-'}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat</label>
                                <p class="text-sm text-slate-600">${data.address || '-'}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No. Telepon</label>
                                <p class="text-sm text-slate-600">${data.phone || '-'}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</label>
                                <p class="text-sm font-bold text-blue-600">${data.zone?.zone_name || '-'}</p>
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
