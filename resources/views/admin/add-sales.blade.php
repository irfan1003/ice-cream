@extends('layouts.app')


@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Tambah Sales</h1>
            <p class="text-sm text-slate-500">Formulir untuk menambahkan sales baru</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.sales.index') }}" class="hover:text-brand-blue-dark">Manajemen Sales</a>
            <span class="text-slate-300">/</span>
            <span>Tambah Sales</span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 custom-shadow overflow-hidden">
        <div class="p-6 lg:p-8">
            <form action="{{ route('admin.sales.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Sales -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Sales</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                            required>
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                            required>
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <input type="password" id="password" name="password"
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-brand-blue-dark focus:border-brand-blue-dark transition-all"
                            required>
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex gap-3 justify-end">
                    <button type="button"
                        class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-bold transition-all text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-brand-pink hover:bg-brand-pink-dark text-white px-4 py-2 rounded-lg font-bold transition-all text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Simpan Sales
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
