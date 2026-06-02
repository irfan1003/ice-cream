@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Verifikasi Surat Jalan</h1>
                <p class="text-sm text-slate-500 mt-1">Daftar pengiriman yang perlu diverifikasi oleh Admin Kantor.</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.deliveries.index') }}" method="GET" class="relative group">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. SPB / Pelanggan..."
                    class="w-full md:w-72 pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-brand-pink focus:border-brand-pink outline-none transition-all shadow-sm">
                <div
                    class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand-pink transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px]">No</th>
                            <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px]">No. SPB</th>
                            <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px]">Pesanan</th>
                            <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px]">Pelanggan
                            </th>
                            <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px]">Status</th>
                            <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px] text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($deliveries as $index => $delivery)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $deliveries->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900">{{ $delivery->spb_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-900 font-medium">{{ $delivery->order->order_number }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                        {{ \Carbon\Carbon::parse($delivery->order->order_date)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-900 font-bold uppercase tracking-tight">
                                        {{ $delivery->order->customer->customer_name }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $delivery->order->customer->address }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending_admin_kantor' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'pending_admin_gudang' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'shipped' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                            'delivered' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        ];
                                        $currentStatus = $delivery->delivery_status;
                                        $class = $statusClasses[$currentStatus] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $class }}">
                                        {{ str_replace('_', ' ', $currentStatus) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.deliveries.surat-jalan', $delivery->id_deliver) }}"
                                            target="_blank"
                                            class="p-2 text-slate-400 hover:text-brand-pink hover:bg-brand-pink/10 rounded-lg transition-all"
                                            title="Lihat Surat Jalan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>

                                        @if ($delivery->delivery_status === 'pending_admin_kantor')
                                            <button onclick="updateStatusToGudang({{ $delivery->id_deliver }})"
                                                class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                                title="Teruskan ke Gudang">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $deliveries->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function updateStatusToGudang(id) {
            Swal.fire({
                title: 'Teruskan ke Gudang?',
                text: "Status akan diubah menjadi Pending Admin Gudang.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Teruskan!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/admin/deliveries/${id}/to-gudang`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(json => { throw new Error(json.message || 'Gagal memperbarui status') });
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: result.value.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        }
    </script>
@endpush