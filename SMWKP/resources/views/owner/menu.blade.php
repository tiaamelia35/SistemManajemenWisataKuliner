@extends('layouts.app')

@section('title', 'Tambah & Kelola Menu | SMWKP')

@section('content')
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur shadow-sm border-b border-outline-variant px-4 py-3 flex items-center gap-3">
    <a href="{{ route('owner.dashboard') }}" class="text-secondary hover:text-primary flex items-center justify-center p-1">
        <span class="material-symbols-outlined font-bold">arrow_back</span>
    </a>
    <h1 class="text-base font-bold text-on-surface">Tambah & Kelola Menu</h1>
</header>

<!-- Main Container -->
<main class="flex-1 p-4 pb-24 space-y-5 max-w-xl mx-auto w-full">
    
    <!-- Info Panel -->
    <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl">
        <h2 class="font-bold text-sm text-on-surface uppercase mb-1">Tambah Hidangan Baru</h2>
        <p class="text-xs text-gray-400">Hidangan baru yang ditambahkan akan langsung dapat dipesan oleh wisatawan di halaman detail restoran Anda.</p>
    </div>

    <!-- Add Menu Form -->
    <div class="bg-surface border border-outline-variant/30 p-4 rounded-2xl shadow-sm">
        <h3 class="font-bold text-xs text-secondary uppercase tracking-wider mb-4 flex items-center gap-1"><span class="material-symbols-outlined text-base">restaurant_menu</span> Form Menu Baru</h3>
        
        <form action="{{ route('owner.menus.post') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-semibold text-secondary mb-1">NAMA MENU KULINER</label>
                <input type="text" name="name" required class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50" placeholder="Contoh: Pempek Lenjer Jumbo">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-semibold text-secondary mb-1">HARGA (RUPIAH)</label>
                    <input type="number" name="price" required class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50" placeholder="Contoh: 15000">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-secondary mb-1">KATEGORI</label>
                    <select name="category" required class="w-full text-xs border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50 py-2 pl-3 pr-8">
                        <option value="Pempek">Pempek</option>
                        <option value="Mie Celor">Mie Celor</option>
                        <option value="Pindang">Pindang</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-semibold text-secondary mb-1">DESKRIPSI MENU</label>
                <textarea name="description" rows="2" class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50" placeholder="Jelaskan bahan-bahan dan keunikan menu hidangan Anda..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-semibold text-secondary mb-1">UNGGAH GAMBAR</label>
                    <input type="file" name="image_file" class="w-full text-xs text-gray-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 border border-gray-200 rounded-xl p-1 bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-secondary mb-1">ATAU URL GAMBAR</label>
                    <input type="url" name="image_url" class="w-full text-xs p-2 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary focus:border-primary bg-gray-50/50" placeholder="https://unsplash.com/photo...">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-primary hover:bg-primary/95 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-md transition-colors flex items-center gap-1">
                    Tambah Hidangan <span class="material-symbols-outlined text-xs">add_circle</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Active Menu Items Grid -->
    <section class="space-y-3">
        <h3 class="text-xs font-bold text-secondary uppercase tracking-wider">Menu Terdaftar saat ini ({{ $menus->count() }})</h3>
        
        @if($menus->isEmpty())
            <div class="bg-surface border border-outline-variant/30 p-6 rounded-2xl text-center text-gray-400 text-xs">
                Belum ada menu yang didaftarkan.
            </div>
        @else
            <div class="grid grid-cols-2 gap-3">
                @foreach($menus as $menu)
                    <div class="bg-surface border border-outline-variant/30 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between">
                        <div class="h-28 w-full bg-gray-100 relative">
                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                            <!-- Delete action -->
                            <form action="{{ route('owner.menus.delete', $menu->id) }}" method="POST" class="absolute top-2 right-2">
                                @csrf
                                <button type="submit" onclick="return confirm('Hapus menu ini?')" class="w-7 h-7 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-primary transition-colors focus:outline-none shadow">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                        <div class="p-3 space-y-1">
                            <span class="bg-primary/5 text-primary text-[8px] font-bold px-2 py-0.5 rounded border border-primary/10 uppercase tracking-wider">{{ $menu->category }}</span>
                            <h4 class="font-bold text-xs text-on-surface truncate">{{ $menu->name }}</h4>
                            <p class="text-[9px] text-gray-400 line-clamp-1 leading-normal">{{ $menu->description }}</p>
                            <p class="text-primary font-bold text-xs pt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</main>
@endsection
