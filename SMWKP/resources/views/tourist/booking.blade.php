@extends('layouts.app')

@section('title', 'Booking Restoran | SMWKP')

@section('content')
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur shadow-sm border-b border-outline-variant px-4 py-3 flex items-center gap-3">
    <a href="{{ route('tourist.detail', $restaurant->id) }}" class="text-secondary hover:text-primary flex items-center justify-center p-1">
        <span class="material-symbols-outlined font-bold">arrow_back</span>
    </a>
    <h1 class="text-base font-bold text-on-surface">Reservasi di {{ $restaurant->name }}</h1>
</header>

<!-- Main Container -->
<main class="flex-1 pb-24 max-w-xl mx-auto w-full p-4">
    
    <!-- Step Wizard Progress Indicator -->
    <div class="flex justify-between items-center mb-6 px-4">
        <div class="flex flex-col items-center">
            <div id="step-dot-1" class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold shadow-sm">1</div>
            <span id="step-lbl-1" class="text-[10px] font-semibold text-primary mt-1">Pilih Menu</span>
        </div>
        <div class="flex-1 h-0.5 bg-gray-200 mx-2 -mt-4" id="step-line-1"></div>
        <div class="flex flex-col items-center">
            <div id="step-dot-2" class="w-8 h-8 rounded-full bg-gray-200 text-secondary flex items-center justify-center text-xs font-bold">2</div>
            <span id="step-lbl-2" class="text-[10px] font-semibold text-secondary mt-1">Tamu & Waktu</span>
        </div>
        <div class="flex-1 h-0.5 bg-gray-200 mx-2 -mt-4" id="step-line-2"></div>
        <div class="flex flex-col items-center">
            <div id="step-dot-3" class="w-8 h-8 rounded-full bg-gray-200 text-secondary flex items-center justify-center text-xs font-bold">3</div>
            <span id="step-lbl-3" class="text-[10px] font-semibold text-secondary mt-1">Konfirmasi</span>
        </div>
    </div>

    <!-- Booking Form -->
    <form id="booking-form" action="{{ route('tourist.booking.post', $restaurant->id) }}" method="POST" class="space-y-4">
        @csrf

        <!-- STEP 1: SELECT MENU -->
        <div id="step-content-1" class="space-y-3">
            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl">
                <h3 class="font-bold text-sm text-on-surface uppercase mb-1">Daftar Menu Restoran</h3>
                <p class="text-xs text-gray-400">Silakan pilih dan tentukan porsi makanan/minuman yang ingin dipesan.</p>
            </div>

            @if($menus->isEmpty())
                <div class="bg-surface border border-outline-variant/30 p-8 rounded-2xl text-center text-gray-400 text-xs">
                    Belum ada menu kuliner terdaftar di restoran ini.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($menus as $menu)
                        <div class="bg-surface border border-outline-variant/30 p-3 rounded-2xl flex items-center gap-3 shadow-sm">
                            <div class="w-16 h-16 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                                <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-xs text-on-surface truncate">{{ $menu->name }}</h4>
                                <p class="text-[10px] text-gray-400 truncate">{{ $menu->category }}</p>
                                <p class="text-primary font-bold text-xs mt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                            </div>
                            <!-- Counter buttons -->
                            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-gray-50/50">
                                <button type="button" onclick="changeQty({{ $menu->id }}, -1)" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 focus:outline-none"><span class="material-symbols-outlined text-xs">remove</span></button>
                                <input type="number" name="items[{{ $menu->id }}]" id="qty-{{ $menu->id }}" value="0" min="0" readonly class="w-8 border-0 bg-transparent text-center text-xs font-bold focus:ring-0 p-0 text-on-surface" data-price="{{ $menu->price }}" data-name="{{ $menu->name }}">
                                <button type="button" onclick="changeQty({{ $menu->id }}, 1)" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 focus:outline-none"><span class="material-symbols-outlined text-xs">add</span></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- STEP 2: TIME & GUEST DETAILS -->
        <div id="step-content-2" class="space-y-4 hidden">
            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl">
                <h3 class="font-bold text-sm text-on-surface uppercase mb-1">Informasi Pemesan & Waktu Kedatangan</h3>
                <p class="text-xs text-gray-400">Pastikan detail informasi kedatangan Anda sudah benar.</p>
            </div>

            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-secondary mb-1">NAMA LENGKAP PENERIMA</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">person</span>
                        <input type="text" name="name" id="input-name" value="{{ old('name', Auth::user()->name) }}" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-secondary mb-1">NOMOR TELEPON AKTIF</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">call</span>
                        <input type="text" name="phone_number" id="input-phone" value="{{ old('phone_number', Auth::user()->phone_number) }}" required class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-secondary mb-1">TANGGAL & WAKTU</label>
                        <div class="relative">
                            <input type="datetime-local" name="booking_date" id="input-date" required class="w-full py-2 px-3 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-secondary mb-1">JUMLAH TAMU (ORANG)</label>
                        <div class="relative">
                            <input type="number" name="pax_count" id="input-pax" value="{{ old('pax_count', '2') }}" min="1" required class="w-full py-2 px-3 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-gray-50/50">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 3: CONFIRMATION -->
        <div id="step-content-3" class="space-y-4 hidden">
            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl">
                <h3 class="font-bold text-sm text-on-surface uppercase mb-1">Review & Konfirmasi Booking</h3>
                <p class="text-xs text-gray-400">Periksa kembali ringkasan pesanan Anda sebelum menekan tombol Kirim.</p>
            </div>

            <!-- Guest Detail Card -->
            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl space-y-2">
                <h4 class="text-xs font-bold text-primary uppercase">Detail Kedatangan</h4>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <p class="text-gray-400 font-medium">Nama Penerima</p>
                        <p class="font-semibold text-on-surface" id="review-name">-</p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Nomor Telepon</p>
                        <p class="font-semibold text-on-surface" id="review-phone">-</p>
                    </div>
                    <div class="col-span-2 pt-2 border-t border-gray-100 flex justify-between">
                        <div>
                            <p class="text-gray-400 font-medium">Tanggal Booking</p>
                            <p class="font-semibold text-on-surface" id="review-date">-</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 font-medium">Jumlah Orang</p>
                            <p class="font-semibold text-on-surface" id="review-pax">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ordered Items Details -->
            <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl space-y-3">
                <h4 class="text-xs font-bold text-primary uppercase">Menu yang Dipesan</h4>
                <div id="confirm-items-container" class="divide-y divide-gray-100 max-h-48 overflow-y-auto pr-1">
                    <!-- Dynamic Items Inject -->
                </div>
                <div class="border-t border-outline-variant/30 pt-3 flex items-center justify-between text-sm">
                    <span class="font-bold text-secondary">Total Tagihan</span>
                    <span class="font-bold text-primary text-base" id="review-grand-total">Rp 0</span>
                </div>
            </div>
        </div>

        <!-- Wizard Navigation Controls -->
        <div class="flex items-center gap-3 pt-4">
            <button type="button" id="btn-prev" onclick="prevStep()" class="flex-1 bg-surface border border-outline-variant text-primary hover:bg-gray-50 font-semibold py-2.5 rounded-xl text-sm hidden">
                Kembali
            </button>
            <button type="button" id="btn-next" onclick="nextStep()" class="flex-1 bg-primary hover:bg-primary/95 text-white font-semibold py-2.5 rounded-xl text-sm shadow-md flex items-center justify-center gap-1">
                Lanjut <span class="material-symbols-outlined text-sm font-bold">arrow_forward</span>
            </button>
            <button type="submit" id="btn-submit" class="flex-1 bg-primary hover:bg-primary/95 text-white font-semibold py-2.5 rounded-xl text-sm shadow-md hidden flex items-center justify-center gap-1">
                Kirim Reservasi <span class="material-symbols-outlined text-sm font-bold">check_circle</span>
            </button>
        </div>
    </form>
