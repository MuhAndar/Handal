@extends('layouts.app')

@section('title', 'Pemenang Kompetisi Speed Coding Scratch - HANDAL Sekolah Aman Digital')

@section('content')
<style>
    /* Cegah getaran / scrolling liar saat berinteraksi dengan tabel atau input pencarian */
    html {
        scroll-behavior: auto !important;
    }
</style>

<div class="relative w-full overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 min-h-screen py-12 lg:py-20"
     x-data="initSpeedCoding()"
     x-init="initTable()">

    {{-- DEKORASI LATAR BELAKANG CYBER / NEON GLOW --}}
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-blue-600/20 rounded-full blur-[140px] pointer-events-none -z-10"></div>
    <div class="absolute top-1/3 -left-32 w-[500px] h-[500px] bg-cyan-500/15 rounded-full blur-[140px] pointer-events-none -z-10"></div>
    <div class="absolute top-2/3 -right-32 w-[500px] h-[500px] bg-amber-500/15 rounded-full blur-[140px] pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- ========================================================= --}}
        {{-- HEADER KOMPETISI & TOMBOL GANTI BATCH                     --}}
        {{-- ========================================================= --}}
        <div class="text-center max-w-4xl mx-auto mb-16">
            {{-- EMBLEM RESMI BBLSDM KOMDIGI --}}
            <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-2xl bg-white shadow-xl shadow-blue-500/10 mb-6 border border-slate-100 transition-transform duration-300 hover:scale-105">
                <img src="{{ asset('img/logo_bblsdm_komdigi.png') }}" alt="BBLSDM Komdigi Makassar" class="h-8 sm:h-10 w-auto object-contain">
            </div>

            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-300 text-xs font-bold uppercase tracking-wider">
                    <i class="bi bi-code-slash"></i> Aplikasi Scratch 3.0 &bull; Kategori Terbuka (SD &amp; SMP Digabung)
                </span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-4">
                Papan Peringkat <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500">
                    Kompetisi Speed Coding
                </span>
            </h1>

            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed mb-8">
                Ajang kompetisi adu kecepatan logika dan algoritma menggunakan aplikasi <strong>Scratch 3.0</strong>. Perlombaan jenjang <strong>SD (Kelas 5 - 6)</strong> dan <strong>SMP (Kelas 1 - 3)</strong> digabung dalam satu kategori kompetisi dengan dua tahap pengerjaan tantangan: <strong>Babak Kualifikasi</strong> dan <strong>Babak Final</strong>.
            </p>

            {{-- TOMBOL GANTI BATCH (BATCH 1 / 2025 DI KIRI, BATCH 2 / 2026 DI KANAN) --}}
            <div class="inline-flex items-center gap-2 p-1.5 rounded-2xl bg-slate-900/90 border border-slate-700 shadow-xl backdrop-blur-md">
                
                {{-- BATCH 1 (EDISI 2025) DI SEBELAH KIRI --}}
                <button type="button"
                    @click.prevent="switchYear('2025')"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2"
                    :class="selectedYear === '2025' ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-extrabold shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80'">
                    <i class="bi bi-trophy-fill text-amber-300" x-show="selectedYear === '2025'"></i>
                    <span>Batch 1 (Edisi 2025)</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase"
                          :class="selectedYear === '2025' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400'">
                        Tahun Lalu
                    </span>
                </button>

                {{-- BATCH 2 (EDISI 2026) DI SEBELAH KANAN --}}
                <button type="button"
                    @click.prevent="switchYear('2026')"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2"
                    :class="selectedYear === '2026' ? 'bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 font-extrabold shadow-lg shadow-amber-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80'">
                    <i class="bi bi-hourglass-split text-slate-950" x-show="selectedYear === '2026'"></i>
                    <span>Batch 2 (Edisi 2026)</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase"
                          :class="selectedYear === '2026' ? 'bg-slate-950 text-amber-300' : 'bg-slate-800 text-slate-400'">
                        <span x-text="activeCompetition.is_published ? 'Terbaru' : 'Coming Soon'"></span>
                    </span>
                </button>

            </div>

            {{-- Info Tanggal & Lokasi --}}
            <div class="mt-4 flex items-center justify-center gap-4 text-xs text-slate-400 flex-wrap">
                <span class="flex items-center gap-1.5 font-semibold text-slate-200">
                    <i class="bi bi-calendar3 text-blue-400"></i>
                    <span x-text="activeCompetition.date"></span>
                </span>
                <span>&bull;</span>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-geo-alt-fill text-rose-400"></i>
                    <span>BBLSDM Komdigi Makassar</span>
                </span>
                <span>&bull;</span>
                <span class="flex items-center gap-1.5 text-amber-400 font-semibold">
                    <i class="bi bi-cpu"></i>
                    Platform: Scratch 3.0
                </span>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- BANNER INFORMASI & KONTROL MODE PRATINJAU ADMIN           --}}
        {{-- ========================================================= --}}
        @if ($isAdmin)
            <div x-show="!activeCompetition.is_published" class="max-w-5xl mx-auto mb-8 p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-amber-500/15 via-slate-900 to-amber-500/10 border-2 border-amber-400/40 shadow-xl backdrop-blur-md">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-amber-400/20 border border-amber-400/40 flex items-center justify-center text-amber-300 text-lg shrink-0 mt-0.5">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase bg-amber-400 text-slate-950">
                                    Mode Pratinjau Administrator
                                </span>
                                <span class="text-xs text-amber-300 font-semibold" x-text="'Edisi ' + selectedYear + ' • Berstatus Draf (Coming Soon)'"></span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                Halaman edisi ini belum dipublikasikan ke publik. Pengunjung biasa melihat halaman ini sebagai <strong>Coming Soon</strong>. Anda sedang mengakses pratinjau data &amp; susunan tabel sebagai <strong>Administrator</strong>.
                            </p>
                        </div>
                    </div>

                    {{-- Kontrol Sakelar Tampilan & Shortcut Panel Admin --}}
                    <div class="flex items-center gap-2 self-start md:self-center shrink-0 flex-wrap">
                        {{-- Toggle Mode: Pratinjau Tabel vs Tampilan Publik --}}
                        <div class="inline-flex p-1 rounded-xl bg-slate-950/80 border border-slate-700/80 text-xs">
                            <button type="button" @click="adminViewMode = 'preview'"
                                class="px-3 py-1.5 rounded-lg font-bold transition-all flex items-center gap-1.5"
                                :class="adminViewMode === 'preview' ? 'bg-amber-400 text-slate-950 shadow-sm' : 'text-slate-400 hover:text-white'">
                                <i class="bi bi-table"></i>
                                <span>Pratinjau Tabel</span>
                            </button>
                            <button type="button" @click="adminViewMode = 'public'"
                                class="px-3 py-1.5 rounded-lg font-bold transition-all flex items-center gap-1.5"
                                :class="adminViewMode === 'public' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-400 hover:text-white'">
                                <i class="bi bi-eye"></i>
                                <span>Simulasi Publik</span>
                            </button>
                        </div>

                        {{-- Tombol Langsung ke Panel Admin --}}
                        <a :href="'{{ route('admin.speed_coding.index') }}?year=' + selectedYear"
                           class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-300 text-xs font-semibold border border-slate-700 transition-all flex items-center gap-1.5 shadow-sm">
                            <i class="bi bi-gear-fill"></i>
                            <span>Panel Admin</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================================= --}}
        {{-- BLOK COMING SOON JIKA EDISI BELUM DIPUBLIKASIKAN          --}}
        {{-- ========================================================= --}}
        <template x-if="!activeCompetition.is_published && (!{{ $isAdmin ? 'true' : 'false' }} || adminViewMode === 'public')">
            <div class="max-w-3xl mx-auto my-12 p-8 sm:p-14 text-center rounded-3xl bg-slate-900/90 border border-slate-800 shadow-2xl backdrop-blur-md">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-tr from-amber-500/20 to-blue-500/20 border border-amber-400/30 flex items-center justify-center text-3xl mb-6 shadow-inner">
                    ⏳
                </div>

                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold text-amber-300 bg-amber-400/10 border border-amber-400/30 uppercase tracking-widest mb-3">
                    Coming Soon
                </span>

                <h2 class="text-2xl sm:text-3xl font-black text-white mb-3">
                    Edisi <span x-text="selectedYear"></span> Sedang Dipersiapkan
                </h2>

                <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl mx-auto mb-8">
                    Data dan papan peringkat resmi Kompetisi Speed Coding Scratch <span class="font-bold text-amber-400" x-text="'Tahun ' + selectedYear"></span> saat ini belum dipublikasikan. Panitia dan dewan juri BBLSDM Komdigi Makassar sedang mempersiapkan pelaksanaan kompetisi.
                </p>

                <div class="inline-flex flex-col sm:flex-row items-center gap-3">
                    <button type="button" @click.prevent="switchYear('2025')"
                        class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                        <i class="bi bi-trophy-fill"></i>
                        <span>Lihat Hasil Juara Edisi 2025 &rarr;</span>
                    </button>
                    @if ($isAdmin)
                        <button type="button" @click="adminViewMode = 'preview'"
                           class="px-5 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs sm:text-sm font-bold shadow-lg shadow-amber-400/20 transition-all flex items-center gap-2">
                            <i class="bi bi-table"></i> Buka Pratinjau Tabel (Admin)
                        </button>
                        <a :href="'{{ route('admin.speed_coding.index') }}?year=' + selectedYear"
                           class="px-5 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-amber-300 text-xs sm:text-sm font-semibold border border-slate-700 transition-all flex items-center gap-2">
                            <i class="bi bi-gear-fill"></i> Panel Admin Edisi <span x-text="selectedYear"></span>
                        </a>
                    @endif
                </div>
            </div>
        </template>

        {{-- ========================================================= --}}
        {{-- VIEW DATA LOMBA (PODIUM, HARAPAN & TABEL)                --}}
        {{-- ========================================================= --}}
        <template x-if="activeCompetition.is_published || ({{ $isAdmin ? 'true' : 'false' }} && adminViewMode === 'preview')">
            <div>
                {{-- Banner Info Admin jika belum ada peserta --}}
                <template x-if="!activeCompetition.is_published && (!activeCompetition.participants || activeCompetition.participants.length === 0)">
                    <div class="max-w-5xl mx-auto mb-8 p-4 rounded-2xl bg-blue-500/10 border border-blue-400/30 text-blue-300 text-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-info-circle-fill text-blue-400 text-base shrink-0"></i>
                            <span>Belum ada data peserta yang dimasukkan untuk edisi <strong x-text="selectedYear"></strong>. Publik saat ini hanya melihat status <strong>Coming Soon</strong>. Gunakan Panel Admin untuk menginput atau mengimpor peserta.</span>
                        </div>
                        <a :href="'{{ route('admin.speed_coding.index') }}?year=' + selectedYear" class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs whitespace-nowrap flex items-center gap-1.5 transition-all self-end sm:self-auto shrink-0 shadow-md">
                            <i class="bi bi-plus-circle-fill"></i> Input Peserta
                        </a>
                    </div>
                </template>

                {{-- PODIUM JUARA 1, 2, 3 --}}
                <template x-if="podium && podium.first">
                    <div class="mb-20">
                        {{-- Header Podium --}}
                        <div class="text-center mb-16">
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/10 border border-amber-400/30 text-amber-300 text-xs font-bold uppercase tracking-wider mb-2">
                                <i class="bi bi-award-fill"></i> Podium Pemenang Utama (Babak Final)
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                                Tiga Terbaik Kecepatan & Akurasi Scratch
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">
                                Hasil penentuan juara berdasarkan pengerjaan tantangan pada <strong>Babak Final</strong> (Top 10 Finalis).
                            </p>
                        </div>

                        {{-- GRID PODIUM (JUARA 2 - JUARA 1 - JUARA 3) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-end max-w-5xl mx-auto pt-14 sm:pt-16">

                            {{-- JUARA 2 (PERAK / SILVER) --}}
                            <div class="order-2 md:order-1 relative rounded-3xl p-6 sm:p-7 bg-gradient-to-b from-slate-800/90 to-slate-900/90 border border-slate-600/60 shadow-xl shadow-slate-900/50 backdrop-blur-md transform transition-all duration-300 hover:-translate-y-2 group">
                                <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 rounded-2xl bg-gradient-to-tr from-slate-400 to-slate-200 text-slate-900 font-black text-xl flex items-center justify-center shadow-lg border-2 border-white/50">
                                    2
                                </div>

                                <div class="text-center pt-5">
                                    <span class="inline-block px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-slate-700/80 text-slate-200 border border-slate-500/50 mb-3">
                                        🥈 Juara 2 (Perak)
                                    </span>

                                    <div class="relative w-16 h-16 mx-auto rounded-2xl bg-gradient-to-tr from-slate-500 to-slate-300 text-slate-950 font-black text-xl flex items-center justify-center mb-3 shadow-md border border-slate-300/40">
                                        <span x-text="podium.second.avatar"></span>
                                        <span class="absolute -bottom-1 -right-1 text-base" title="Piala Perak">🥈</span>
                                    </div>

                                    <h3 class="text-lg font-bold text-white mb-1 group-hover:text-cyan-300 transition-colors"
                                        x-text="podium.second.name"></h3>

                                    <p class="text-xs font-medium text-slate-300 mb-4"
                                       x-text="podium.second.school"></p>

                                    <div class="p-3.5 rounded-2xl bg-slate-950/70 border border-slate-800 space-y-2 text-xs">
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Waktu Final:</span>
                                            <strong class="text-emerald-400 font-mono" x-text="podium.second.time"></strong>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Skor Final:</span>
                                            <strong class="text-white font-mono" x-text="podium.second.score"></strong>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Waktu Kualifikasi:</span>
                                            <span class="text-slate-300 font-mono text-[11px]" x-text="podium.second.kualifikasi_time"></span>
                                        </div>
                                        <div class="flex justify-between items-center pt-1 border-t border-slate-800/80">
                                            <span class="text-slate-400">Aplikasi:</span>
                                            <span class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-300 text-[10px] font-mono" x-text="podium.second.language"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- JUARA 1 (EMAS / GOLD) --}}
                            <div class="order-1 md:order-2 relative rounded-3xl p-7 sm:p-9 bg-gradient-to-b from-amber-950/40 via-slate-900 to-slate-900 border-2 border-amber-400/80 shadow-2xl shadow-amber-500/20 backdrop-blur-md transform transition-all duration-300 hover:-translate-y-3 group md:-translate-y-4">
                                <div class="absolute -top-14 left-1/2 -translate-x-1/2 flex flex-col items-center pointer-events-none">
                                    <span class="text-3xl animate-bounce mb-1">👑</span>
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 via-yellow-300 to-amber-600 text-slate-950 font-black text-2xl flex items-center justify-center shadow-xl shadow-amber-500/50 border-2 border-white pointer-events-auto">
                                        1
                                    </div>
                                </div>

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

                                    <p class="text-xs font-semibold text-slate-200 mb-5"
                                       x-text="podium.first.school"></p>

                                    <div class="p-4 rounded-2xl bg-slate-950/80 border border-amber-500/30 space-y-2.5 text-xs">
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Waktu Final Tercepat:</span>
                                            <strong class="text-amber-400 font-mono text-sm" x-text="podium.first.time"></strong>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Skor Final:</span>
                                            <strong class="text-emerald-400 font-mono text-sm" x-text="podium.first.score + ' Poin'"></strong>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Waktu Kualifikasi:</span>
                                            <span class="text-slate-300 font-mono text-xs" x-text="podium.first.kualifikasi_time"></span>
                                        </div>
                                        <div class="flex justify-between items-center pt-1 border-t border-slate-800">
                                            <span class="text-slate-400">Aplikasi:</span>
                                            <span class="px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 text-[10px] font-mono font-bold" x-text="podium.first.language"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- JUARA 3 (PERUNGGU / BRONZE) --}}
                            <div class="order-3 relative rounded-3xl p-6 sm:p-7 bg-gradient-to-b from-slate-800/90 to-slate-900/90 border border-slate-600/60 shadow-xl shadow-slate-900/50 backdrop-blur-md transform transition-all duration-300 hover:-translate-y-2 group">
                                <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-700 to-amber-900 text-amber-100 font-black text-xl flex items-center justify-center shadow-lg border-2 border-amber-500/40">
                                    3
                                </div>

                                <div class="text-center pt-5">
                                    <span class="inline-block px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-amber-900/60 text-amber-300 border border-amber-700/60 mb-3">
                                        🥉 Juara 3 (Perunggu)
                                    </span>

                                    <div class="relative w-16 h-16 mx-auto rounded-2xl bg-gradient-to-tr from-amber-800 to-amber-950 text-amber-200 font-black text-xl flex items-center justify-center mb-3 shadow-md border border-amber-700/40">
                                        <span x-text="podium.third.avatar"></span>
                                        <span class="absolute -bottom-1 -right-1 text-base" title="Piala Perunggu">🥉</span>
                                    </div>

                                    <h3 class="text-lg font-bold text-white mb-1 group-hover:text-amber-400 transition-colors"
                                        x-text="podium.third.name"></h3>

                                    <p class="text-xs font-medium text-slate-300 mb-4"
                                       x-text="podium.third.school"></p>

                                    <div class="p-3.5 rounded-2xl bg-slate-950/70 border border-slate-800 space-y-2 text-xs">
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Waktu Final:</span>
                                            <strong class="text-emerald-400 font-mono" x-text="podium.third.time"></strong>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Skor Final:</span>
                                            <strong class="text-white font-mono" x-text="podium.third.score"></strong>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Waktu Kualifikasi:</span>
                                            <span class="text-slate-300 font-mono text-[11px]" x-text="podium.third.kualifikasi_time"></span>
                                        </div>
                                        <div class="flex justify-between items-center pt-1 border-t border-slate-800/80">
                                            <span class="text-slate-400">Aplikasi:</span>
                                            <span class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-300 text-[10px] font-mono" x-text="podium.third.language"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </template>

                {{-- ========================================================= --}}
                {{-- JUARA HARAPAN 1, 2, 3 (KHUSUS UNTUK EDISI 2025)           --}}
                {{-- ========================================================= --}}
                <template x-if="activeCompetition.has_harapan">
                    <div class="max-w-5xl mx-auto mb-20">
                        <div class="text-center mb-6">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-slate-800/90 text-amber-300 border border-slate-700 text-xs font-bold uppercase tracking-wider">
                                <i class="bi bi-award"></i> Juara Harapan (Babak Final Edisi 2025)
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <template x-for="h in activeCompetition.harapan" :key="h.final_rank">
                                <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 text-center hover:border-slate-700 transition-all shadow-lg">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase bg-slate-800 text-amber-300 border border-slate-700 mb-3" x-text="h.badge"></span>
                                    
                                    <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-800 text-amber-300 font-black text-lg flex items-center justify-center mb-3 shadow-inner"
                                         x-text="h.avatar"></div>
                                    
                                    <h4 class="font-extrabold text-white text-base mb-1" x-text="h.name"></h4>
                                    <p class="text-xs text-slate-400 mb-4" x-text="h.school"></p>

                                    <div class="p-3 rounded-2xl bg-slate-950/80 border border-slate-800/80 flex items-center justify-around text-xs">
                                        <div>
                                            <span class="text-slate-500 block text-[10px] uppercase font-semibold">Waktu Final</span>
                                            <strong class="text-emerald-400 font-mono text-sm" x-text="h.time"></strong>
                                        </div>
                                        <div class="h-7 w-px bg-slate-800"></div>
                                        <div>
                                            <span class="text-slate-500 block text-[10px] uppercase font-semibold">Skor Final</span>
                                            <strong class="text-white font-mono text-sm" x-text="h.score"></strong>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- ========================================================= --}}
                {{-- STATISTIK CEPAT KOMPETISI                                 --}}
                {{-- ========================================================= --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto mb-16">
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
                        <span class="block text-2xl lg:text-3xl font-black text-amber-400 mb-1" x-text="activeCompetition.total_participants"></span>
                        <span class="text-xs text-slate-400 font-semibold">Peserta Kualifikasi</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
                        <span class="block text-2xl lg:text-3xl font-black text-cyan-400 mb-1" x-text="activeCompetition.finalists_count"></span>
                        <span class="text-xs text-slate-400 font-semibold">Lolos ke Babak Final</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
                        <span class="block text-2xl lg:text-3xl font-black text-emerald-400 mb-1" x-text="podium && podium.first ? podium.first.time : '-'"></span>
                        <span class="text-xs text-slate-400 font-semibold">Rekor Final Tercepat</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
                        <span class="block text-2xl lg:text-3xl font-black text-indigo-400 mb-1">Scratch 3.0</span>
                        <span class="text-xs text-slate-400 font-semibold">Platform Standar</span>
                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- TABEL HASIL (BABAK KUALIFIKASI VS BABAK FINAL)            --}}
                {{-- ========================================================= --}}
                <div class="max-w-6xl mx-auto bg-slate-900/80 rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-2xl backdrop-blur-md">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-black text-white flex items-center gap-2">
                                <span>Papan Hasil Kompetisi 2 Babak</span>
                                <span class="text-xs px-2.5 py-1 rounded-full bg-amber-400/20 text-amber-300 font-bold" x-text="activeCompetition.batch_name"></span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                                Dua kali pengerjaan tantangan terpisah: <strong>Babak Kualifikasi</strong> (<span x-text="activeCompetition.total_participants"></span> Peserta) dan <strong>Babak Final</strong> (<span x-text="activeCompetition.finalists_count"></span> Finalis). Kategori SD &amp; SMP bersaing bersama.
                            </p>
                        </div>

                        {{-- Search Box dengan Tombol Cari & Tombol Reset (Bebas Jump / Anti-Scroll Movement) --}}
                        <div class="flex items-center gap-2 w-full md:w-80">
                            <div class="relative flex-1">
                                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                <input type="text"
                                    x-ref="searchInput"
                                    x-model.debounce.150ms="search"
                                    @focus="maintainTableHeight()"
                                    @input="maintainTableHeight()"
                                    @keydown.enter.prevent="maintainTableHeight()"
                                    placeholder="Cari nama peserta / sekolah..."
                                    class="w-full pl-9 pr-8 py-2 rounded-xl border border-slate-700 bg-slate-950 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 shadow-inner">
                                <button type="button"
                                    x-show="search && search.length > 0"
                                    @click.prevent="search = ''; maintainTableHeight()"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white text-xs transition-colors p-1"
                                    title="Hapus pencarian">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>
                            <button type="button"
                                @click.prevent="maintainTableHeight()"
                                class="px-3.5 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs shadow-md transition-colors flex items-center gap-1.5 flex-shrink-0"
                                title="Terapkan pencarian">
                                <i class="bi bi-search"></i>
                                <span>Cari</span>
                            </button>
                        </div>
                    </div>

                    {{-- Filter Tabs (Babak Kualifikasi di Kiri, Babak Final di Tengah, Podium di Kanan) --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-800">
                        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                            <span class="text-slate-400 mr-1 text-[11px] uppercase tracking-wider font-bold">Tahapan Lomba:</span>
                            
                            {{-- TAB 1: BABAK KUALIFIKASI --}}
                            <button type="button" @click.prevent="setFilterStage('KUALIFIKASI')"
                                class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5"
                                :class="filterStage === 'KUALIFIKASI' ? 'bg-blue-600 text-white font-black shadow-md shadow-blue-500/20' : 'bg-slate-800 text-slate-300 hover:text-white'">
                                <span>⚡ Babak Kualifikasi (<span x-text="activeCompetition.total_participants"></span> Peserta)</span>
                            </button>

                            {{-- TAB 2: BABAK FINAL --}}
                            <button type="button" @click.prevent="setFilterStage('FINAL')"
                                class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5"
                                :class="filterStage === 'FINAL' ? 'bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 font-black shadow-md shadow-amber-500/20' : 'bg-slate-800 text-slate-300 hover:text-white'">
                                <span>🏆 Babak Final (<span x-text="activeCompetition.finalists_count"></span> Finalis)</span>
                            </button>

                            {{-- TAB 3: PODIUM JUARA --}}
                            <button type="button" @click.prevent="setFilterStage('PODIUM')"
                                class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5"
                                :class="filterStage === 'PODIUM' ? 'bg-amber-600 text-white font-black shadow-md' : 'bg-slate-800 text-slate-300 hover:text-white'">
                                <span>🥇 Podium Juara (1, 2, 3)</span>
                            </button>
                        </div>

                        {{-- Keterangan Jenjang Gabungan --}}
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-slate-800/80 border border-slate-700/60 text-[11px] text-slate-300">
                            <i class="bi bi-people-fill text-amber-400"></i>
                            <span>Jenjang SD &amp; SMP Terbuka (Digabung)</span>
                        </div>
                    </div>

                    {{-- Table dengan container stabil (anti-jump, min-height terjaga) --}}
                    <div id="speed-coding-table-container"
                         class="rounded-2xl border border-slate-800 overflow-hidden bg-slate-950/60 shadow-inner min-h-[500px] flex flex-col justify-start">
                        <div class="w-full overflow-hidden">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm table-auto">
                                <thead>
                                    <tr class="bg-slate-900 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                        <th class="px-3 py-3.5 text-center w-14 whitespace-nowrap">
                                            <span>Rank</span>
                                        </th>
                                        <th class="px-3.5 py-3.5 text-left">Nama Peserta</th>
                                        <th class="px-3.5 py-3.5 text-left">Asal Sekolah</th>
                                        <th class="px-3 py-3.5 text-center w-20 whitespace-nowrap">Jenjang</th>
                                        
                                        {{-- Kolom Waktu & Skor Seragam untuk Semua Tahapan --}}
                                        <th class="px-3.5 py-3.5 text-center w-24 whitespace-nowrap"
                                            :class="filterStage === 'KUALIFIKASI' ? 'text-cyan-300' : 'text-amber-300'">
                                            Waktu
                                        </th>
                                        <th class="px-3.5 py-3.5 text-center w-20 whitespace-nowrap"
                                            :class="filterStage === 'KUALIFIKASI' ? 'text-cyan-300' : 'text-amber-300'">
                                            Skor
                                        </th>

                                        <th class="px-3.5 py-3.5 text-center w-36 whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-800/80">
                                    <template x-for="p in filteredParticipants" :key="'p-' + (p.id || p.name)">
                                        <tr class="hover:bg-slate-800/50 transition-colors"
                                            :class="{
                                                'bg-amber-500/10 font-semibold': p.final_rank === 1 && filterStage !== 'KUALIFIKASI',
                                                'bg-slate-700/20': p.final_rank === 2 && filterStage !== 'KUALIFIKASI',
                                                'bg-amber-900/15': p.final_rank === 3 && filterStage !== 'KUALIFIKASI',
                                                'bg-blue-950/20': p.is_finalist && filterStage === 'KUALIFIKASI'
                                            }">
                                            
                                            {{-- Rank Column --}}
                                            <td class="px-4 py-3.5 text-center font-bold whitespace-nowrap">
                                                <template x-if="filterStage !== 'KUALIFIKASI'">
                                                    <div>
                                                        <template x-if="p.final_rank === 1">
                                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-slate-950 font-black shadow-md shadow-amber-400/30">🥇</span>
                                                        </template>
                                                        <template x-if="p.final_rank === 2">
                                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-300 text-slate-950 font-black shadow-md">🥈</span>
                                                        </template>
                                                        <template x-if="p.final_rank === 3">
                                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white font-black shadow-md">🥉</span>
                                                        </template>
                                                        <template x-if="p.final_rank > 3">
                                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-blue-900/60 text-cyan-300 font-bold border border-blue-500/30" x-text="p.final_rank"></span>
                                                        </template>
                                                    </div>
                                                </template>

                                                <template x-if="filterStage === 'KUALIFIKASI'">
                                                    <div>
                                                        <span class="font-mono text-xs font-bold"
                                                              :class="p.kualifikasi_rank <= 10 ? 'text-amber-400 font-extrabold' : 'text-slate-500'"
                                                              x-text="'#' + p.kualifikasi_rank"></span>
                                                    </div>
                                                </template>
                                            </td>

                                            {{-- Nama Peserta (Maksimal 30 karakter, ellipsis, tooltip saat hover) --}}
                                            <td class="px-3.5 py-3.5 text-left">
                                                <span class="font-bold text-white block truncate max-w-[150px] sm:max-w-[200px] md:max-w-[240px] cursor-help"
                                                      :title="p.name"
                                                      x-text="formatTruncate(p.name, 30)"></span>
                                            </td>

                                            {{-- Asal Sekolah (Maksimal 30 karakter, ellipsis, tooltip saat hover) --}}
                                            <td class="px-3.5 py-3.5 text-left">
                                                <span class="text-slate-300 block truncate max-w-[150px] sm:max-w-[200px] md:max-w-[240px] cursor-help"
                                                      :title="p.school"
                                                      x-text="formatTruncate(p.school, 30)"></span>
                                            </td>

                                            {{-- Jenjang --}}
                                            <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold"
                                                      :class="p.jenjang === 'SD' ? 'bg-emerald-950 text-emerald-300 border border-emerald-700/60' : 'bg-cyan-950 text-cyan-300 border border-cyan-700/60'"
                                                      x-text="p.jenjang || 'SMP'"></span>
                                            </td>

                                            {{-- Waktu (Seragam: Kualifikasi di tab Kualifikasi, Final di tab Final/Podium) --}}
                                            <td class="px-3.5 py-3.5 text-center font-mono font-bold whitespace-nowrap"
                                                :class="filterStage === 'KUALIFIKASI' ? 'text-cyan-300' : 'text-amber-300'"
                                                x-text="filterStage === 'KUALIFIKASI' ? (p.kualifikasi_time || '-') : (p.final_time || '-')">
                                            </td>

                                            {{-- Skor (Seragam: Kualifikasi di tab Kualifikasi, Final di tab Final/Podium) --}}
                                            <td class="px-3.5 py-3.5 text-center font-extrabold text-white whitespace-nowrap"
                                                x-text="filterStage === 'KUALIFIKASI' ? (p.kualifikasi_score !== null ? Number(p.kualifikasi_score).toFixed(1) : '-') : (p.final_score !== null ? Number(p.final_score).toFixed(1) : '-')">
                                            </td>

                                            {{-- Status / Keterangan --}}
                                            <td class="px-3.5 py-3.5 text-center whitespace-nowrap">
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-block"
                                                      :class="{
                                                        'bg-amber-400 text-slate-950 font-black shadow-xs': p.final_rank === 1 && filterStage !== 'KUALIFIKASI',
                                                        'bg-slate-300 text-slate-950 font-bold': p.final_rank === 2 && filterStage !== 'KUALIFIKASI',
                                                        'bg-amber-700 text-white font-bold': p.final_rank === 3 && filterStage !== 'KUALIFIKASI',
                                                        'bg-amber-950/80 text-amber-300 border border-amber-600/50': (p.final_rank === 4 || p.final_rank === 5 || p.final_rank === 6) && filterStage !== 'KUALIFIKASI',
                                                        'bg-blue-900/60 text-cyan-300 border border-blue-500/40': (p.is_finalist && filterStage === 'KUALIFIKASI') || (p.final_rank > 6 && filterStage !== 'KUALIFIKASI'),
                                                        'bg-slate-800 text-slate-400': !p.is_finalist
                                                      }"
                                                      x-text="filterStage === 'KUALIFIKASI' ? (p.is_finalist ? 'Lolos ke Final 🎖️' : 'Peserta') : (p.status || (p.is_finalist ? 'Finalis Top 10' : 'Peserta'))"></span>
                                            </td>
                                        </tr>
                                    </template>

                                    <tr x-show="filteredParticipants.length === 0">
                                        <td colspan="7" class="px-5 py-12 text-center text-slate-500 text-xs">
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
                            Sertifikat resmi e-verifikasi kompetisi dapat diunduh oleh seluruh peserta melalui portal resmi BBLSDM Komdigi Makassar.
                        </span>
                        <a href="{{ route('landing') }}" class="font-bold text-amber-400 hover:text-amber-300 hover:underline flex items-center gap-1">
                            &larr; Kembali ke Beranda HANDAL
                        </a>
                    </div>

                </div>
            </div>
        </template>

    </div>
