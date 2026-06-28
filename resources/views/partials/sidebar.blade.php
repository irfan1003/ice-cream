@php
    $active = 'sidebar-link-active';
    $inactive = 'text-slate-500 hover:text-slate-900 hover:bg-slate-50';
@endphp

<div class="flex flex-col h-full overflow-y-auto">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-home h-5 w-5">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Dashboard
                </a>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">DATA MASTERS</p>
                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('products.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <polyline points="12 12 12 22 19 17" />
                        <polyline points="3.29 7 12 12 4 17" />
                    </svg>
                    Stock Awal (Products)
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
                        class="lucide lucide-truck h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M10 17H6l-2-4V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10h2a2 2 0 0 0 2-2v-1" />
                        <circle cx="7" cy="17" r="2" />
                        <path d="M18 17h-8" />
                        <circle cx="16" cy="17" r="2" />
                    </svg>

                    Wilayah Customers
                </a>
                <a href="{{ route('admin.supplier.index') }}"
                    class="flex {{ request()->routeIs('admin.supplier.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>

                    Supplier
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

                <a href="{{ route('admin.po-supplier.index') }}"
                    class="flex {{ request()->routeIs('admin.po-supplier.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-plus h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L15 2z" />
                        <path d="M14 2v6h6" />
                        <path d="M12 18v-6" />
                        <path d="M9 15h6" />
                    </svg>
                    Buat P.O Supplier
                </a>

                <a href="{{ route('admin.barang-masuk.index') }}"
                    class="flex {{ request()->routeIs('admin.barang-masuk.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-check-2 h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M4 22h14a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v4" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="M3 15l2 2l4-4" />
                    </svg>
                    Verifikasi Akhir P.O
                </a>
                <a href="{{ route('admin.incorders.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.incorders.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-inbox h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                        <path
                            d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                    </svg>
                    Pesananan Masuk Pelanggan
                </a>
                <a href="{{ route('admin.po.index') }}"
                    class="flex {{ request()->routeIs('admin.po.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <polyline points="12 12 12 22 19 17" />
                        <polyline points="3.29 7 12 12 4 17" />
                    </svg>
                    Pesananan P.O pelanggan
                </a>
                <a href="{{ route('admin.deliveries.index') }}"
                    class="flex {{ request()->routeIs('admin.deliveries.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-truck h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M10 17H6l-2-4V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10h2a2 2 0 0 0 2-2v-1" />
                        <circle cx="7" cy="17" r="2" />
                        <path d="M18 17h-8" />
                        <circle cx="16" cy="17" r="2" />
                    </svg>
                    Surat Jalan
                </a>
                <a href="{{ route('admin.rekap-penjualan.index') }}"
                    class="flex {{ request()->routeIs('admin.rekap-penjualan.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chart-bar h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M12 20V10" />
                        <path d="M18 20V4" />
                        <path d="M6 20v-4" />
                    </svg>
                    Rekap Penjualan
                </a>
                <a href="{{ route('admin.stock-logs.index') }}"
                    class="flex {{ request()->routeIs('admin.stock-logs.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-history h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path d="M3 3v5h5" />
                        <path d="M3.05 13a9 9 0 1 0 0-10l-.09 0" />
                    </svg>
                    Log Pergerakan Barang
                </a>
            </nav>
        @endif

        @if (auth()->user()->role == 'admin_gudang')
            <nav class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-3">Administrasi</p>
                <a href="{{ route('gudang.home') }}"
                    class=" {{ request()->routeIs('gudang.home') ? $active : $inactive }} flex items-center gap-3 px-3 py-2 rounded-lg transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-home h-5 w-5">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('gudang.verifikasi.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('gudang.verifikasi.*') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-check-2 h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path d="M4 22h14a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v4" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="M3 15l2 2l4-4" />
                    </svg>
                    Verifikasi Barang Datang
                </a>
                <a href="{{ route('gudang.incorders.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('gudang.incorders.index') ? $active : $inactive }}  rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-inbox h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                        <path
                            d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                    </svg>
                    Pemuatan & Surat Jalan
                </a>
                <a href="{{ route('gudang.stock.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('gudang.stock.*') ? $active : $inactive }}  rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-history h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path d="M3 3v5h5" />
                        <path d="M3.05 13a9 9 0 1 0 0-10l-.09 0" />
                    </svg>
                    Manajemen Stok Produk
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-inbox h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                        <path
                            d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                    </svg>
                    Orders
                </a>
                <a href="{{ route('customers.purchase-order.index') }}"
                    class="flex {{ request()->routeIs('customers.purchase-order.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L15 2z" />
                        <path d="M14 2v6h6" />
                        <path d="M10 12H8" />
                        <path d="M16 16H8" />
                        <path d="M16 12H12" />
                    </svg>
                    P.O
                </a>
                <a href="{{ route('customers.products.index') }}"
                    class="flex {{ request()->routeIs('customers.products.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-inbox h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                        <path
                            d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                    </svg>
                    Pesananan Masuk
                </a>
                <a href="{{ route('sales.incomingpo.index') }}"
                    class="flex {{ request()->routeIs('sales.incomingpo.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L15 2z" />
                        <path d="M14 2v6h6" />
                        <path d="M10 12H8" />
                        <path d="M16 16H8" />
                        <path d="M16 12H12" />
                    </svg>
                    Pesananan P.O
                </a>
                <a href="{{ route('sales.order.index') }}"
                    class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('sales.order.index') ? $active : $inactive }} rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-inbox h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                        <path
                            d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                    </svg>
                    Orders
                </a>
                <a href="{{ route('sales.purchase-order.index') }}"
                    class="flex {{ request()->routeIs('sales.purchase-order.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L15 2z" />
                        <path d="M14 2v6h6" />
                        <path d="M10 12H8" />
                        <path d="M16 16H8" />
                        <path d="M16 12H12" />
                    </svg>
                    P.O
                </a>
                <a href="{{ route('sales.products.index') }}"
                    class="flex {{ request()->routeIs('sales.products.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-inbox h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                        <path
                            d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                    </svg>
                    Pesananan Masuk
                </a>
                <a href="{{ route('koor.po.index') }}"
                    class="flex {{ request()->routeIs('koor.po.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L15 2z" />
                        <path d="M14 2v6h6" />
                        <path d="M10 12H8" />
                        <path d="M16 16H8" />
                        <path d="M16 12H12" />
                    </svg>
                    Pesananan P.O
                </a>
                <a href="{{ route('koor.products.index') }}"
                    class="flex {{ request()->routeIs('koor.products.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-check-2 h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path d="M4 22h14a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v4" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="M3 15l2 2l4-4" />
                    </svg>
                    Verifikasi Pesananan
                </a>
                <a href="{{ route('direktur.verificationpo.index') }}"
                    class="flex {{ request()->routeIs('direktur.verificationpo.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L15 2z" />
                        <path d="M14 2v6h6" />
                        <path d="M10 12H8" />
                        <path d="M16 16H8" />
                        <path d="M16 12H12" />
                    </svg>
                    Verifikasi P.O
                </a>

                <a href="{{ route('direktur.po-supplier.index') }}"
                    class="flex {{ request()->routeIs('direktur.po-supplier.*') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L15 2z" />
                        <path d="M14 2v6h6" />
                        <path d="M10 12H8" />
                        <path d="M16 16H8" />
                        <path d="M16 12H12" />
                    </svg>
                    Persetujuan PO Supplier
                </a>

                <a href="{{ route('direktur.report.index') }}"
                    class="flex {{ request()->routeIs('direktur.report.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bar-chart-2 h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path d="M18 20V10" />
                        <path d="M12 20V4" />
                        <path d="M6 20v-6" />
                    </svg>
                    Laporan-Laporan
                </a>

                <a href="{{ route('direktur.stock-logs.index') }}"
                    class="flex {{ request()->routeIs('direktur.stock-logs.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-history h-5 w-5 group-hover:text-brand-pink-dark transition-colors">
                        <path d="M3 3v5h5" />
                        <path d="M3.05 13a9 9 0 1 0 0-10l-.09 0" />
                    </svg>
                    Monitoring Mutasi Stok
                </a>

                <a href="{{ route('direktur.products.index') }}"
                    class="flex {{ request()->routeIs('direktur.products.index') ? $active : $inactive }} items-center gap-3 px-3 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-box h-5 w-5 group-hover:text-brand-blue-dark transition-colors">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.29 7 12 12 20.71 7" />
                        <polyline points="12 12 12 22 19 17" />
                        <polyline points="3.29 7 12 12 4 17" />
                    </svg>
                    Stock awal (Products)
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