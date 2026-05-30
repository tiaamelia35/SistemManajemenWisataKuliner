@extends('layouts.app')

@section('title', $restaurant->name . ' - Detail Restoran')

@section('styles')
<style>
    #map-detail { z-index: 1; }
    .halal-badge {
        background-color: #2e7d32;
        color: white;
        padding: 4px 12px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<!-- TopAppBar -->
<header class="w-full sticky top-0 z-50 bg-surface border-b border-outline-variant shadow-sm h-20">
    <div class="flex justify-between items-center px-6 md:px-10 h-full max-w-[1280px] mx-auto">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-primary text-3xl font-bold">restaurant_menu</span>
            <h1 class="font-bold text-xl md:text-2xl text-primary tracking-tight">Ampera Culinary</h1>
        </div>
        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-secondary">
            <a class="hover:text-primary transition-colors py-2" href="{{ route('tourist.jelajah') }}">Home</a>
            <a class="hover:text-primary transition-colors py-2" href="{{ route('tourist.bookings') }}">My Reservations</a>
            <a class="hover:text-primary transition-colors py-2" href="{{ route('tourist.profile') }}">Profile</a>
        </nav>
        <div class="flex items-center gap-4">
            <a href="{{ route('tourist.jelajah') }}" class="material-symbols-outlined p-2 text-secondary hover:bg-gray-100 rounded-full transition-all" title="Kembali">
                arrow_back
            </a>
            <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container border-2 border-outline-variant overflow-hidden font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </div>
</header>

<main class="flex-grow">
    <!-- Hero Gallery Section (Bento Style) -->
    <section class="max-w-[1280px] mx-auto px-6 md:px-10 py-6">
        <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-4 h-[350px] md:h-[500px]">
            <!-- Box 1 (colspan 2, rowspan 2) -->
            <div class="md:col-span-2 md:row-span-2 relative rounded-xl overflow-hidden group bg-gray-100">
                <img alt="Restaurant main photo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $restaurant->image_url }}">
                <div class="absolute top-4 right-4 halal-badge shadow">
                    <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 1;">verified</span>
                    HALAL CERTIFIED
                </div>
            </div>
            <!-- Box 2 -->
            <div class="hidden md:block relative rounded-xl overflow-hidden group bg-gray-100">
                <img alt="Indonesian Spices" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://images.unsplash.com/photo-1596797038530-2c107229654b?w=500">
            </div>
            <!-- Box 3 -->
            <div class="hidden md:block relative rounded-xl overflow-hidden group bg-gray-100">
                <img alt="Restaurant Interior" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500">
            </div>
            <!-- Box 4 (colspan 2) -->
            <div class="hidden md:block md:col-span-2 relative rounded-xl overflow-hidden group bg-gray-100">
                <img alt="Food Preparation" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://images.unsplash.com/photo-1544025162-d76694265947?w=800">
            </div>
        </div>
    </section>

    <!-- Content Grid (Bento Style, Left Main Info, Right Sidebar) -->
    <section class="max-w-[1280px] mx-auto px-6 md:px-10 pb-20">
        <form id="direct-booking-form" action="{{ route('tourist.booking.post', $restaurant->id) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Info Column (Left & Center) -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Restaurant Header Card -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm space-y-3">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-2">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-0.5 rounded">PREMIUM PARTNER</span>
                                    <span class="flex items-center text-primary font-bold text-xs">
                                        <span class="material-symbols-outlined text-sm mr-0.5" style="font-variation-settings: 'FILL' 1;">star</span>
                                        {{ $restaurant->average_rating }} ({{ $restaurant->reviews_count }} Reviews)
                                    </span>
                                </div>
                                <h2 class="text-2xl font-bold text-on-surface leading-tight">{{ $restaurant->name }}</h2>
                                <p class="text-xs text-secondary mt-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-on-surface-variant text-sm">location_on</span>
                                    {{ $restaurant->address }}
                                </p>
                            </div>
                            <div class="flex flex-row sm:flex-col sm:items-end gap-1.5 shrink-0 pt-2 sm:pt-0">
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-3 py-1 rounded-full flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> OPEN NOW
                                </span>
                                <p class="text-[10px] text-secondary">Closes at 22:00</p>
                            </div>
                        </div>
                        <p class="text-xs text-secondary leading-relaxed pt-2 border-t border-gray-100">
                            {{ $restaurant->description }}
                        </p>
                    </div>

                    <!-- Menu Section Card -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-bold text-primary">Signature Menu</h3>
                            <span class="text-xs text-secondary font-medium">Tentukan Porsi di Sini</span>
                        </div>

                        @if($menusByCategory->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada menu yang didaftarkan.</p>
                        @else
                            <div class="space-y-6">
                                @foreach($menusByCategory as $categoryName => $catMenus)
                                    <div class="space-y-2.5">
                                        <h4 class="text-xs font-bold text-secondary uppercase tracking-wider border-l-2 border-primary pl-2">{{ $categoryName }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($catMenus as $menu)
                                                <div class="flex gap-3 p-3 rounded-lg border border-outline-variant hover:border-primary transition-colors group bg-white shadow-sm">
                                                    <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                                        <img alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" src="{{ $menu->image_url }}">
                                                    </div>
                                                    <div class="flex-grow min-w-0 flex flex-col justify-between">
                                                        <div>
                                                            <div class="flex justify-between items-start gap-1">
                                                                <h5 class="font-bold text-xs text-on-surface truncate">{{ $menu->name }}</h5>
                                                                <span class="text-primary font-bold text-xs shrink-0">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                                            </div>
                                                            <p class="text-[9px] text-gray-400 line-clamp-1 mt-0.5">{{ $menu->description }}</p>
                                                        </div>
                                                        <!-- Counter selector inside menu item card -->
                                                        <div class="flex items-center justify-between pt-2">
                                                            <span class="text-[9px] text-gray-400">Pesan:</span>
                                                            <div class="flex items-center border border-gray-200 rounded bg-gray-50/50">
                                                                <button type="button" onclick="adjustMenuQty({{ $menu->id }}, -1)" class="w-6 h-6 flex items-center justify-center hover:bg-gray-100 text-xs font-bold text-secondary focus:outline-none">-</button>
                                                                <input type="number" name="items[{{ $menu->id }}]" id="menu-qty-{{ $menu->id }}" value="0" min="0" readonly class="w-6 border-0 bg-transparent text-center text-[10px] font-bold focus:ring-0 p-0 text-on-surface" data-price="{{ $menu->price }}" data-name="{{ $menu->name }}">
                                                                <button type="button" onclick="adjustMenuQty({{ $menu->id }}, 1)" class="w-6 h-6 flex items-center justify-center hover:bg-gray-100 text-xs font-bold text-secondary focus:outline-none">+</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Reviews Section Card -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm space-y-6">
                        <h3 class="text-base font-bold text-on-surface">Verified Guest Reviews</h3>
                        
                        <!-- Submit review embedded form -->
                        <div class="p-4 border border-outline-variant rounded-xl bg-gray-50/50 space-y-3">
                            <h4 class="text-xs font-bold text-secondary uppercase">Bagikan Pengalaman Kuliner Anda</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[9px] font-semibold text-secondary mb-1">SKOR RATING</label>
                                    <select id="review-rating-input" class="w-full text-xs border-gray-200 rounded-lg bg-white py-1">
                                        <option value="5">⭐⭐⭐⭐⭐ (5 - Luar Biasa)</option>
                                        <option value="4">⭐⭐⭐⭐ (4 - Sangat Bagus)</option>
                                        <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                        <option value="2">⭐⭐ (2 - Kurang)</option>
                                        <option value="1">⭐ (1 - Buruk)</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="submitDirectReview()" class="w-full bg-primary hover:bg-primary/95 text-white text-[10px] font-bold py-2 rounded-lg transition-all shadow-sm">Kirim Ulasan</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] font-semibold text-secondary mb-1">KOMENTAR</label>
                                <textarea id="review-text-input" rows="2" class="w-full text-xs p-2 border border-gray-200 rounded-lg bg-white" placeholder="Citarasa pempek, mie celor, kuah cuko..."></textarea>
                            </div>
                        </div>

                        <!-- Review list -->
                        <div class="space-y-6 divide-y divide-gray-100">
                            @if($reviews->isEmpty())
                                <p class="text-xs text-gray-400 text-center py-4">Belum ada ulasan untuk restoran ini.</p>
                            @else
                                @foreach($reviews as $rev)
                                    <div class="pt-6 first:pt-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary text-xs">
                                                    {{ strtoupper(substr($rev->tourist->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-xs text-on-surface">{{ $rev->tourist->name }}</p>
                                                    <p class="text-[10px] text-gray-400">Dikunjungi {{ $rev->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <div class="flex text-amber-500">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' {{ $i <= $rev->rating ? 1 : 0 }};">star</span>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-xs text-secondary leading-relaxed font-light">{{ $rev->review_text }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Right Sidebar Column (Reservations & Contact Card) -->
                <div class="space-y-6">
                    
                    <!-- Instantly Reservation Card -->
                    <div class="bg-primary text-white p-6 rounded-xl shadow-lg sticky top-24 space-y-4">
                        <h3 class="text-base font-bold tracking-wide flex items-center gap-1.5"><span class="material-symbols-outlined text-lg">calendar_month</span> Book a Table</h3>
                        
                        <div class="space-y-3.5 text-xs text-on-surface">
                            <div>
                                <label class="block text-[9px] font-bold text-white/80 mb-1">NAMA PENERIMA RESERVASI</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full py-2 px-3 border border-transparent rounded-lg text-xs bg-white focus:ring-1 focus:ring-primary focus:border-primary text-on-background">
                            </div>
                            
                            <div>
                                <label class="block text-[9px] font-bold text-white/80 mb-1">NOMOR TELEPON HUBUNG</label>
                                <input type="text" name="phone_number" value="{{ Auth::user()->phone_number }}" required class="w-full py-2 px-3 border border-transparent rounded-lg text-xs bg-white focus:ring-1 focus:ring-primary focus:border-primary text-on-background">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[9px] font-bold text-white/80 mb-1">TANGGAL & WAKTU</label>
                                    <input type="datetime-local" name="booking_date" required class="w-full py-1.5 px-2 border border-transparent rounded-lg text-[10px] bg-white focus:ring-0 text-on-background">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-white/80 mb-1">JUMLAH TAMU</label>
                                    <input type="number" name="pax_count" value="2" min="1" required class="w-full py-1.5 px-2 border border-transparent rounded-lg text-[10px] bg-white focus:ring-0 text-on-background">
                                </div>
                            </div>
                        </div>

                        <!-- Running Subtotal Calculator View -->
                        <div class="bg-white/10 p-3 rounded-lg text-xs space-y-1.5 border border-white/10">
                            <div class="flex justify-between">
                                <span class="text-white/80">Running Total Menu:</span>
                                <span class="font-bold text-white" id="sidebar-total-lbl">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-white text-primary hover:bg-gray-50 font-bold py-3 rounded-xl shadow-md transition-all active:scale-95 duration-150 text-xs">
                            RESERVE NOW
                        </button>
                        <p class="text-center text-[10px] opacity-80">Instant confirmation. No booking fee.</p>
                    </div>

                    <!-- Contact & Map Widget -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant shadow-sm space-y-6">
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-base">contact_page</span>
                                CONTACT INFO
                            </h3>
                            <div class="space-y-2 text-xs text-on-surface">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-sm">call</span>
                                    <span>{{ Auth::user()->phone_number ?: '+62 711 354 988' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-sm">public</span>
                                    <span>www.amperaculinary.com</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-base">schedule</span>
                                OPERATING HOURS
                            </h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between text-secondary">
                                    <span>Mon - Fri</span>
                                    <span class="font-semibold text-on-surface">10:00 - 22:00</span>
                                </div>
                                <div class="flex justify-between text-primary font-bold">
                                    <span>Sat - Sun</span>
                                    <span>09:00 - 23:00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Map widget -->
                        <div class="space-y-2">
                            <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-base">map</span>
                                LOKASI MAP
                            </h3>
                            <div id="map-detail" class="h-44 rounded-lg overflow-hidden border border-outline-variant shadow-inner"></div>
                            <button type="button" onclick="getDirections()" class="w-full mt-2 flex items-center justify-center gap-1 text-primary hover:text-primary-container font-bold text-xs transition-colors">
                                <span class="material-symbols-outlined text-sm">directions</span>
                                GET DIRECTIONS
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </form>
    </section>
</main>

<!-- Footer -->
<footer class="w-full mt-auto bg-surface-container-lowest border-t border-outline-variant">
    <div class="flex flex-col md:flex-row justify-between items-center py-10 px-6 md:px-10 max-w-[1280px] mx-auto gap-6">
        <div class="flex flex-col items-center md:items-start gap-2">
            <span class="font-bold text-lg text-primary">Ampera Culinary</span>
            <p class="text-secondary text-xs">© 2026 Ampera Culinary Palembang. All Rights Reserved.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-6 text-xs text-secondary font-medium">
            <a class="hover:text-primary transition-opacity" href="#">Privacy Policy</a>
            <a class="hover:text-primary transition-opacity" href="#">Terms of Service</a>
            <a class="hover:text-primary transition-opacity" href="#">Restaurant Partnership</a>
            <a class="hover:text-primary transition-opacity" href="#">Contact Support</a>
        </div>
    </div>
</footer>

<!-- BottomNavBar (Mobile Only) -->
<nav class="fixed bottom-0 left-0 right-0 w-full z-50 md:hidden border-t border-outline-variant bg-surface/90 backdrop-blur-md shadow-lg">
    <div class="flex justify-around items-center h-16 px-2">
        <a class="flex flex-col items-center text-secondary active:scale-90 transition-transform duration-100" href="{{ route('tourist.jelajah') }}">
            <span class="material-symbols-outlined">home</span>
            <span class="text-[10px] mt-0.5">Home</span>
        </a>
        <a class="flex flex-col items-center text-primary font-bold active:scale-90 transition-transform duration-100" href="{{ route('tourist.bookings') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">book_online</span>
            <span class="text-[10px] mt-0.5">Bookings</span>
        </a>
        <a class="flex flex-col items-center text-secondary active:scale-90 transition-transform duration-100" href="{{ route('tourist.profile') }}">
            <span class="material-symbols-outlined">account_circle</span>
            <span class="text-[10px] mt-0.5">Profile</span>
        </a>
    </div>
</nav>

<!-- Hidden form for posting review details via JavaScript -->
<form id="hidden-review-form" action="{{ route('tourist.review.post', $restaurant->id) }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="rating" id="hidden-rating-field">
    <input type="hidden" name="review_text" id="hidden-text-field">
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Leaflet map
        var lat = {{ $restaurant->latitude ?: -2.983333 }};
        var lng = {{ $restaurant->longitude ?: 104.75 }};
        
        var mapDetail = L.map('map-detail').setView([lat, lng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapDetail);
        
        var marker = L.marker([lat, lng]).addTo(mapDetail);
        marker.bindPopup("<b class='text-xs font-bold'>{{ $restaurant->name }}</b><br><span class='text-[10px] text-gray-500'>{{ $restaurant->address }}</span>").openPopup();
    });

    // Helper functions for menu adjustments and instant totals calculations
    function adjustMenuQty(id, offset) {
        const qtyField = document.getElementById('menu-qty-' + id);
        let val = parseInt(qtyField.value) + offset;
        if (val < 0) val = 0;
        qtyField.value = val;
        
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('input[id^="menu-qty-"]').forEach(function(el) {
            const qty = parseInt(el.value);
            if (qty > 0) {
                const price = parseFloat(el.getAttribute('data-price'));
                grandTotal += qty * price;
            }
        });
        
        document.getElementById('sidebar-total-lbl').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Direct review posting trigger
    function submitDirectReview() {
        const rating = document.getElementById('review-rating-input').value;
        const text = document.getElementById('review-text-input').value;

        if (!text.trim()) {
            alert('Silakan tulis ulasan teks terlebih dahulu.');
            return;
        }

        document.getElementById('hidden-rating-field').value = rating;
        document.getElementById('hidden-text-field').value = text;
        document.getElementById('hidden-review-form').submit();
    }

    // Navigation trigger for driving directions
    function getDirections() {
        var lat = {{ $restaurant->latitude ?: -2.983333 }};
        var lng = {{ $restaurant->longitude ?: 104.75 }};
        window.open(`https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`, '_blank');
    }
</script>
@endsection