</main>

<script>
    let currentStep = 1;

    // Helper to increment/decrement quantities
    function changeQty(id, offset) {
        const input = document.getElementById('qty-' + id);
        let val = parseInt(input.value) + offset;
        if (val < 0) val = 0;
        input.value = val;
    }

    // Wizard navigation controls
    function nextStep() {
        if (currentStep === 1) {
            // Check if user selected at least 1 item
            let selectedCount = 0;
            document.querySelectorAll('input[id^="qty-"]').forEach(function(el) {
                selectedCount += parseInt(el.value);
            });

            if (selectedCount === 0) {
                alert("Silakan pilih setidaknya 1 menu makanan atau minuman.");
                return;
            }

            currentStep = 2;
            showStep(2);
        } else if (currentStep === 2) {
            // Validate form input fields for step 2
            const name = document.getElementById('input-name').value;
            const phone = document.getElementById('input-phone').value;
            const date = document.getElementById('input-date').value;
            const pax = document.getElementById('input-pax').value;

            if (!name || !phone || !date || !pax) {
                alert("Mohon isi semua informasi tamu, tanggal, dan waktu.");
                return;
            }

            // Fill Step 3 Summary fields
            document.getElementById('review-name').innerText = name;
            document.getElementById('review-phone').innerText = phone;
            
            // Format readable local datetime
            const dt = new Date(date);
            document.getElementById('review-date').innerText = dt.toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
            document.getElementById('review-pax').innerText = pax + ' Orang';

            // Generate menu review list
            const container = document.getElementById('confirm-items-container');
            container.innerHTML = '';
            let grandTotal = 0;

            document.querySelectorAll('input[id^="qty-"]').forEach(function(el) {
                const qty = parseInt(el.value);
                if (qty > 0) {
                    const price = parseFloat(el.getAttribute('data-price'));
                    const name = el.getAttribute('data-name');
                    const subtotal = qty * price;
                    grandTotal += subtotal;

                    const row = document.createElement('div');
                    row.className = "py-2 flex items-center justify-between text-xs";
                    row.innerHTML = `
                        <div>
                            <p class="font-bold text-on-surface">${name}</p>
                            <p class="text-gray-400 font-medium">${qty} Porsi x Rp ${price.toLocaleString('id-ID')}</p>
                        </div>
                        <span class="font-semibold text-on-surface">Rp ${subtotal.toLocaleString('id-ID')}</span>
                    `;
                    container.appendChild(row);
                }
            });

            document.getElementById('review-grand-total').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

            currentStep = 3;
            showStep(3);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function showStep(step) {
        // Toggle step contents visibility
        document.getElementById('step-content-1').classList.add('hidden');
        document.getElementById('step-content-2').classList.add('hidden');
        document.getElementById('step-content-3').classList.add('hidden');
        document.getElementById('step-content-' + step).classList.remove('hidden');

        // Toggle Wizard Dots active states
        for (let i = 1; i <= 3; i++) {
            const dot = document.getElementById('step-dot-' + i);
            const lbl = document.getElementById('step-lbl-' + i);
            if (i <= step) {
                dot.className = "w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold shadow-sm";
                lbl.className = "text-[10px] font-semibold text-primary mt-1";
            } else {
                dot.className = "w-8 h-8 rounded-full bg-gray-200 text-secondary flex items-center justify-center text-xs font-bold";
                lbl.className = "text-[10px] font-semibold text-secondary mt-1";
            }
            // Toggle connector lines
            if (i < 3) {
                const line = document.getElementById('step-line-' + i);
                if (i < step) {
                    line.className = "flex-1 h-0.5 bg-primary mx-2 -mt-4";
                } else {
                    line.className = "flex-1 h-0.5 bg-gray-200 mx-2 -mt-4";
                }
            }
        }

        // Toggle action buttons
        if (step === 1) {
            document.getElementById('btn-prev').classList.add('hidden');
            document.getElementById('btn-next').classList.remove('hidden');
            document.getElementById('btn-submit').classList.add('hidden');
        } else if (step === 2) {
            document.getElementById('btn-prev').classList.remove('hidden');
            document.getElementById('btn-next').classList.remove('hidden');
            document.getElementById('btn-submit').classList.add('hidden');
        } else if (step === 3) {
            document.getElementById('btn-prev').classList.remove('hidden');
            document.getElementById('btn-next').classList.add('hidden');
            document.getElementById('btn-submit').classList.remove('hidden');
        }
    }
</script>
@endsection
