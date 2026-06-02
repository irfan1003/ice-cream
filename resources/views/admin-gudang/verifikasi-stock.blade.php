@extends('layouts.app')


@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Verifikasi Barang Masuk</h1>
        <p class="text-sm text-slate-500">Review dan verifikasi kesesuaian stok yang baru datang</p>
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

    <div class="space-y-8">
        @forelse ($incomingGoods as $reference => $logs)
            <div class="bg-white rounded-2xl border border-slate-200 custom-shadow overflow-hidden">
                <!-- Header Group -->
                <div
                    class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-brand-blue/10 rounded-xl text-brand-blue-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">No.
                                Referensi</span>
                            <h3 class="font-bold text-slate-900">#{{ $reference }}</h3>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-right hidden md:block border-r border-slate-200 pr-6">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Waktu
                                Kedatangan</span>
                            <span
                                class="text-xs text-slate-600 font-medium">{{ $logs->first()->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Dibuat
                                Oleh</span>
                            <span
                                class="text-xs text-slate-600 font-medium">{{ $logs->first()->user->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('gudang.verifikasi.submit', $reference) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50/50 text-slate-500 font-bold text-[10px] uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Produk</th>
                                    <th class="px-6 py-4 text-center">Jumlah (Pcs)</th>
                                    <th class="px-6 py-4">Status Verifikasi</th>
                                    <th class="px-6 py-4">Catatan Gudang (Opsional)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($logs as $index => $log)
                                    <input type="hidden" name="items[{{ $index }}][id_log]"
                                        value="{{ $log->id_log }}">
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if ($log->product->image)
                                                    <img src="{{ asset('storage/' . $log->product->image) }}" alt=""
                                                        class="w-10 h-10 rounded-xl object-cover border border-slate-100 shadow-sm">
                                                @else
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $log->product->product_name }}
                                                    </p>
                                                    <p
                                                        class="text-[10px] text-slate-400 font-medium uppercase tracking-tight">
                                                        {{ $log->product->brand }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold">
                                                {{ $log->quantity }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="items[{{ $index }}][verification_status]"
                                                class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-brand-blue focus:border-brand-blue transition-all bg-white @if ($log->verification_status == 'pending') border-amber-300 ring-2 ring-amber-100 @endif">
                                                <option value="pending"
                                                    {{ $log->verification_status == 'pending' ? 'selected' : '' }}
                                                    disabled>Pilih Status...</option>
                                                <option value="sesuai"
                                                    {{ $log->verification_status == 'sesuai' ? 'selected' : '' }}>Sesuai
                                                    (Verified)
                                                </option>
                                                <option value="tidak_sesuai"
                                                    {{ $log->verification_status == 'tidak_sesuai' ? 'selected' : '' }}>
                                                    Tidak Sesuai (Reject)</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="items[{{ $index }}][warehouse_note]"
                                                value="{{ $log->warehouse_note }}"
                                                class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-brand-blue focus:border-brand-blue transition-all"
                                                placeholder="Berikan alasan jika reject...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="flex h-2 w-2 relative">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-pink opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-pink"></span>
                            </span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Membutuhkan Verifikasi
                            </p>
                        </div>
                        <button type="submit"
                            class="bg-brand-pink hover:bg-brand-pink-dark text-white px-6 py-2 rounded-lg text-sm font-bold transition-all shadow-lg shadow-brand-pink/20 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Verifikasi #{{ $reference }}
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 mb-1">Semua Stok Sudah Terverifikasi</h3>
                <p class="text-sm text-slate-500">Tidak ada barang masuk baru yang menunggu antrean verifikasi.</p>
            </div>
        @endforelse
    </div>
@endsection
