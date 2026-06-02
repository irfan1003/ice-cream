<header
    class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex items-center justify-between z-40">
    <div class="flex items-center gap-4">
        <button id="sidebarToggle" class="lg:hidden p-2 text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Mobile Brand Logo -->
        <div class="flex lg:hidden items-center gap-2">
            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo"
                class="w-7 h-7 object-cover rounded-lg shadow-sm shadow-brand-pink/20">
            <span class="text-sm font-bold tracking-tight text-slate-900">CV. PRIMA <span
                    class="text-brand-blue-dark">AMANAH</span></span>
        </div>

        <div class="hidden md:flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Search..." class="bg-transparent border-none focus:ring-0 text-sm w-48">
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button class="p-2 text-slate-400 hover:text-slate-600 transition-colors relative">
            <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-brand-pink rounded-full border border-white"></span> -->
        </button>
        <div class="h-6 w-[1px] bg-slate-200"></div>
        <div class="relative">
            <button id="userMenuBtn" class="flex items-center gap-3 focus:outline-none">
                <div class="text-right hidden sm:block text-left">
                    <p class="text-sm font-semibold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-500 font-medium mt-1 uppercase tracking-wider text-right">
                        {{ auth()->user()->role }}
                    </p>
                </div>
                <div
                    class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 overflow-hidden transition-transform active:scale-95 cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=96CBFC&color=fff"
                        alt="">
                </div>
            </button>

            <!-- Logout Dropdown -->
            <div id="userDropdown"
                class="absolute right-0 mt-3 w-48 bg-white border border-slate-200 rounded-xl shadow-xl py-2 hidden z-50 transform origin-top-right transition-all">
                <div class="px-4 py-2 border-b border-slate-50 mb-1 lg:hidden">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-500 uppercase">{{ auth()->user()->role }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:text-rose-500 hover:bg-rose-50 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar Aplikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('userMenuBtn');
        const dropdown = document.getElementById('userDropdown');

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>