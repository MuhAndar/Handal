@extends('layouts.app')

@section('title', 'Pemenang Kompetisi Speed Coding Scratch - HANDAL Sekolah Aman Digital')

@section('content')
<div class="relative w-full overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 min-h-screen py-12 lg:py-20"
     x-data="initSpeedCoding()">

    {{-- DEKORASI LATAR BELAKANG CYBER / NEON GLOW --}}
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-blue-600/20 rounded-full blur-[140px] pointer-events-none -z-10"></div>
    <div class="absolute top-1/3 -left-32 w-[500px] h-[500px] bg-cyan-500/15 rounded-full blur-[140px] pointer-events-none -z-10"></div>
    <div class="absolute top-2/3 -right-32 w-[500px] h-[500px] bg-amber-500/15 rounded-full blur-[140px] pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- ========================================================= --}}
        {{-- HEADER KOMPETISI & TOMBOL GANTI TAHUN                     --}}
        {{-- ========================================================= --}}
        <div class="text-center max-w-4xl mx-auto mb-14">
            {{-- EMBLEM RESMI BBLSDM KOMDIGI --}}
            <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-2xl bg-white shadow-xl shadow-blue-500/10 mb-6 border border-slate-100 transition-transform duration-300 hover:scale-105">
                <img src="{{ asset('img/logo_bblsdm_komdigi.png') }}" alt="BBLSDM Komdigi Makassar" class="h-8 sm:h-10 w-auto object-contain">
            </div>

            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-300 text-xs font-bold uppercase tracking-wider">
                    <i class="bi bi-code-slash"></i> Aplikasi Scratch 3.0 &bull; SD (Kelas 5-6) &amp; SMP (Kelas 1-3)
                </span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-4">
                Papan Peringkat <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500">
                    Kompetisi Speed Coding
                </span>
            </h1>

            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed mb-8">
                Ajang kompetisi tahunan untuk menguji kecepatan logika, efisiensi algoritma pemecahan masalah, dan mitigasi keamanan kode menggunakan aplikasi <strong>Scratch</strong> bagi pelajar tingkat <strong>SD (Kelas 5 - 6)</strong> dan <strong>SMP (Kelas 1 - 3)</strong>.
            </p>

            {{-- TOMBOL GANTI TAHUN (SWITCHER 2026 / 2025) --}}
            <div class="inline-flex items-center gap-2 p-1.5 rounded-2xl bg-slate-900/90 border border-slate-700 shadow-xl backdrop-blur-md">
                <button type="button"
                    @click="selectedYear = '2026'"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all duration-300 flex items-center gap-2"
                    :class="selectedYear === '2026' ? 'bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 font-extrabold shadow-lg shadow-amber-500/30 scale-[1.02]' : 'text-slate-300 hover:text-white hover:bg-slate-800/80'">
                    <i class="bi bi-trophy-fill text-slate-950" x-show="selectedYear === '2026'"></i>
                    <span>Edisi 2026 (Tahun Ini)</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase"
                          :class="selectedYear === '2026' ? 'bg-slate-950 text-amber-300' : 'bg-slate-800 text-slate-400'">
                        Terbaru
                    </span>
                </button>

                <button type="button"
                    @click="selectedYear = '2025'"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all duration-300 flex items-center gap-2"
                    :class="selectedYear === '2025' ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-extrabold shadow-lg shadow-blue-500/30 scale-[1.02]' : 'text-slate-300 hover:text-white hover:bg-slate-800/80'">
                    <i class="bi bi-clock-history text-white" x-show="selectedYear === '2025'"></i>
                    <span>Edisi 2025 (Tahun Lalu)</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase"
                          :class="selectedYear === '2025' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400'">
                        Arsip
                    </span>
                </button>
            </div>

            {{-- Info Tanggal & Lokasi --}}
            <div class="mt-4 flex items-center justify-center gap-4 text-xs text-slate-400 flex-wrap">
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-calendar3 text-blue-400"></i>
                    <span x-text="activeCompetition.date"></span>
                </span>
                <span>&bull;</span>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-geo-alt-fill text-rose-400"></i>
                    <span x-text="activeCompetition.location"></span>
                </span>
                <span>&bull;</span>
                <span class="flex items-center gap-1.5 text-amber-400 font-semibold">
                    <i class="bi bi-cpu"></i>
                    Platform: Scratch 3.0
                </span>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- VIEW & ANIMASI KHUSUS JUARA 1, 2, 3 (PODIUM JUARA)        --}}
        {{-- ========================================================= --}}
        <div class="mb-20">
            <div class="text-center mb-8">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/10 border border-amber-400/30 text-amber-300 text-xs font-bold uppercase tracking-wider mb-2">
                    <i class="bi bi-award-fill"></i> Podium Pemenang Utama
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                    Tiga Terbaik Kecepatan & Akurasi Scratch
                </h2>
            </div>

            {{-- GRID PODIUM (JUARA 2 - JUARA 1 - JUARA 3) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-end max-w-5xl mx-auto pt-6">

                {{-- JUARA 2 (PERAK / SILVER) --}}
                <div class="order-2 md:order-1 relative rounded-3xl p-6 sm:p-7 bg-gradient-to-b from-slate-800/90 to-slate-900/90 border border-slate-600/60 shadow-xl shadow-slate-900/50 backdrop-blur-md transform transition-all duration-500 hover:-translate-y-2 group">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 rounded-2xl bg-gradient-to-tr from-slate-400 to-slate-200 text-slate-900 font-black text-xl flex items-center justify-center shadow-lg border-2 border-white/50">
                        2
                    </div>

                    <div class="text-center pt-5">
                        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-slate-700/80 text-slate-200 border border-slate-500/50 mb-3">
                            🥈 Juara 2 (Perak)
                        </span>

                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-tr from-slate-500 to-slate-300 text-slate-950 font-black text-xl flex items-center justify-center mb-3 shadow-md"
                             x-text="podium.second.avatar"></div>

                        <h3 class="text-lg font-bold text-white mb-1 group-hover:text-cyan-300 transition-colors"
                            x-text="podium.second.name"></h3>

                        <p class="text-xs font-medium text-slate-300"
                           x-text="podium.second.school"></p>
                        <p class="text-[11px] font-bold text-amber-300 mb-4"
                           x-text="podium.second.kelas"></p>

                        <div class="p-3.5 rounded-2xl bg-slate-950/70 border border-slate-800 space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Waktu Tempuh:</span>
                                <strong class="text-emerald-400 font-mono" x-text="podium.second.time + ' Menit'"></strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Skor Akurasi:</span>
                                <strong class="text-white font-mono" x-text="podium.second.score + ' / 100'"></strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Aplikasi:</span>
                                <span class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-300 text-[10px] font-mono" x-text="podium.second.language"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-800 text-[11px] text-cyan-300 font-medium text-center"
                                 x-text="podium.second.challenges"></div>
                        </div>
                    </div>
                </div>

                {{-- JUARA 1 (EMAS / GOLD) - PALING TINGGI & MEMILIKI ANIMASI KHUSUS --}}
                <div class="order-1 md:order-2 relative rounded-3xl p-7 sm:p-9 bg-gradient-to-b from-amber-950/40 via-slate-900 to-slate-900 border-2 border-amber-400/80 shadow-2xl shadow-amber-500/20 backdrop-blur-md transform transition-all duration-500 hover:-translate-y-3 group md:-translate-y-4">
                    {{-- Mahkota Animasi --}}
                    <div class="absolute -top-11 left-1/2 -translate-x-1/2 flex flex-col items-center">
                        <span class="text-3xl animate-bounce">👑</span>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 via-yellow-300 to-amber-600 text-slate-950 font-black text-2xl flex items-center justify-center shadow-xl shadow-amber-500/50 border-2 border-white">
                            1
                        </div>
                    </div>

                    {{-- Halo Efek Emas Berdenyut --}}
                    <div class="absolute inset-0 rounded-3xl bg-amber-400/5 pointer-events-none animate-pulse"></div>

                    <div class="text-center pt-8">
                        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 shadow-md shadow-amber-500/30 mb-3">
                            🥇 Juara 1 • Gold Medal
                        </span>

                        <div class="relative w-20 h-20 mx-auto rounded-3xl bg-gradient-to-tr from-amber-400 to-yellow-200 text-slate-950 font-black text-2xl flex items-center justify-center mb-3 shadow-lg shadow-amber-400/30 ring-4 ring-amber-400/20">
                            <span x-text="podium.first.avatar"></span>
                            <span class="absolute -bottom-1 -right-1 text-base">🏆</span>
                        </div>

                        <h3 class="text-xl font-extrabold text-white mb-1 group-hover:text-amber-300 transition-colors"
                            x-text="podium.first.name"></h3>

                        <p class="text-xs font-semibold text-slate-200"
                           x-text="podium.first.school"></p>
                        <p class="text-xs font-bold text-amber-300 mb-5"
                           x-text="podium.first.kelas"></p>

                        <div class="p-4 rounded-2xl bg-slate-950/80 border border-amber-500/30 space-y-2.5 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Rekor Tercepat:</span>
                                <strong class="text-amber-400 font-mono text-sm" x-text="podium.first.time + ' Menit'"></strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Skor Akurasi:</span>
                                <strong class="text-emerald-400 font-mono text-sm" x-text="podium.first.score + ' (Sempurna)'"></strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Aplikasi:</span>
                                <span class="px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 text-[10px] font-mono font-bold" x-text="podium.first.language"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-800 text-xs text-amber-300 font-bold text-center flex items-center justify-center gap-1">
                                <i class="bi bi-patch-check-fill text-amber-400"></i>
                                <span x-text="podium.first.challenges"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- JUARA 3 (PERUNGGU / BRONZE) --}}
                <div class="order-3 relative rounded-3xl p-6 sm:p-7 bg-gradient-to-b from-slate-800/90 to-slate-900/90 border border-slate-600/60 shadow-xl shadow-slate-900/50 backdrop-blur-md transform transition-all duration-500 hover:-translate-y-2 group">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-700 to-amber-900 text-amber-100 font-black text-xl flex items-center justify-center shadow-lg border-2 border-amber-500/40">
                        3
                    </div>

                    <div class="text-center pt-5">
                        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-amber-900/60 text-amber-300 border border-amber-700/60 mb-3">
                            🥉 Juara 3 (Perunggu)
                        </span>

                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-tr from-amber-800 to-amber-950 text-amber-200 font-black text-xl flex items-center justify-center mb-3 shadow-md border border-amber-700/40"
                             x-text="podium.third.avatar"></div>

                        <h3 class="text-lg font-bold text-white mb-1 group-hover:text-amber-400 transition-colors"
                            x-text="podium.third.name"></h3>

                        <p class="text-xs font-medium text-slate-300"
                           x-text="podium.third.school"></p>
                        <p class="text-[11px] font-bold text-amber-300 mb-4"
                           x-text="podium.third.kelas"></p>

                        <div class="p-3.5 rounded-2xl bg-slate-950/70 border border-slate-800 space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Waktu Tempuh:</span>
                                <strong class="text-emerald-400 font-mono" x-text="podium.third.time + ' Menit'"></strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Skor Akurasi:</span>
                                <strong class="text-white font-mono" x-text="podium.third.score + ' / 100'"></strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Aplikasi:</span>
                                <span class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-300 text-[10px] font-mono" x-text="podium.third.language"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-800 text-[11px] text-amber-400/80 font-medium text-center"
                                 x-text="podium.third.challenges"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- STATISTIK CEPAT KOMPETISI                                 --}}
        {{-- ========================================================= --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto mb-16">
            <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
                <span class="block text-2xl lg:text-3xl font-black text-amber-400 mb-1" x-text="activeCompetition.total_participants"></span>
                <span class="text-xs text-slate-400 font-semibold">Total Peserta Lomba</span>
            </div>
            <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
                <span class="block text-2xl lg:text-3xl font-black text-cyan-400 mb-1" x-text="activeCompetition.finalists_count"></span>
                <span class="text-xs text-slate-400 font-semibold">Top 10 Finalis</span>
            </div>
            <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
                <span class="block text-2xl lg:text-3xl font-black text-emerald-400 mb-1" x-text="podium.first.time + 'm'"></span>
                <span class="text-xs text-slate-400 font-semibold">Rekor Tercepat</span>
            </div>
            <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
                <span class="block text-2xl lg:text-3xl font-black text-indigo-400 mb-1">Scratch 3.0</span>
                <span class="text-xs text-slate-400 font-semibold">Aplikasi Standar</span>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- TABEL 30 PESERTA (SEARCH & FILTER REAL-TIME)              --}}
        {{-- ========================================================= --}}
        <div class="max-w-6xl mx-auto bg-slate-900/80 rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-2xl backdrop-blur-md">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-white">
                        Daftar Peringkat Lengkap (30 Peserta)
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">
                        Menampilkan hasil urutan waktu tercepat dan ketepatan blok algoritma Scratch <span class="font-bold text-amber-400" x-text="'Tahun ' + selectedYear"></span>.
                    </p>
                </div>

                {{-- Search Box --}}
                <div class="relative w-full md:w-72">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" x-model="search" placeholder="Cari nama siswa / sekolah..."
                        class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-700 bg-slate-950 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 shadow-inner">
                </div>
            </div>

            {{-- Filter Tabs (Kategori & Jenjang SD/SMP) --}}
            <div class="flex flex-wrap items-center gap-2 mb-6 text-xs font-semibold">
                <span class="text-slate-400 mr-1 text-[11px] uppercase tracking-wider font-bold">Kategori:</span>
                
                <button type="button" @click="filterCategory = 'ALL'"
                    class="px-3 py-1.5 rounded-xl transition-all"
                    :class="filterCategory === 'ALL' ? 'bg-amber-400 text-slate-950 font-black shadow-md' : 'bg-slate-800 text-slate-300 hover:text-white'">
                    Semua (30 Peserta)
                </button>
                <button type="button" @click="filterCategory = 'TOP10'"
                    class="px-3 py-1.5 rounded-xl transition-all"
                    :class="filterCategory === 'TOP10' ? 'bg-blue-600 text-white font-black shadow-md' : 'bg-slate-800 text-slate-300 hover:text-white'">
                    🎖️ Top 10 Finalis
                </button>
                <button type="button" @click="filterCategory = 'PODIUM'"
                    class="px-3 py-1.5 rounded-xl transition-all"
                    :class="filterCategory === 'PODIUM' ? 'bg-amber-500 text-slate-950 font-black shadow-md' : 'bg-slate-800 text-slate-300 hover:text-white'">
                    🏆 Podium Juara (1, 2, 3)
                </button>

                <div class="h-4 w-px bg-slate-700 mx-2 hidden sm:block"></div>
                <span class="text-slate-400 mr-1 text-[11px] uppercase tracking-wider font-bold">Jenjang:</span>

                <button type="button" @click="filterJenjang = 'ALL'"
                    class="px-2.5 py-1 rounded-lg transition-all"
                    :class="filterJenjang === 'ALL' ? 'bg-slate-700 text-white font-bold' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800'">
                    Semua Jenjang
                </button>
                <button type="button" @click="filterJenjang = 'SD'"
                    class="px-2.5 py-1 rounded-lg transition-all"
                    :class="filterJenjang === 'SD' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800'">
                    SD (Kelas 5 - 6)
                </button>
                <button type="button" @click="filterJenjang = 'SMP'"
                    class="px-2.5 py-1 rounded-lg transition-all"
                    :class="filterJenjang === 'SMP' ? 'bg-cyan-600 text-white font-bold' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800'">
                    SMP (Kelas 1 - 3)
                </button>
            </div>

            {{-- Table --}}
            <div class="rounded-2xl border border-slate-800 overflow-hidden bg-slate-950/60">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-900 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="px-5 py-3.5 text-center w-16">Rank</th>
                                <th class="px-5 py-3.5">Nama Peserta</th>
                                <th class="px-5 py-3.5">Asal Sekolah</th>
                                <th class="px-5 py-3.5 text-center">Kelas &amp; Jenjang</th>
                                <th class="px-5 py-3.5 text-center">Aplikasi</th>
                                <th class="px-5 py-3.5 text-center">Waktu Tempuh</th>
                                <th class="px-5 py-3.5 text-center">Skor Akurasi</th>
                                <th class="px-5 py-3.5 text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800/80">
                            <template x-for="p in filteredParticipants" :key="selectedYear + '-' + p.rank">
                                <tr class="hover:bg-slate-800/50 transition-colors"
                                    :class="{
                                        'bg-amber-500/10 font-semibold': p.rank === 1,
                                        'bg-slate-700/20': p.rank === 2,
                                        'bg-amber-900/15': p.rank === 3,
                                        'bg-blue-950/20': p.rank > 3 && p.rank <= 10
                                    }">
                                    
                                    {{-- Rank Column with Badges --}}
                                    <td class="px-5 py-3.5 text-center font-bold">
                                        <template x-if="p.rank === 1">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-slate-950 font-black shadow-md shadow-amber-400/30">🥇</span>
                                        </template>
                                        <template x-if="p.rank === 2">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-300 text-slate-950 font-black shadow-md">🥈</span>
                                        </template>
                                        <template x-if="p.rank === 3">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white font-black shadow-md">🥉</span>
                                        </template>
                                        <template x-if="p.rank > 3 && p.rank <= 10">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-blue-900/60 text-cyan-300 font-bold border border-blue-500/30" x-text="p.rank"></span>
                                        </template>
                                        <template x-if="p.rank > 10">
                                            <span class="text-slate-500 font-semibold" x-text="p.rank"></span>
                                        </template>
                                    </td>

                                    {{-- Name --}}
                                    <td class="px-5 py-3.5">
                                        <span class="font-bold text-white block" x-text="p.name"></span>
                                    </td>

                                    {{-- School --}}
                                    <td class="px-5 py-3.5">
                                        <span class="text-slate-300" x-text="p.school"></span>
                                    </td>

                                    {{-- Kelas & Jenjang --}}
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold"
                                                  :class="p.jenjang === 'SD' ? 'bg-emerald-900/80 text-emerald-200 border border-emerald-700/50' : 'bg-cyan-900/80 text-cyan-200 border border-cyan-700/50'"
                                                  x-text="p.jenjang"></span>
                                            <span class="text-slate-400 text-[11px]" x-text="p.kelas"></span>
                                        </div>
                                    </td>

                                    {{-- Language / Scratch --}}
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-2 py-0.5 rounded bg-slate-800 text-amber-300 font-mono text-[11px] font-semibold" x-text="p.language"></span>
                                    </td>

                                    {{-- Execution Time --}}
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="font-mono text-emerald-400 font-bold" x-text="p.time + ' Menit'"></span>
                                    </td>

                                    {{-- Score --}}
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="font-extrabold text-white" x-text="Number(p.score).toFixed(1)"></span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                                              :class="{
                                                'bg-amber-400 text-slate-950 font-black': p.rank === 1,
                                                'bg-slate-300 text-slate-950 font-bold': p.rank === 2,
                                                'bg-amber-700 text-white font-bold': p.rank === 3,
                                                'bg-blue-900/60 text-cyan-300 border border-blue-500/40': p.rank > 3 && p.rank <= 10,
                                                'bg-slate-800 text-slate-400': p.rank > 10
                                              }"
                                              x-text="p.status"></span>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="filteredParticipants.length === 0">
                                <td colspan="8" class="px-5 py-12 text-center text-slate-500 text-xs">
                                    Tidak ada data peserta yang cocok dengan kriteria pencarian Anda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-400 gap-3 border-t border-slate-800 pt-4">
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-shield-check text-blue-400"></i>
                    Sertifikat resmi e-verifikasi dapat diunduh oleh 30 peserta melalui portal resmi BBLSDM Komdigi Makassar.
                </span>
                <a href="{{ route('landing') }}" class="font-bold text-amber-400 hover:text-amber-300 hover:underline flex items-center gap-1">
                    &larr; Kembali ke Beranda HANDAL
                </a>
            </div>

        </div>

    </div>
