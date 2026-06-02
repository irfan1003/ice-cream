@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div
            class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl flex items-center gap-3 animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Input Barang Masuk</h1>
            <p class="text-sm text-slate-500">Verifikasi dan masukkan stok barang yang baru datang</p>
        </div>
        <a href="{{ route('admin.barang-masuk.create') }}"
            class="bg-brand-pink hover:bg-brand-pink-dark text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Barang Masuk
        </a>
    </div>

    <div class="space-y-6">
        @forelse ($incomingGoods as $reference => $logs)
            <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
                <!-- Header Group -->
                <div
                    class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-brand-blue/10 rounded-lg text-brand-blue-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">No.
                                Referensi</span>
                            <h3 class="font-bold text-slate-900">#{{ $reference }}</h3>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden md:block">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Waktu
                                Input</span>
                            <span
                                class="text-xs text-slate-600 font-medium">{{ $logs->first()->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        @php
                            $hasVerified = $logs->contains(function ($log) {
                                return $log->verification_status == 'tidak_sesuai';
                            });

                            $notVerified = $logs->contains(function ($log) {
                                return $log->verification_status !== 'sesuai';
                            });
                        @endphp

                        @if ($hasVerified)
                            <form action="{{ route('admin.barang-masuk.reset', $reference) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <button type="submit"
                                    class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2 rounded-lg text-sm font-bold transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Verifikasi Ulang
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.barang-masuk.update-stock', $reference) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" {{ $notVerified ? 'disabled' : '' }}
                                    class="bg-brand-blue hover:bg-brand-blue-dark text-white px-6 py-2 rounded-lg text-sm font-bold transition-all shadow-lg shadow-brand-blue/20 flex items-center gap-2 {{ $notVerified ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Submit & Update Stock
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 text-slate-500 font-bold text-[10px] uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3 text-center">Jumlah Datang</th>
                                <th class="px-6 py-3">Status Saat Ini</th>
                                <th class="px-6 py-3">Catatan Gudang</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($logs as $log)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($log->product->image)
                                                <img src="{{ asset('storage/' . $log->product->image) }}" alt=""
                                                    class="w-8 h-8 rounded-lg object-cover border border-slate-100">
                                            @else
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-slate-900 text-xs">
                                                    {{ $log->product->product_name }}
                                                </div>
                                                <div class="text-[10px] text-slate-400">{{ $log->product->brand }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-3 py-1 bg-brand-blue/5 text-brand-blue-dark rounded-full font-bold text-xs">
                                            {{ $log->quantity }} Pcs
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        @if ($log->verification_status == 'pending')
                                            <span class="text-amber-500 flex items-center gap-1.5 font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Menunggu Verifikasi
                                            </span>
                                        @else
                                            <span
                                                class="{{ $log->verification_status == 'sesuai' ? 'text-emerald-500' : 'text-red-500' }} flex items-center gap-1.5 font-medium">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full {{ $log->verification_status == 'sesuai' ? 'bg-emerald-500' : 'bg-red-500' }} "></span>
                                                Terverifikasi ({{ $log->verification_status }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 italic">
                                        {{ $log->warehouse_note ?? 'Tidak ada catatan' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.barang-masuk.edit', $log->id_log) }}"
                                            class="inline-flex items-center justify-center p-2 text-brand-blue hover:bg-brand-blue/10 rounded-lg transition-colors"
                                            title="Edit Data">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                <div
                    class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Barang Masuk</h3>
                <p class="text-sm text-slate-500">Semua data stock log dengan tipe "In" akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
@endsection
