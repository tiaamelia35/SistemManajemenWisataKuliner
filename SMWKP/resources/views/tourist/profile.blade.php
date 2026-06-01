@extends('layouts.app')

@section('title', 'Profil Saya - SMWKP')

@section('content')
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur shadow-sm border-b border-outline-variant px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-surface/80 text-secondary hover:bg-surface transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <span class="material-symbols-outlined text-primary text-2xl font-bold">account_circle</span>
        <h1 class="text-lg font-bold text-primary">Profil Saya</h1>
    </div>
</header>

<!-- Main Container -->
<main class="flex-1 p-4 pb-24 space-y-4 max-w-xl mx-auto w-full">
    
    <!-- User Avatar & Core info -->
    <div class="bg-surface border border-outline-variant/30 p-6 rounded-2xl flex flex-col items-center text-center space-y-3 shadow-sm relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#af101a_1px,transparent_1px)] [background-size:12px_12px]"></div>
        
        <div class="relative w-20 h-20 rounded-full bg-primary/10 border-2 border-primary flex items-center justify-center text-primary font-bold text-2xl shadow overflow-hidden">
            @if($user->profile_photo && $user->profile_photo !== 'default.jpg' && file_exists(public_path('uploads/profiles/' . $user->profile_photo)))
                <img src="/uploads/profiles/{{ $user->profile_photo }}" alt="Profile Photo" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($user->name, 0, 2)) }}
            @endif
        </div>
        
        <div class="relative z-10">
            <h2 class="font-bold text-base text-on-surface">{{ $user->name }}</h2>
            <p class="text-xs text-gray-400 font-medium">{{ $user->email }}</p>
            <span class="inline-block bg-primary/5 text-primary text-[10px] font-bold px-3 py-1 rounded-full border border-primary/20 uppercase tracking-wider mt-2">{{ $user->role }}</span>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl shadow-sm">
        <h3 class="font-bold text-xs text-secondary uppercase tracking-wider mb-4 flex items-center gap-1"><span class="material-symbols-outlined text-base">manage_accounts</span> Pengaturan Profil</h3>
        
        <form action="{{ route('tourist.profile.post') }}" method="POST" enctype="multipart/form-electron" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-semibold text-secondary mb-1">NAMA LENGKAP</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">person</span>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-xs bg-gray-50/50">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-semibold text-secondary mb-1">NOMOR TELEPON AKTIF</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">call</span>
                    <input type="text" name="phone_number" value="{{ $user->phone_number }}" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-xs bg-gray-50/50">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-semibold text-secondary mb-1">FOTO PROFIL (BISA DIOSONGKAN)</label>
                <input type="file" name="profile_photo" class="w-full text-xs text-gray-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 border border-gray-200 rounded-xl p-1 bg-gray-50/50">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-primary hover:bg-primary/95 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-1">
                    Simpan Perubahan <span class="material-symbols-outlined text-xs">save</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Log Out Panel -->
    <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <p class="text-xs font-bold text-on-surface">Keluar dari Aplikasi</p>
            <p class="text-[10px] text-gray-400">Akhiri sesi aktif Anda di perangkat ini.</p>
        </div>
        <a href="{{ route('logout') }}" class="bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1 shadow-sm transition-colors">
            Logout <span class="material-symbols-outlined text-sm font-bold">logout</span>
        </a>
    </div>

</main>


@endsection
