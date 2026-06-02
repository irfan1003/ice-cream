<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV. PRIMA AMANAH - Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        .custom-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
        }

        .auth-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="text-slate-800 antialiased min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <!-- Logo Section -->
        <div class="flex flex-col items-center mb-10">
            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="w-12 h-12 object-cover rounded-xl mb-4 shadow-lg shadow-brand-pink/20">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">CV. PRIMA <span
                    class="text-brand-blue-dark">AMANAH</span></h1>
            <p class="text-sm text-slate-400 font-medium mt-1">Management System</p>
        </div>

        <!-- Auth Card -->
        <div class="bg-white rounded-2xl border border-slate-100 custom-shadow p-8 auth-card overflow-hidden">

            <!-- Login View -->
            <div id="loginView">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-900">Welcome Back</h2>
                    <p class="text-xs text-slate-500 font-medium mt-1">Please enter your details to sign in.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-rose-500 flex-shrink-0 flex items-center justify-center mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-rose-600 uppercase tracking-widest mb-1">Authentikasi Gagal</p>
                            @foreach ($errors->all() as $error)
                                <p class="text-[11px] text-rose-500 font-medium leading-relaxed">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form class="space-y-5" action="{{ route('post.login') }}" method="POST">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email
                            Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com"
                            class="w-full px-4 py-3 bg-slate-50 border {{ $errors->has('email') ? 'border-rose-300 ring-4 ring-rose-50' : 'border-slate-200' }} rounded-xl text-sm focus:ring-1 focus:ring-brand-pink focus:border-brand-pink outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Password</label>
                            <a href="#"
                                class="text-[10px] font-bold text-brand-blue-dark hover:underline uppercase tracking-widest">Forgot?</a>
                        </div>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full px-4 py-3 bg-slate-50 border {{ $errors->has('email') ? 'border-rose-300 ring-4 ring-rose-50' : 'border-slate-200' }} rounded-xl text-sm focus:ring-1 focus:ring-brand-pink focus:border-brand-pink outline-none transition-all">
                    </div>

                    <div class="flex items-center gap-2 py-1">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-4 h-4 rounded border-slate-300 text-brand-pink focus:ring-brand-pink cursor-pointer">
                        <label for="remember" class="text-xs font-medium text-slate-500 cursor-pointer">Stay signed in
                            for 30 days</label>
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-md shadow-slate-200">Sign
                        In</button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-500 font-medium">Don't have an account?
                        <a href="{{ route('register') }}"
                            class="text-brand-blue-dark font-bold hover:underline">Register Now</a>
                    </p>
                </div>

            </div>

        </div>

        <!-- Footer Info -->
        <p class="mt-8 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">© 2026 CV. PRIMA AMANAH
            Digital System</p>
    </div>
</body>

</html>