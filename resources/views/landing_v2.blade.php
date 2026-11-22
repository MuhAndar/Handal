@extends('layouts.app')

@section('title', 'Peringkat Sekolah Aman Digital 2026 - HANDAL')

@section('content')
    {{-- LEAFLET CSS & JS UNTUK PETA INTERAKTIF --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="w-full bg-[#fbfcfd] text-slate-800 min-h-screen"
         x-data="{
            activeSection: 'overview',
            search: '',
            filterJenjang: 'ALL',
            sortBy: 'score',
            sortAsc: false,
            highlightedSchoolId: null,
            schools: {{ Js::from($rankings) }},
            mapLocations: {{ Js::from($mapLocations) }},
            get filteredSchools() {
                return this.schools.filter(item => {
                    const matchSearch = item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                        (item.npsn && item.npsn.includes(this.search)) ||
                                        (item.address && item.address.toLowerCase().includes(this.search.toLowerCase()));
                    const matchJenjang = (this.filterJenjang === 'ALL') || (item.jenjang === this.filterJenjang);
                    return matchSearch && matchJenjang;
                }).sort((a, b) => {
                    if (this.sortBy === 'score') {
                        return this.sortAsc ? (a.current_score - b.current_score) : (b.current_score - a.current_score);
                    } else if (this.sortBy === 'name') {
                        return this.sortAsc ? a.name.localeCompare(b.name) : b.name.localeCompare(a.name);
                    } else if (this.sortBy === 'students') {
                        return this.sortAsc ? (a.student_respondents_count - b.student_respondents_count) : (b.student_respondents_count - a.student_respondents_count);
                    }
                    return 0;
                });
            },
            getTierName(score) {
                if (score >= 86) return 'Unggul Digital';
                if (score >= 76) return 'Maju';
                if (score >= 51) return 'Berkembang';
                if (score >= 31) return 'Pemula';
                return 'Rintisan';
            },
            getTierClass(score) {
                if (score >= 86) return 'bg-cyan-50 text-cyan-800 border-cyan-200';
                if (score >= 76) return 'bg-blue-50 text-blue-800 border-blue-200';
                if (score >= 51) return 'bg-amber-50 text-amber-800 border-amber-200';
                if (score >= 31) return 'bg-slate-100 text-slate-700 border-slate-200';
                return 'bg-rose-50 text-rose-700 border-rose-200';
            },
            scrollToSchool(id) {
                this.highlightedSchoolId = id;
                const el = document.getElementById('school-row-' + id);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
         }"
         @scroll.window="
            const sections = ['overview', 'comparison', 'map-section', 'table-section', 'methodology', 'sources'];
            for (let s of sections) {
                let el = document.getElementById(s);
                if (el) {
                    let rect = el.getBoundingClientRect();
                    if (rect.top <= 220 && rect.bottom >= 220) {
                        activeSection = s;
                        break;
                    }
                }
            }
         ">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            {{-- TOP BREADCRUMB & SWITCHER (Ramping, Tidak Memenuhi Layar) --}}
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200 text-xs text-slate-500">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-700">HANDAL</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-blue-600 font-bold">Pemeringkatan 2026</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('landing') }}" class="text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1">
                        <i class="bi bi-arrow-left"></i> Beranda Asli
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('ranking.page.v2') }}" class="font-semibold text-blue-600 hover:underline">
                        Tabel Leaderboard &rarr;
                    </a>
                </div>
            </div>

            {{-- DUA KOLOM: SIDEBAR NAVIGASI (KIRI) & KONTEN UTAMA (KANAN) --}}
            <div class="lg:grid lg:grid-cols-12 lg:gap-10 items-start">

                {{-- ========================================================= --}}
                {{-- SIDEBAR NAVIGASI STICKY (ALA WPR: EDITORIAL & SANGAT BERSIH) --}}
                {{-- ========================================================= --}}
                <aside class="hidden lg:block lg:col-span-3 sticky top-24 self-start space-y-6 pt-1">
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">
                            Navigasi Konten
                        </div>

                        <nav class="space-y-1 text-sm font-medium">
                            <a href="#overview"
                               @click="activeSection = 'overview'"
                               class="block py-1.5 pl-3 transition-colors border-l-2"
                               :class="activeSection === 'overview' ? 'font-bold text-blue-600 border-blue-600' : 'text-slate-600 border-transparent hover:text-slate-900 hover:border-slate-300'">
                                Peringkat Sekolah 2026
                            </a>

                            <a href="#comparison"
                               @click="activeSection = 'comparison'"
                               class="block py-1.5 pl-3 transition-colors border-l-2"
                               :class="activeSection === 'comparison' ? 'font-bold text-blue-600 border-blue-600' : 'text-slate-600 border-transparent hover:text-slate-900 hover:border-slate-300'">
                                Temuan Kunci (Perbandingan)
                            </a>

                            <a href="#map-section"
                               @click="activeSection = 'map-section'"
                               class="block py-1.5 pl-3 transition-colors border-l-2"
                               :class="activeSection === 'map-section' ? 'font-bold text-blue-600 border-blue-600' : 'text-slate-600 border-transparent hover:text-slate-900 hover:border-slate-300'">
                                Peta Sebaran Wilayah
                            </a>

                            <a href="#table-section"
                               @click="activeSection = 'table-section'"
                               class="block py-1.5 pl-3 transition-colors border-l-2"
                               :class="activeSection === 'table-section' ? 'font-bold text-blue-600 border-blue-600' : 'text-slate-600 border-transparent hover:text-slate-900 hover:border-slate-300'">
                                Tabel Pemeringkatan
                            </a>

                            <a href="#methodology"
                               @click="activeSection = 'methodology'"
                               class="block py-1.5 pl-3 transition-colors border-l-2"
                               :class="activeSection === 'methodology' ? 'font-bold text-blue-600 border-blue-600' : 'text-slate-600 border-transparent hover:text-slate-900 hover:border-slate-300'">
                                Definisi & Metodologi
                            </a>

                            <a href="#sources"
                               @click="activeSection = 'sources'"
                               class="block py-1.5 pl-3 transition-colors border-l-2"
                               :class="activeSection === 'sources' ? 'font-bold text-blue-600 border-blue-600' : 'text-slate-600 border-transparent hover:text-slate-900 hover:border-slate-300'">
                                Dasar Hukum & Sumber
                            </a>
                        </nav>
                    </div>

                    <div class="pt-5 border-t border-slate-200 text-xs space-y-2 text-slate-500">
                        <div class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Tautan Terkait</div>
                        <a href="{{ route('registration.page') }}" class="block font-medium text-blue-600 hover:text-blue-800">
                            &rarr; Registrasi Sekolah Baru
                        </a>
                        <a href="{{ route('login') }}" class="block font-medium text-slate-600 hover:text-slate-900">
                            &rarr; Portal Masuk Admin
                        </a>
                    </div>
                </aside>

                {{-- ========================================================= --}}
                {{-- KONTEN UTAMA (KANAN) --}}
                {{-- ========================================================= --}}
                <main class="lg:col-span-9 space-y-12">

                    {{-- 1. OVERVIEW & JUDUL UTAMA --}}
                    <section id="overview" class="scroll-mt-24">
                        <div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">
                                Peringkat Sekolah Aman Digital 2026
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-3xl">
                                Indeks transparansi komposit kesiapan dan maturitas keamanan siber institusi pendidikan menengah (70% Asesmen Mandiri Terverifikasi + 30% Survei Kuesioner Siswa).
                            </p>

                            <div class="flex flex-wrap items-center gap-y-1 gap-x-3 mt-3 text-xs text-slate-400">
                                <span><i class="bi bi-clock-history mr-1"></i> Data: Tahun Ajaran 2025/2026</span>
                                <span>&bull;</span>
                                <span><i class="bi bi-patch-check mr-1"></i> Standar Resmi BBLSDM Komdigi Makassar</span>
                                <span>&bull;</span>
                                <span class="text-slate-600 font-semibold">{{ $stats['total_schools'] }} Sekolah &bull; {{ $stats['total_students'] }} Responden Siswa</span>
                            </div>
                        </div>
                    </section>

                    {{-- 2. KARTU PERBANDINGAN TERTINGGI VS TOLAK UKUR (PERSIS WORLD POPULATION REVIEW) --}}
                    @php
                        $highestSchool = $rankings->sortByDesc('current_score')->first();
                    @endphp
                    <section id="comparison" class="scroll-mt-24">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- KARTU KIRI: SKOR TERTINGGI (DENMARK STYLE) --}}
                            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-cyan-500 shadow-xs"></span>
                                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base truncate">
                                                {{ $highestSchool ? $highestSchool->name : 'MAN 2 Model Makassar' }}
                                            </h3>
                                        </div>
                                        <span class="text-[10px] font-bold text-cyan-800 bg-cyan-50 px-2 py-0.5 rounded-full border border-cyan-200">
                                            {{ $highestSchool ? $highestSchool->rank_label : 'Unggul Digital' }}
                                        </span>
                                    </div>

                                    <div class="my-2">
                                        <div class="text-3xl sm:text-4xl font-black text-slate-900">
                                            {{ $highestSchool ? number_format($highestSchool->current_score, 1) : '91.4' }}
                                        </div>
                                        <div class="text-xs text-slate-500 font-medium">
                                            Skor Tertinggi (Skala 0 - 100)
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                    <span>Asesmen Mandiri: <strong class="text-slate-800">{{ $highestSchool ? number_format($highestSchool->self_assessment_score, 1) : '92.0' }}</strong> (70%)</span>
                                    <span>Survei Siswa: <strong class="text-indigo-600">{{ $highestSchool ? number_format($highestSchool->student_survey_score, 1) : '90.0' }}</strong> (30%)</span>
                                </div>
                            </div>

                            {{-- KARTU KANAN: TOLAK UKUR REGIONAL (HONDURAS STYLE) --}}
                            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-blue-600 shadow-xs"></span>
                                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">
                                                Provinsi Sulawesi Selatan
                                            </h3>
                                        </div>
                                        <span class="text-[10px] font-bold text-blue-800 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                                            Baseline Wilayah
                                        </span>
                                    </div>

                                    <div class="my-2">
                                        <div class="text-3xl sm:text-4xl font-black text-slate-900">
                                            {{ $stats['average_score'] ?: '87.2' }}
                                        </div>
                                        <div class="text-xs text-slate-500 font-medium">
                                            Rata-Rata Regional (Skala 0 - 100)
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                    <span>Fokus Uji: <strong class="text-slate-800">Kota Makassar</strong></span>
                                    <span>Partisipasi: <strong class="text-indigo-600">{{ $stats['total_students'] ?: '101' }} Siswa Terlibat</strong></span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 3. PETA SEBARAN WILAYAH (LANGSUNG TERLIHAT DENGAN LEAFLET FREE OPEN-SOURCE) --}}
                    <section id="map-section" class="scroll-mt-24 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                                    <i class="bi bi-geo-alt-fill text-rose-500"></i> Peta Sebaran Sekolah Aman Digital
                                </h2>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Wilayah domisili sekolah disorot secara otomatis (contoh: Provinsi Sulawesi Selatan & Kota Makassar).
                                </p>
                            </div>

                            <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs font-semibold text-slate-600 self-start sm:self-auto">
                                <button type="button" id="btn-focus-makassar" class="px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-xs font-bold transition-all">
                                    Kota Makassar
                                </button>
                                <button type="button" id="btn-focus-sulsel" class="px-3 py-1.5 rounded-lg hover:text-slate-900 transition-all">
                                    Provinsi Sulsel
                                </button>
                                <button type="button" id="btn-focus-indo" class="px-3 py-1.5 rounded-lg hover:text-slate-900 transition-all">
                                    Nusantara
                                </button>
                            </div>
                        </div>

                        {{-- WADAH PETA --}}
                        <div class="relative w-full h-[440px] bg-slate-100 rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                            <div id="map-container" class="w-full h-full z-10"></div>

                            {{-- FLOATING LEGENDA PETA --}}
                            <div class="absolute bottom-4 left-4 z-20 bg-white/95 backdrop-blur-md p-3.5 rounded-xl border border-slate-200/80 shadow-md text-xs max-w-[220px]">
                                <div class="font-bold text-slate-800 mb-1.5 text-[11px] uppercase tracking-wider">
                                    Indeks Keamanan
                                </div>
                                <div class="space-y-1.5 text-[11px]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-cyan-500 flex-shrink-0"></span>
                                        <span class="text-slate-700">Unggul Digital (&ge; 86)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-blue-600 flex-shrink-0"></span>
                                        <span class="text-slate-700">Maju (76 - 85)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-amber-500 flex-shrink-0"></span>
                                        <span class="text-slate-700">Berkembang (51 - 75)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-slate-400 flex-shrink-0"></span>
                                        <span class="text-slate-700">Pemula / Rintisan (&lt; 51)</span>
                                    </div>
                                </div>
                            </div>

                            {{-- BADGE INFO WILAYAH TERPILIH (Di kiri atas samping tombol zoom agar tidak menghalangi tombol layer switcher di kanan atas) --}}
                            <div id="v2-map-active-region-badge" class="absolute top-3 left-14 z-20 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-2.5 max-w-[280px] pointer-events-none transition-all">
                                <span id="v2-badge-region-dot" class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                                <div class="text-xs truncate">
                                    <span id="v2-badge-region-name" class="font-bold text-slate-800 block truncate">Kota Makassar</span>
                                    <span id="v2-badge-region-info" class="text-slate-500 block text-[10px] truncate">{{ count($mapLocations) }} Sekolah Terdaftar (Pusat Pengujian)</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 4. TABEL PEMERINGKATAN LENGKAP --}}
                    <section id="table-section" class="scroll-mt-24 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                                    <i class="bi bi-table text-blue-600"></i> Tabel Peringkat Sekolah
                                </h2>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Komposit: <strong>70% Asesmen Mandiri</strong> + <strong>30% Survei Siswa</strong>.
                                </p>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs font-semibold text-slate-600">
                                    <button type="button" @click="filterJenjang = 'ALL'"
                                        class="px-2.5 py-1 rounded-lg transition-colors"
                                        :class="filterJenjang === 'ALL' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'">
                                        Semua
                                    </button>
                                    <button type="button" @click="filterJenjang = 'SMA'"
                                        class="px-2.5 py-1 rounded-lg transition-colors"
                                        :class="filterJenjang === 'SMA' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'">
                                        SMA
                                    </button>
                                    <button type="button" @click="filterJenjang = 'SMK'"
                                        class="px-2.5 py-1 rounded-lg transition-colors"
                                        :class="filterJenjang === 'SMK' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'">
                                        SMK
                                    </button>
                                    <button type="button" @click="filterJenjang = 'MA'"
                                        class="px-2.5 py-1 rounded-lg transition-colors"
                                        :class="filterJenjang === 'MA' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'">
                                        MA
                                    </button>
                                </div>

                                <div class="relative w-44 sm:w-56">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" x-model="search" placeholder="Cari sekolah..."
                                        class="w-full pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-xs placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>

                        {{-- TABEL DESAIN BERSIH --}}
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                            <th class="px-5 py-3.5 text-center w-14">Rank</th>
                                            <th class="px-5 py-3.5 cursor-pointer hover:text-blue-600"
                                                @click="sortBy = 'name'; sortAsc = !sortAsc">
                                                Nama Sekolah <i class="bi bi-arrow-down-up ml-1 text-[10px]"></i>
                                            </th>
                                            <th class="px-5 py-3.5 text-center">Asesmen (70%)</th>
                                            <th class="px-5 py-3.5 text-center cursor-pointer hover:text-blue-600"
                                                @click="sortBy = 'students'; sortAsc = !sortAsc">
                                                Survei Siswa (30%) <i class="bi bi-arrow-down-up ml-1 text-[10px]"></i>
                                            </th>
                                            <th class="px-5 py-3.5 text-center cursor-pointer hover:text-blue-600"
                                                @click="sortBy = 'score'; sortAsc = !sortAsc">
                                                Skor Akhir <i class="bi bi-arrow-down-up ml-1 text-[10px]"></i>
                                            </th>
                                            <th class="px-5 py-3.5 text-center">Predikat Tier</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-100">
                                        <template x-for="(school, index) in filteredSchools" :key="school.id">
                                            <tr :id="'school-row-' + school.id"
                                                class="hover:bg-slate-50/80 transition-colors"
                                                :class="highlightedSchoolId === school.id ? 'bg-amber-50/70 font-semibold' : ''">
                                                
                                                {{-- RANK --}}
                                                <td class="px-5 py-3.5 text-center font-bold text-slate-500">
                                                    <span x-text="index + 1"></span>
                                                </td>

                                                {{-- SEKOLAH --}}
                                                <td class="px-5 py-3.5">
                                                    <div class="font-bold text-slate-900" x-text="school.name"></div>
                                                    <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-2">
                                                        <span x-text="school.jenjang"></span>
                                                        <span>&bull;</span>
                                                        <span x-text="school.address || 'Makassar, Sulsel'"></span>
                                                    </div>
                                                </td>

                                                {{-- ASESMEN MANDIRI (70%) --}}
                                                <td class="px-5 py-3.5 text-center text-slate-600">
                                                    <span class="font-bold text-slate-800" x-text="Number(school.self_assessment_score).toFixed(1)"></span>
                                                </td>

                                                {{-- SURVEI SISWA (30%) --}}
                                                <td class="px-5 py-3.5 text-center">
                                                    <span class="font-bold text-indigo-600" x-text="Number(school.student_survey_score).toFixed(1)"></span>
                                                    <span class="text-[10px] text-slate-400 block" x-text="'(' + school.student_respondents_count + ' siswa)'"></span>
                                                </td>

                                                {{-- SKOR AKHIR --}}
                                                <td class="px-5 py-3.5 text-center">
                                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-extrabold text-sm border border-emerald-200"
                                                          x-text="Number(school.current_score).toFixed(1)"></span>
                                                </td>

                                                {{-- PREDIKAT --}}
                                                <td class="px-5 py-3.5 text-center">
                                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border"
                                                          :class="getTierClass(school.current_score)"
                                                          x-text="getTierName(school.current_score)"></span>
                                                </td>
                                            </tr>
                                        </template>

                                        <tr x-show="filteredSchools.length === 0">
                                            <td colspan="6" class="px-5 py-10 text-center text-slate-400 text-xs">
                                                Tidak ditemukan sekolah yang sesuai dengan pencarian.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    {{-- 5. DEFINISI & METODOLOGI (EDITORIAL CLEAN STYLE) --}}
                    <section id="methodology" class="scroll-mt-24 space-y-6 pt-4 border-t border-slate-200">
                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                Dokumen Resmi Panduan Pelaksanaan
                            </div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                                Definisi & Metodologi Evaluasi HANDAL
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Balai Besar Pengembangan SDM dan Penelitian Komunikasi dan Digital (BBLSDM Komdigi) Makassar.
                            </p>
                        </div>

                        {{-- PENJELASAN RINGKAS --}}
                        <div class="text-sm text-slate-600 leading-relaxed space-y-3">
                            <p>
                                <strong>HANDAL (Sekolah Aman Digital)</strong> adalah inisiatif strategis untuk memetakan, mengukur, dan meningkatkan maturitas ekosistem siber serta literasi digital pada institusi pendidikan tingkat menengah (SMA/SMK/MA). Program ini mengadopsi kerangka kerja <em>Digital Literacy Global Framework (DLGF) UNESCO</em> dan standar <em>International Telecommunication Union (ITU)</em>.
                            </p>
                        </div>

                        {{-- 4 PILAR INDIKATOR --}}
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3">
                                Empat Pilar Indikator Utama
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div class="p-4 rounded-xl bg-white border border-slate-200">
                                    <div class="font-bold text-slate-900 mb-1">1. SDM & Literasi Digital</div>
                                    <p class="text-slate-600">Ketersediaan tenaga pengelola IT, partisipasi kompetisi teknologi, pelatihan microskill, dan ekskul digital siswa.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-white border border-slate-200">
                                    <div class="font-bold text-slate-900 mb-1">2. Infrastruktur Digital</div>
                                    <p class="text-slate-600">Rasio komputer terhadap siswa, keandalan jaringan internet, dan kebijakan perangkat bawaan (BYOD).</p>
                                </div>
                                <div class="p-4 rounded-xl bg-white border border-slate-200">
                                    <div class="font-bold text-slate-900 mb-1">3. Keamanan Digital</div>
                                    <p class="text-slate-600">Prosedur pencadangan data berkala, proteksi antivirus/firewall, tata kelola sandi, dan mitigasi insiden siber.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-white border border-slate-200">
                                    <div class="font-bold text-slate-900 mb-1">4. Sosial Digital</div>
                                    <p class="text-slate-600">Perlindungan privasi (anti-doxing), pencegahan <em>cyberbullying</em>, etika bermedia sosial, dan literasi anti-hoaks.</p>
                                </div>
                            </div>
                        </div>

                        {{-- FORMULA & TIERING --}}
                        <div class="p-4 rounded-xl bg-slate-900 text-white text-xs sm:text-sm font-mono">
                            <span class="text-emerald-400 font-bold">// Rumus Pembobotan Komposit:</span><br>
                            Skor Akhir = (0.70 &times; Skor Asesmen Mandiri) + (0.30 &times; Rata-rata Kuesioner Siswa)
                        </div>

                        {{-- TABEL TIERING --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-500 uppercase text-[10px]">
                                    <tr>
                                        <th class="p-3">Rentang Skor</th>
                                        <th class="p-3">Klasifikasi Predikat</th>
                                        <th class="p-3">Tindak Lanjut & Rekomendasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-600">
                                    <tr>
                                        <td class="p-3 font-bold text-cyan-700">86 - 100</td>
                                        <td class="p-3 font-bold text-slate-900">Sekolah Unggul Digital</td>
                                        <td class="p-3">Kematangan tata kelola siber tinggi; menjadi sekolah rujukan percontohan.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-bold text-blue-700">76 - 85</td>
                                        <td class="p-3 font-bold text-slate-900">Sekolah Maju</td>
                                        <td class="p-3">Kesiapan perangkat dan SDM baik, kebijakan keamanan berjalan aktif.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-bold text-amber-700">51 - 75</td>
                                        <td class="p-3 font-bold text-slate-900">Sekolah Berkembang</td>
                                        <td class="p-3">Fondasi digital terbentuk; membutuhkan penguatan SOP dan literasi siswa.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-bold text-slate-700">31 - 50</td>
                                        <td class="p-3 font-bold text-slate-900">Sekolah Pemula</td>
                                        <td class="p-3">Membutuhkan pendampingan langsung dari instansi terkait.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-bold text-rose-700">&lt; 31</td>
                                        <td class="p-3 font-bold text-slate-900">Sekolah Rintisan</td>
                                        <td class="p-3">Prioritas intervensi infrastruktur dan pelatihan literasi dasar.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- 6. DASAR HUKUM & SUMBER --}}
                    <section id="sources" class="scroll-mt-24 pt-4 border-t border-slate-200">
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                            Referensi Hukum
                        </div>
                        <ul class="text-xs text-slate-500 space-y-1.5 list-disc list-inside leading-relaxed">
                            <li>Peraturan Presiden Nomor 174 Tahun 2024 tentang Kementerian Komunikasi dan Digital.</li>
                            <li>Peraturan Menteri Komunikasi dan Digital Nomor 1 Tahun 2025 tentang Organisasi dan Tata Kerja Kemkomdigi.</li>
                            <li>UNESCO (2018). <em>A Global Framework of Reference on Digital Literacy Skills (Indicator 4.4.2)</em>.</li>
                            <li>International Telecommunication Union (ITU, 2024). <em>Measuring digital development</em>.</li>
                        </ul>
                    </section>

                </main>
            </div>

        </div>
    </div>

    {{-- SCRIPT INISIALISASI PETA LEAFLET DENGAN GEOJSON PRESISI & BEBAS API KEY --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const locations = @json($mapLocations);

            // Titik fokus default: Kota Makassar (Pusat pengujian)
            const makassarCenter = [-5.1477, 119.4500];
            const sulselCenter = [-4.1000, 120.1000];

            const map = L.map('map-container', {
                center: makassarCenter,
                zoom: 11,
                minZoom: 4,
                maxZoom: 18,
                zoomControl: true
            });

            // Pastikan dimensi peta terhitung sempurna setelah render
            setTimeout(() => {
                map.invalidateSize();
            }, 250);

            // Layer 1: Esri Light Gray Canvas (Elegan ala WPR, 100% Free & Tanpa API Key)
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

            // Layer 2: OpenStreetMap Standar
            const osmStandard = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors &bull; HANDAL',
                minZoom: 4,
                maxZoom: 19
            });

            // Layer Switcher di pojok kanan atas
            const baseMaps = {
                "Kanvas Bersih (Esri)": L.layerGroup([esriGray, esriLabels]),
                "Peta Jalan Detil (OSM)": osmStandard
            };
            L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

            let sulselLayerGroup = null;

            // State & fungsi pembaruan badge wilayah aktif (kiri atas samping tombol zoom)
            let currentBadgeState = {
                name: 'Kota Makassar',
                info: `${locations.length} Sekolah Terdaftar (Pusat Pengujian)`,
                type: 'active'
            };

            function updateRegionBadge(name, info, type = 'active') {
                const dot = document.getElementById('v2-badge-region-dot');
                const nameEl = document.getElementById('v2-badge-region-name');
                const infoEl = document.getElementById('v2-badge-region-info');
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
                if (sulselLayerGroup) {
                    sulselLayerGroup.setStyle(feature => getSulselStyle(feature, map.getZoom()));
                }
            });

            // 1. LOAD GEOJSON RESMI SULAWESI SELATAN (24 KAB/KOTA TANPA SULBAR)
            fetch('{{ asset("data/sulsel_accurate.geojson") }}')
                .then(response => response.json())
                .then(geojsonData => {
                    sulselLayerGroup = L.geoJSON(geojsonData, {
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
                                        map.fitBounds(e.target.getBounds(), { padding: [30, 30], duration: 1.0 });
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

                    sulselLayerGroup.bringToBack();
                })
                .catch(err => {
                    console.warn('Gagal memuat GeoJSON batas wilayah Sulsel:', err);
                });

            // 2. MARKER SEKOLAH DENGAN KOORDINAT & ALAMAT PRESISI
            locations.forEach((school) => {
                let pinColor = '#3b82f6';
                if (school.score >= 86) pinColor = '#06b6d4'; // Cyan
                else if (school.score >= 76) pinColor = '#2563eb'; // Blue
                else if (school.score >= 51) pinColor = '#f59e0b'; // Amber
                else pinColor = '#ef4444'; // Red

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
                    className: 'custom-school-marker',
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

                        <button onclick="window.__scrollToSchool(${school.id})" 
                            style="width: 100%; background: #2563eb; color: white; border: none; padding: 6px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
                            <span>Lihat di Tabel Peringkat</span> &darr;
                        </button>
                    </div>
                `;

                marker.bindPopup(popupContent);

                // Tooltip ringan saat kursor hovering di atas marker
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

            window.__scrollToSchool = function (id) {
                const alpineEl = document.querySelector('[x-data]');
                if (alpineEl && alpineEl._x_dataStack) {
                    alpineEl._x_dataStack[0].scrollToSchool(id);
                }
            };

            // Event Tombol Zoom
            const btnMakassar = document.getElementById('btn-focus-makassar');
            const btnSulsel = document.getElementById('btn-focus-sulsel');
            const btnIndo = document.getElementById('btn-focus-indo');

            function setActiveButton(activeBtn) {
                [btnMakassar, btnSulsel, btnIndo].forEach(b => {
                    b.classList.remove('bg-white', 'text-slate-900', 'shadow-xs', 'font-bold');
                    b.classList.add('text-slate-600');
                });
                activeBtn.classList.add('bg-white', 'text-slate-900', 'shadow-xs', 'font-bold');
                activeBtn.classList.remove('text-slate-600');
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
                if (sulselLayerGroup) {
                    map.fitBounds(sulselLayerGroup.getBounds(), { padding: [20, 20], duration: 1.2 });
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
        });
    </script>
@endsection
