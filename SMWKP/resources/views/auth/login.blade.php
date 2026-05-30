@extends('layouts.app')

@section('title', 'Masuk & Daftar | SMWKP Palembang')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-background">
    <div class="bg-surface rounded-2xl shadow-xl overflow-hidden max-w-4xl w-full grid md:grid-cols-2 border border-outline-variant/30">
        
        <!-- Left Side: Decorative Branding -->
        <div class="bg-primary p-8 text-white flex flex-col justify-between relative overflow-hidden md:flex">
            <!-- Pattern Overlay -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-8">
                    <span class="material-symbols-outlined text-3xl font-bold">restaurant_menu</span>
                    <span class="font-bold text-xl tracking-wider">SMWKP</span>
                </div>
                <h2 class="text-3xl font-bold leading-tight mb-4">Jelajahi Cita Rasa Otentik Palembang</h2>
                <p class="text-white/80 text-sm leading-relaxed">
                    Sistem Manajemen Wisata Kuliner Palembang mempermudah wisatawan menemukan kuliner tersertifikasi dan membantu pemilik restoran menjangkau lebih banyak penikmat rasa.
                </p>
            </div>
            
            <div class="relative z-10 mt-12 pt-6 border-t border-white/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center font-bold">PLG</div>
                    <div>
                        <p class="text-xs text-white/60">Destinasi Kuliner</p>
                        <p class="text-sm font-semibold">Jembatan Ampera, Palembang</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Forms (Login / Register) -->
        <div class="p-8 flex flex-col justify-center">
            
            <!-- Tabs Header -->
            <div class="flex border-b border-gray-200 mb-6">
                <button id="tab-login" onclick="switchTab('login')" class="flex-1 pb-3 text-center font-semibold text-sm border-b-2 transition-colors duration-200 focus:outline-none">
                    Masuk
                </button>
                <button id="tab-register" onclick="switchTab('register')" class="flex-1 pb-3 text-center font-semibold text-sm border-b-2 transition-colors duration-200 focus:outline-none">
                    Daftar Akun
                </button>
            </div>

            <!-- 1. LOGIN FORM -->
            <form id="form-login" action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-secondary mb-1">EMAIL RESTORAN / TOURIST</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50" placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-secondary mb-1">PASSWORD</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">lock</span>
                        <input type="password" name="password" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-secondary cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary">
                        Ingat Saya
                    </label>
                    <a href="#" class="text-primary font-semibold hover:underline">Lupa Password?</a>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary/95 text-white font-semibold py-2.5 rounded-xl shadow-md transition-all duration-200 text-sm flex items-center justify-center gap-2 mt-4">
                    Masuk Sekarang <span class="material-symbols-outlined text-sm">login</span>
                </button>

                <!-- Seed Account Info Banner -->
                <div class="mt-6 p-3 bg-gray-50 border border-gray-100 rounded-xl text-[11px] text-gray-500 space-y-1">
                    <p class="font-semibold text-gray-700">Akun Uji Coba Cepat (Seeded):</p>
                    <p>• <span class="font-semibold">Admin</span>: admin@smwkp.com / password</p>
                    <p>• <span class="font-semibold">Owner</span>: owner@smwkp.com / password</p>
                    <p>• <span class="font-semibold">Tourist</span>: tourist@smwkp.com / password</p>
                </div>
            </form>

            <!-- 2. REGISTER FORM -->
            <form id="form-register" action="{{ route('register.post') }}" method="POST" class="space-y-3 hidden">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-secondary mb-1">NAMA LENGKAP</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">person</span>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50" placeholder="Nama Lengkap Anda">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-secondary mb-1">EMAIL AKTIF</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50" placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-secondary mb-1">NOMOR TELEPON</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">call</span>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-secondary mb-1">PASSWORD</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">lock</span>
                            <input type="password" name="password" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50" placeholder="Min. 8 Karakter">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-secondary mb-1">KONFIRMASI</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">lock_reset</span>
                            <input type="password" name="password_confirmation" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50" placeholder="Ulangi Password">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-secondary mb-1">PERAN AKUN (ROLE)</label>
                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 text-sm">
                            <span class="flex items-center gap-2 font-medium">
                                <span class="material-symbols-outlined text-primary text-lg">travel</span> Wisatawan
                            </span>
                            <input type="radio" name="role" value="tourist" checked class="text-primary focus:ring-primary">
                        </label>
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 text-sm">
                            <span class="flex items-center gap-2 font-medium">
                                <span class="material-symbols-outlined text-primary text-lg">storefront</span> Pemilik Resto
                            </span>
                            <input type="radio" name="role" value="owner" class="text-primary focus:ring-primary">
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary/95 text-white font-semibold py-2.5 rounded-xl shadow-md transition-all duration-200 text-sm flex items-center justify-center gap-2 mt-3">
                    Daftar Sekarang <span class="material-symbols-outlined text-sm">how_to_reg</span>
                </button>
            </form>

        </div>
    </div>
</div>

<script>
    // Tab Switching Logic
    function switchTab(tab) {
        const loginForm = document.getElementById('form-login');
        const registerForm = document.getElementById('form-register');
        const tabLogin = document.getElementById('tab-login');
        const tabRegister = document.getElementById('tab-register');

        if (tab === 'login') {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            tabLogin.classList.add('border-primary', 'text-primary');
            tabLogin.classList.remove('border-transparent', 'text-gray-400');
            tabRegister.classList.remove('border-primary', 'text-primary');
            tabRegister.classList.add('border-transparent', 'text-gray-400');
        } else {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            tabRegister.classList.add('border-primary', 'text-primary');
            tabRegister.classList.remove('border-transparent', 'text-gray-400');
            tabLogin.classList.remove('border-primary', 'text-primary');
            tabLogin.classList.add('border-transparent', 'text-gray-400');
        }
    }

    // Set initial tab state based on session validation errors
    document.addEventListener("DOMContentLoaded", function() {
        const activeTab = "{{ session('active_tab', 'login') }}";
        switchTab(activeTab);
    });
</script>
@endsection
