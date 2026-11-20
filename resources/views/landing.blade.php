@extends('layouts.app')

@section('content')
    {{-- LEAFLET CSS & JS UNTUK PETA INTERAKTIF --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-28">

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center mb-28">
                <div class="order-2 lg:order-1">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 mb-6 rounded-full bg-blue-100 text-blue-700 text-sm font-bold border border-blue-200">
                        <i class="bi bi-shield-check"></i> BBPSDM Komdigi Makassar
                    </span>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                        Wujudkan Ekosistem <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">
                            Sekolah Aman Digital
                        </span>
                    </h1>

                    <p class="text-lg text-slate-600 leading-relaxed max-w-xl mb-8">
                        Platform resmi pemeringkatan (HANDAL) untuk mengukur kesiapan dan keamanan digital sekolah menengah
                        di Indonesia.
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

                <div class="relative order-1 lg:order-2 group">
                    <div
                        class="absolute -inset-4 bg-gradient-to-tr from-blue-400 to-cyan-300 rounded-[2.5rem] blur-2xl opacity-30 group-hover:opacity-50 transition duration-500">
                    </div>
                    <div
                        class="relative rounded-[2rem] overflow-hidden shadow-2xl border-[6px] border-white transform transition duration-500 hover:scale-[1.02]">
                        <img src="{{ asset('img/SAMPUL FINAL XTRA COLOR 2.jpg') }}" class="w-full h-auto object-cover">
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
            <div class="max-w-6xl mx-auto mb-28">
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

                        {{-- BADGE INFO WILAYAH AKTIF --}}
                        <div class="absolute top-4 right-4 z-20 bg-white/95 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <div class="text-xs">
                                <span class="font-bold text-slate-800">Sulawesi Selatan &bull; Makassar</span>
                                <span class="text-slate-500 block text-[10px]">{{ count($mapLocations) }} Sekolah Terdaftar</span>
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
            const esriGray = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &bull; HANDAL Kemkomdigi',
                maxZoom: 16
            }).addTo(map);

            const esriLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Reference/MapServer/tile/{z}/{y}/{x}', {
                attribution: '',
                maxZoom: 16
            }).addTo(map);

            const osmStandard = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors &bull; HANDAL',
                maxZoom: 19
            });

            const baseMaps = {
                "Kanvas Bersih": L.layerGroup([esriGray, esriLabels]),
                "OpenStreetMap": osmStandard
            };
            L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

            let sulselLayer = null;

            // 1. LOAD GEOJSON RESMI SULAWESI SELATAN (24 KAB/KOTA TANPA SULBAR)
            fetch('{{ asset("data/sulsel_accurate.geojson") }}')
                .then(res => res.json())
                .then(geoData => {
                    sulselLayer = L.geoJSON(geoData, {
                        style: function (feature) {
                            const isMakassar = (feature.properties.name || '').toUpperCase().includes('MAKASSAR');
                            if (isMakassar) {
                                return {
                                    fillColor: '#0284c7',
                                    fillOpacity: 0.28,
                                    color: '#0284c7',
                                    weight: 2.5,
                                    opacity: 1
                                };
                            }
                            return {
                                fillColor: '#38bdf8',
                                fillOpacity: 0.12,
                                color: '#0284c7',
                                weight: 1.5,
                                opacity: 0.8
                            };
                        },
                        onEachFeature: function (feature, layer) {
                            const kabName = feature.properties.name || feature.properties.KABKOT || 'Wilayah';
                            const isMakassar = kabName.toUpperCase().includes('MAKASSAR');
                            if (isMakassar) {
                                layer.bindTooltip(`<strong>${kabName} (Sulawesi Selatan)</strong><br><span style="color:#0284c7;font-weight:700;">Wilayah Pusat Pengujian</span> &bull; ${locations.length} Sekolah`, { sticky: true });
                            } else {
                                layer.bindTooltip(`<strong>${kabName}</strong><br>Provinsi Sulawesi Selatan`, { sticky: true });
                            }
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
            });

            btnSulsel.addEventListener('click', function () {
                setActiveButton(this);
                if (sulselLayer) {
                    map.fitBounds(sulselLayer.getBounds(), { padding: [30, 30], duration: 1.2 });
                } else {
                    map.flyTo(sulselCenter, 7, { duration: 1.2 });
                }
            });

            btnIndo.addEventListener('click', function () {
                setActiveButton(this);
                map.flyTo([-2.5489, 118.0149], 5, { duration: 1.4 });
            });
        });
    </script>
@endsection
