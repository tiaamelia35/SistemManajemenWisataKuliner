<!DOCTYPE html>
<html class="light h-full" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMWKP - Wisata Kuliner Palembang')</title>

    <!-- Tailwind CSS with Forms and Container Queries plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Google Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- LeafletJS (Map Library) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- ChartJS (Analytics Library) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Tailwind Configuration to match Figma Design Token -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#af101a", // Ampera Red
                        "primary-container": "#d32f2f",
                        secondary: "#5d5f5f",
                        background: "#fcf9f8", // Soft Porcelain Gray
                        surface: "#ffffff", // Pure White
                        "surface-container": "#f0eded",
                        "surface-container-low": "#f6f3f2",
                        "surface-container-high": "#eae7e7",
                        "surface-container-lowest": "#ffffff",
                        "on-background": "#1b1c1c",
                        "on-surface": "#1b1c1c",
                        "on-surface-variant": "#5b403d",
                        "outline-variant": "#e4beba",
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "2xl": "1.5rem",
                        "full": "9999px"
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fcf9f8;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @yield('styles')
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col antialiased">

    <!-- Flash Alerts Container -->
    @if(session('success') || session('error') || $errors->any())
        <div class="fixed top-4 right-4 z-[9999] max-w-sm w-full space-y-2 pointer-events-auto">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-lg flex items-start gap-3 transform translate-y-0 transition-all duration-300">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600"><span class="material-symbols-outlined text-sm">close</span></button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl shadow-lg flex items-start gap-3 transform translate-y-0 transition-all duration-300">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
                    <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600"><span class="material-symbols-outlined text-sm">close</span></button>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl shadow-lg flex items-start gap-3 transform translate-y-0 transition-all duration-300">
                    <span class="material-symbols-outlined text-rose-600">warning</span>
                    <div class="flex-1 text-sm font-medium">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600"><span class="material-symbols-outlined text-sm">close</span></button>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content Area -->
    @yield('content')

    <!-- Scripts Injection Section -->
    @yield('scripts')
    
</body>
</html>
