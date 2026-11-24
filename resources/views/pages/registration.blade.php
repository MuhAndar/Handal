@extends('layouts.app')

@section('title', 'Pendaftaran Sekolah Baru')

@section('content')
    <div class="min-h-screen relative overflow-hidden bg-slate-50">

        <div class="absolute inset-0 pointer-events-none opacity-[0.4]"
            style="background-image: radial-gradient(#94a3b8 1px, transparent 1px); background-size: 32px 32px;">
        </div>

        <div class="absolute -top-24 -right-24 w-[600px] h-[600px] bg-blue-500/20 rounded-full blur-[100px] animate-pulse">
        </div>
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-cyan-400/20 rounded-full blur-[80px]"></div>

        <div class="absolute -bottom-32 -left-20 w-[500px] h-[500px] bg-red-600/10 rounded-full blur-[120px]"></div>

        <div class="absolute top-1/3 -left-24 w-[300px] h-[300px] bg-yellow-500/20 rounded-full blur-[90px]"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 py-10">

            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol
                    class="inline-flex items-center space-x-2 text-sm text-slate-500 bg-white/50 backdrop-blur-sm py-2 px-4 rounded-full border border-white/60 shadow-sm">
                    <li><a href="/" class="hover:text-blue-600 transition-colors font-medium"><i
                                class="bi bi-house-door-fill mr-1"></i> Beranda</a></li>
                    <li class="text-slate-300">/</li>
                    <li class="font-semibold text-blue-600" aria-current="page">Pendaftaran Sekolah</li>
                </ol>
            </nav>

            <div class="relative">
                <div class="absolute inset-0 bg-blue-900/5 blur-2xl transform translate-y-4 rounded-[2.5rem]"></div>
                <div
                    class="relative bg-white/90 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white/50 overflow-hidden">

                    <div
                        class="bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-600 px-8 py-10 relative overflow-hidden text-white text-center md:text-left">
                        <div
                            class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl">
                        </div>
                        <div class="absolute bottom-0 right-20 w-24 h-24 bg-yellow-400 opacity-20 rounded-full blur-2xl">
                        </div>

                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                            <div>
                                <h1 class="text-3xl font-extrabold tracking-tight mb-2">Formulir Pendaftaran</h1>
                                <p class="text-blue-50 text-sm md:text-base opacity-90 font-light">Bergabunglah dengan
                                    ekosistem pendidikan digital masa depan.</p>
                            </div>
                            <div class="hidden md:block">
                                <div
                                    class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-inner">
                                    <i class="bi bi-building-add text-3xl text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 md:p-12">
                        @if (session('success'))
                            <div
                                class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl flex items-start gap-3 shadow-sm">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-xl mt-0.5"></i>
                                <div>
                                    <h3 class="text-emerald-800 font-bold text-sm">Pendaftaran Berhasil</h3>
                                    <p class="text-emerald-700 text-sm mt-1">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div
                                class="mb-8 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl flex items-start gap-3 shadow-sm">
                                <i class="bi bi-exclamation-triangle-fill text-rose-600 text-xl mt-0.5"></i>
                                <div>
                                    <h3 class="text-rose-800 font-bold text-sm">Perhatian</h3>
                                    <ul class="list-disc list-inside text-rose-700 text-sm mt-1 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-12">
                            @csrf

                            <div class="relative pl-0 md:pl-10 border-l-0 md:border-l-2 border-slate-100">
                                <div
                                    class="hidden md:flex absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-blue-600 text-white font-bold items-center justify-center shadow-lg shadow-blue-200">
                                    1</div>

                                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                                    <span
                                        class="md:hidden bg-blue-100 text-blue-600 text-xs font-bold px-2 py-1 rounded">1</span>
                                    Identitas Sekolah
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label
                                            class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama
                                            Sekolah <span class="text-rose-500">*</span></label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i
                                                    class="bi bi-mortarboard text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            </div>
                                            <input type="text" name="school_name"
                                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-medium text-slate-700 placeholder-slate-400"
                                                placeholder="Contoh: SMA Negeri 1 Makassar" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">Jenjang
                                            <span class="text-rose-500">*</span></label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i
                                                    class="bi bi-layers text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            </div>
                                            <select name="jenjang"
                                                class="w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none appearance-none cursor-pointer font-medium text-slate-700"
                                                required>
                                                <option value="" disabled {{ old('jenjang') ? '' : 'selected' }}>Pilih Jenjang Sekolah</option>
                                                
                                                <optgroup label="Tingkat Dasar (SD / MI / Sederajat)">
                                                    <option value="SD" {{ old('jenjang') == 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                                                    <option value="MI" {{ old('jenjang') == 'MI' ? 'selected' : '' }}>MI (Madrasah Ibtidaiyah)</option>
                                                </optgroup>

                                                <optgroup label="Tingkat Menengah Pertama (SMP / MTs / Sederajat)">
                                                    <option value="SMP" {{ old('jenjang') == 'SMP' ? 'selected' : '' }}>SMP (Sekolah Menengah Pertama)</option>
                                                    <option value="MTs" {{ old('jenjang') == 'MTs' ? 'selected' : '' }}>MTs (Madrasah Tsanawiyah)</option>
                                                </optgroup>

                                                <optgroup label="Tingkat Menengah Atas & Kejuruan (SMA / SMK / MA)">
                                                    <option value="SMA" {{ old('jenjang') == 'SMA' ? 'selected' : '' }}>SMA (Sekolah Menengah Atas)</option>
                                                    <option value="SMK" {{ old('jenjang') == 'SMK' ? 'selected' : '' }}>SMK (Sekolah Menengah Kejuruan)</option>
                                                    <option value="MA" {{ old('jenjang') == 'MA' ? 'selected' : '' }}>MA (Madrasah Aliyah)</option>
                                                    <option value="MAK" {{ old('jenjang') == 'MAK' ? 'selected' : '' }}>MAK (Madrasah Aliyah Kejuruan)</option>
                                                </optgroup>

                                                <optgroup label="Pendidikan Khusus & Non-Formal">
                                                    <option value="SLB" {{ old('jenjang') == 'SLB' ? 'selected' : '' }}>SLB (Sekolah Luar Biasa)</option>
                                                    <option value="Sederajat" {{ old('jenjang') == 'Sederajat' ? 'selected' : '' }}>Pendidikan Sederajat / Lainnya</option>
                                                </optgroup>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">NPSN
                                            <span class="text-rose-500">*</span></label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i
                                                    class="bi bi-123 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            </div>
                                            <input type="text" name="npsn"
                                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-medium text-slate-700 placeholder-slate-400"
                                                placeholder="8 Digit Angka" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative pl-0 md:pl-10 border-l-0 md:border-l-2 border-slate-100">
                                <div
                                    class="hidden md:flex absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-white border-2 border-blue-600 text-blue-600 font-bold items-center justify-center">
                                    2</div>

                                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                                    <span
                                        class="md:hidden bg-blue-100 text-blue-600 text-xs font-bold px-2 py-1 rounded">2</span>
                                    Lokasi & Alamat
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    {{-- PROVINSI --}}
                                    <div>
                                        <label class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Provinsi <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="province" id="provinceSelect"
                                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none appearance-none cursor-pointer text-sm font-medium text-slate-700"
                                                required>
                                                <option value="" disabled selected>Memuat daftar provinsi...</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- KABUPATEN / KOTA --}}
                                    <div>
                                        <label class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Kabupaten / Kota <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="city" id="citySelect" disabled
                                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none appearance-none cursor-pointer text-sm font-medium text-slate-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                                required>
                                                <option value="" disabled selected>-- Pilih Provinsi Terlebih Dahulu --</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- KECAMATAN --}}
                                    <div>
                                        <label class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Kecamatan <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="district" id="districtSelect" disabled
                                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none appearance-none cursor-pointer text-sm font-medium text-slate-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                                required>
                                                <option value="" disabled selected>-- Pilih Kabupaten/Kota Terlebih Dahulu --</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- KELURAHAN / DESA --}}
                                    <div>
                                        <label class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Kelurahan / Desa <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="village" id="villageSelect" disabled
                                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none appearance-none cursor-pointer text-sm font-medium text-slate-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                                required>
                                                <option value="" disabled selected>-- Pilih Kecamatan Terlebih Dahulu --</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ALAMAT LENGKAP --}}
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        Alamat Lengkap <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute top-3.5 left-0 pl-4 flex items-start pointer-events-none">
                                            <i class="bi bi-geo-alt text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <textarea name="address" rows="3"
                                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none text-sm font-medium text-slate-700 resize-none placeholder-slate-400"
                                            placeholder="Jalan, Nomor Gedung, RT/RW, Patokan Lokasi (Ketik manual)"
                                            required>{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="relative pl-0 md:pl-10 border-l-0 md:border-l-2 border-slate-100">
                                <div
                                    class="hidden md:flex absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-white border-2 border-blue-600 text-blue-600 font-bold items-center justify-center">
                                    3</div>

                                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                                    <span
                                        class="md:hidden bg-blue-100 text-blue-600 text-xs font-bold px-2 py-1 rounded">3</span>
                                    Kontak & Dokumen
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div>
                                        <label
                                            class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">WhatsApp
                                            Admin <span class="text-rose-500">*</span></label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i
                                                    class="bi bi-whatsapp text-slate-400 group-focus-within:text-green-500 transition-colors"></i>
                                            </div>
                                            <input type="text" name="contact_number"
                                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                                                placeholder="08xxxxxxxx" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">Email
                                            Sekolah <span class="text-rose-500">*</span></label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i
                                                    class="bi bi-envelope text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            </div>
                                            <input type="email" name="email"
                                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                                                placeholder="admin@sekolah.sch.id" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- 
                                    FITUR UPLOAD SURAT PERMOHONAN DINONAKTIFKAN SEMENTARA 
                                    (Kolom database dan struktur model tetap dipertahankan agar siap diaktifkan kembali kapan saja)
                                <div>
                                    <label class="block mb-3 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        Upload Surat Permohonan <span
                                            class="ml-2 normal-case font-normal text-slate-400 bg-slate-100 px-2 py-0.5 rounded text-[10px]">Format
                                            PDF (Maks. 2MB)</span>
                                    </label>

                                    <div class="relative group">
                                        <input id="assessment_letter" name="assessment_letter" type="file"
                                            accept=".pdf"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                            onchange="updateFileName(this)" />

                                        <div id="upload-container"
                                            class="flex flex-col items-center justify-center w-full h-36 border-2 border-slate-200 border-dashed rounded-2xl bg-slate-50 group-hover:bg-blue-50 group-hover:border-blue-400 transition-all duration-300">
                                            <div
                                                class="flex flex-col items-center justify-center text-center p-4 transition-transform group-hover:scale-105">
                                                <div
                                                    class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-3">
                                                    <i id="upload-icon"
                                                        class="bi bi-cloud-arrow-up-fill text-2xl text-blue-500"></i>
                                                </div>
                                                <p id="file-name"
                                                    class="text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">
                                                    Klik untuk upload dokumen
                                                </p>
                                                <p id="file-help" class="text-xs text-slate-400 mt-1">atau seret file ke
                                                    sini</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                --}}
                            </div>

                            <div class="pt-8 mt-8 border-t border-slate-100">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-blue-500/30 hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-3">
                                    <span>Kirim Pengajuan Sekarang</span>
                                    <i class="bi bi-arrow-right-circle-fill text-lg"></i>
                                </button>
                                <p class="text-center text-slate-400 text-xs mt-4 flex items-center justify-center gap-1">
                                    <i class="bi bi-lock-fill"></i> Data Anda dilindungi dan diverifikasi secara manual.
                                </p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileNameElement = document.getElementById('file-name');
            const fileHelpElement = document.getElementById('file-help');
            const uploadIcon = document.getElementById('upload-icon');
            const container = document.getElementById('upload-container');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                fileNameElement.innerText = file.name;
                fileNameElement.classList.add('text-blue-700');

                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileHelpElement.innerText = `Ukuran: ${fileSize} MB - Siap diupload`;
                fileHelpElement.classList.add('text-green-600', 'font-semibold');
                uploadIcon.className = 'bi bi-file-earmark-pdf-fill text-2xl text-red-500';
                uploadIcon.parentElement.classList.add('bg-red-50');

                container.classList.remove('bg-slate-50', 'border-slate-200');
                container.classList.add('bg-blue-50', 'border-blue-500', 'border-solid');
            } else {
                fileNameElement.innerText = 'Klik untuk upload dokumen';
                fileNameElement.classList.remove('text-blue-700');

                fileHelpElement.innerText = 'atau seret file ke sini';
                fileHelpElement.classList.remove('text-green-600', 'font-semibold');

                uploadIcon.className = 'bi bi-cloud-arrow-up-fill text-2xl text-blue-500';
                uploadIcon.parentElement.classList.remove('bg-red-50');

                container.classList.add('bg-slate-50', 'border-slate-200', 'border-dashed');
                container.classList.remove('bg-blue-50', 'border-blue-500', 'border-solid');
            }
        }

        // ==========================================
        // CASCADING DROPDOWN WILAYAH INDONESIA VIA JSON
        // ==========================================
        (function() {
            const provinceSelect = document.getElementById('provinceSelect');
            const citySelect = document.getElementById('citySelect');
            const districtSelect = document.getElementById('districtSelect');
            const villageSelect = document.getElementById('villageSelect');

            if (!provinceSelect) return;

            function toTitleCase(str) {
                if (!str) return '';
                return str.toLowerCase().replace(/(?:^|\s|-|\/)\S/g, function(m) {
                    return m.toUpperCase();
                });
            }

            const oldProvince = "{{ old('province') }}";
            const oldCity = "{{ old('city') }}";
            const oldDistrict = "{{ old('district') }}";
            const oldVillage = "{{ old('village') }}";

            // Fetch data wilayah: utamakan file JSON lokal untuk keandalan maksimal tanpa kendala CORS/jaringan
            async function fetchProvinces() {
                try {
                    let localRes = await fetch('/data/wilayah/provinces.json');
                    if (localRes.ok) return await localRes.json();
                } catch (e) {}
                let res = await fetch('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
                return await res.json();
            }

            async function fetchRegencies(provId) {
                try {
                    let localRes = await fetch(`/data/wilayah/regencies_${provId}.json`);
                    if (localRes.ok) return await localRes.json();
                } catch (e) {}
                let res = await fetch(`https://emsifa.github.io/api-wilayah-indonesia/api/regencies/${provId}.json`);
                return await res.json();
            }

            async function fetchDistricts(regId) {
                try {
                    let localRes = await fetch(`/data/wilayah/districts_${regId}.json`);
                    if (localRes.ok) return await localRes.json();
                } catch (e) {}
                let res = await fetch(`https://emsifa.github.io/api-wilayah-indonesia/api/districts/${regId}.json`);
                return await res.json();
            }

            async function fetchVillages(disId) {
                try {
                    let localRes = await fetch(`/data/wilayah/villages_${disId}.json`);
                    if (localRes.ok) return await localRes.json();
                } catch (e) {}
                let res = await fetch(`https://emsifa.github.io/api-wilayah-indonesia/api/villages/${disId}.json`);
                return await res.json();
            }

            // Inisialisasi Dropdown Provinsi
            fetchProvinces().then(provinces => {
                provinceSelect.innerHTML = '<option value="" disabled selected>-- Pilih Provinsi --</option>';
                provinces.forEach(p => {
                    const opt = document.createElement('option');
                    const title = toTitleCase(p.name);
                    opt.value = title;
                    opt.textContent = title;
                    opt.dataset.id = p.id;

                    if (oldProvince && (oldProvince.toLowerCase() === title.toLowerCase() || oldProvince.toLowerCase() === p.name.toLowerCase())) {
                        opt.selected = true;
                    } else if (!oldProvince && p.id === '73') {
                        // Default Sulawesi Selatan untuk kemudahan user
                        opt.selected = true;
                    }
                    provinceSelect.appendChild(opt);
                });

                if (provinceSelect.value) {
                    provinceSelect.dispatchEvent(new Event('change'));
                }
            }).catch(() => {
                provinceSelect.innerHTML = '<option value="Sulawesi Selatan" selected>Sulawesi Selatan</option><option value="Sulawesi Barat">Sulawesi Barat</option>';
            });

            // Handler: Perubahan Provinsi
            provinceSelect.addEventListener('change', async function() {
                const opt = provinceSelect.options[provinceSelect.selectedIndex];
                const provId = opt ? opt.dataset.id : null;

                citySelect.innerHTML = '<option value="" disabled selected>Memuat kabupaten/kota...</option>';
                citySelect.disabled = true;
                districtSelect.innerHTML = '<option value="" disabled selected>-- Pilih Kabupaten/Kota Terlebih Dahulu --</option>';
                districtSelect.disabled = true;
                villageSelect.innerHTML = '<option value="" disabled selected>-- Pilih Kecamatan Terlebih Dahulu --</option>';
                villageSelect.disabled = true;

                if (!provId) return;

                try {
                    const regencies = await fetchRegencies(provId);
                    citySelect.innerHTML = '<option value="" disabled selected>-- Pilih Kabupaten / Kota --</option>';
                    regencies.forEach(r => {
                        const rOpt = document.createElement('option');
                        const rTitle = toTitleCase(r.name);
                        rOpt.value = rTitle;
                        rOpt.textContent = rTitle;
                        rOpt.dataset.id = r.id;

                        if (oldCity && (oldCity.toLowerCase() === rTitle.toLowerCase() || oldCity.toLowerCase() === r.name.toLowerCase())) {
                            rOpt.selected = true;
                        }
                        citySelect.appendChild(rOpt);
                    });
                    citySelect.disabled = false;

                    if (citySelect.value) {
                        citySelect.dispatchEvent(new Event('change'));
                    }
                } catch (e) {
                    citySelect.innerHTML = '<option value="" disabled selected>Gagal memuat kabupaten/kota</option>';
                }
            });

            // Handler: Perubahan Kabupaten / Kota
            citySelect.addEventListener('change', async function() {
                const opt = citySelect.options[citySelect.selectedIndex];
                const regId = opt ? opt.dataset.id : null;

                districtSelect.innerHTML = '<option value="" disabled selected>Memuat kecamatan...</option>';
                districtSelect.disabled = true;
                villageSelect.innerHTML = '<option value="" disabled selected>-- Pilih Kecamatan Terlebih Dahulu --</option>';
                villageSelect.disabled = true;

                if (!regId) return;

                try {
                    const districts = await fetchDistricts(regId);
                    districtSelect.innerHTML = '<option value="" disabled selected>-- Pilih Kecamatan --</option>';
                    districts.forEach(d => {
                        const dOpt = document.createElement('option');
                        const dTitle = toTitleCase(d.name);
                        dOpt.value = dTitle;
                        dOpt.textContent = dTitle;
                        dOpt.dataset.id = d.id;

                        if (oldDistrict && (oldDistrict.toLowerCase() === dTitle.toLowerCase() || oldDistrict.toLowerCase() === d.name.toLowerCase())) {
                            dOpt.selected = true;
                        }
                        districtSelect.appendChild(dOpt);
                    });
                    districtSelect.disabled = false;

                    if (districtSelect.value) {
                        districtSelect.dispatchEvent(new Event('change'));
                    }
                } catch (e) {
                    districtSelect.innerHTML = '<option value="" disabled selected>Gagal memuat kecamatan</option>';
                }
            });

            // Handler: Perubahan Kecamatan
            districtSelect.addEventListener('change', async function() {
                const opt = districtSelect.options[districtSelect.selectedIndex];
                const disId = opt ? opt.dataset.id : null;

                villageSelect.innerHTML = '<option value="" disabled selected>Memuat kelurahan/desa...</option>';
                villageSelect.disabled = true;

                if (!disId) return;

                try {
                    const villages = await fetchVillages(disId);
                    villageSelect.innerHTML = '<option value="" disabled selected>-- Pilih Kelurahan / Desa --</option>';
                    villages.forEach(v => {
                        const vOpt = document.createElement('option');
                        const vTitle = toTitleCase(v.name);
                        vOpt.value = vTitle;
                        vOpt.textContent = vTitle;
                        vOpt.dataset.id = v.id;

                        if (oldVillage && (oldVillage.toLowerCase() === vTitle.toLowerCase() || oldVillage.toLowerCase() === v.name.toLowerCase())) {
                            vOpt.selected = true;
                        }
                        villageSelect.appendChild(vOpt);
                    });
                    villageSelect.disabled = false;
                } catch (e) {
                    villageSelect.innerHTML = '<option value="" disabled selected>Gagal memuat kelurahan</option>';
                }
            });
        })();
    </script>
@endsection
