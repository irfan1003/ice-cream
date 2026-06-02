@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Koordinator Sales</h1>
                <p class="text-sm text-slate-500 mt-1">Selamat datang kembali! Berikut ringkasan tugas verifikasi Anda hari ini.</p>
            </div>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card PO Belum Disetujui -->
            <a href="{{ route('koor.po.index') }}" 
               class="block bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 transition-colors group-hover:bg-amber-100">
                            <!-- Clean SVG Icon for PO -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Purchase Order (PO)</p>
                            <p class="text-sm text-slate-400 mt-0.5">Menunggu Verifikasi Anda</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h3 class="text-3xl font-black text-slate-900 leading-none">{{ $pendingPOsCount }}</h3>
                        <span class="inline-flex items-center text-xs font-bold text-amber-700 mt-2 bg-amber-50 px-2 py-0.5 rounded">
                            Pending
                        </span>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-slate-900 transition-colors">
                    <span>Lihat Daftar PO Masuk</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <!-- Card Orders Belum Disetujui -->
            <a href="{{ route('koor.orders.index') }}" 
               class="block bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 transition-colors group-hover:bg-blue-100">
                            <!-- Clean SVG Icon for Regular Order -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pesanan Reguler</p>
                            <p class="text-sm text-slate-400 mt-0.5">Menunggu Verifikasi Anda</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h3 class="text-3xl font-black text-slate-900 leading-none">{{ $pendingOrdersCount }}</h3>
                        <span class="inline-flex items-center text-xs font-bold text-blue-700 mt-2 bg-blue-50 px-2 py-0.5 rounded">
                            Pending
                        </span>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-slate-900 transition-colors">
                    <span>Lihat Daftar Pesanan Masuk</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>
    </div>
@endsection
