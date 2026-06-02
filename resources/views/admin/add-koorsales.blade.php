@extends('layouts.app')


@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.koordinator.index') }}"
            class="text-slate-500 hover:text-brand-blue-dark transition-colors flex items-center gap-2 text-sm mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Koordinator
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Koordinator Sales</h1>
        <p class="text-sm text-slate-500">Daftarkan koordinator sales baru ke dalam sistem</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
        <div class="p-6 lg:p-8">
            <form action="{{ route('admin.koordinator.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Koordinator -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                            placeholder="Masukkan nama lengkap..." required>
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                            placeholder="nama@email.com" required>
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <input type="password" id="password" name="password"
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                            placeholder="••••••••" required>
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex gap-3 justify-end">
                    <a href="{{ route('admin.koordinator.index') }}"
                        class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-brand-pink hover:bg-brand-pink-dark text-white px-6 py-2 rounded-lg text-sm font-bold transition-all shadow-lg shadow-brand-pink/20">
                        Simpan Koordinator
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