</div>

{{-- SCRIPT ALPINE.JS DILUAR INLINE ATTRIBUTE AGAR TIDAK BOCOR KE HTML --}}
<script>
    window.initSpeedCoding = function() {
        return {
            selectedYear: '2026',
            competitions: @json($competitions),
            search: '',
            filterCategory: 'ALL',
            filterJenjang: 'ALL',

            get activeCompetition() {
                return this.competitions[this.selectedYear] || this.competitions['2026'];
            },

            get podium() {
                return this.activeCompetition.podium;
            },

            get filteredParticipants() {
                let list = this.activeCompetition.participants || [];

                if (this.filterCategory === 'TOP10') {
                    list = list.filter(function(p) { return p.rank <= 10; });
                } else if (this.filterCategory === 'PODIUM') {
                    list = list.filter(function(p) { return p.rank <= 3; });
                }

                if (this.filterJenjang !== 'ALL') {
                    const fj = this.filterJenjang;
                    list = list.filter(function(p) {
                        return (p.jenjang || '').toUpperCase().includes(fj);
                    });
                }

                if (this.search.trim() !== '') {
                    const q = this.search.toLowerCase();
                    list = list.filter(function(p) {
                        return (p.name || '').toLowerCase().includes(q) ||
                               (p.school || '').toLowerCase().includes(q) ||
                               (p.kelas || '').toLowerCase().includes(q) ||
                               (p.language || '').toLowerCase().includes(q);
                    });
                }

                return list;
            }
        };
    };
</script>
@endsection