</div>

{{-- SCRIPT ALPINE.JS --}}
<script>
    window.initSpeedCoding = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const initialYear = urlParams.get('year') || '2025';

        return {
            selectedYear: initialYear, // Default 2025 (Tahun Lalu / Kiri)
            competitions: @json($competitions),
            search: '',
            filterStage: 'KUALIFIKASI', // Babak Kualifikasi aktif secara default di sebelah kiri
            adminViewMode: 'preview', // 'preview' atau 'public' untuk peninjauan admin

            initTable() {
                this.$nextTick(() => {
                    this.maintainTableHeight();
                });
                setTimeout(() => {
                    this.maintainTableHeight();
                }, 250);
            },

            // Kunci tinggi minimum container tabel agar tidak pernah menciut dan memicu loncatan scroll dokumen
            maintainTableHeight() {
                const container = document.getElementById('speed-coding-table-container');
                if (container) {
                    const currentH = container.offsetHeight;
                    if (currentH > 350) {
                        const existingMinH = parseInt(container.style.minHeight || 0);
                        if (currentH > existingMinH) {
                            container.style.minHeight = currentH + 'px';
                        }
                    }
                }
            },

            // Ganti tahapan lomba tanpa jump/lompat scroll layar
            setFilterStage(stage) {
                this.maintainTableHeight();
                this.filterStage = stage;
            },

            // Format pemotongan teks maksimal 30 karakter dengan tanda titik-titik (...)
            formatTruncate(text, max = 30) {
                if (!text) return '-';
                return text.length > max ? text.slice(0, max) + '...' : text;
            },

            // Ganti tahun batch dengan reset minHeight
            switchYear(year) {
                const container = document.getElementById('speed-coding-table-container');
                if (container) {
                    container.style.minHeight = '';
                }
                this.selectedYear = year;
                this.search = '';
                this.$nextTick(() => {
                    this.maintainTableHeight();
                });
            },

            get activeCompetition() {
                return this.competitions[this.selectedYear] || this.competitions['2025'] || {};
            },

            get podium() {
                return this.activeCompetition.podium;
            },

            get filteredParticipants() {
                let list = this.activeCompetition.participants || [];

                if (this.filterStage === 'FINAL') {
                    // Hanya 10 peserta yang lolos ke final, diurutkan berdasarkan final_rank
                    list = list.filter(function(p) { return p.is_finalist; })
                               .sort(function(a, b) { return a.final_rank - b.final_rank; });
                } else if (this.filterStage === 'PODIUM') {
                    // Hanya 3 juara teratas
                    list = list.filter(function(p) { return p.final_rank && p.final_rank <= 3; })
                               .sort(function(a, b) { return a.final_rank - b.final_rank; });
                } else if (this.filterStage === 'KUALIFIKASI') {
                    // Seluruh peserta diurutkan berdasarkan peringkat kualifikasi
                    list = [...list].sort(function(a, b) { return a.kualifikasi_rank - b.kualifikasi_rank; });
                }

                if (this.search && this.search.trim() !== '') {
                    const q = this.search.toLowerCase().trim();
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
