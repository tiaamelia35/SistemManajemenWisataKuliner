@extends('layouts.app')

@section('title', 'Laporan & Grafik | Admin SMWKP')

@section('content')
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur shadow-sm border-b border-outline-variant px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="text-secondary hover:text-primary flex items-center justify-center p-1">
            <span class="material-symbols-outlined font-bold">arrow_back</span>
        </a>
        <h1 class="text-base font-bold text-primary">Laporan & Grafik Pertumbuhan</h1>
    </div>
    <div class="flex items-center gap-4">
        <nav class="hidden sm:flex items-center gap-4 text-xs font-semibold text-secondary">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors pb-1">Dashboard</a>
            <a href="{{ route('admin.users') }}" class="hover:text-primary transition-colors pb-1">User Management</a>
            <a href="{{ route('admin.reports') }}" class="text-primary border-b-2 border-primary pb-1">Laporan Grafik</a>
        </nav>
    </div>
</header>

<!-- Main Container -->
<main class="flex-1 p-4 pb-24 space-y-5 max-w-4xl mx-auto w-full">
    
    <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl">
        <h2 class="font-bold text-sm text-on-surface uppercase mb-1">Perkembangan Transaksi Wisata Kuliner</h2>
        <p class="text-xs text-gray-400">Analisis visual data bulanan untuk jumlah pendaftaran akun baru, transaksi booking terdaftar, serta omset pendapatan.</p>
    </div>

    <!-- Charts Row -->
    <div class="grid md:grid-cols-2 gap-4">
        
        <!-- 1. Booking Growth Chart (Line Chart) -->
        <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl shadow-sm space-y-3">
            <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-0.5"><span class="material-symbols-outlined text-base">show_chart</span> Tren Reservasi Bulanan</h3>
            <div class="relative h-64 w-full">
                <canvas id="chart-bookings"></canvas>
            </div>
        </div>

        <!-- 2. User Registrations Chart (Bar Chart) -->
        <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl shadow-sm space-y-3">
            <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-0.5"><span class="material-symbols-outlined text-base">bar_chart</span> Pendaftaran Akun Wisatawan Baru</h3>
            <div class="relative h-64 w-full">
                <canvas id="chart-users"></canvas>
            </div>
        </div>

    </div>

    <!-- Top Restaurants List -->
    <section class="space-y-3">
        <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-0.5"><span class="material-symbols-outlined text-base">emoji_events</span> Restoran Terpopuler (Volume Booking Terbanyak)</h3>
        
        <div class="bg-surface border border-outline-variant/30 rounded-2xl overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-secondary uppercase tracking-wider">
                        <th class="p-3.5 pl-4">Restoran</th>
                        <th class="p-3.5">Alamat</th>
                        <th class="p-3.5 text-center">Jumlah Menu</th>
                        <th class="p-3.5 text-center">Total Reservasi</th>
                        <th class="p-3.5 pr-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($topRestaurants as $index => $rest)
                        <tr class="hover:bg-gray-50/50">
                            <!-- Restaurant Rank & Name -->
                            <td class="p-3.5 pl-4 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[10px] 
                                    {{ $index === 0 ? 'bg-amber-100 text-amber-800' : ($index === 1 ? 'bg-slate-100 text-slate-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $index + 1 }}
                                </span>
                                <span class="font-bold text-on-surface">{{ $rest->name }}</span>
                            </td>
                            <!-- Address -->
                            <td class="p-3.5 text-secondary font-medium">{{ $rest->address }}</td>
                            <!-- Menus count -->
                            <td class="p-3.5 text-center font-semibold text-secondary">{{ $rest->menus->count() }} Hidangan</td>
                            <!-- Bookings count -->
                            <td class="p-3.5 text-center font-bold text-primary">{{ $rest->bookings_count }} Kali</td>
                            <!-- Status -->
                            <td class="p-3.5 pr-4 text-right">
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold px-2.5 py-0.5 rounded-full uppercase">Aktif</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

</main>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Chart labels (Months)
        const labels = {!! json_encode($months) !!};

        // 1. Line Chart: Bookings & Revenue
        const ctxBookings = document.getElementById('chart-bookings').getContext('2d');
        new Chart(ctxBookings, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Booking',
                        data: {!! json_encode($bookingCounts) !!},
                        borderColor: '#af101a', // Ampera Red
                        backgroundColor: '#af101a20',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Omset Omset (Juta Rp)',
                        data: {!! json_encode($revenueData) !!},
                        borderColor: '#5d5f5f',
                        backgroundColor: '#5d5f5f10',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { size: 10, weight: 'semibold' }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Bar Chart: User registrations
        const ctxUsers = document.getElementById('chart-users').getContext('2d');
        new Chart(ctxUsers, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registrasi Akun Baru',
                    data: {!! json_encode($userRegistrations) !!},
                    backgroundColor: '#af101a', // Ampera Red bar fill
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
