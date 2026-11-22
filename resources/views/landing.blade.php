@extends('layouts.app')

@section('content')
    <style>
        html { scroll-behavior: smooth; }
    </style>

    {{-- LEAFLET CSS & JS UNTUK PETA INTERAKTIF --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- FLOATING QUICK NAVIGATION DOCK (MUNCUL PERLAHAN SAAT SCROLL KE BAWAH) --}}
    <aside id="floating-quick-nav"
           class="fixed left-3 lg:left-6 xl:left-8 top-1/2 -translate-y-1/2 z-40 hidden md:block transition-all duration-500 ease-out opacity-0 pointer-events-none -translate-x-6">
        <div class="bg-white/90 backdrop-blur-xl border border-slate-200/90 shadow-2xl shadow-blue-500/10 rounded-2xl p-2.5 w-48 transition-all">
            <div class="px-2.5 py-1.5 mb-1.5 flex items-center justify-between border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                <span class="flex items-center gap-1.5 text-blue-600">
                    <i class="bi bi-compass-fill"></i> Navigasi Halaman
                </span>
                <a href="#hero-top" class="text-slate-400 hover:text-blue-600 transition-colors" title="Kembali ke atas">
                    <i class="bi bi-arrow-up"></i>
                </a>
            </div>

            <nav class="space-y-1 text-xs font-semibold">
                <a href="#peta-sebaran"
                   data-nav-target="peta-sebaran"
                   class="quick-nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-blue-50/70 transition-all">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs flex-shrink-0 transition-colors">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <span class="truncate">Peta Sebaran</span>
                </a>

                <a href="#tabel-pemeringkatan"
                   data-nav-target="tabel-pemeringkatan"
                   class="quick-nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-blue-50/70 transition-all">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs flex-shrink-0 transition-colors">
                        <i class="bi bi-trophy-fill"></i>
                    </span>
                    <span class="truncate">Tabel Peringkat</span>
                </a>

                <a href="#definisi-metodologi"
                   data-nav-target="definisi-metodologi"
                   class="quick-nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-blue-50/70 transition-all">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs flex-shrink-0 transition-colors">
                        <i class="bi bi-diagram-3-fill"></i>
                    </span>
                    <span class="truncate">Metodologi</span>
                </a>

                <a href="#dasar-hukum"
                   data-nav-target="dasar-hukum"
                   class="quick-nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-blue-50/70 transition-all">
                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs flex-shrink-0 transition-colors">
                        <i class="bi bi-shield-check"></i>
                    </span>
                    <span class="truncate">Dasar Hukum</span>
                </a>
            </nav>
        </div>
    </aside>

    <div class="relative w-full overflow-hidden">

        <div class="absolute inset-0 bg-gradient-to-b from-blue-50 via-white to-slate-50 -z-30"></div>

        <div class="absolute inset-0 -z-20 opacity-30 pointer-events-none"
            style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px;">
        </div>

        <div
            class="absolute -top-20 -right-20 w-[500px] h-[500px] bg-blue-300/30 rounded-full blur-[100px] -z-20 mix-blend-multiply animate-pulse">
        </div>

        <div
            class="absolute bottom-0 -left-20 w-[500px] h-[500px] bg-cyan-300/30 rounded-full blur-[100px] -z-20 mix-blend-multiply">
        </div>

        <div class="absolute -top-10 -left-10 w-[600px] opacity-20 -z-20 pointer-events-none mix-blend-multiply">
            <img src="{{ asset('img/1.png') }}" class="w-full" alt="Pattern">
        </div>
        <div class="hidden lg:block absolute top-20 left-24 -z-10 animate-[bounce_4s_infinite]">
            <div
                class="w-16 h-16 bg-white/50 backdrop-blur-md rounded-2xl border border-white/50 shadow-sm flex items-center justify-center transform -rotate-12">
                <i class="bi bi-shield-lock text-3xl text-blue-500"></i>
            </div>
        </div>

        <div id="hero-top" class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-28">

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center mb-28">
                <div class="order-2 lg:order-1">
                    <div class="flex items-center gap-3 mb-6 flex-wrap">
                        <span
                            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200/80 shadow-xs">
                            <i class="bi bi-shield-check text-blue-600"></i> BBLSDM Komdigi Makassar
                        </span>
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/80 shadow-xs">
                            <i class="bi bi-patch-check-fill text-emerald-500"></i> Platform Resmi
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                        Wujudkan Ekosistem <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">
                            Sekolah Aman Digital
                        </span>
                    </h1>

                    <p class="text-lg text-slate-600 leading-relaxed max-w-xl mb-8">
                        Platform resmi pemeringkatan (HANDAL) untuk mengukur kesiapan dan keamanan digital seluruh jenjang sekolah (SD, SMP, SMA/SMK/MA) di Indonesia.
                    </p>

                    <div
                        class="inline-flex items-center gap-6 bg-white/80 backdrop-blur px-5 py-3 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping"></span>
                                <span class="relative inline-flex h-3 w-3 rounded-full bg-green-500"></span>
                            </span>
                            Terverifikasi
                        </div>
                        <div class="h-4 w-px bg-slate-300"></div>
                        <div class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <i class="bi bi-database text-blue-600"></i> Data Real-time
                        </div>
                    </div>
                </div>

                {{-- LOGO BESAR HANDAL SHIELD TRANSPARAN (MENGGANTIKAN FOTO SAMPUL BESAR DI KANAN) --}}
                <div class="relative order-1 lg:order-2 flex items-center justify-center py-4 lg:py-8 group">
                    {{-- Ambient Glow Lembut di Belakang Logo --}}
                    <div
                        class="absolute -inset-4 bg-gradient-to-tr from-blue-500/20 via-cyan-400/25 to-blue-600/15 rounded-full blur-3xl opacity-75 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
                    </div>

                    <div class="relative z-10 w-full max-w-lg flex flex-col items-center justify-center p-2 sm:p-6">
                        <img src="{{ asset('img/logo_handal_shield_transparent.png') }}"
                            alt="Logo HANDAL Sekolah Aman Digital"
                            class="w-full h-auto max-h-[380px] object-contain drop-shadow-[0_20px_40px_rgba(37,99,235,0.28)] hover:scale-105 transition-transform duration-500 cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-28">
                <div
                    class="group relative bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute -right-12 -bottom-12 opacity-5 pointer-events-none group-hover:opacity-10 transition-opacity">
                        <i class="bi bi-pencil-square text-[10rem] text-blue-600 -rotate-12"></i>
                    </div>

                    <div
                        class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 text-3xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Isi Asesmen Sekolah</h3>
                    <p class="text-slate-500 mb-8">Lakukan evaluasi mandiri kesiapan digital sekolah Anda.</p>

                    @auth
                        @if (Auth::user()->role === 'school')
                            <a href="{{ route('school.survey.start') }}"
                                class="inline-flex items-center font-bold text-blue-600 hover:gap-3 transition-all">Mulai
                                Sekarang <i class="bi bi-arrow-right ml-2"></i></a>
                        @else
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center font-bold text-blue-600 hover:gap-3 transition-all">Ke Dashboard
                                <i class="bi bi-arrow-right ml-2"></i></a>
                        @endif
                    @else
                        <a href="{{ route('registration.page') }}"
                            class="inline-flex items-center font-bold text-blue-600 hover:gap-3 transition-all">Masuk untuk
                            Mulai <i class="bi bi-arrow-right ml-2"></i></a>
                    @endauth
                </div>

                <div
                    class="group relative bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute -right-12 -bottom-12 opacity-5 pointer-events-none group-hover:opacity-10 transition-opacity">
                        <i class="bi bi-trophy-fill text-[10rem] text-emerald-600 rotate-12"></i>
                    </div>

                    <div
                        class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-3xl mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                        <i class="bi bi-trophy-fill"></i>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Peringkat Sekolah</h3>
                    <p class="text-slate-500 mb-8">Lihat sekolah dengan indeks keamanan terbaik.</p>

                    <a href="{{ route('ranking.page') }}"
                        class="inline-flex items-center font-bold text-emerald-600 hover:gap-3 transition-all">Lihat
                        Leaderboard <i class="bi bi-arrow-right ml-2"></i></a>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- SEKSI PETA SEBARAN SEKOLAH AMAN DIGITAL (LEAFLET PRESISI) --}}
            {{-- ========================================================= --}}
            <div id="peta-sebaran" class="max-w-6xl mx-auto mb-20 scroll-mt-24">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden p-6 md:p-8">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold mb-2">
                                <i class="bi bi-geo-alt-fill text-rose-500"></i> Pemetaan Geografis Digital
                            </div>
                            <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Peta Sebaran Sekolah Aman Digital
                            </h3>
                            <p class="text-slate-500 text-sm mt-1">
                                Batas administratif resmi Provinsi Sulawesi Selatan & titik koordinat sekolah terverifikasi di Kota Makassar.
                            </p>
                        </div>

                        <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs font-semibold text-slate-600 self-start md:self-auto">
                            <button type="button" id="btn-focus-makassar-landing" class="px-3.5 py-2 rounded-lg bg-white text-slate-900 shadow-xs font-bold transition-all">
                                Kota Makassar
                            </button>
                            <button type="button" id="btn-focus-sulsel-landing" class="px-3.5 py-2 rounded-lg hover:text-slate-900 transition-all">
                                Provinsi Sulsel
                            </button>
                            <button type="button" id="btn-focus-indo-landing" class="px-3.5 py-2 rounded-lg hover:text-slate-900 transition-all">
                                Seluruh Nusantara
                            </button>
                        </div>
                    </div>

                    <div class="relative w-full h-[480px] bg-slate-100 rounded-3xl overflow-hidden border border-slate-200 shadow-inner">
                        <div id="landing-map-container" class="w-full h-full z-10"></div>

                        {{-- LEGENDA INDEKS KEAMANAN --}}
                        <div class="absolute bottom-4 left-4 z-20 bg-white/95 backdrop-blur-md p-4 rounded-2xl border border-slate-200/90 shadow-lg text-xs max-w-[230px]">
                            <div class="font-bold text-slate-800 mb-2 flex items-center gap-1.5 text-xs uppercase tracking-wider">
                                <i class="bi bi-shield-check text-blue-600"></i> Tingkat Kesiapan
                            </div>
                            <div class="space-y-1.5 text-[11px]">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-cyan-500 shadow-xs flex-shrink-0"></span>
                                    <span class="text-slate-700 font-medium">Unggul Digital (&ge; 86)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-blue-600 shadow-xs flex-shrink-0"></span>
                                    <span class="text-slate-700 font-medium">Maju (76 - 85)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-amber-500 shadow-xs flex-shrink-0"></span>
                                    <span class="text-slate-700 font-medium">Berkembang (51 - 75)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-slate-400 shadow-xs flex-shrink-0"></span>
                                    <span class="text-slate-700 font-medium">Pemula / Rintisan (&lt; 51)</span>
                                </div>
                            </div>
                        </div>

                        {{-- BADGE INFO WILAYAH AKTIF (Ditempatkan di kiri atas samping tombol zoom agar tidak menghalangi tombol layer switcher di kanan atas) --}}
                        <div id="map-active-region-badge" class="absolute top-3 left-14 z-20 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-2.5 transition-all max-w-[280px] pointer-events-none">
                            <span id="badge-region-dot" class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                            <div class="text-xs truncate">
                                <span id="badge-region-name" class="font-bold text-slate-800 block truncate">Kota Makassar</span>
                                <span id="badge-region-info" class="text-slate-500 block text-[10px] truncate">{{ count($mapLocations) }} Sekolah Terdaftar (Pusat Pengujian)</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-400 gap-2">
                        <span><i class="bi bi-info-circle mr-1"></i> Klik marker pin pada peta untuk melihat profil skor lengkap dan alamat sekolah.</span>
                        <a href="{{ route('ranking.page') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                            Lihat Seluruh Peringkat Sekolah &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- SEKSI TABEL PEMERINGKATAN SEKOLAH (KOMPOSIT 70% - 30%)    --}}
            {{-- ========================================================= --}}
            <div id="tabel-pemeringkatan" class="max-w-6xl mx-auto mb-20 scroll-mt-24" x-data="initRankingTable()">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden p-6 md:p-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold mb-2">
                                <i class="bi bi-trophy-fill text-amber-500"></i> Peringkat Terverifikasi
                            </div>
                            <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Tabel Pemeringkatan Sekolah
                            </h3>
                            <p class="text-slate-500 text-sm mt-1">
                                Evaluasi komposit terpadu: <strong>70% Asesmen Mandiri</strong> + <strong>30% Survei Siswa</strong>.
                            </p>
                        </div>

                        {{-- Filter Jenjang & Search --}}
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs font-semibold text-slate-600 flex-wrap">
                                <button type="button" @click="filterJenjang = 'ALL'"
                                    class="px-3 py-1.5 rounded-lg transition-all"
                                    :class="filterJenjang === 'ALL' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'hover:text-slate-900'">
                                    Semua
                                </button>
                                <button type="button" @click="filterJenjang = 'SD'"
                                    class="px-3 py-1.5 rounded-lg transition-all"
                                    :class="filterJenjang === 'SD' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'hover:text-slate-900'">
                                    SD
                                </button>
                                <button type="button" @click="filterJenjang = 'SMP'"
                                    class="px-3 py-1.5 rounded-lg transition-all"
                                    :class="filterJenjang === 'SMP' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'hover:text-slate-900'">
                                    SMP
                                </button>
                                <button type="button" @click="filterJenjang = 'SMA'"
                                    class="px-3 py-1.5 rounded-lg transition-all"
                                    :class="filterJenjang === 'SMA' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'hover:text-slate-900'">
                                    SMA
                                </button>
                                <button type="button" @click="filterJenjang = 'SMK'"
                                    class="px-3 py-1.5 rounded-lg transition-all"
                                    :class="filterJenjang === 'SMK' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'hover:text-slate-900'">
                                    SMK
                                </button>
                                <button type="button" @click="filterJenjang = 'MA'"
                                    class="px-3 py-1.5 rounded-lg transition-all"
                                    :class="filterJenjang === 'MA' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'hover:text-slate-900'">
                                    MA
                                </button>
                            </div>

                            <div class="relative w-full sm:w-60">
                                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" x-model="search" placeholder="Cari nama / NPSN..."
                                    class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-xs placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="rounded-2xl border border-slate-100 overflow-hidden shadow-xs">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        <th class="px-5 py-4 text-center w-16">Peringkat</th>
                                        <th class="px-5 py-4 cursor-pointer hover:text-blue-600 transition-colors"
                                            @click="sortBy = 'name'; sortAsc = !sortAsc">
                                            Institusi Sekolah <i class="bi bi-arrow-down-up ml-1 text-[10px]"></i>
                                        </th>
                                        <th class="px-5 py-4 text-center">Asesmen Mandiri (70%)</th>
                                        <th class="px-5 py-4 text-center cursor-pointer hover:text-blue-600 transition-colors"
                                            @click="sortBy = 'students'; sortAsc = !sortAsc">
                                            Survei Siswa (30%) <i class="bi bi-arrow-down-up ml-1 text-[10px]"></i>
                                        </th>
                                        <th class="px-5 py-4 text-center cursor-pointer hover:text-blue-600 transition-colors"
                                            @click="sortBy = 'score'; sortAsc = !sortAsc">
                                            Skor Komposit <i class="bi bi-arrow-down-up ml-1 text-[10px]"></i>
                                        </th>
                                        <th class="px-5 py-4 text-center">Maturitas Tier</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                    <template x-for="(school, index) in filteredSchools" :key="school.id">
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="px-5 py-4 text-center font-bold">
                                                <template x-if="index === 0">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-700 font-extrabold shadow-xs">🥇</span>
                                                </template>
                                                <template x-if="index === 1">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-200 text-slate-700 font-extrabold shadow-xs">🥈</span>
                                                </template>
                                                <template x-if="index === 2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-200/60 text-amber-800 font-extrabold shadow-xs">🥉</span>
                                                </template>
                                                <template x-if="index > 2">
                                                    <span class="text-slate-500 font-bold" x-text="index + 1"></span>
                                                </template>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="font-extrabold text-slate-900" x-text="school.name"></div>
                                                <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-2">
                                                    <span class="font-semibold text-blue-600" x-text="school.jenjang"></span>
                                                    <span>&bull;</span>
                                                    <span x-text="'NPSN: ' + (school.npsn || '-')"></span>
                                                    <span>&bull;</span>
                                                    <span x-text="school.address || 'Makassar'"></span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="font-bold text-slate-800" x-text="Number(school.self_assessment_score).toFixed(1)"></span>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="font-bold text-indigo-600" x-text="Number(school.student_survey_score).toFixed(1)"></span>
                                                <span class="text-[10px] text-slate-400 block" x-text="'(' + school.student_respondents_count + ' responden)'"></span>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="inline-block px-3 py-1 rounded-xl bg-emerald-50 text-emerald-800 font-extrabold text-sm border border-emerald-200"
                                                      x-text="Number(school.current_score).toFixed(1)"></span>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="px-3 py-1 rounded-full text-[11px] font-bold border"
                                                      :class="getTierClass(school.current_score)"
                                                      x-text="getTierName(school.current_score)"></span>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="filteredSchools.length === 0">
                                        <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                            Tidak ada data sekolah yang sesuai dengan pencarian.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-400 gap-2">
                        <span><i class="bi bi-shield-check text-blue-600 mr-1"></i> Data diperbarui otomatis berdasarkan survei siswa & verifikasi bukti asesmen.</span>
                        <a href="{{ route('ranking.page.v2') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                            Buka Rincian Komposit 70/30 &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- SEKSI DEFINISI & METODOLOGI (KERANGKA RESMI KEMKOMDIGI)   --}}
            {{-- ========================================================= --}}
            <div id="definisi-metodologi" class="max-w-6xl mx-auto mb-20 scroll-mt-24">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden p-6 md:p-10">
                    <div class="mb-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold mb-2">
                            <i class="bi bi-diagram-3-fill text-blue-600"></i> Kerangka Kerja Resmi
                        </div>
                        <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Definisi & Metodologi Evaluasi HANDAL
                        </h3>
                        <p class="text-slate-500 text-sm mt-1 max-w-3xl">
                            Mengacu pada standar <em>Digital Literacy Global Framework (DLGF) UNESCO</em> dan pedoman operasional BBLSDM Komdigi Makassar.
                        </p>
                    </div>

                    {{-- 4 Pilar Indikator --}}
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50/60 to-white border border-blue-100/80 shadow-xs hover:-translate-y-1 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm mb-3 shadow-md shadow-blue-500/20">
                                01
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1.5">SDM & Literasi Digital</h4>
                            <p class="text-slate-600 text-xs leading-relaxed">Pengelola IT bersertifikasi, pelatihan microskill berkala, kompetisi siber, dan ekskul literasi digital siswa.</p>
                        </div>

                        <div class="p-5 rounded-2xl bg-gradient-to-br from-cyan-50/60 to-white border border-cyan-100/80 shadow-xs hover:-translate-y-1 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-cyan-600 text-white flex items-center justify-center font-bold text-sm mb-3 shadow-md shadow-cyan-500/20">
                                02
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Infrastruktur Digital</h4>
                            <p class="text-slate-600 text-xs leading-relaxed">Rasio perangkat laboratorium, kapasitas & stabilitas bandwidth internet, serta kebijakan BYOD aman.</p>
                        </div>

                        <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-50/60 to-white border border-emerald-100/80 shadow-xs hover:-translate-y-1 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm mb-3 shadow-md shadow-emerald-500/20">
                                03
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Keamanan Digital</h4>
                            <p class="text-slate-600 text-xs leading-relaxed">SOP pencadangan data berkala (*backup*), proteksi firewall/antivirus berlisensi, dan mitigasi insiden siber.</p>
                        </div>

                        <div class="p-5 rounded-2xl bg-gradient-to-br from-indigo-50/60 to-white border border-indigo-100/80 shadow-xs hover:-translate-y-1 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm mb-3 shadow-md shadow-indigo-500/20">
                                04
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Sosial Digital</h4>
                            <p class="text-slate-600 text-xs leading-relaxed">Perlindungan privasi (anti-doxing), pencegahan *cyberbullying*, etika bersosial media, dan literasi anti-hoaks.</p>
                        </div>
                    </div>

                    {{-- Formula Komposit Card --}}
                    <div class="p-6 rounded-2xl bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white mb-8 border border-slate-800 shadow-xl">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div>
                                <span class="text-emerald-400 font-mono text-xs font-bold uppercase tracking-wider">// Formula Pembobotan Resmi HANDAL</span>
                                <h4 class="text-lg md:text-xl font-mono font-bold mt-1">
                                    Skor Akhir = (0.70 &times; Asesmen Mandiri) + (0.30 &times; Survei Persepsi Siswa)
                                </h4>
                            </div>
                            <div class="text-xs text-slate-300 max-w-sm font-light">
                                Menggabungkan validasi dokumen bukti administratif sekolah dengan penilaian riil skala Likert dari siswa secara objektif.
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Tiering --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="bi bi-bar-chart-fill text-blue-600"></i> Klasifikasi Predikat Kematangan Digital (5 Tier)
                        </h4>
                        <div class="rounded-2xl border border-slate-100 overflow-hidden shadow-xs">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-5 py-3.5">Rentang Nilai</th>
                                            <th class="px-5 py-3.5">Klasifikasi Predikat</th>
                                            <th class="px-5 py-3.5">Tindak Lanjut & Rekomendasi Program</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-5 py-3 font-extrabold text-cyan-600 text-sm">86 - 100</td>
                                            <td class="px-5 py-3 font-bold text-slate-900">Sekolah Unggul Digital</td>
                                            <td class="px-5 py-3 text-slate-600">Kematangan tata kelola siber tinggi; berhak menjadi rujukan percontohan nasional (*benchmark*).</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-5 py-3 font-extrabold text-blue-600 text-sm">76 - 85</td>
                                            <td class="px-5 py-3 font-bold text-slate-900">Sekolah Maju</td>
                                            <td class="px-5 py-3 text-slate-600">Infrastruktur dan SDM siap, kebijakan keamanan aktif dan konsisten dijalankan.</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-5 py-3 font-extrabold text-amber-600 text-sm">51 - 75</td>
                                            <td class="px-5 py-3 font-bold text-slate-900">Sekolah Berkembang</td>
                                            <td class="px-5 py-3 text-slate-600">Fondasi digital terbentuk; membutuhkan pendampingan SOP teknis dan literasi siber siswa.</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-5 py-3 font-extrabold text-slate-600 text-sm">31 - 50</td>
                                            <td class="px-5 py-3 font-bold text-slate-900">Sekolah Pemula</td>
                                            <td class="px-5 py-3 text-slate-600">Memerlukan pelatihan terpadu bagi staf pengelola IT serta audit infrastruktur dasar.</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-5 py-3 font-extrabold text-rose-600 text-sm">&lt; 31</td>
                                            <td class="px-5 py-3 font-bold text-slate-900">Sekolah Rintisan</td>
                                            <td class="px-5 py-3 text-slate-600">Prioritas intervensi fasilitas sarana prasarana dan pelatihan keamanan dasar.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- SEKSI DASAR HUKUM & SUMBER (REGULASI & STANDAR GLOBAL)    --}}
            {{-- ========================================================= --}}
            <div id="dasar-hukum" class="max-w-6xl mx-auto mb-28 scroll-mt-24">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden p-6 md:p-10">
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold mb-2">
                            <i class="bi bi-shield-check text-emerald-600"></i> Dasar Hukum & Sumber Regulasi
                        </div>
                        <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Landasan Hukum & Referensi Internasional
                        </h3>
                        <p class="text-slate-500 text-sm mt-1">
                            Penyusunan instrumen evaluasi HANDAL diselaraskan dengan undang-undang Republik Indonesia dan standar global.
                        </p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/70 flex gap-3.5 items-start">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold flex-shrink-0 text-sm">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div>
                                <strong class="text-slate-900 block text-xs">Perpres No. 174 Tahun 2024</strong>
                                <span class="text-slate-500 leading-relaxed block mt-0.5">Tentang Kementerian Komunikasi dan Digital Republik Indonesia sebagai pembina ekosistem siber nasional.</span>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/70 flex gap-3.5 items-start">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold flex-shrink-0 text-sm">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <strong class="text-slate-900 block text-xs">UU No. 27 Tahun 2022 (UU PDP)</strong>
                                <span class="text-slate-500 leading-relaxed block mt-0.5">Kewajiban pelindungan data pribadi siswa, guru, dan tenaga kependidikan pada seluruh sistem elektronik sekolah.</span>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/70 flex gap-3.5 items-start">
                            <div class="w-9 h-9 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center font-bold flex-shrink-0 text-sm">
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div>
                                <strong class="text-slate-900 block text-xs">UNESCO (2018) DLGF Indicator 4.4.2</strong>
                                <span class="text-slate-500 leading-relaxed block mt-0.5">A Global Framework of Reference on Digital Literacy Skills untuk pemetaan kompetensi siber generasi muda.</span>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/70 flex gap-3.5 items-start">
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold flex-shrink-0 text-sm">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div>
                                <strong class="text-slate-900 block text-xs">ITU (2024) Measuring Digital Development</strong>
                                <span class="text-slate-500 leading-relaxed block mt-0.5">Standar pengukuran kesiapan infrastruktur dan aksesibilitas telekomunikasi institusi pendidikan.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-20 mb-20">

                <div class="mt-20 mb-20">
                    <div class="text-center mb-16 px-4">
                        <span
                            class="inline-block py-1 px-3 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-bold tracking-wider uppercase mb-4">
                            Dokumentasi Kegiatan
                        </span>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 mb-6">
                            Jejak Langkah <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">HANDAL</span>
                        </h2>
                        <p class="text-slate-500 max-w-2xl mx-auto text-lg leading-relaxed">
                            Melihat lebih dekat bagaimana kami berkolaborasi dengan sekolah-sekolah di Indonesia untuk
                            menciptakan ruang digital yang aman.
                        </p>
                    </div>
                    <div x-data="{
                        activeSlide: 0,
                        slides: [
                            { img: '{{ asset('img/SAMPUL FINAL XTRA COLOR 2.jpg') }}', title: 'SMK Negeri 1 Makassar', desc: 'Sosialisasi Literasi Digital', date: '12 Jan 2026' },
                            { img: '{{ asset('img/SAMPUL FINAL XTRA COLOR 2.jpg') }}', title: 'SMA Islam Athirah', desc: 'Workshop Keamanan Siber', date: '05 Feb 2026' },
                            { img: '{{ asset('img/SAMPUL FINAL XTRA COLOR 2.jpg') }}', title: 'MAN 2 Model Makassar', desc: 'Monitoring Jaringan', date: '28 Jan 2026' }
                        ],
                        next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                        prev() { this.activeSlide = (this.activeSlide === 0) ? this.slides.length - 1 : this.activeSlide - 1 },
                        timer: null,
                        init() { this.timer = setInterval(() => this.next(), 5000) },
                        stop() { clearInterval(this.timer) },
                        start() { this.timer = setInterval(() => this.next(), 5000) }
                    }" @mouseenter="stop()" @mouseleave="start()"
                        class="relative max-w-6xl mx-auto rounded-[2rem] overflow-hidden shadow-2xl bg-slate-900 group h-[500px]">

                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index"
                                x-transition:enter="transition transform duration-700 ease-out"
                                x-transition:enter-start="opacity-0 scale-105"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition transform duration-500 ease-in"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0"
                                class="absolute inset-0">

                                <img :src="slide.img" class="w-full h-full object-cover opacity-80">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent">
                                </div>

                                <div class="absolute bottom-0 left-0 p-10 pb-20 md:p-14 md:pb-24 max-w-3xl">
                                    <span x-text="slide.date"
                                        class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wider text-blue-300 uppercase bg-blue-900/50 backdrop-blur rounded-full border border-blue-500/30"></span>
                                    <h3 x-text="slide.title"
                                        class="text-3xl md:text-5xl font-bold text-white mb-3 leading-tight drop-shadow-lg">
                                    </h3>
                                    <p x-text="slide.desc"
                                        class="text-slate-300 text-lg md:text-xl font-light drop-shadow-md"></p>
                                </div>
                            </div>
                        </template>

                        <div class="absolute inset-0 flex items-center justify-between px-4 z-20 pointer-events-none">

                            <button @click="prev()"
                                class="pointer-events-auto opacity-0 group-hover:opacity-100 w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white hover:bg-white hover:text-blue-900 hover:scale-110 transition-all duration-300 flex items-center justify-center shadow-lg -ml-2 md:ml-0">
                                <i class="bi bi-chevron-left text-2xl"></i>
                            </button>

                            <button @click="next()"
                                class="pointer-events-auto opacity-0 group-hover:opacity-100 w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white hover:bg-white hover:text-blue-900 hover:scale-110 transition-all duration-300 flex items-center justify-center shadow-lg -mr-2 md:mr-0">
                                <i class="bi bi-chevron-right text-2xl"></i>
                            </button>

                        </div>

                        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex gap-2 z-20">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="activeSlide = index"
                                    class="h-1.5 rounded-full transition-all duration-500 ease-out shadow-sm cursor-pointer hover:bg-white"
                                    :class="activeSlide === index ? 'w-8 bg-blue-500' : 'w-2 bg-white/50'">
                                </button>
                            </template>
                        </div>

                    </div>
                </div>

                <div
                    class="relative max-w-6xl mx-auto px-6 py-20 rounded-[3rem] overflow-hidden shadow-2xl mb-24 text-center text-white group">

                    <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500 z-0"></div>

                    <div class="absolute -top-10 -right-10 opacity-10 animate-[bounce_6s_infinite]">
                        <i class="bi bi-shield-lock-fill text-[10rem]"></i>
                    </div>
                    <div class="absolute -bottom-10 -left-10 opacity-10 animate-[bounce_8s_infinite]">
                        <i class="bi bi-globe-asia-australia text-[10rem]"></i>
                    </div>

                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-1.5 mb-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sm font-semibold shadow-sm">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            Program Skala Nasional
                        </div>

                        <h2 class="text-4xl md:text-5xl font-extrabold mb-6 tracking-tight leading-tight">
                            Siap Menjadi Sekolah <br> <span class="text-blue-100">Aman Digital?</span>
                        </h2>

                        <p class="text-lg md:text-xl text-blue-50 max-w-2xl mx-auto mb-10 leading-relaxed font-light">
                            Bergabunglah dengan jaringan sekolah di seluruh Indonesia. Ikuti asesmen resmi dan dapatkan
                            sertifikasi keamanan digital.
                        </p>

                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            @auth
                                @if (Auth::user()->role === 'school')
                                    <a href="{{ route('school.survey.start') }}"
                                        class="group relative px-8 py-4 bg-white text-blue-700 font-bold rounded-2xl hover:scale-105 transition-all shadow-[0_10px_20px_-10px_rgba(255,255,255,0.5)] flex items-center justify-center gap-3">
                                        Mulai Asesmen
                                        <i
                                            class="bi bi-play-circle-fill text-xl group-hover:text-blue-500 transition-colors"></i>
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}"
                                        class="group relative px-8 py-4 bg-white text-blue-700 font-bold rounded-2xl hover:scale-105 transition-all shadow-[0_10px_20px_-10px_rgba(255,255,255,0.5)] flex items-center justify-center gap-3">
                                        Ke Dashboard
                                        <i class="bi bi-grid-fill text-xl group-hover:text-blue-500 transition-colors"></i>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('registration.page') }}"
                                    class="group relative px-8 py-4 bg-white text-blue-700 font-bold rounded-2xl hover:scale-105 transition-all shadow-[0_10px_20px_-10px_rgba(255,255,255,0.5)] flex items-center justify-center gap-3">
                                    Daftar Sekolah Sekarang
                                    <i
                                        class="bi bi-arrow-right-circle-fill text-xl group-hover:text-blue-500 transition-colors"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT PETA INTERAKTIF LANDING DENGAN BATAS SULSEL RESMI & KOORDINAT GOOGLE MAPS --}}
    <script>
        // Alpine component untuk tabel pemeringkatan (didefinisikan di luar DOMContentLoaded agar Alpine menemukannya)
        window.initRankingTable = function() {
            return {
                search: '',
                filterJenjang: 'ALL',
                sortBy: 'score',
                sortAsc: false,
                schools: @json($rankings ?? []),
                get filteredSchools() {
                    let result = this.schools || [];
                    if (this.filterJenjang !== 'ALL') {
                        result = result.filter(s => (s.jenjang || '').toUpperCase().includes(this.filterJenjang));
                    }
                    if (this.search.trim() !== '') {
                        const q = this.search.toLowerCase();
                        result = result.filter(s =>
                            (s.name || '').toLowerCase().includes(q) ||
                            (s.npsn || '').toLowerCase().includes(q) ||
                            (s.address || '').toLowerCase().includes(q)
                        );
                    }
                    const isAsc = this.sortAsc;
                    const key = this.sortBy;
                    return result.slice().sort((a, b) => {
                        let valA, valB;
                        if (key === 'name') {
                            valA = (a.name || '').toLowerCase();
                            valB = (b.name || '').toLowerCase();
                            return isAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
                        }
                        if (key === 'students') {
                            valA = a.student_respondents_count || 0;
                            valB = b.student_respondents_count || 0;
                        } else {
                            valA = parseFloat(a.current_score) || 0;
                            valB = parseFloat(b.current_score) || 0;
                        }
                        return isAsc ? (valA - valB) : (valB - valA);
                    });
                },
                getTierClass(score) {
                    const s = parseFloat(score);
                    if (s >= 86) return 'bg-cyan-50 text-cyan-700 border-cyan-200';
                    if (s >= 76) return 'bg-blue-50 text-blue-700 border-blue-200';
                    if (s >= 51) return 'bg-amber-50 text-amber-700 border-amber-200';
                    if (s >= 31) return 'bg-slate-100 text-slate-700 border-slate-200';
                    return 'bg-rose-50 text-rose-700 border-rose-200';
                },
                getTierName(score) {
                    const s = parseFloat(score);
                    if (s >= 86) return 'Unggul Digital';
                    if (s >= 76) return 'Maju';
                    if (s >= 51) return 'Berkembang';
                    if (s >= 31) return 'Pemula';
                    return 'Rintisan';
                }
            };
        };

        document.addEventListener('DOMContentLoaded', function () {
            const locations = @json($mapLocations);

            // Koordinat fokus
            const makassarCenter = [-5.1450, 119.4400];
            const sulselCenter = [-4.0500, 120.1000];

            const map = L.map('landing-map-container', {
                center: makassarCenter,
                zoom: 12,
                minZoom: 4,
                maxZoom: 18,
                zoomControl: true
            });

            // Pastikan dimensi peta terhitung sempurna setelah render
            setTimeout(() => {
                map.invalidateSize();
            }, 250);

            // Layer Esri Light Gray Canvas (Bersih, Free & Tanpa API Key)
            // Menggunakan maxNativeZoom: 16 dan maxZoom: 18 agar saat zoom mendalam (17-18)
            // Leaflet tetap mempertahankan dan memperbesar ubin peta, bukan menghilangkannya.
            const esriGray = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &bull; HANDAL Kemkomdigi',
                minZoom: 4,
                maxZoom: 18,
                maxNativeZoom: 16
            }).addTo(map);

            const esriLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Reference/MapServer/tile/{z}/{y}/{x}', {
                attribution: '',
                minZoom: 4,
                maxZoom: 18,
                maxNativeZoom: 16
            }).addTo(map);

            const osmStandard = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors &bull; HANDAL',
                minZoom: 4,
                maxZoom: 19
            });

            const baseMaps = {
                "Kanvas Bersih (Esri)": L.layerGroup([esriGray, esriLabels]),
                "Peta Jalan Detil (OSM)": osmStandard
            };
            L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

            let sulselLayer = null;

            // State & fungsi pembaruan badge wilayah aktif (kiri atas samping tombol zoom)
            let currentBadgeState = {
                name: 'Kota Makassar',
                info: `${locations.length} Sekolah Terdaftar (Pusat Pengujian)`,
                type: 'active'
            };

            function updateRegionBadge(name, info, type = 'active') {
                const dot = document.getElementById('badge-region-dot');
                const nameEl = document.getElementById('badge-region-name');
                const infoEl = document.getElementById('badge-region-info');
                if (!dot || !nameEl || !infoEl) return;

                nameEl.textContent = name;
                infoEl.textContent = info;

                dot.className = 'w-2.5 h-2.5 rounded-full flex-shrink-0 transition-all';
                if (type === 'active') {
                    dot.classList.add('bg-emerald-500', 'animate-pulse');
                } else if (type === 'info') {
                    dot.classList.add('bg-blue-500');
                } else if (type === 'inactive') {
                    dot.classList.add('bg-slate-400');
                } else if (type === 'school') {
                    dot.classList.add('bg-indigo-600', 'animate-pulse');
                }
            }

            function restoreRegionBadge() {
                updateRegionBadge(currentBadgeState.name, currentBadgeState.info, currentBadgeState.type);
            }

            // Fungsi pewarnaan dinamis poligon wilayah:
            // Pada zoom jauh (provinsi), fill terlihat untuk mempertegas batas wilayah.
            // Saat zoom mendekati kota/jalan (zoom >= 13), transparansi fill otomatis diturunkan
            // agar pengguna dapat melihat jaringan jalan dan lokasi sekolah dengan jelas tanpa tertutup warna biru pekat.
            function getSulselStyle(feature, currentZoom) {
                const z = currentZoom !== undefined ? currentZoom : map.getZoom();
                const isMakassar = (feature.properties.name || '').toUpperCase().includes('MAKASSAR');

                let fillOp = 0.10;
                if (z >= 14) {
                    fillOp = 0.02; // nyaris transparan saat zoom dekat ke sekolah/jalan
                } else if (z >= 12) {
                    fillOp = isMakassar ? 0.08 : 0.04;
                } else {
                    fillOp = isMakassar ? 0.22 : 0.10;
                }

                return {
                    fillColor: isMakassar ? '#0284c7' : '#38bdf8',
                    fillOpacity: fillOp,
                    color: '#0284c7',
                    weight: isMakassar ? (z >= 14 ? 1.5 : 2.5) : 1.2,
                    opacity: z >= 14 ? 0.45 : 0.85
                };
            }

            // Perbarui transparansi poligon saat tingkat zoom berubah
            map.on('zoomend', function () {
                if (sulselLayer) {
                    sulselLayer.setStyle(feature => getSulselStyle(feature, map.getZoom()));
                }
            });

            // 1. LOAD GEOJSON RESMI SULAWESI SELATAN (24 KAB/KOTA TANPA SULBAR)
            fetch('{{ asset("data/sulsel_accurate.geojson") }}')
                .then(res => res.json())
                .then(geoData => {
                    sulselLayer = L.geoJSON(geoData, {
                        style: feature => getSulselStyle(feature, map.getZoom()),
                        onEachFeature: function (feature, layer) {
                            const rawName = feature.properties.name || feature.properties.KABKOT || 'Wilayah';
                            const kabName = rawName.replace(/^KABUPATEN\s+/i, 'Kab. ').replace(/^KOTA\s+/i, 'Kota ');
                            const isMakassar = rawName.toUpperCase().includes('MAKASSAR');

                            if (isMakassar) {
                                layer.bindTooltip(`<strong>${kabName} (Sulawesi Selatan)</strong><br><span style="color:#0284c7;font-weight:700;">Wilayah Pusat Pengujian</span> &bull; ${locations.length} Sekolah Terdaftar`, { sticky: true });
                            } else {
                                layer.bindTooltip(`<strong>${kabName}</strong><br>Provinsi Sulawesi Selatan &bull; <span style="color:#64748b;">Belum ada data sekolah</span>`, { sticky: true });
                            }

                            layer.on({
                                mouseover: function () {
                                    if (isMakassar) {
                                        updateRegionBadge(kabName, `${locations.length} Sekolah Terdaftar (Pusat Pengujian)`, 'active');
                                    } else {
                                        updateRegionBadge(kabName, '0 Sekolah Terdaftar (Tahap Perluasan)', 'inactive');
                                    }
                                },
                                mouseout: function () {
                                    restoreRegionBadge();
                                },
                                click: function (e) {
                                    if (isMakassar) {
                                        map.flyTo(makassarCenter, 12, { duration: 1.0 });
                                        setActiveButton(btnMakassar);
                                        currentBadgeState = {
                                            name: 'Kota Makassar',
                                            info: `${locations.length} Sekolah Terdaftar (Pusat Pengujian)`,
                                            type: 'active'
                                        };
                                        restoreRegionBadge();
                                    } else {
                                        map.fitBounds(e.target.getBounds(), { padding: [40, 40], duration: 1.0 });
                                        currentBadgeState = {
                                            name: kabName,
                                            info: '0 Sekolah Terdaftar (Tahap Perluasan)',
                                            type: 'inactive'
                                        };
                                        restoreRegionBadge();
                                    }
                                }
                            });
                        }
                    }).addTo(map);

                    sulselLayer.bringToBack();
                })
                .catch(err => {
                    console.warn('Gagal memuat batas resmi Sulsel:', err);
                });

            // 2. TAMPILKAN PIN SEKOLAH DENGAN KOORDINAT GOOGLE MAPS PRESISI
            locations.forEach((school) => {
                let pinColor = '#3b82f6';
                if (school.score >= 86) pinColor = '#06b6d4';
                else if (school.score >= 76) pinColor = '#2563eb';
                else if (school.score >= 51) pinColor = '#f59e0b';
                else pinColor = '#ef4444';

                const iconHtml = `
                    <div style="position: relative; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                        <span style="position: absolute; width: 30px; height: 30px; border-radius: 50%; background-color: ${pinColor}; opacity: 0.3; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                        <div style="width: 24px; height: 24px; border-radius: 50%; background-color: ${pinColor}; border: 2.5px solid #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.25); display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: 800;">
                            ${school.jenjang.substring(0, 2)}
                        </div>
                    </div>
                `;

                const customIcon = L.divIcon({
                    html: iconHtml,
                    className: 'landing-school-marker',
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                    popupAnchor: [0, -17]
                });

                const marker = L.marker([school.lat, school.lng], { icon: customIcon }).addTo(map);

                const popupContent = `
                    <div style="font-family: inherit; min-width: 230px; padding: 4px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                            <span style="background: #eff6ff; color: #1d4ed8; font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 6px;">
                                ${school.jenjang} &bull; NPSN: ${school.npsn || '-'}
                            </span>
                            <span style="font-size: 11px; font-weight: 800; color: #047857; background: #ecfdf5; padding: 2px 6px; border-radius: 6px;">
                                Skor: ${Number(school.score).toFixed(1)}
                            </span>
                        </div>
                        <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; line-height: 1.25;">${school.name}</h4>
                        
                        <div style="font-size: 11px; color: #64748b; margin-bottom: 8px; line-height: 1.35; display: flex; gap: 4px;">
                            <i class="bi bi-geo-alt-fill" style="color: #ef4444; margin-top: 1px;"></i>
                            <span>${school.address || school.city}</span>
                        </div>

                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 8px; margin-bottom: 8px; font-size: 11px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                <span style="color: #64748b;">Asesmen Mandiri (70%):</span>
                                <strong style="color: #0f172a;">${Number(school.self_score).toFixed(1)}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b;">Survei Siswa (30%):</span>
                                <strong style="color: #4338ca;">${Number(school.student_score).toFixed(1)} (${school.respondents} Siswa)</strong>
                            </div>
                        </div>

                        <a href="{{ route('ranking.page') }}" 
                            style="display: block; text-align: center; background: #2563eb; color: white; text-decoration: none; padding: 6px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">
                            Buka di Leaderboard &rarr;
                        </a>
                    </div>
                `;

                marker.bindPopup(popupContent);
                marker.bindTooltip(`<strong>${school.name}</strong><br><span style="font-size:10px;color:#64748b;">${school.address}</span>`, {
                    direction: 'top',
                    offset: [0, -14]
                });

                marker.on('click', function () {
                    currentBadgeState = {
                        name: school.name,
                        info: `Skor ${Number(school.score).toFixed(1)} (${school.jenjang}) &bull; ${school.address || school.city}`,
                        type: 'school'
                    };
                    restoreRegionBadge();
                });
            });

            // Tombol Interaksi Peta
            const btnMakassar = document.getElementById('btn-focus-makassar-landing');
            const btnSulsel = document.getElementById('btn-focus-sulsel-landing');
            const btnIndo = document.getElementById('btn-focus-indo-landing');

            function setActiveButton(btn) {
                [btnMakassar, btnSulsel, btnIndo].forEach(b => {
                    b.classList.remove('bg-white', 'text-slate-900', 'shadow-xs', 'font-bold');
                    b.classList.add('text-slate-600');
                });
                btn.classList.add('bg-white', 'text-slate-900', 'shadow-xs', 'font-bold');
                btn.classList.remove('text-slate-600');
            }

            btnMakassar.addEventListener('click', function () {
                setActiveButton(this);
                map.flyTo(makassarCenter, 12, { duration: 1.2 });
                currentBadgeState = {
                    name: 'Kota Makassar',
                    info: `${locations.length} Sekolah Terdaftar (Pusat Pengujian)`,
                    type: 'active'
                };
                restoreRegionBadge();
            });

            btnSulsel.addEventListener('click', function () {
                setActiveButton(this);
                if (sulselLayer) {
                    map.fitBounds(sulselLayer.getBounds(), { padding: [30, 30], duration: 1.2 });
                } else {
                    map.flyTo(sulselCenter, 7, { duration: 1.2 });
                }
                currentBadgeState = {
                    name: 'Provinsi Sulawesi Selatan',
                    info: `24 Kab/Kota &bull; ${locations.length} Sekolah Terdaftar`,
                    type: 'info'
                };
                restoreRegionBadge();
            });

            btnIndo.addEventListener('click', function () {
                setActiveButton(this);
                map.flyTo([-2.5489, 118.0149], 5, { duration: 1.4 });
                currentBadgeState = {
                    name: 'Skala Nasional (Indonesia)',
                    info: 'Fase 1: Uji Coba Sulawesi Selatan',
                    type: 'info'
                };
                restoreRegionBadge();
            });

            // =========================================================
            // FLOATING QUICK NAVIGATION DOCK (FADE IN & SCROLLSPY)
            // =========================================================
            const navDock = document.getElementById('floating-quick-nav');
            const triggerOffset = 380; // Muncul perlahan setelah melewati Hero Banner

            function handleDockVisibility() {
                if (!navDock) return;
                if (window.scrollY > triggerOffset) {
                    navDock.classList.remove('opacity-0', 'pointer-events-none', '-translate-x-6');
                    navDock.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                } else {
                    navDock.classList.add('opacity-0', 'pointer-events-none', '-translate-x-6');
                    navDock.classList.remove('opacity-100', 'pointer-events-auto', 'translate-x-0');
                }
            }

            window.addEventListener('scroll', handleDockVisibility, { passive: true });
            handleDockVisibility();

            // Scrollspy aktif menandai menu saat section berada dalam viewport
            const sectionIds = ['peta-sebaran', 'tabel-pemeringkatan', 'definisi-metodologi', 'dasar-hukum'];
            const navItems = document.querySelectorAll('.quick-nav-item');

            const observerOptions = {
                root: null,
                rootMargin: '-20% 0px -55% 0px',
                threshold: 0
            };

            const sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const activeId = entry.target.id;
                        navItems.forEach(item => {
                            const target = item.getAttribute('data-nav-target');
                            const iconBox = item.querySelector('span:first-child');

                            if (target === activeId) {
                                item.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/20');
                                item.classList.remove('text-slate-600', 'hover:bg-blue-50/70', 'hover:text-blue-600');
                                if (iconBox) {
                                    iconBox.className = 'w-6 h-6 rounded-lg bg-white/20 text-white flex items-center justify-center text-xs flex-shrink-0 transition-colors';
                                }
                            } else {
                                item.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/20');
                                item.classList.add('text-slate-600', 'hover:bg-blue-50/70', 'hover:text-blue-600');
                                if (iconBox) {
                                    if (target === 'peta-sebaran') iconBox.className = 'w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs flex-shrink-0 transition-colors';
                                    else if (target === 'tabel-pemeringkatan') iconBox.className = 'w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs flex-shrink-0 transition-colors';
                                    else if (target === 'definisi-metodologi') iconBox.className = 'w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs flex-shrink-0 transition-colors';
                                    else if (target === 'dasar-hukum') iconBox.className = 'w-6 h-6 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs flex-shrink-0 transition-colors';
                                }
                            }
                        });
                    }
                });
            }, observerOptions);

            sectionIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) sectionObserver.observe(el);
            });
        });
    </script>
@endsection
