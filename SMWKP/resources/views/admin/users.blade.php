@extends('layouts.app')

@section('title', 'Manajemen Pengguna | Admin SMWKP')

@section('content')
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur shadow-sm border-b border-outline-variant px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="text-secondary hover:text-primary flex items-center justify-center p-1">
            <span class="material-symbols-outlined font-bold">arrow_back</span>
        </a>
        <h1 class="text-base font-bold text-primary">Manajemen Pengguna</h1>
    </div>
    <div class="flex items-center gap-4">
        <nav class="hidden sm:flex items-center gap-4 text-xs font-semibold text-secondary">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors pb-1">Dashboard</a>
            <a href="{{ route('admin.users') }}" class="text-primary border-b-2 border-primary pb-1">User Management</a>
            <a href="{{ route('admin.reports') }}" class="hover:text-primary transition-colors pb-1">Laporan Grafik</a>
        </nav>
    </div>
</header>

<!-- Main Container -->
<main class="flex-1 p-4 pb-24 space-y-4 max-w-4xl mx-auto w-full">
    
    <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl">
        <h2 class="font-bold text-sm text-on-surface uppercase mb-1">Daftar Pengguna Sistem ({{ $users->count() }})</h2>
        <p class="text-xs text-gray-400">Kelola otorisasi akun pengguna, tinjau nomor telepon terdaftar, serta pantau aktivitas pembuatan transaksi.</p>
    </div>

    <!-- Users Table Card -->
    <div class="bg-surface border border-outline-variant/30 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-secondary uppercase tracking-wider">
                        <th class="p-3.5 pl-4">Pengguna</th>
                        <th class="p-3.5">Email</th>
                        <th class="p-3.5">Nomor Telepon</th>
                        <th class="p-3.5">Peran</th>
                        <th class="p-3.5 text-center">Aktivitas</th>
                        <th class="p-3.5 pr-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50">
                            <!-- User Name & Avatar -->
                            <td class="p-3.5 pl-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center font-bold text-primary text-[10px] uppercase">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span class="font-semibold text-on-surface">{{ $user->name }}</span>
                            </td>
                            <!-- Email -->
                            <td class="p-3.5 text-secondary font-medium">{{ $user->email }}</td>
                            <!-- Phone -->
                            <td class="p-3.5 text-secondary font-medium">{{ $user->phone_number ?: '-' }}</td>
                            <!-- Role -->
                            <td class="p-3.5">
                                @if($user->role === 'admin')
                                    <span class="bg-purple-50 text-purple-700 border border-purple-200 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase">Admin</span>
                                @elseif($user->role === 'owner')
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase">Owner</span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 border border-gray-200 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase">Tourist</span>
                                @endif
                            </td>
                            <!-- Stats Count Activity -->
                            <td class="p-3.5 text-center font-semibold text-secondary">
                                @if($user->role === 'owner')
                                    {{ $user->restaurants_count }} Resto
                                @elseif($user->role === 'tourist')
                                    {{ $user->bookings_count }} Booking
                                @else
                                    -
                                @endif
                            </td>
                            <!-- Action Trigger button -->
                            <td class="p-3.5 pr-4 text-right">
                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-white border border-gray-200 text-secondary hover:bg-gray-50 text-[10px] font-bold px-2.5 py-1.5 rounded-lg shadow-sm transition-all focus:outline-none">
                                        Perbarui Status
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</main>
@endsection
