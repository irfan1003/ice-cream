@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Stok Produk</h1>
        <p class="text-slate-500 mt-1">Data stok produk saat ini.</p>
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

    <!-- Main Content -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4 font-bold">Produk</th>
                        <th class="px-6 py-4 font-bold text-center">Stok Sekarang</th>
                        <th class="px-6 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @foreach ($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $product->product_name }}</div>
                                @if ($product->brand)
                                    <span class="text-xs text-slate-400 font-medium uppercase">{{ $product->brand }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-900">{{ $product->current_stock }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        onclick="openAdjustmentModal({{ $product->id_product }}, {{ $product->current_stock }}, '{{ $product->product_name }}', '{{ $product->brand }}')"
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                        title="Adjustment Stok">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button onclick="openLogDetailModal({{ $product->id_product }})"
                                        class="p-2 text-slate-600 hover:bg-slate-50 rounded-lg transition-all"
                                        title="Lihat Riwayat">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Adjustment Modal -->
    <div id="adjustmentModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeAdjustmentModal()">
            </div>
            <div
                class="relative bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl transition-all border border-slate-100">
                <form id="adjustmentForm" method="POST">
                    @csrf
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                        <h3 class="text-base font-bold text-slate-900">Adjustment Stok</h3>
                        <button type="button" onclick="closeAdjustmentModal()"
                            class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <input type="hidden" id="productId" name="productId">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Produk</label>
                            <div id="productNameDisplay" class="text-sm text-slate-900 font-bold"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Stok Saat Ini</label>
                            <div id="currentStockDisplay" class="text-sm text-slate-900 font-bold"></div>
                        </div>
                        <div>
                            <label for="jumlah_fisik" class="block text-sm font-medium text-slate-700 mb-1">Jumlah
                                Fisik</label>
                            <input type="number" id="jumlah_fisik" name="jumlah_fisik" required min="0"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-brand-blue focus:border-brand-blue">
                        </div>
                        <div>
                            <label for="alasan" class="block text-sm font-medium text-slate-700 mb-1">Alasan
                                Adjustment</label>
                            <input type="text" id="alasan" name="alasan" required maxlength="255"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-brand-blue focus:border-brand-blue">
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" onclick="closeAdjustmentModal()"
                            class="px-4 py-2 text-sm font-medium text-slate-700 rounded-lg border border-slate-300 hover:bg-slate-100">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Log Detail Modal -->
    <div id="logDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                onclick="closeLogDetailModal()">
            </div>
            <div
                class="relative bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transition-all border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Riwayat Stok Produk</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5" id="modalProductName"></p>
                    </div>
                    <button type="button" onclick="closeLogDetailModal()"
                        class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Summary Section -->
                <div class="px-6 py-4 border-b border-slate-100">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Stok Awal Bulan
                                Ini</p>
                            <p class="text-lg font-bold text-slate-900" id="modalStokAwal">-</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1">Total Masuk (+)
                            </p>
                            <p class="text-lg font-bold text-emerald-600" id="modalTotalMasuk">-</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wider mb-1">Total Keluar (-)
                            </p>
                            <p class="text-lg font-bold text-rose-600" id="modalTotalKeluar">-</p>
                        </div>
                    </div>
                </div>
                <!-- Log Table -->
                <div class="overflow-x-auto max-h-[60vh]">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/30 sticky top-0">
                            <tr class="text-slate-400 text-[10px] uppercase tracking-wider border-b border-slate-100">
                                <th class="px-6 py-3 font-bold">Tanggal & Waktu</th>
                                <th class="px-6 py-3 font-bold">Tipe</th>
                                <th class="px-6 py-3 font-bold text-center">Jumlah</th>
                                <th class="px-6 py-3 font-bold">Oleh</th>
                                <th class="px-6 py-3 font-bold">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody" class="divide-y divide-slate-100 text-sm text-slate-600">
                            <!-- Logs will be populated here via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function openAdjustmentModal(productId, currentStock, productName, productBrand) {
                const modal = document.getElementById('adjustmentModal');
                document.getElementById('productId').value = productId;
                document.getElementById('currentStockDisplay').innerText = currentStock;
                document.getElementById('productNameDisplay').innerText = productName + (productBrand ? ` (${productBrand})` :
                    '');
                document.getElementById('jumlah_fisik').value = currentStock;
                document.getElementById('alasan').value = '';
                document.getElementById('adjustmentForm').action = `/admin-gudang/stock/${productId}/adjustment`;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeAdjustmentModal() {
                document.getElementById('adjustmentModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            async function openLogDetailModal(productId) {
                const modal = document.getElementById('logDetailModal');
                const tbody = document.getElementById('logTableBody');
                tbody.innerHTML =
                    '<tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Loading...</td></tr>';

                try {
                    const response = await fetch(`/admin-gudang/stock/${productId}/logs`);
                    const data = await response.json();

                    document.getElementById('modalProductName').innerText = data.product.product_name + (data.product
                        .brand ? ` (${data.product.brand})` : '');
                    document.getElementById('modalStokAwal').innerText = data.summary.stok_awal;
                    document.getElementById('modalTotalMasuk').innerText = data.summary.total_masuk;
                    document.getElementById('modalTotalKeluar').innerText = data.summary.total_keluar;

                    if (data.logs.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat stok.</td></tr>';
                    } else {
                        tbody.innerHTML = data.logs.map(log => {
                            const isIn = log.type === 'in';
                            const badgeClass = isIn ? 'bg-emerald-50 text-emerald-600 border-emerald-100' :
                                'bg-rose-50 text-rose-600 border-rose-100';
                            const qtyClass = isIn ? 'text-emerald-600' : 'text-rose-600';
                            const qtyPrefix = isIn ? '+' : '-';
                            const date = new Date(log.created_at);

                            return `
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-900">${date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</div>
                                        <div class="text-[11px] text-slate-400">${date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border ${badgeClass}">${isIn ? 'Masuk' : 'Keluar'}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold ${qtyClass}">${qtyPrefix}${log.quantity}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-900">${log.user?.name || '-'}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">${log.reference_note || '-'}</div>
                                    </td>
                                </tr>
                            `;
                        }).join('');
                    }
                } catch (error) {
                    tbody.innerHTML =
                        '<tr><td colspan="5" class="px-6 py-12 text-center text-rose-500 italic">Gagal memuat riwayat stok.</td></tr>';
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeLogDetailModal() {
                document.getElementById('logDetailModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        </script>
    @endpush
@endsection
