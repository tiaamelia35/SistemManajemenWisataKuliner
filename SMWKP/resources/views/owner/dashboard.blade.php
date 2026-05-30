@extends('layouts.app')

@section('title', 'Dashboard Pemilik | SMWKP')

@section('styles')
<style>
    #map-select { z-index: 1; }
</style>
@endsection

@section('content')
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur shadow-sm border-b border-outline-variant px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-primary text-2xl font-bold">dashboard</span>
        <h1 class="text-base font-bold text-primary">Dashboard Pemilik</h1>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs font-semibold text-secondary bg-gray-100 px-3 py-1 rounded-full border border-gray-200">{{ $restaurant->name }}</span>
        <a href="{{ route('logout') }}" class="text-secondary hover:text-primary flex items-center justify-center p-1" title="Keluar">
            <span class="material-symbols-outlined text-lg">logout</span>
        </a>
    </div>
</header>

<!-- Main Container -->
<main class="flex-1 p-4 pb-24 space-y-5 max-w-xl mx-auto w-full">
    
    <!-- Greeting & Status -->
    <section class="flex justify-between items-start">
        <div>
            <h2 class="text-lg font-bold text-on-surface">Selamat Datang, {{ Auth::user()->name }}</h2>
            <p class="text-xs text-gray-400">Restoran: <span class="font-semibold text-primary">{{ $restaurant->name }}</span></p>
        </div>
        <div class="flex items-center gap-1.5 bg-emerald-50 text-emerald-800 text-[10px] font-bold px-3 py-1 rounded-full border border-emerald-200">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span> RESTORAN AKTIF
        </div>
    </section>

    <!-- Core Metrics Grid -->
    <section class="grid grid-cols-2 gap-3">
        <div class="bg-surface border border-outline-variant/30 p-3.5 rounded-2xl shadow-sm">
            <p class="text-[10px] font-bold text-secondary uppercase tracking-wider">Pendapatan</p>
            <p class="text-lg font-bold text-primary mt-1">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
            <div class="flex items-center text-emerald-600 text-[9px] font-semibold mt-1">
                <span class="material-symbols-outlined text-xs">trending_up</span>
                <span class="ml-0.5">+12% minggu ini</span>
            </div>
        </div>
        <div class="bg-surface border border-outline-variant/30 p-3.5 rounded-2xl shadow-sm">
            <p class="text-[10px] font-bold text-secondary uppercase tracking-wider">Total Reservasi</p>
            <p class="text-lg font-bold text-on-surface mt-1">{{ $bookingsCount }}</p>
            <div class="flex items-center text-amber-600 text-[9px] font-semibold mt-1">
                <span class="material-symbols-outlined text-xs">pending</span>
                <span class="ml-0.5">{{ $pendingBookingsCount }} Menunggu</span>
            </div>
        </div>
        <div class="bg-surface border border-outline-variant/30 p-3.5 rounded-2xl shadow-sm">
            <p class="text-[10px] font-bold text-secondary uppercase tracking-wider">Pengunjung Resto</p>
            <p class="text-lg font-bold text-on-surface mt-1">1.208</p>
            <div class="flex items-center text-emerald-600 text-[9px] font-semibold mt-1">
                <span class="material-symbols-outlined text-xs">visibility</span>
                <span class="ml-0.5">+15% kunjungan</span>
            </div>
        </div>
        <div class="bg-surface border border-outline-variant/30 p-3.5 rounded-2xl shadow-sm">
            <p class="text-[10px] font-bold text-secondary uppercase tracking-wider">Rating Restoran</p>
            <div class="flex items-center mt-1">
                <p class="text-lg font-bold text-on-surface mr-1">{{ $averageRating }}</p>
                <span class="material-symbols-outlined text-amber-500 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            </div>
            <p class="text-[9px] text-gray-400 mt-1">{{ $reviewsCount }} Ulasan Disetujui</p>
        </div>
    </section>

    <!-- Certification Verification Status Banner -->
    <section class="bg-primary/5 border border-primary/20 p-4 rounded-2xl shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-primary uppercase tracking-wider">STATUS SERTIFIKASI HALAL</p>
            @if($restaurant->certification_status === 'approved')
                <p class="text-xs font-bold text-emerald-600 mt-0.5 flex items-center gap-0.5"><span class="material-symbols-outlined text-xs font-bold">verified</span> SERTIFIKASI SELESAI (100%)</p>
            @elseif($restaurant->certification_status === 'pending')
                <p class="text-xs font-bold text-amber-600 mt-0.5 flex items-center gap-0.5"><span class="material-symbols-outlined text-xs font-bold">hourglass_empty</span> MENUNGGU DI-REVIEW ADMIN</p>
            @else
                <p class="text-xs font-bold text-red-600 mt-0.5 flex items-center gap-0.5"><span class="material-symbols-outlined text-xs font-bold">cancel</span> BELUM TERSERTIFIKASI (85%)</p>
                <p class="text-[9px] text-on-surface-variant mt-0.5">Unggah dokumen sertifikat Halal Anda di bawah.</p>
            @endif
        </div>
    </section>

    <!-- Menu Listings (Horizontal Scroll Carousel) -->
    <section class="space-y-2.5">
        <div class="flex justify-between items-center">
            <h3 class="text-xs font-bold text-secondary uppercase tracking-wider">Daftar Menu Hidangan</h3>
            <a href="{{ route('owner.menus') }}" class="text-primary hover:text-primary-container text-xs font-bold flex items-center gap-0.5">
                <span class="material-symbols-outlined text-sm font-bold">add</span> Tambah Menu
            </a>
        </div>

        @if($menus->isEmpty())
            <div class="bg-surface border border-outline-variant/30 p-6 rounded-2xl text-center text-gray-400 text-xs">
                Belum ada menu yang didaftarkan.
            </div>
        @else
            <div class="flex gap-3 overflow-x-auto hide-scrollbar -mx-4 px-4 py-1">
                @foreach($menus as $menu)
                    <div class="min-w-[140px] bg-surface border border-outline-variant/30 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between">
                        <div class="h-24 w-full bg-gray-100 relative">
                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                            <!-- Delete button on card -->
                            <form action="{{ route('owner.menus.delete', $menu->id) }}" method="POST" class="absolute top-1 right-1">
                                @csrf
                                <button type="submit" onclick="return confirm('Hapus menu ini?')" class="w-6 h-6 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-primary transition-colors focus:outline-none">
                                    <span class="material-symbols-outlined text-xs">delete</span>
                                </button>
                            </form>
                        </div>
                        <div class="p-2">
                            <p class="font-bold text-[11px] text-on-surface truncate">{{ $menu->name }}</p>
                            <p class="text-primary font-bold text-[10px] mt-0.5">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Recent Booking Approvals Grid -->
    <section class="space-y-2.5">
        <h3 class="text-xs font-bold text-secondary uppercase tracking-wider">Reservasi Pelanggan Terkini</h3>
        
        @if($recentBookings->isEmpty())
            <div class="bg-surface border border-outline-variant/30 p-6 rounded-2xl text-center text-gray-400 text-xs">
                Belum ada pengajuan reservasi masuk.
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentBookings as $booking)
                    <div class="bg-surface border border-outline-variant/30 p-3 rounded-2xl space-y-3 shadow-sm">
                        
                        <!-- Top Summary Header -->
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-xs text-on-surface">{{ $booking->name }}</h4>
                                <p class="text-[9px] text-gray-400 flex items-center gap-0.5 mt-0.5"><span class="material-symbols-outlined text-xs">calendar_today</span> {{ $booking->booking_date->format('d M Y • H:i') }}</p>
                            </div>
                            
                            <!-- Badges -->
                            @if($booking->status === 'pending')
                                <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[9px] font-bold px-2 py-0.5 rounded-full">Menunggu</span>
                            @elseif($booking->status === 'confirmed')
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 text-[9px] font-bold px-2 py-0.5 rounded-full">Disetujui</span>
                            @elseif($booking->status === 'completed')
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold px-2 py-0.5 rounded-full">Selesai</span>
                            @else
                                <span class="bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-bold px-2 py-0.5 rounded-full">Batal</span>
                            @endif
                        </div>

                        <!-- Mid Summary Details -->
                        <div class="grid grid-cols-2 gap-2 text-[11px] pt-2 border-t border-gray-100">
                            <div>
                                <span class="text-gray-400 font-medium block">Nomor Telepon</span>
                                <span class="font-semibold text-on-surface">{{ $booking->phone_number }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 font-medium block">Jumlah Tamu</span>
                                <span class="font-semibold text-on-surface">{{ $booking->pax_count }} Orang</span>
                            </div>
                            <div class="col-span-2 pt-1">
                                <span class="text-gray-400 font-medium block">Menu yang Dipesan:</span>
                                <div class="mt-1 pl-2 border-l border-primary/20 space-y-0.5 text-[10px]">
                                    @foreach($booking->menus as $menu)
                                        <p class="text-on-surface-variant"><span class="font-semibold">{{ $menu->pivot->quantity }}x</span> {{ $menu->name }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Action Approval Forms -->
                        @if($booking->status === 'pending' || $booking->status === 'confirmed')
                            <div class="pt-2 border-t border-gray-100 flex gap-2">
                                @if($booking->status === 'pending')
                                    <form action="{{ route('owner.bookings.status', $booking->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="w-full bg-primary hover:bg-primary/95 text-white text-[10px] font-bold py-1.5 rounded-lg shadow-sm flex items-center justify-center gap-0.5">
                                            <span class="material-symbols-outlined text-xs">done</span> Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.bookings.status', $booking->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="w-full bg-white border border-outline text-secondary hover:bg-gray-50 text-[10px] font-bold py-1.5 rounded-lg">
                                            Tolak
                                        </button>
                                    </form>
                                @elseif($booking->status === 'confirmed')
                                    <form action="{{ route('owner.bookings.status', $booking->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold py-1.5 rounded-lg shadow-sm flex items-center justify-center gap-0.5">
                                            <span class="material-symbols-outlined text-xs">check_circle</span> Tandai Selesai Makan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Profile & Restaurant Settings Form -->
    <section class="bg-surface border border-outline-variant/30 p-4 rounded-2xl shadow-sm space-y-4">
        <h3 class="font-bold text-xs text-secondary uppercase tracking-wider flex items-center gap-1"><span class="material-symbols-outlined text-base">storefront</span> Informasi Restoran & Sertifikat</h3>
        
        <form action="{{ route('owner.restaurant.update') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-[10px] font-semibold text-secondary mb-1">NAMA RESTORAN KULINER</label>
                <input type="text" name="name" value="{{ $restaurant->name }}" required class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50">
            </div>

            <div>
                <label class="block text-[10px] font-semibold text-secondary mb-1">DESKRIPSI KULINER</label>
                <textarea name="description" rows="2" class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50">{{ $restaurant->description }}</textarea>
            </div>

            <div>
                <label class="block text-[10px] font-semibold text-secondary mb-1">ALAMAT RESTORAN LENGKAP</label>
                <input type="text" name="address" value="{{ $restaurant->address }}" required class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50">
            </div>

            <!-- Latitude / Longitude & Map coordinate selector -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-semibold text-secondary mb-1">LATITUDE (GARIS LINTANG)</label>
                    <input type="text" name="latitude" id="lat-select" value="{{ $restaurant->latitude ?: -2.983333 }}" required class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-secondary mb-1">LONGITUDE (GARIS BUJUR)</label>
                    <input type="text" name="longitude" id="lng-select" value="{{ $restaurant->longitude ?: 104.75 }}" required class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50">
                </div>
            </div>
            
            <div class="space-y-1">
                <label class="block text-[10px] font-semibold text-secondary mb-1">KLIK DI PETA UNTUK MEMILIH KOORDINAT</label>
                <div id="map-select" class="h-36 w-full rounded-xl bg-gray-100 border border-gray-200 shadow-inner"></div>
            </div>

            <!-- Upload Halal Certificate -->
            <div class="pt-3 border-t border-gray-100 space-y-3">
                <h4 class="text-xs font-bold text-primary uppercase">Ajukan Sertifikasi Halal Restoran</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-secondary mb-1">NOMOR SERTIFIKAT</label>
                        <input type="text" name="certificate_number" class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50" placeholder="Contoh: ID3111000...">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-secondary mb-1">TANGGAL KADALUARSA</label>
                        <input type="date" name="expiry_date" class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-secondary mb-1">DOKUMEN SERTIFIKAT (PDF / GAMBAR)</label>
                    <input type="file" name="certificate_file" class="w-full text-xs text-gray-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 border border-gray-200 rounded-xl p-1 bg-gray-50/50">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-primary hover:bg-primary/95 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-md transition-colors flex items-center gap-1">
                    Simpan Informasi Resto <span class="material-symbols-outlined text-xs">save</span>
                </button>
            </div>
        </form>
    </section>

</main>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Map select functionality
        var latInput = document.getElementById('lat-select');
        var lngInput = document.getElementById('lng-select');
        
        var startLat = parseFloat(latInput.value) || -2.983333;
        var startLng = parseFloat(lngInput.value) || 104.75;
        
        var mapSelect = L.map('map-select').setView([startLat, startLng], 14);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapSelect);
        
        var activeMarker = L.marker([startLat, startLng], {draggable: true}).addTo(mapSelect);
        
        // Listen to marker drag events to auto update lat/lng inputs
        activeMarker.on('dragend', function(event) {
            var position = activeMarker.getLatLng();
            latInput.value = position.lat.toFixed(6);
            lngInput.value = position.lng.toFixed(6);
        });

        // Click on map listener to relocate marker
        mapSelect.on('click', function(event) {
            var position = event.latlng;
            activeMarker.setLatLng(position);
            latInput.value = position.lat.toFixed(6);
            lngInput.value = position.lng.toFixed(6);
        });
    });
</script>
@endsection
