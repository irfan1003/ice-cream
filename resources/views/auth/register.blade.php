<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV. PRIMA AMANAH - Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="text-slate-800 antialiased min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-lg" x-data="{ 
        step: 1, 
        errors: {},
        formData: {
            name: '{{ old('name') }}',
            email: '{{ old('email') }}',
            password: '',
            password_confirmation: '',
            customer_name: '{{ old('customer_name') }}',
            phone: '{{ old('phone') }}',
            zone_id: '{{ old('zone_id') }}',
            address: `{{ old('address') }}`
        },
        validateStep1() {
            this.errors = {};
            if (!this.formData.name) this.errors.name = 'Nama lengkap wajib diisi';
            if (!this.formData.email) {
                this.errors.email = 'Email wajib diisi';
            } else if (!/^\S+@\S+\.\S+$/.test(this.formData.email)) {
                this.errors.email = 'Format email tidak valid';
            }
            if (!this.formData.password) {
                this.errors.password = 'Password wajib diisi';
            } else if (this.formData.password.length < 8) {
                this.errors.password = 'Password minimal 8 karakter';
            }
            if (this.formData.password !== this.formData.password_confirmation) {
                this.errors.password_confirmation = 'Konfirmasi password tidak cocok';
            }
            return Object.keys(this.errors).length === 0;
        },
        validateStep2() {
            this.errors = {};
            if (!this.formData.customer_name) this.errors.customer_name = 'Nama toko/instansi wajib diisi';
            if (!this.formData.phone) this.errors.phone = 'Nomor WhatsApp wajib diisi';
            if (!this.formData.zone_id) this.errors.zone_id = 'Wilayah wajib dipilih';
            if (!this.formData.address) this.errors.address = 'Alamat lengkap wajib diisi';
            return Object.keys(this.errors).length === 0;
        },
        nextStep() {
            if (this.validateStep1()) {
                this.step = 2;
                window.scrollTo(0, 0);
            }
        },
        submitForm(e) {
            if (!this.validateStep2()) {
                e.preventDefault();
            }
        }
    }">
        <!-- Logo Section -->
        <div class="flex flex-col items-center mb-10">
            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="w-12 h-12 object-cover rounded-xl mb-4 shadow-lg shadow-brand-pink/20">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">CV. PRIMA <span
                    class="text-brand-blue-dark">AMANAH</span></h1>
            <p class="text-sm text-slate-400 font-medium mt-1">Management System</p>
        </div>

        <!-- Auth Card -->
        <div class="bg-white rounded-2xl border border-slate-100 custom-shadow p-8 auth-card overflow-hidden">
            
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-900">Create New Account</h2>
                <p class="text-xs text-slate-500 font-medium mt-1">Join our community and manage your orders easily.</p>
                
                <!-- Progress Bar -->
                <div class="mt-6 flex items-center gap-2">
                    <div class="flex-1 h-1.5 rounded-full overflow-hidden bg-slate-100">
                        <div class="h-full bg-brand-blue transition-all duration-500" :style="`width: ${step === 1 ? '50%' : '100%'}`"></div>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap" x-text="`Step ${step} of 2` text-slate-400">Step 1 of 2</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 flex items-start gap-3">
                    <div class="w-5 h-5 rounded-full bg-rose-500 flex-shrink-0 flex items-center justify-center mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-rose-600 uppercase tracking-widest mb-1">Registrasi Gagal</p>
                        @foreach ($errors->all() as $error)
                            <p class="text-[11px] text-rose-500 font-medium leading-relaxed">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('post.register') }}" method="POST" @submit="submitForm">
                @csrf
                
                <!-- Step 1: Account Info -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name" x-model="formData.name" placeholder="John Doe"
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:ring-1 outline-none transition-all"
                            :class="errors.name ? 'border-rose-300 ring-rose-50' : 'border-slate-200 focus:ring-brand-blue focus:border-brand-blue'">
                        <p x-show="errors.name" class="text-[10px] font-bold text-rose-500 uppercase tracking-tight mt-1" x-text="errors.name"></p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email Address</label>
                        <input type="email" name="email" x-model="formData.email" placeholder="name@company.com"
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:ring-1 outline-none transition-all"
                            :class="errors.email ? 'border-rose-300 ring-rose-50' : 'border-slate-200 focus:ring-brand-blue focus:border-brand-blue'">
                        <p x-show="errors.email" class="text-[10px] font-bold text-rose-500 uppercase tracking-tight mt-1" x-text="errors.email"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Password</label>
                            <input type="password" name="password" x-model="formData.password" placeholder="••••••••"
                                class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:ring-1 outline-none transition-all"
                                :class="errors.password ? 'border-rose-300 ring-rose-50' : 'border-slate-200 focus:ring-brand-blue focus:border-brand-blue'">
                            <p x-show="errors.password" class="text-[10px] font-bold text-rose-500 uppercase tracking-tight mt-1" x-text="errors.password"></p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Confirm</label>
                            <input type="password" name="password_confirmation" x-model="formData.password_confirmation" placeholder="••••••••"
                                class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:ring-1 outline-none transition-all"
                                :class="errors.password_confirmation ? 'border-rose-300 ring-rose-50' : 'border-slate-200 focus:ring-brand-blue focus:border-brand-blue'">
                            <p x-show="errors.password_confirmation" class="text-[10px] font-bold text-rose-500 uppercase tracking-tight mt-1" x-text="errors.password_confirmation"></p>
                        </div>
                    </div>
                    
                    <button type="button" @click="nextStep()"
                        class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-md shadow-slate-200 flex items-center justify-center gap-2">
                        Next Step
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Step 2: Customer Info -->
                <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Toko / Instansi</label>
                        <input type="text" name="customer_name" x-model="formData.customer_name" placeholder="Toko Maju Jaya"
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:ring-1 outline-none transition-all"
                            :class="errors.customer_name ? 'border-rose-300 ring-rose-50' : 'border-slate-200 focus:ring-brand-blue focus:border-brand-blue'">
                        <p x-show="errors.customer_name" class="text-[10px] font-bold text-rose-500 uppercase tracking-tight mt-1" x-text="errors.customer_name"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">WhatsApp / Phone</label>
                            <input type="text" name="phone" x-model="formData.phone" placeholder="0812xxxx"
                                class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:ring-1 outline-none transition-all"
                                :class="errors.phone ? 'border-rose-300 ring-rose-50' : 'border-slate-200 focus:ring-brand-blue focus:border-brand-blue'">
                            <p x-show="errors.phone" class="text-[10px] font-bold text-rose-500 uppercase tracking-tight mt-1" x-text="errors.phone"></p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Wilayah (Zone)</label>
                            <select name="zone_id" x-model="formData.zone_id" class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:ring-1 outline-none transition-all appearance-none"
                                :class="errors.zone_id ? 'border-rose-300 ring-rose-50' : 'border-slate-200 focus:ring-brand-blue focus:border-brand-blue'">
                                <option value="">Pilih Wilayah</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id_zone }}">{{ $zone->zone_name }}</option>
                                @endforeach
                            </select>
                            <p x-show="errors.zone_id" class="text-[10px] font-bold text-rose-500 uppercase tracking-tight mt-1" x-text="errors.zone_id"></p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Lengkap</label>
                        <textarea name="address" x-model="formData.address" rows="3" placeholder="Jl. Raya No. 123..."
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:ring-1 outline-none transition-all"
                            :class="errors.address ? 'border-rose-300 ring-rose-50' : 'border-slate-200 focus:ring-brand-blue focus:border-brand-blue'"></textarea>
                        <p x-show="errors.address" class="text-[10px] font-bold text-rose-500 uppercase tracking-tight mt-1" x-text="errors.address"></p>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" @click="step = 1"
                            class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">
                            Back
                        </button>
                        <button type="submit"
                            class="flex-[2] py-3 bg-brand-blue-dark text-white font-bold rounded-xl hover:bg-brand-blue transition-all shadow-md shadow-brand-blue/20">
                            Complete Registration
                        </button>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-500 font-medium">Already have an account? 
                        <a href="{{ route('login') }}" class="text-brand-blue-dark font-bold hover:underline">Sign In Instead</a>
                    </p>
                </div>
            </form>

        </div>

        <!-- Footer Info -->
        <p class="mt-8 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">© 2026 CV. PRIMA AMANAH
            Digital System</p>
    </div>
</body>

</html>
