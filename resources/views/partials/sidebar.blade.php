@php
    $active = 'sidebar-link-active';
    $inactive = 'text-slate-500 hover:text-slate-900 hover:bg-slate-50';
@endphp

<div class="flex flex-col h-full">
    <div class="p-6">
        <div class="flex items-center gap-2 mb-8">
            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo"
                class="w-8 h-8 object-cover rounded-lg shadow-sm shadow-brand-pink/20">
            <span class="text-sm font-bold tracking-tight text-slate-900 leading-tight">CV. PRIMA <span
                    class="text-brand-blue-dark">AMANAH</span></span>
        </div>
        @if (auth()->user()->role == 'admin_kantor')
            <nav class="space-y-1">
                <a href="{{ route('admin.home') }}"
                    class=" {{ request()->routeIs('admin.home') ? $active : $inactive }} flex items-center gap-3 px-3 py-2 rounded-lg transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">DATA MASTERS</p>
                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('products.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    Products
                </a>
                <a href="{{ route('admin.customers.index') }}"
                    class="flex {{ request()->routeIs('admin.customers.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Customers
                </a>
                <a href="{{ route('admin.zones.index') }}"
                    class="flex {{ request()->routeIs('admin.zones.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>

                    Wilayah Customers
                </a>
                <a href="{{ route('admin.sales.index') }}"
                    class="flex {{ request()->routeIs('admin.sales.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Sales
                </a>
                <a href="{{ route('admin.koordinator.index') }}"
                    class="flex {{ request()->routeIs('admin.koordinator.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    Koordinator Sales
                </a>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">DATA TRANSAKSI</p>
                <a href="{{ route('admin.barang-masuk.index') }}"
                    class="flex {{ request()->routeIs('admin.barang-masuk.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859M12 3v8.25m0 0l-3-3m3 3l3-3" />
                    </svg>
                    Input Barang Masuk
                </a>
                <a href="{{ route('admin.incorders.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.incorders.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.375v-3a2.25 2.25 0 012.25-2.25h15a2.25 2.25 0 012.25 2.25v3m-19.5 0a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25m-19.5 0v3.375A2.25 2.25 0 004.5 21h15a2.25 2.25 0 002.25-2.25V13.875M12 3v11.25m0 0l-3.75-3.75M12 14.25l3.75-3.75" />
                    </svg>
                    Pesananan Masuk
                </a>
                <a href="{{ route('admin.po.index') }}"
                    class="flex {{ request()->routeIs('admin.po.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m.75-12H6a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 006 21h12a2.25 2.25 0 002.25-2.25V8.25A2.25 2.25 0 0018 6H14.25M9 4.5V3c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v1.5m-9 0h9" />
                    </svg>
                    Pesananan P.O
                </a>
                <a href="{{ route('admin.deliveries.index') }}"
                    class="flex {{ request()->routeIs('admin.deliveries.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25a9 9 0 0 0-9-9h-2.25" />
                    </svg>
                    Surat Jalan
                </a>
                <a href="{{ route('admin.rekap-penjualan.index') }}"
                    class="flex {{ request()->routeIs('admin.rekap-penjualan.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13.5 10.5V6.75a3.75 3.75 0 00-7.5 0v3.75M13.5 10.5H21l-4.5 6H9.75l-4.5-6H6M13.5 10.5v3.75m0-3.75H6.75" />
                    </svg>
                    Rekap Penjualan
                </a>
                <a href="{{ route('admin.stock-logs.index') }}"
                    class="flex {{ request()->routeIs('admin.stock-logs.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                        <path d="M12 7v5l4 2" />
                    </svg>
                    Riwayat Stok
                </a>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">Profile</p>
                <a href="{{ route('admin.profile.index') }}"
                    class="flex {{ request()->routeIs('admin.profile.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Profile
                </a>
            </nav>
        @endif

        @if (auth()->user()->role == 'admin_gudang')
            <nav class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">Administrasi</p>
                <a href="{{ route('gudang.home') }}"
                    class=" {{ request()->routeIs('admin.home') ? $active : $inactive }} flex items-center gap-3 px-3 py-2 rounded-lg transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('gudang.verifikasi.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('products.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Stock Masuk
                </a>
                <a href="{{ route('gudang.incorders.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('gudang.incorders.index') ? $active : $inactive }}  rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                    Pesanan Masuk
                </a>
                <a href="{{ route('gudang.stock-logs.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('gudang.stock-logs.index') ? $active : $inactive }}  rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                        <path d="M12 7v5l4 2" />
                    </svg>
                    Riwayat Stok
                </a>

                <a href="{{ route('gudang.profile.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('gudang.profile.index') ? $active : $inactive }}  rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Profile
                </a>
            </nav>
        @endif

        @if (auth()->user()->role == 'pelanggan')
            <nav class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">Administrasi</p>
                <a href="{{ route('customers.home') }}"
                    class=" {{ request()->routeIs('customers.home') ? $active : $inactive }} flex items-center gap-3 px-3 py-2 rounded-lg transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('customers.order.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('customers.order.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    Orders
                </a>
                <a href="{{ route('customers.purchase-order.index') }}"
                    class="flex {{ request()->routeIs('customers.purchase-order.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Purchase Orders
                </a>
                <a href="{{ route('customers.products.index') }}"
                    class="flex {{ request()->routeIs('customers.products.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <polyline points="12 12 12 22 19 17" />
                        <polyline points="3.29 7 12 12 4 17" />
                    </svg>
                    Products
                </a>
            </nav>
        @endif

        @if (auth()->user()->role == 'sales')
            <nav class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">Administrasi</p>
                <a href="{{ route('sales.home') }}"
                    class=" {{ request()->routeIs('sales.home') ? $active : $inactive }} flex items-center gap-3 px-3 py-2 rounded-lg transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('sales.incomingorders.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('sales.incomingorders.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.375v-3a2.25 2.25 0 012.25-2.25h15a2.25 2.25 0 012.25 2.25v3m-19.5 0a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25m-19.5 0v3.375A2.25 2.25 0 004.5 21h15a2.25 2.25 0 002.25-2.25V13.875M12 3v11.25m0 0l-3.75-3.75M12 14.25l3.75-3.75" />
                    </svg>
                    Pesananan Masuk
                </a>
                <a href="{{ route('sales.incomingpo.index') }}"
                    class="flex {{ request()->routeIs('sales.incomingpo.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m.75-12H6a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 006 21h12a2.25 2.25 0 002.25-2.25V8.25A2.25 2.25 0 0018 6H14.25M9 4.5V3c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v1.5m-9 0h9" />
                    </svg>
                    Pesananan P.O
                </a>
                <a href="{{ route('sales.order.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('sales.order.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    Orders
                </a>
                <a href="{{ route('sales.purchase-order.index') }}"
                    class="flex {{ request()->routeIs('sales.purchase-order.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Purchase Orders
                </a>
                <a href="{{ route('sales.products.index') }}"
                    class="flex {{ request()->routeIs('sales.products.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <polyline points="12 12 12 22 19 17" />
                        <polyline points="3.29 7 12 12 4 17" />
                    </svg>
                    Products
                </a>
            </nav>
        @endif

        @if (auth()->user()->role == 'koordinator_sales')
            <nav class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">Administrasi</p>
                <a href="{{ route('koor.sales.home') }}"
                    class=" {{ request()->routeIs('koor.sales.home') ? $active : $inactive }} flex items-center gap-3 px-3 py-2 rounded-lg transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('koor.orders.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('koor.orders.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.375v-3a2.25 2.25 0 012.25-2.25h15a2.25 2.25 0 012.25 2.25v3m-19.5 0a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25m-19.5 0v3.375A2.25 2.25 0 004.5 21h15a2.25 2.25 0 002.25-2.25V13.875M12 3v11.25m0 0l-3.75-3.75M12 14.25l3.75-3.75" />
                    </svg>
                    Pesananan Masuk
                </a>
                <a href="{{ route('koor.po.index') }}"
                    class="flex {{ request()->routeIs('koor.po.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m.75-12H6a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 006 21h12a2.25 2.25 0 002.25-2.25V8.25A2.25 2.25 0 0018 6H14.25M9 4.5V3c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v1.5m-9 0h9" />
                    </svg>
                    Pesananan P.O
                </a>
                <a href="{{ route('koor.products.index') }}"
                    class="flex {{ request()->routeIs('koor.products.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <polyline points="12 12 12 22 19 17" />
                        <polyline points="3.29 7 12 12 4 17" />
                    </svg>
                    Products
                </a>
            </nav>
        @endif

        @if (auth()->user()->role == 'direktur')
            <nav class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">Administrasi</p>
                <a href="{{ route('direktur.home') }}"
                    class=" {{ request()->routeIs('direktur.home') ? $active : $inactive }} flex items-center gap-3 px-3 py-2 rounded-lg transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('direktur.verification.orders') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('direktur.verification.orders') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-blue-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Pesananan
                </a>
                <a href="{{ route('direktur.verificationpo.index') }}"
                    class="flex {{ request()->routeIs('direktur.verificationpo.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25L15 7.125M10.125 2.25v4.875c0 .621.504 1.125 1.125 1.125H15M9 15l2.25 2.25L15 12" />
                    </svg>
                    Verifikasi P.O
                </a>

                <a href="{{ route('direktur.report.index') }}"
                    class="flex {{ request()->routeIs('direktur.report.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-clipboard-list h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <rect x="8" y="2" width="8" height="4" />
                        <path d="M10.4 12H14" />
                        <path d="M10.4 18H14" />
                        <path d="M4 20h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2Z" />
                        <path d="M16 12H16" />
                    </svg>
                    Laporan Pengiriman dan Pemasaran
                </a>

                <a href="{{ route('direktur.stock-logs.index') }}"
                    class="flex {{ request()->routeIs('direktur.stock-logs.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                        <path d="M12 7v5l4 2" />
                    </svg>
                    Riwayat Stok
                </a>

                <a href="{{ route('direktur.products.index') }}"
                    class="flex {{ request()->routeIs('direktur.products.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <polyline points="12 12 12 22 19 17" />
                        <polyline points="3.29 7 12 12 4 17" />
                    </svg>
                    Products
                </a>
                <a href="{{ route('direktur.profile.index') }}"
                    class="flex {{ request()->routeIs('direktur.profile.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Profile
                </a>
            </nav>
        @endif

        @if (auth()->user()->role == 'driver')
            <nav class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">Administrasi</p>
                <a href="{{ route('driver.home') }}"
                    class=" {{ request()->routeIs('driver.home') ? $active : $inactive }} flex items-center gap-3 px-3 py-2 rounded-lg transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
            </nav>
        @endif
    </div>
</div>