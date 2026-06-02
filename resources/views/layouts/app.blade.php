<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield($title ?? 'CV. PRIMA AMANAH')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            pink: '#FFC2D9',
                            blue: '#96CBFC',
                            'pink-light': '#FFF0F5',
                            'blue-light': '#F0F7FF',
                            'pink-dark': '#E0A1B8',
                            'blue-dark': '#7AA9D6',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #fcfcfc;
        }

        .sidebar-link-active {
            color: #1e293b;
            background-color: #f1f5f9;
            font-weight: 600;
        }

        .sidebar-link-active svg {
            color: #FFC2D9;
        }

        .custom-shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        }
    </style>
</head>

<body class="text-slate-800 antialiased">

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed left-0 top-0 h-screen w-64 bg-white border-r border-slate-200 z-50 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 flex flex-col">
        <!-- Close button (mobile only) -->
        <button onclick="closeSidebar()" id="sidebarCloseBtn"
            class="lg:hidden absolute top-4 right-4 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors z-10"
            aria-label="Tutup sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        @include('partials.sidebar')
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">

        <!-- Navbar -->

        @include('partials.navbar')
        <!-- Dashboard Content -->
        <div class="p-6 lg:p-8">
            @yield('content')
        </div>
    </main>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        sidebarToggle.addEventListener('click', () => {
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });

        // Auto-close sidebar on link click (mobile)
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) closeSidebar();
            });
        });
    </script>

    @stack('scripts')
</body>

</html>