@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pesanan P.O Masuk</h1>
            <p class="text-sm text-slate-500">Kelola dan verifikasi permintaan Purchase Order dari pelanggan.</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-600 rounded-xl border border-amber-100 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-widest">Menunggu Tinjauan: {{ $purchaseOrders->count() }} PO</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('sales.incomingpo.kirimKolektif') }}" id="batchForm">
        @csrf
        
        @if($purchaseOrders->count() > 0)
            <div class="mb-4 flex items-center gap-3">
                <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:shadow-md active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Kirim PO Terpilih ke Koordinator
                </button>
                <button type="button" id="toggleAllBtn" onclick="toggleAllCheckboxes(this)" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-all">
                    Pilih Semua
                </button>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            @if($purchaseOrders->count() > 0)
                                <th class="px-4 py-4 text-center">
                                    <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 focus:ring-2">
                                </th>
                            @endif
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">No. PO</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Pelanggan</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Tanggal PO</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Total</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($purchaseOrders as $po)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" name="order_ids[]" value="{{ $po->id_po }}" class="po-checkbox w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 focus:ring-2">
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $po->po_number }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $po->customer->customer_name }}</div>
                                <div class="text-[10px] text-slate-400 font-medium tracking-tight">{{ $po->customer->store_name ?? 'Regular Store' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-black text-slate-900 tracking-tight text-sm">Rp {{ number_format($po->grand_total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-600">
                                    {{ str_replace('_', ' ', $po->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end">
                                    <a href="{{ route('sales.incomingpo.show', $po->id_po) }}" 
                                        class="w-9 h-9 flex items-center justify-center bg-slate-50 text-slate-600 rounded-lg border border-slate-100 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all active:scale-95 group/btn"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-bold text-sm tracking-tight">Tidak ada Purchase Order yang perlu diproses.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleAllCheckboxes(source) {
        const checkboxes = document.querySelectorAll('.po-checkbox');
        let checkedState;
        
        if (source && source.type === 'checkbox') {
            checkedState = source.checked;
        } else {
            const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(checkbox => checkbox.checked);
            checkedState = !allChecked;
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = checkedState;
        });
        
        updateSelectAllState();
    }

    function updateSelectAllState() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.po-checkbox');
        const toggleBtn = document.getElementById('toggleAllBtn');
        
        const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(checkbox => checkbox.checked);
        
        if (selectAll) {
            selectAll.checked = allChecked;
        }
        
        if (toggleBtn) {
            toggleBtn.textContent = allChecked ? 'Batal Pilih' : 'Pilih Semua';
        }
    }

    // Bind change listener ke checkbox individual
    document.querySelectorAll('.po-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAllState);
    });

    // Validasi sebelum submit
    document.getElementById('batchForm').addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('.po-checkbox:checked');
        
        if (checkboxes.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal satu PO untuk dikirim.');
            return false;
        }
        
        if (!confirm(`Anda akan mengirim ${checkboxes.length} PO ke Koordinator. Lanjutkan?`)) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endpush
@endsection
