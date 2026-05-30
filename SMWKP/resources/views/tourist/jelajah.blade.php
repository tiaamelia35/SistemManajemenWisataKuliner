@extends('layouts.app')

@section('title', 'Ampera Culinary - Jelajah Kuliner Palembang')

@section('content')
<!-- TopAppBar -->
<header class="w-full sticky top-0 z-50 bg-surface border-b border-outline-variant shadow-sm h-20">
    <div class="flex justify-between items-center px-6 md:px-10 h-full max-w-[1280px] mx-auto">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-primary text-3xl font-bold">restaurant_menu</span>
            <h1 class="font-bold text-xl md:text-2xl text-primary tracking-tight">Ampera Culinary</h1>
        </div>
        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-secondary">
            <a class="text-primary font-bold border-b-2 border-primary py-2 transition-colors" href="{{ route('tourist.jelajah') }}">Home</a>
            <a class="hover:text-primary transition-colors py-2" href="{{ route('tourist.bookings') }}">My Reservations</a>
            <a class="hover:text-primary transition-colors py-2" href="{{ route('tourist.profile') }}">Profile</a>
        </nav>
        <div class="flex items-center gap-4">
            <a href="{{ route('logout') }}" class="material-symbols-outlined p-2 text-secondary hover:bg-gray-100 rounded-full transition-all" title="Keluar">
                logout
            </a>
            <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container border-2 border-outline-variant overflow-hidden font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </div>
</header>

