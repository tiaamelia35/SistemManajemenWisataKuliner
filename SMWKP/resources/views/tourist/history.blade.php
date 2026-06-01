@extends('layouts.app')

@section('title', 'Riwayat Booking Saya - SMWKP')

@section('content')
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur shadow-sm border-b border-outline-variant px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-surface/80 text-secondary hover:bg-surface transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <span class="material-symbols-outlined text-primary text-2xl font-bold">book_online</span>
        <h1 class="text-lg font-bold text-primary">Riwayat Booking</h1>
    </div>
</header>

<!-- Main Container -->
<main class="flex-1 p-4 pb-24 space-y-4 max-w-xl mx-auto w-full">
    
    <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl">
        <h2 class="font-bold text-sm text-on-surface uppercase mb-1">Daftar Reservasi Anda</h2>
        <p class="text-xs text-gray-400">Pantau status persetujuan meja makan Anda di sini secara berkala.</p>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-surface rounded-2xl border border-outline-variant/30 p-8 text-center">
            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">event_busy</span>
            <p class="text-sm font-semibold text-secondary">Belum ada riwayat booking</p>
            <p class="text-xs text-gray-400 mt-1">Anda belum melakukan pemesanan meja di restoran mana pun.</p>
            <a href="{{ route('tourist.jelajah') }}" class="inline-block mt-4 bg-primary hover:bg-primary/95 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm">Jelajahi Restoran</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-surface border border-outline-variant/30 rounded-2xl overflow-hidden shadow-sm">
                    <!-- Top Summary Card -->
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-on-surface">{{ $booking->restaurant->name }}</h3>
                                <p class="text-[10px] text-gray-400 flex items-center gap-0.5 mt-0.5"><span class="material-symbols-outlined text-xs">calendar_today</span> {{ $booking->booking_date->format('d M Y • H:i') }}</p>
                            </div>
                            
                            <!-- Status Badges -->
                            @if($booking->status === 'pending')
                                <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Menunggu</span>
                            @elseif($booking->status === 'confirmed')
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Disetujui</span>
                            @elseif($booking->status === 'completed')
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Selesai</span>
                            @else
                                <span class="bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Batal</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs pt-2 border-t border-gray-100">
                            <div>
                                <p class="text-gray-400 font-medium">Penerima</p>
                                <p class="font-semibold text-on-surface">{{ $booking->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 font-medium">Tamu / Pax</p>
                                <p class="font-semibold text-on-surface">{{ $booking->pax_count }} Orang</p>
                            </div>
                        </div>

                        <!-- Dropdown Trigger Action -->
                        <div class="pt-2 flex items-center justify-between">
                            <button onclick="toggleDetails({{ $booking->id }})" class="text-primary text-xs font-semibold flex items-center gap-0.5 hover:underline">
                                Detail Pesanan <span id="arrow-{{ $booking->id }}" class="material-symbols-outlined text-sm transition-transform duration-200">keyboard_arrow_down</span>
                            </button>
                            <div class="text-right">
                                <span class="text-[10px] text-gray-400 block leading-none">Total Pembayaran</span>
                                <span class="font-bold text-sm text-primary">Rp {{ number_format($booking->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Collapsible Menu Items Detail -->
                    <div id="details-{{ $booking->id }}" class="bg-gray-50/50 border-t border-gray-100 p-4 space-y-2 hidden">
                        <h4 class="text-[10px] font-bold text-secondary uppercase">Daftar Menu Dipesan</h4>
                        <div class="space-y-1.5 divide-y divide-gray-100">
                            @foreach($booking->menus as $menu)
                                <div class="flex items-center justify-between text-xs pt-1.5 first:pt-0">
                                    <div>
                                        <p class="font-semibold text-on-surface">{{ $menu->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $menu->pivot->quantity }} Porsi x Rp {{ number_format($menu->pivot->price_at_booking, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-bold text-on-surface">Rp {{ number_format($menu->pivot->quantity * $menu->pivot->price_at_booking, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</main>



<script>
    function toggleDetails(id) {
        const detailsDiv = document.getElementById('details-' + id);
        const arrow = document.getElementById('arrow-' + id);
        
        if (detailsDiv.classList.contains('hidden')) {
            detailsDiv.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            detailsDiv.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }
</script>
@endsection
