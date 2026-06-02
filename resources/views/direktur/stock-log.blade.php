@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Stok</h1>
            <p class="text-slate-500 mt-1">Laporan pergerakan stok barang masuk dan keluar.</p>
        </div>
        
        <!-- Filter Form -->
        <form action="{{ route('direktur.stock-logs.index') }}" method="GET" class="flex items-center gap-3">
            <select name="period" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-brand-blue focus:border-brand-blue block p-2.5 shadow-sm">
                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="month" {{ request('period', 'month') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
            <button type="submit" class="bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-slate-800 transition-colors shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-white border border-slate-100 text-slate-400 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Data Riwayat Stok</h2>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[11px] font-bold text-slate-600 shadow-sm">
                        {{ $logs->count() ?? 0 }} Data
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/30 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 font-bold">Tanggal</th>
                            <th class="px-6 py-4 font-bold">Produk</th>
                            <th class="px-6 py-4 font-bold text-center">Tipe Log</th>
                            <th class="px-6 py-4 font-bold text-center">Kuantitas</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
                            <th class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                        @forelse ($logs ?? [] as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $log->product->product_name ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $log->product->category ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($log->type == 'in')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-emerald-50 text-emerald-600 border-emerald-100">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-rose-50 text-rose-600 border-rose-100">
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-900">
                                    {{ $log->type == 'in' ? '+' : '-' }}{{ $log->quantity }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['label' => 'Pending', 'class' => 'bg-amber-50 text-amber-600 border-amber-100'],
                                            'verified' => ['label' => 'Terverifikasi', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-600 border-rose-100'],
                                            'completed' => ['label' => 'Selesai', 'class' => 'bg-indigo-50 text-indigo-600 border-indigo-100'],
                                        ];
                                        $status = $statusConfig[$log->final_status] ?? ['label' => $log->final_status ?: 'Draft', 'class' => 'bg-slate-50 text-slate-600 border-slate-100'];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="showLogDetail({{ json_encode($log) }})"
                                        class="p-1.5 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand-blue hover:text-white transition-all shadow-sm"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                    Tidak ada riwayat stok untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="logDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeLogModal()"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Detail Riwayat Stok</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5" id="modalLogDate"></p>
                    </div>
                    <button onclick="closeLogModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Produk</p>
                            <p class="text-sm font-bold text-slate-900" id="modalLogProductName"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">User Pelaksana</p>
                            <p class="text-sm font-bold text-slate-900" id="modalLogUser"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tipe & Kuantitas</p>
                            <p class="text-sm font-bold text-slate-900">
                                <span id="modalLogType" class="mr-1"></span> <span id="modalLogQty"></span>
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Referensi</p>
                            <p class="text-sm font-medium text-slate-700" id="modalLogReference"></p>
                        </div>
                    </div>
                    
                    <div class="space-y-1.5">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Catatan Gudang</p>
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-lg text-sm text-slate-700" id="modalLogNote">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 pt-2 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status Verifikasi</p>
                            <span id="modalLogVerificationBadge" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border inline-block"></span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status Final</p>
                            <span id="modalLogFinalBadge" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border inline-block"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showLogDetail(log) {
            const modal = document.getElementById('logDetailModal');
            
            // Format Date
            const date = new Date(log.created_at);
            document.getElementById('modalLogDate').innerText = date.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
            }) + ' WIB';
            
            document.getElementById('modalLogProductName').innerText = log.product?.product_name || '-';
            document.getElementById('modalLogUser').innerText = log.user?.name || '-';
            
            const isMasuk = log.type === 'in';
            const typeSpan = document.getElementById('modalLogType');
            typeSpan.innerText = isMasuk ? 'Masuk' : 'Keluar';
            typeSpan.className = isMasuk ? 'text-emerald-600' : 'text-rose-600';
            
            document.getElementById('modalLogQty').innerText = (isMasuk ? '+' : '-') + log.quantity;
            document.getElementById('modalLogReference').innerText = log.reference || '-';
            document.getElementById('modalLogNote').innerText = log.warehouse_note || 'Tidak ada catatan';
            
            // Verificaiton Badge
            const vBadge = document.getElementById('modalLogVerificationBadge');
            if (log.verification_status === 'verified') {
                vBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-emerald-50 text-emerald-600 border-emerald-100';
                vBadge.innerText = 'Verified';
            } else if (log.verification_status === 'pending') {
                vBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-amber-50 text-amber-600 border-amber-100';
                vBadge.innerText = 'Pending';
            } else if (log.verification_status === 'rejected') {
                vBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-rose-50 text-rose-600 border-rose-100';
                vBadge.innerText = 'Rejected';
            } else {
                vBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-slate-50 text-slate-600 border-slate-100';
                vBadge.innerText = log.verification_status || 'N/A';
            }
            
            // Final Badge
            const fBadge = document.getElementById('modalLogFinalBadge');
            if (log.final_status === 'completed' || log.final_status === 'verified') {
                fBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-emerald-50 text-emerald-600 border-emerald-100';
                fBadge.innerText = log.final_status;
            } else if (log.final_status === 'pending') {
                fBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-amber-50 text-amber-600 border-amber-100';
                fBadge.innerText = 'Pending';
            } else if (log.final_status === 'rejected') {
                fBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-rose-50 text-rose-600 border-rose-100';
                fBadge.innerText = 'Rejected';
            } else {
                fBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-slate-50 text-slate-600 border-slate-100';
                fBadge.innerText = log.final_status || 'Draft';
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLogModal() {
            document.getElementById('logDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
@endsection