<main class="flex-grow">
    <!-- Hero Section -->
    <section class="relative w-full overflow-hidden bg-surface-container-lowest">
        <div class="max-w-[1280px] mx-auto px-6 md:px-10 py-12 md:py-20 grid md:grid-cols-2 items-center gap-12">
            <div class="z-10 text-center md:text-left">
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary font-bold text-xs mb-6">Authentic Palembang Tastes</span>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-primary leading-tight mb-6">Savor the Heritage of <span class="text-on-background">Wong Kito Galo</span></h2>
                <p class="text-secondary text-sm md:text-base mb-8 max-w-xl leading-relaxed">Explore the legendary flavors of Palembang. From the savory crunch of Pempek to the creamy broth of Mie Celor, find the best certified culinary spots in the heart of South Sumatra.</p>
                
                <!-- Search Bar Integrated into Hero -->
                <form action="{{ route('tourist.jelajah') }}" method="GET" class="relative max-w-2xl">
                    <div class="flex items-center p-1.5 bg-white rounded-xl shadow-md border border-outline-variant">
                        <span class="material-symbols-outlined px-3 text-secondary">search</span>
                        <input name="search" value="{{ $search }}" class="w-full py-2 bg-transparent border-none focus:ring-0 text-sm text-on-surface" placeholder="Search for Pempek, Mie Celor, or Pindang..." type="text">
                        @if($category && $category !== 'Semua')
                            <input type="hidden" name="category" value="{{ $category }}">
                        @endif
                        <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-bold text-xs hover:brightness-110 active:scale-95 transition-all">
                            Search
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Side Hero Images -->
            <div class="relative hidden md:block">
                <div class="aspect-square rounded-[2rem] overflow-hidden border-8 border-white shadow-xl rotate-3">
                    <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800" alt="Pempek Palembang">
                </div>
                <div class="absolute -bottom-10 -left-10 w-48 h-48 rounded-2xl overflow-hidden border-4 border-white shadow-lg -rotate-6">
                    <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800" alt="Mie Celor">
                </div>
            </div>
        </div>
    </section>

    <!-- Category Filter Chips -->
    <section class="bg-surface py-6 overflow-x-auto whitespace-nowrap border-y border-outline-variant/30">
        <div class="max-w-[1280px] mx-auto px-6 md:px-10 flex gap-3">
            @foreach($categories as $cat)
                <a href="{{ route('tourist.jelajah', ['search' => $search, 'category' => $cat]) }}" 
                   class="px-6 py-2 rounded-full text-xs font-bold transition-all duration-200 border
                   {{ ($category === $cat || (!$category && $cat === 'Semua')) 
                      ? 'bg-primary border-primary text-white shadow' 
                      : 'bg-surface-container-low border-gray-200 text-secondary hover:bg-gray-100' }}">
                    {{ $cat === 'Semua' ? 'All Categories' : $cat }}
                </a>
            @endforeach
        </div>
    </section>

    <!-- Bento Grid for Featured Restaurants -->
    <section class="max-w-[1280px] mx-auto px-6 md:px-10 py-16 space-y-8">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-on-background">Featured Destinations</h3>
                <p class="text-secondary text-xs mt-1">The most highly-rated culinary gems in Palembang.</p>
            </div>
            <a href="{{ route('tourist.jelajah') }}" class="text-primary font-bold text-xs flex items-center gap-1 hover:underline">
                Refresh Catalog <span class="material-symbols-outlined text-sm">refresh</span>
            </a>
        </div>

        @if($restaurants->isEmpty())
            <div class="bg-surface border border-outline-variant/30 rounded-2xl p-12 text-center max-w-md mx-auto">
                <span class="material-symbols-outlined text-5xl text-gray-300">search_off</span>
                <h4 class="font-bold text-sm text-secondary mt-3">No restaurants found</h4>
                <p class="text-xs text-gray-400 mt-1">Try refining your search keyword or selecting a different category chip.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Large Featured Card (colspan 2) -->
                @if($restaurants->count() > 0)
                    @php $first = $restaurants->first(); @endphp
                    <div class="md:col-span-2 group bg-white rounded-xl border border-outline-variant overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
                        <div class="aspect-[16/9] overflow-hidden relative bg-gray-100">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $first->image_url }}" alt="{{ $first->name }}">
                            <div class="absolute top-4 right-4 flex gap-2">
                                <span class="bg-emerald-600 text-white flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-extrabold shadow">
                                    <span class="material-symbols-outlined text-[12px] font-bold">check_circle</span>
                                    HALAL MUI
                                </span>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-xl font-bold text-on-surface group-hover:text-primary transition-colors">{{ $first->name }}</h4>
                                        <div class="flex items-center gap-1 mt-1">
                                            <span class="material-symbols-outlined text-amber-500 text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                            <span class="font-bold text-sm text-on-surface">{{ $first->average_rating }}</span>
                                            <span class="text-secondary text-xs">({{ $first->reviews_count }} reviews)</span>
                                        </div>
                                    </div>
                                    <span class="text-primary font-bold text-sm">Signature Taste</span>
                                </div>
                                <p class="text-secondary text-xs leading-relaxed mt-2 line-clamp-2">{{ $first->description }}</p>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-xs text-secondary">
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">location_on</span> {{ Str::limit($first->address, 45) }}</span>
                                <a href="{{ route('tourist.detail', $first->id) }}" class="bg-primary hover:bg-primary/95 text-white font-bold px-5 py-2.5 rounded-lg flex items-center gap-0.5">
                                    Lihat Restoran <span class="material-symbols-outlined text-sm">chevron_right</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Column Stack Card 2 & 3 -->
                <div class="flex flex-col gap-6">
                    @foreach($restaurants->slice(1, 2) as $rest)
                        <div class="group bg-white rounded-xl border border-outline-variant overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
                            <div class="aspect-video overflow-hidden relative bg-gray-100">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $rest->image_url }}" alt="{{ $rest->name }}">
                                <span class="absolute top-3 right-3 bg-emerald-600 text-white px-2 py-0.5 rounded-full text-[9px] font-bold shadow">HALAL</span>
                            </div>
                            <div class="p-4 flex-grow flex flex-col justify-between space-y-3">
                                <div>
                                    <h4 class="font-bold text-sm text-on-surface truncate group-hover:text-primary transition-colors">{{ $rest->name }}</h4>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center gap-0.5">
                                            <span class="material-symbols-outlined text-amber-500 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                            <span class="font-bold text-xs text-on-surface">{{ $rest->average_rating }}</span>
                                        </div>
                                        <span class="text-primary font-bold text-xs">Pilihan Terbaik</span>
                                    </div>
                                </div>
                                <a href="{{ route('tourist.detail', $rest->id) }}" class="w-full py-2 border border-primary text-primary text-center text-xs font-bold rounded-lg hover:bg-primary/5 transition-all">
                                    Detail Resto
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Remaining Restaurants List Grid -->
            @if($restaurants->count() > 3)
                <div class="pt-8 border-t border-outline-variant/30">
                    <h3 class="text-xs font-bold text-secondary uppercase tracking-wider mb-4">Destinasi Kuliner Lainnya</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($restaurants->slice(3) as $rest)
                            <div class="group bg-white rounded-xl border border-outline-variant overflow-hidden hover:shadow-lg transition-all duration-300">
                                <div class="h-44 overflow-hidden relative bg-gray-100">
                                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $rest->image_url }}" alt="{{ $rest->name }}">
                                    <span class="absolute top-3 right-3 bg-emerald-600 text-white px-2 py-0.5 rounded-full text-[9px] font-bold shadow">HALAL</span>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <h4 class="font-bold text-sm text-on-surface truncate group-hover:text-primary transition-colors">{{ $rest->name }}</h4>
                                        <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ $rest->address }}</p>
                                    </div>
                                    <div class="flex items-center justify-between text-xs pt-1">
                                        <div class="flex items-center gap-0.5">
                                            <span class="material-symbols-outlined text-amber-500 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                            <span class="font-bold text-on-surface">{{ $rest->average_rating }}</span>
                                        </div>
                                        <a href="{{ route('tourist.detail', $rest->id) }}" class="text-primary font-bold hover:underline flex items-center gap-0.5">Lihat <span class="material-symbols-outlined text-sm">chevron_right</span></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
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
        <a class="flex flex-col items-center text-primary font-bold active:scale-90 transition-transform duration-100" href="{{ route('tourist.jelajah') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
            <span class="text-[10px] mt-0.5">Home</span>
        </a>
        <a class="flex flex-col items-center text-secondary active:scale-90 transition-transform duration-100" href="{{ route('tourist.bookings') }}">
            <span class="material-symbols-outlined">book_online</span>
            <span class="text-[10px] mt-0.5">Bookings</span>
        </a>
        <a class="flex flex-col items-center text-secondary active:scale-90 transition-transform duration-100" href="{{ route('tourist.profile') }}">
            <span class="material-symbols-outlined">account_circle</span>
            <span class="text-[10px] mt-0.5">Profile</span>
        </a>
    </div>
</nav>
@endsection
