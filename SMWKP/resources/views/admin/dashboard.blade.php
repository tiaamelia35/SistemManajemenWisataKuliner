@extends('layouts.app')

@section('title', 'Admin Console | SMWKP')

@section('content')
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur shadow-sm border-b border-outline-variant px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-primary text-2xl font-bold">shield_person</span>
        <h1 class="text-base font-bold text-primary">Konsol Admin Sistem</h1>
    </div>
    <div class="flex items-center gap-4">
        <nav class="hidden sm:flex items-center gap-4 text-xs font-semibold text-secondary">
            <a href="{{ route('admin.dashboard') }}" class="text-primary border-b-2 border-primary pb-1">Dashboard</a>
            <a href="{{ route('admin.users') }}" class="hover:text-primary transition-colors pb-1">User Management</a>
            <a href="{{ route('admin.reports') }}" class="hover:text-primary transition-colors pb-1">Laporan Grafik</a>
        </nav>
        <a href="{{ route('logout') }}" class="text-secondary hover:text-primary flex items-center justify-center p-1" title="Keluar">
            <span class="material-symbols-outlined text-lg">logout</span>
        </a>
    </div>
</header>

<!-- Main Container -->
<main class="flex-1 p-4 space-y-5 max-w-4xl mx-auto w-full">
    
    <!-- Mobile Navigation Bar -->
    <div class="sm:hidden bg-surface border border-outline-variant/30 p-2 rounded-xl flex justify-around text-[10px] font-bold text-secondary">
        <a href="{{ route('admin.dashboard') }}" class="text-primary flex items-center gap-0.5"><span class="material-symbols-outlined text-sm">dashboard</span> Dashboard</a>
        <a href="{{ route('admin.users') }}" class="flex items-center gap-0.5"><span class="material-symbols-outlined text-sm">group</span> Users</a>
        <a href="{{ route('admin.reports') }}" class="flex items-center gap-0.5"><span class="material-symbols-outlined text-sm">bar_chart</span> Laporan</a>
    </div>

    <!-- Overall KPI metrics -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-surface border border-outline-variant/30 p-3.5 rounded-2xl shadow-sm">
            <p class="text-[9px] font-bold text-secondary uppercase tracking-wider">Total Pengguna</p>
            <p class="text-xl font-bold text-on-surface mt-0.5">{{ $usersCount }}</p>
            <p class="text-[8px] text-gray-400 mt-1">Wisatawan & Pemilik</p>
        </div>
        <div class="bg-surface border border-outline-variant/30 p-3.5 rounded-2xl shadow-sm">
            <p class="text-[9px] font-bold text-secondary uppercase tracking-wider">Resto Publik</p>
            <p class="text-xl font-bold text-emerald-600 mt-0.5">{{ $approvedRestaurantsCount }}</p>
            <p class="text-[8px] text-emerald-600 mt-1 font-semibold">Tersertifikasi Halal</p>
        </div>
        <div class="bg-surface border border-outline-variant/30 p-3.5 rounded-2xl shadow-sm">
            <p class="text-[9px] font-bold text-secondary uppercase tracking-wider">Total Booking</p>
            <p class="text-xl font-bold text-on-surface mt-0.5">{{ $totalBookingsCount }}</p>
            <p class="text-[8px] text-gray-400 mt-1">Sistem Transaksi</p>
        </div>
        <div class="bg-surface border border-outline-variant/30 p-3.5 rounded-2xl shadow-sm">
            <p class="text-[9px] font-bold text-secondary uppercase tracking-wider">Butuh Tindakan</p>
            <p class="text-xl font-bold text-primary mt-0.5">{{ $actionRequiredCount }}</p>
            <p class="text-[8px] text-primary mt-1 font-semibold">Persetujuan Pending</p>
        </div>
    </section>

    <div class="grid md:grid-cols-3 gap-5">
        
        <!-- Left 2 Cols: Action Required Approvals & Reviews Moderation -->
        <div class="md:col-span-2 space-y-5">
            
            <!-- 1. ACTION REQUIRED APPROVALS -->
            <section class="space-y-2.5">
                <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-1"><span class="material-symbols-outlined text-base">gavel</span> Action Required (Persetujuan)</h3>
                
                @if($pendingRestaurants->isEmpty() && $pendingCertifications->isEmpty())
                    <div class="bg-surface border border-outline-variant/30 p-6 rounded-2xl text-center text-gray-400 text-xs">
                        Tidak ada restoran atau sertifikasi yang membutuhkan persetujuan saat ini.
                    </div>
                @else
                    <div class="space-y-3">
                        <!-- Pending Restaurants -->
                        @foreach($pendingRestaurants as $rest)
                            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl shadow-sm flex flex-col justify-between gap-3">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[8px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Resto Baru</span>
                                        <h4 class="font-bold text-sm text-on-surface mt-1">{{ $rest->name }}</h4>
                                        <p class="text-[10px] text-gray-400 flex items-center gap-0.5 mt-0.5"><span class="material-symbols-outlined text-xs">person</span> Pemilik: {{ $rest->owner->name }} ({{ $rest->owner->email }})</p>
                                        <p class="text-[10px] text-secondary mt-1.5 font-light leading-relaxed">{{ $rest->description }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2 justify-end border-t border-gray-50 pt-3">
                                    <form action="{{ route('admin.verify.restaurant', $rest->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="bg-white border border-outline text-secondary hover:bg-gray-50 text-[10px] font-bold px-3 py-1.5 rounded-lg">Tolak</button>
                                    </form>
                                    <form action="{{ route('admin.verify.restaurant', $rest->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="bg-primary hover:bg-primary/95 text-white text-[10px] font-bold px-4 py-1.5 rounded-lg shadow-sm flex items-center gap-0.5"><span class="material-symbols-outlined text-xs">verified</span> Setujui</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pending Certifications -->
                        @foreach($pendingCertifications as $cert)
                            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl shadow-sm flex flex-col justify-between gap-3">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="bg-primary/5 text-primary border border-primary/20 text-[8px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Sertifikat Halal</span>
                                        <h4 class="font-bold text-sm text-on-surface mt-1">{{ $cert->restaurant->name }}</h4>
                                        <p class="text-[10px] text-gray-400 mt-1">Nomor: <span class="font-semibold text-on-surface">{{ $cert->certificate_number }}</span></p>
                                        <p class="text-[10px] text-gray-400">Penerbit: {{ $cert->issued_by }} | Kadaluarsa: {{ $cert->expiry_date->format('d M Y') }}</p>
                                    </div>
                                    @if($cert->certificate_file)
                                        <span class="bg-gray-100 text-gray-600 text-[8px] font-semibold px-2 py-1 rounded border border-gray-200">PDF ATTACHED</span>
                                    @endif
                                </div>
                                <div class="flex gap-2 justify-end border-t border-gray-50 pt-3">
                                    <form action="{{ route('admin.verify.certification', $cert->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="bg-white border border-outline text-secondary hover:bg-gray-50 text-[10px] font-bold px-3 py-1.5 rounded-lg">Tolak</button>
                                    </form>
                                    <form action="{{ route('admin.verify.certification', $cert->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="bg-primary hover:bg-primary/95 text-white text-[10px] font-bold px-4 py-1.5 rounded-lg shadow-sm flex items-center gap-0.5"><span class="material-symbols-outlined text-xs">verified</span> Setujui</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- 2. REVIEWS MODERATION -->
            <section class="space-y-2.5">
                <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-1"><span class="material-symbols-outlined text-base">reviews</span> Review Moderasi</h3>
                
                @if($allReviews->isEmpty())
                    <div class="bg-surface border border-outline-variant/30 p-6 rounded-2xl text-center text-gray-400 text-xs">
                        Belum ada ulasan yang dibuat wisatawan.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($allReviews as $review)
                            <div class="bg-surface border border-outline-variant/30 p-3 rounded-2xl shadow-sm flex flex-col justify-between gap-2.5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-1">
                                            <span class="font-bold text-xs text-on-surface">{{ $review->tourist->name }}</span>
                                            <span class="text-gray-400 text-[10px] font-medium">di</span>
                                            <span class="font-bold text-xs text-primary">{{ $review->restaurant->name }}</span>
                                        </div>
                                        <div class="flex items-center text-amber-500 font-bold text-[10px] gap-0.5 mt-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? 1 : 0 }};">star</span>
                                            @endfor
                                            <span class="ml-1 text-secondary font-medium">{{ $review->rating }}.0</span>
                                        </div>
                                    </div>
                                    @if($review->status === 'pending')
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[8px] font-bold px-1.5 py-0.5 rounded">PENDING APPROVAL</span>
                                    @else
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[8px] font-bold px-1.5 py-0.5 rounded">APPROVED</span>
                                    @endif
                                </div>
                                <p class="text-xs text-secondary leading-relaxed font-light">{{ $review->review_text }}</p>
                                
                                <div class="flex gap-2 justify-end border-t border-gray-50 pt-2 text-[9px] font-bold">
                                    <form action="{{ route('admin.reviews.status', $review->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" onclick="return confirm('Hapus ulasan ini?')" class="text-rose-600 hover:bg-rose-50 px-2.5 py-1.5 rounded-md border border-rose-200 transition-colors">Hapus</button>
                                    </form>
                                    @if($review->status === 'pending')
                                        <form action="{{ route('admin.reviews.status', $review->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="bg-primary hover:bg-primary/95 text-white px-3 py-1.5 rounded-md shadow-sm transition-colors">Approve</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>

        <!-- Right 1 Col: Audit Activity Logs -->
        <div class="space-y-2.5">
            <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-1"><span class="material-symbols-outlined text-base">history</span> Log Aktivitas Sistem</h3>
            
            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl shadow-sm space-y-4 max-h-[500px] overflow-y-auto pr-1">
                @if($logs->isEmpty())
                    <p class="text-center text-gray-400 text-xs py-4">Belum ada catatan aktivitas.</p>
                @else
                    <div class="relative pl-4 border-l border-gray-200 space-y-4 text-[10px]">
                        @foreach($logs as $log)
                            <div class="relative">
                                <!-- Dot symbol -->
                                <span class="absolute -left-[20.5px] top-1 w-2.5 h-2.5 rounded-full border-2 border-primary bg-white shadow-sm"></span>
                                <div class="space-y-0.5">
                                    <span class="font-bold text-primary block tracking-wider uppercase text-[8px]">{{ str_replace('_', ' ', $log->action) }}</span>
                                    <p class="text-on-surface-variant font-light leading-normal">{{ $log->details }}</p>
                                    <span class="text-[8px] text-gray-400 block mt-0.5">{{ $log->created_at->diffForHumans() }} @if($log->user)• {{ $log->user->name }}@endif</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

</main>
@endsection
