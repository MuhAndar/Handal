<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Handal - Sekolah Aman Digital')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        primary: '#2563EB'
                    }
                }
            }
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }
        /* Smooth transitions on interactive elements */
        a, button, .transition-smooth {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Indikator Loading Garis Halus di Atas Layar (Gaya YouTube / GitHub / NProgress) */
        #top-loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, #F59E0B, #FBBF24, #3B82F6);
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.7), 0 0 5px rgba(59, 130, 246, 0.5);
            z-index: 99999;
            transition: width 0.3s cubic-bezier(0.1, 0.05, 0.25, 1), opacity 0.2s ease;
            pointer-events: none;
            opacity: 0;
        }
        #top-loading-bar.active {
            opacity: 1;
        }
        /* Hilangkan kotak hitam / focus outline bawaan browser pada poligon & marker peta Leaflet */
        .leaflet-container path:focus,
        .leaflet-container path:focus-visible,
        .leaflet-container svg:focus,
        .leaflet-container svg:focus-visible,
        .leaflet-container div:focus,
        .leaflet-container div:focus-visible,
        path.leaflet-interactive:focus,
        path.leaflet-interactive:focus-visible,
        .leaflet-interactive:focus,
        .leaflet-interactive:focus-visible {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 flex flex-col min-h-full">
    {{-- GARIS INDIKATOR LOADING HALUS --}}
    <div id="top-loading-bar"></div>

    @php
        $isDarkNavbar = request()->routeIs('speed.coding');
    @endphp

    <nav class="{{ $isDarkNavbar ? 'bg-slate-950/95 backdrop-blur-md border-b border-slate-800 shadow-2xl' : 'bg-blue-600 shadow-lg' }} sticky top-0 z-50 transition-colors duration-300" x-data="{ open: false }">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex justify-between items-center h-20">

                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/Handal 8.png') }}" alt="Logo Handal"
                        class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-110 drop-shadow-md">
                </a>

                <div class="hidden md:flex items-center gap-2 lg:gap-3">
                    <a href="{{ route('landing') }}"
                        class="px-3.5 py-2 rounded-full text-sm font-semibold transition-all duration-200
                        @if(request()->routeIs('landing'))
                            {{ $isDarkNavbar ? 'bg-slate-800 text-white font-bold' : 'bg-white/15 text-white font-bold' }}
                        @else
                            {{ $isDarkNavbar ? 'text-slate-300 hover:text-white hover:bg-slate-800/80' : 'text-blue-100 hover:text-white hover:bg-white/10' }}
                        @endif">
                        Beranda
                    </a>

                    <a href="{{ route('speed.coding') }}"
                        class="px-3.5 py-2 rounded-full text-sm font-semibold transition-all duration-200 flex items-center gap-1.5
                        @if(request()->routeIs('speed.coding'))
                            bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 font-black shadow-lg shadow-amber-500/25 scale-[1.02]
                        @else
                            {{ $isDarkNavbar ? 'text-amber-300 hover:text-white hover:bg-slate-800/80' : 'text-amber-200 hover:text-white hover:bg-white/10' }}
                        @endif">
                        <i class="bi bi-lightning-charge-fill {{ request()->routeIs('speed.coding') ? 'text-slate-950' : 'text-amber-300' }}"></i>
                        <span>Speed Coding</span>
                        <span class="px-1.5 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full {{ request()->routeIs('speed.coding') ? 'bg-slate-950 text-amber-300' : 'bg-amber-400 text-blue-950' }}">
                            Kompetisi
                        </span>
                    </a>

                    <a href="{{ route('ranking.page') }}"
                        class="px-3.5 py-2 rounded-full text-sm font-semibold transition-all duration-200
                        @if(request()->routeIs('ranking.page'))
                            {{ $isDarkNavbar ? 'bg-slate-800 text-white font-bold' : 'bg-white/15 text-white font-bold' }}
                        @else
                            {{ $isDarkNavbar ? 'text-slate-300 hover:text-white hover:bg-slate-800/80' : 'text-blue-100 hover:text-white hover:bg-white/10' }}
                        @endif">
                        Leaderboard
                    </a>

                    @auth
                        @if (Auth::user()->role === 'school')
                            <div
                                class="inline-flex items-center gap-1 {{ $isDarkNavbar ? 'bg-slate-900/90 border-slate-700/80' : 'bg-blue-800/40 border-blue-400/30' }} backdrop-blur-md rounded-full p-1 border shadow-inner">
                                <a href="{{ route('school.dashboard') }}"
                                    class="px-4 py-2 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2
                                   {{ request()->routeIs('school.dashboard') ? ($isDarkNavbar ? 'bg-amber-400 text-slate-950 shadow-md font-black' : 'bg-white text-blue-600 shadow-md') : ($isDarkNavbar ? 'text-slate-300 hover:bg-slate-800 hover:text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white') }}">
                                    <i class="bi bi-grid-fill"></i> Dashboard
                                </a>
                                <a href="{{ route('school.profile.edit') }}"
                                    class="px-4 py-2 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2
                                   {{ request()->routeIs('school.profile.edit') ? ($isDarkNavbar ? 'bg-amber-400 text-slate-950 shadow-md font-black' : 'bg-white text-blue-600 shadow-md') : ($isDarkNavbar ? 'text-slate-300 hover:bg-slate-800 hover:text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white') }}">
                                    <i class="bi bi-person-circle"></i> Profil
                                </a>
                            </div>
                        @elseif (Auth::user()->role === 'admin')
                            <div
                                class="inline-flex items-center gap-1 {{ $isDarkNavbar ? 'bg-slate-900/90 border-slate-700/80' : 'bg-blue-800/40 border-blue-400/30' }} backdrop-blur-md rounded-full p-1 border shadow-inner">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="px-4 py-2 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2
                                   {{ request()->routeIs('admin.dashboard') ? ($isDarkNavbar ? 'bg-amber-400 text-slate-950 shadow-md font-black' : 'bg-white text-blue-600 shadow-md') : ($isDarkNavbar ? 'text-slate-300 hover:bg-slate-800 hover:text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white') }}">
                                    <i class="bi bi-grid-1x2-fill"></i> Panel Admin
                                </a>
                            </div>
                        @elseif (Auth::user()->role === 'validator')
                            <div
                                class="inline-flex items-center gap-1 {{ $isDarkNavbar ? 'bg-slate-900/90 border-slate-700/80' : 'bg-blue-800/40 border-blue-400/30' }} backdrop-blur-md rounded-full p-1 border shadow-inner">
                                <a href="{{ route('validator.dashboard') }}"
                                    class="px-4 py-2 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2
                                   {{ request()->routeIs('validator.dashboard') ? ($isDarkNavbar ? 'bg-amber-400 text-slate-950 shadow-md font-black' : 'bg-white text-blue-600 shadow-md') : ($isDarkNavbar ? 'text-slate-300 hover:bg-slate-800 hover:text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white') }}">
                                    <i class="bi bi-shield-check"></i> Panel Validator
                                </a>
                            </div>
                        @endif

                        <div class="h-6 w-px {{ $isDarkNavbar ? 'bg-slate-700' : 'bg-blue-400' }} mx-2"></div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full {{ $isDarkNavbar ? 'bg-slate-800/90 hover:bg-slate-700 text-slate-200 border-slate-700' : 'bg-white/10 hover:bg-white/20 text-white border-white/20' }} border transition-all duration-200 shadow-xs group"
                                title="Klik untuk membuka {{ Auth::user()->role === 'admin' ? 'Panel Admin' : (Auth::user()->role === 'validator' ? 'Panel Validator' : 'Dashboard') }}">
                                <span class="w-6 h-6 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-amber-400 text-slate-950' : (Auth::user()->role === 'validator' ? 'bg-emerald-400 text-slate-950' : 'bg-blue-400 text-white') }} flex items-center justify-center font-black text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="text-sm font-bold group-hover:underline">{{ Auth::user()->name }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider rounded-full {{ Auth::user()->role === 'admin' ? 'bg-amber-400/25 text-amber-300 border border-amber-400/30' : (Auth::user()->role === 'validator' ? 'bg-emerald-400/25 text-emerald-300 border border-emerald-400/30' : 'bg-blue-300/20 text-blue-100 border border-blue-200/30') }}">
                                    {{ Auth::user()->role }}
                                </span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center justify-center w-9 h-9 rounded-full {{ $isDarkNavbar ? 'bg-slate-800 text-slate-300 hover:bg-red-500 hover:text-white border-slate-700' : 'bg-blue-700 text-blue-200 hover:bg-red-500 hover:text-white border-blue-500' }} border hover:border-red-400 transition-all shadow-sm"
                                    title="Keluar">
                                    <i class="bi bi-box-arrow-right text-lg"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="group flex items-center gap-2 px-6 py-2.5 text-sm font-bold {{ $isDarkNavbar ? 'text-slate-950 bg-gradient-to-r from-amber-400 to-yellow-300 hover:from-amber-300 hover:to-yellow-200 shadow-amber-500/20' : 'text-blue-700 bg-white hover:bg-slate-100' }} rounded-full shadow-lg transition-all transform hover:-translate-y-0.5">
                            <i class="bi bi-person-circle text-lg opacity-80 group-hover:opacity-100"></i>
                            <span>Masuk</span>
                        </a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="open = !open"
                        class="{{ $isDarkNavbar ? 'text-slate-200 hover:bg-slate-800' : 'text-white hover:bg-blue-700' }} p-2 focus:outline-none rounded-lg transition-colors">
                        <i class="bi text-3xl" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden {{ $isDarkNavbar ? 'bg-slate-900 border-t border-slate-800 shadow-2xl' : 'bg-blue-700 border-t border-blue-500 shadow-inner' }}" style="display: none;">

            <div class="px-4 pt-4 pb-6 space-y-3">
                <div class="space-y-1 pb-3 {{ $isDarkNavbar ? 'border-b border-slate-800' : 'border-b border-blue-500/50' }}">
                    <a href="{{ route('landing') }}"
                        class="block px-4 py-2.5 rounded-xl font-semibold transition-colors
                        @if(request()->routeIs('landing'))
                            {{ $isDarkNavbar ? 'bg-slate-800 text-white font-bold' : 'bg-blue-800 text-white font-bold' }}
                        @else
                            {{ $isDarkNavbar ? 'text-slate-300 hover:bg-slate-800/80 hover:text-white' : 'text-white hover:bg-blue-800/60' }}
                        @endif">
                        <i class="bi bi-house-door-fill mr-2 {{ $isDarkNavbar ? 'text-slate-400' : 'text-blue-200' }}"></i> Beranda
                    </a>
                    <a href="{{ route('speed.coding') }}"
                        class="flex items-center justify-between px-4 py-2.5 rounded-xl font-semibold transition-colors
                        @if(request()->routeIs('speed.coding'))
                            bg-amber-400 text-slate-950 font-black
                        @else
                            {{ $isDarkNavbar ? 'text-slate-300 hover:bg-slate-800/80' : 'text-white hover:bg-blue-800/60' }}
                        @endif">
                        <span>
                            <i class="bi bi-lightning-charge-fill mr-2 {{ request()->routeIs('speed.coding') ? 'text-slate-950' : 'text-amber-300' }}"></i>
                            Kompetisi Speed Coding
                        </span>
                        <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-full {{ request()->routeIs('speed.coding') ? 'bg-slate-950 text-amber-300' : 'bg-amber-400 text-blue-950' }}">
                            Kompetisi
                        </span>
                    </a>
                    <a href="{{ route('ranking.page') }}"
                        class="block px-4 py-2.5 rounded-xl font-semibold transition-colors
                        @if(request()->routeIs('ranking.page'))
                            {{ $isDarkNavbar ? 'bg-slate-800 text-white font-bold' : 'bg-blue-800 text-white font-bold' }}
                        @else
                            {{ $isDarkNavbar ? 'text-slate-300 hover:bg-slate-800/80 hover:text-white' : 'text-white hover:bg-blue-800/60' }}
                        @endif">
                        <i class="bi bi-trophy-fill mr-2 text-amber-300"></i> Leaderboard Sekolah
                    </a>
                </div>

                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 mb-4 px-3 py-2 rounded-2xl bg-white/10 hover:bg-white/15 transition-all">
                        <div
                            class="w-10 h-10 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-amber-400 text-slate-950' : (Auth::user()->role === 'validator' ? 'bg-emerald-400 text-slate-950' : 'bg-blue-500 text-white') }} flex items-center justify-center font-black text-lg shadow-sm flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-white font-bold flex items-center gap-2">
                                <span class="truncate">{{ Auth::user()->name }}</span>
                                <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-full {{ Auth::user()->role === 'admin' ? 'bg-amber-400 text-slate-950' : (Auth::user()->role === 'validator' ? 'bg-emerald-400 text-slate-950' : 'bg-white/20 text-white') }}">
                                    {{ Auth::user()->role }}
                                </span>
                            </p>
                            <p class="text-blue-200 text-xs truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </a>

                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-4 py-3 rounded-xl {{ $isDarkNavbar ? 'bg-slate-800 text-amber-300 border-slate-700 hover:bg-slate-700' : 'bg-blue-800 text-white border-blue-600 hover:bg-blue-900' }} font-bold transition-colors border">
                            <i class="bi bi-grid-1x2-fill mr-2"></i> Panel Admin
                        </a>
                        <a href="{{ route('admin.speed_coding.index') }}"
                            class="block px-4 py-3 rounded-xl {{ $isDarkNavbar ? 'bg-slate-800 text-slate-200 border-slate-700 hover:bg-slate-700' : 'bg-blue-800 text-white border-blue-600 hover:bg-blue-900' }} font-bold transition-colors border">
                            <i class="bi bi-lightning-charge-fill mr-2"></i> Kelola Speed Coding
                        </a>
                    @elseif (Auth::user()->role === 'validator')
                        <a href="{{ route('validator.dashboard') }}"
                            class="block px-4 py-3 rounded-xl {{ $isDarkNavbar ? 'bg-slate-800 text-emerald-300 border-slate-700 hover:bg-slate-700' : 'bg-blue-800 text-white border-blue-600 hover:bg-blue-900' }} font-bold transition-colors border">
                            <i class="bi bi-shield-check mr-2"></i> Panel Validator
                        </a>
                    @elseif (Auth::user()->role === 'school')
                        <a href="{{ route('school.dashboard') }}"
                            class="block px-4 py-3 rounded-xl {{ $isDarkNavbar ? 'bg-slate-800 text-amber-300 border-slate-700 hover:bg-slate-700' : 'bg-blue-800 text-white border-blue-600 hover:bg-blue-900' }} font-bold transition-colors border">
                            <i class="bi bi-grid-fill mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('school.profile.edit') }}"
                            class="block px-4 py-3 rounded-xl {{ $isDarkNavbar ? 'bg-slate-800 text-slate-200 border-slate-700 hover:bg-slate-700' : 'bg-blue-800 text-white border-blue-600 hover:bg-blue-900' }} font-bold transition-colors border">
                            <i class="bi bi-person-circle mr-2"></i> Profil Sekolah
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-3 rounded-xl bg-red-500/20 text-red-100 font-bold hover:bg-red-500 hover:text-white transition-colors border border-red-500/30">
                            <i class="bi bi-box-arrow-right mr-2"></i> Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block w-full text-center px-4 py-3 rounded-xl {{ $isDarkNavbar ? 'bg-gradient-to-r from-amber-400 to-yellow-300 text-slate-950 font-black shadow-md hover:from-amber-300 hover:to-yellow-200' : 'bg-white text-blue-700 font-bold shadow-md hover:bg-slate-100' }}">
                        Masuk Akun
                    </a>
                    <a href="{{ route('registration.page') }}"
                        class="block w-full text-center px-4 py-3 rounded-xl border {{ $isDarkNavbar ? 'border-slate-700 bg-slate-800/80 text-slate-200 hover:bg-slate-800' : 'border-white/30 text-white hover:bg-white/10' }} font-bold">
                        Daftar Sekolah Baru
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-auto relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 via-cyan-500 to-blue-600"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="container mx-auto px-6 pt-16 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                <div class="space-y-6 text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start">
                        <img src="{{ asset('img/Handal 8.png') }}" class="h-14 w-auto object-contain"
                            alt="Logo HANDAL">
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Platform resmi pemeringkatan dan asesmen keamanan digital untuk mewujudkan ekosistem sekolah
                        yang aman.
                    </p>
                </div>

                <div class="text-center md:text-left">
                    <h3
                        class="text-white font-bold uppercase tracking-wider text-sm mb-6 border-l-0 md:border-l-4 border-blue-600 md:pl-3">
                        Jelajahi</h3>
                    <ul class="space-y-4 text-sm">
                        <li><a href="/" class="hover:text-blue-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('ranking.page') }}"
                                class="hover:text-blue-400 transition-colors">Peringkat Sekolah</a></li>
                        <li><a href="{{ route('registration.page') }}"
                                class="hover:text-blue-400 transition-colors">Daftar Sekolah</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Panduan Teknis</a></li>
                    </ul>
                </div>

                <div class="text-center md:text-left">
                    <h3
                        class="text-white font-bold uppercase tracking-wider text-sm mb-6 border-l-0 md:border-l-4 border-blue-600 md:pl-3">
                        Hubungi Kami</h3>
                    <ul class="space-y-4 text-sm flex flex-col items-center md:items-start">
                        <li class="flex items-start gap-3">
                            <i class="bi bi-geo-alt-fill text-blue-500 mt-0.5"></i>
                            <span>BBLSDM Komdigi Makassar<br>Jl. Prof. Abdurahman Basalamah</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-envelope-fill text-blue-500"></i>
                            <a href="mailto:info@handalsekolah.id" class="hover:text-white">info@handalsekolah.id</a>
                        </li>
                    </ul>
                </div>

                <div class="text-center md:text-left">
                    <h3
                        class="text-white font-bold uppercase tracking-wider text-sm mb-6 border-l-0 md:border-l-4 border-blue-600 md:pl-3">
                        Ikuti Kami</h3>
                    <div class="flex gap-4 justify-center md:justify-start">
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all"><i
                                class="bi bi-facebook"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-pink-600 hover:text-white transition-all"><i
                                class="bi bi-instagram"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-sky-500 hover:text-white transition-all"><i
                                class="bi bi-twitter-x"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-red-600 hover:text-white transition-all"><i
                                class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>

            <div
                class="border-t border-slate-800 pt-8 mt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-center md:text-left">
                <p>&copy; {{ date('Y') }} <span class="text-blue-400 font-bold">Handal Sekolah Aman
                        Digital</span>. Hak Cipta Dilindungi.</p>
                <div class="flex gap-4 md:gap-6 justify-center">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors">Bantuan</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- SCRIPT INDIKATOR PROGRESS BAR HALUS PADA NAVIGASI MENU & SMOOTH SCROLL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bar = document.getElementById('top-loading-bar');

            // Quick finish progress bar on initial page load
            if (bar) {
                bar.classList.add('active');
                bar.style.width = '100%';
                setTimeout(() => {
                    bar.classList.remove('active');
                    bar.style.width = '0%';
                }, 200);
            }

            // Smooth top loading indicator untuk link internal (tanpa memutihkan layar)
            const internalLinks = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])');
            internalLinks.forEach(function (link) {
                link.addEventListener('click', function (e) {
                    if (this.hostname === window.location.hostname && !e.ctrlKey && !e.shiftKey && !e.metaKey && e.button === 0) {
                        const targetUrl = this.href;
                        if (targetUrl === window.location.href || targetUrl === window.location.href + '#') return;
                        
                        if (bar) {
                            bar.classList.add('active');
                            bar.style.width = '30%';
                            setTimeout(() => { bar.style.width = '75%'; }, 80);
                        }
                    }
                });
            });

            // Reset progress bar on browser back/forward (pageshow)
            window.addEventListener('pageshow', function () {
                if (bar) {
                    bar.style.width = '100%';
                    setTimeout(() => {
                        bar.classList.remove('active');
                        bar.style.width = '0%';
                    }, 200);
                }
            });

            // Smooth scroll untuk link hash internal misal #peta-sebaran, #tabel-pemeringkatan
            document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
                anchor.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href').substring(1);
                    if (!targetId) return;
                    const targetEl = document.getElementById(targetId);
                    if (targetEl) {
                        e.preventDefault();
                        targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        });

        // =========================================================================
        // HANDAL GLOBAL DIALOG API (PENGGANTI POPUP BROWSER NATIVE)
        // =========================================================================
        window.handalConfirm = function(options = {}) {
            return new Promise((resolve) => {
                const title = options.title || 'Konfirmasi Tindakan';
                const message = options.message || options.text || '';
                const confirmText = options.confirmText || 'Lanjutkan';
                const cancelText = options.cancelText || 'Batal';
                const type = options.type || 'info'; // info | warning | danger | success

                const modal = document.getElementById('handalModalDialog');
                const card = document.getElementById('handalModalCard');
                const iconContainer = document.getElementById('handalModalIconContainer');
                const icon = document.getElementById('handalModalIcon');
                const titleEl = document.getElementById('handalModalTitle');
                const msgEl = document.getElementById('handalModalMessage');
                const confirmBtn = document.getElementById('handalModalConfirmBtn');
                const cancelBtn = document.getElementById('handalModalCancelBtn');

                if (!modal || !confirmBtn) {
                    // Fallback jika elemen modal belum siap
                    resolve(window.confirm(title + "\n\n" + message));
                    return;
                }

                titleEl.innerText = title;
                msgEl.innerText = message;
                confirmBtn.innerText = confirmText;
                cancelBtn.innerText = cancelText;
                cancelBtn.classList.remove('hidden');

                if (type === 'danger') {
                    iconContainer.className = "w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg bg-red-100 text-red-600 shadow-red-500/15";
                    icon.className = "bi bi-trash3-fill";
                    confirmBtn.className = "flex-1 py-2.5 px-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-md shadow-red-500/20 transition-all";
                } else if (type === 'warning') {
                    iconContainer.className = "w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg bg-amber-100 text-amber-700 shadow-amber-500/15";
                    icon.className = "bi bi-exclamation-triangle-fill";
                    confirmBtn.className = "flex-1 py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-md shadow-amber-500/20 transition-all";
                } else if (type === 'success') {
                    iconContainer.className = "w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg bg-emerald-100 text-emerald-600 shadow-emerald-500/15";
                    icon.className = "bi bi-check-circle-fill";
                    confirmBtn.className = "flex-1 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all";
                } else {
                    iconContainer.className = "w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg bg-blue-100 text-blue-600 shadow-blue-500/15";
                    icon.className = "bi bi-shield-check";
                    confirmBtn.className = "flex-1 py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all";
                }

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    card.classList.remove('scale-95');
                }, 10);

                const handleClose = (result) => {
                    modal.classList.add('opacity-0');
                    card.classList.add('scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        resolve(result);
                    }, 200);
                };

                confirmBtn.onclick = () => handleClose(true);
                cancelBtn.onclick = () => handleClose(false);
            });
        };
    </script>

    {{-- HANDAL BRANDED CUSTOM DIALOG MODAL --}}
    <div id="handalModalDialog" class="hidden fixed inset-0 z-[9999] bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-200 opacity-0">
        <div id="handalModalCard" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 text-center transform scale-95 transition-transform duration-200 space-y-4">
            <div id="handalModalIconContainer" class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg transition-colors">
                <i id="handalModalIcon" class="bi bi-shield-check"></i>
            </div>
            <div>
                <h3 id="handalModalTitle" class="text-lg font-black text-slate-800 leading-snug mb-1.5">
                    Konfirmasi Tindakan
                </h3>
                <p id="handalModalMessage" class="text-xs text-slate-600 leading-relaxed font-medium">
                    Pesan dialog konfirmasi.
                </p>
            </div>
            <div class="flex items-center gap-2 pt-2">
                <button type="button" id="handalModalCancelBtn"
                        class="flex-1 py-2.5 px-4 rounded-xl text-slate-600 hover:bg-slate-100 font-bold text-xs border border-slate-200 transition-colors">
                    Batal
                </button>
                <button type="button" id="handalModalConfirmBtn"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>
</body>

</html>
